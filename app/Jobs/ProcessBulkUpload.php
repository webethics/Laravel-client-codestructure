<?php

namespace App\Jobs;

use Excel;
use App\Category;
use App\Location;
use App\BulkUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class ProcessBulkUpload implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $bulkUpload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BulkUpload $bulkUpload)
    {
        $this->bulkUpload = $bulkUpload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::selectSheetsByIndex(0)->load(
            'storage/app/' . $this->bulkUpload->file,
            function ($reader) {
                $reader->each(
                    function ($row) {

                        //usually indicates the last row
                        if(empty($row->name)){
                            return;
                        }

                        $cleaned_data = collect($row->all())
                                        ->map(function( $item, $key) {
                                            $item = $this->bulkUpload->clean($item);
                                            $item = $this->bulkUpload->clarify($item, $key);

                                            return $item; 
                                        })
                                        ->toArray();                        



                        $createdLocation = Location::create($cleaned_data);

                        $this->attachLocationToCategories($row->get('categories'), $createdLocation);
                    }
                );
            }
        )->get();

        $this->bulkUpload->status = 'Processed';
        $this->bulkUpload->save();
    }

    /**
     * Check if any categories need to be attached to the location
     *
     * @param      string  $categories       The categories as a comma-separated string
     * @param      object  $createdLocation  App\Location
     */
    private function attachLocationToCategories($categories, $createdLocation)
    {
        if (!emptyOrNull($categories)) {
            $categories = explode(',', $categories);

            foreach ($categories as $categoryName) {
                $category = Category::whereName(trim($categoryName))->first();

                if ($category) {
                    $createdLocation->categories()->attach($category);
                }else{
                    Log::info('The category "' . $categoryName . '" for the location "' . $createdLocation->name . '" could not be found.' );
                }
            }
        }
    }
}
