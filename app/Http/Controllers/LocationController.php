<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Notifications\NotifyAdminOfLocationSubmission;

class LocationController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Location $location, Request $request)
    {
		
        $this->crumbs->addCrumb('Things to see', iframeUrl(url('location')));
        $this->addSearchSummaryCrumb($request);
        $locations = $location->search($request)
                              ->isPublished()
                              ->orderBy('name', 'asc')
							  ->paginate($this->paginationCount(20));
		
		
					  	
        foreach ($locations as $location) {
            $location->setAttribute('newURL', 'https://splashpro.webethics.online/things-to-see/show/?location='.$location->slug);
        }
                              
        $lastRecordCount = $locations->count() + (($locations->currentPage() - 1) * $locations->perPage());
        $firstRecordCount = $lastRecordCount - $locations->count() + 1;
		
        $user = auth()->user();
		
        $data = [
            'itineraries' => $user ? $user->itinerarySelectOptions : false,
            'locations' => $locations,
            'firstRecordCount' => $firstRecordCount,
            'lastRecordCount' => $lastRecordCount,
            'categories' => parentAndChildCategories(),
            'favouriteIds' => $user
                                ? $user->favourites()->pluck('location_id')
                                : collect(session()->get('favourites')),
        ];
		
		
        return view('location.index')->with($data);
    }

    public function addSearchSummaryCrumb($request)
    {
		
        if ($request->has('s')) {
            $searchSummary = collect(
                $request->only(['free_text', 'county'])
            )->filter();

            if ($request->has('parent_category')) {
                $parent = Category::whereSlug($request->get('parent_category'))->firstOrFail();
                $searchSummary->push($parent->name);
            }

            if ($request->has('child_category')) {
                $parent = Category::whereSlug($request->get('child_category'))->firstOrFail();
                $searchSummary->push($parent->name);
            }

            $this->crumbs->addCrumb($searchSummary->implode(', '));
        }
    }

    /**
     * A laravel route for guest users to store some locations in session
     * (session isn't available in Dingo - though perhaps could be with some
     * middleware changes)
     *
     * @return response
     */
    public function favourite($hashid, Location $location)
    {
        $location = $location->isPublished()->whereHashid($hashid)->firstOrFail();

        $favourites = collect(session()->get('favourites', []));

        // If it already exists in the user favourites, remove it
        if ($favourites->contains($location->id)) {
            $favourites->pull($favourites->search($location->id));
            session()->put('favourites', $favourites->toArray());
            return ['message' => 'Location was removed from favourites.', 'status' => 200];
        } else {
            // Otherwise add it
            session()->push('favourites', $location->id);
            return ['message' => 'Location was added to favourites.', 'status' => 200];
        }
    }


    /**
     * Show the application dashboard.
     *
     * @param      object    $location  App\Location
     *
     * @return     response
     */
    public function create(Location $location)
    {
		
        $this->crumbs->addCrumb('Things to see', iframeUrl(route('location.index')))
                     ->addCrumb('Suggest an attraction');

        $data = [
            'parentCategories' => Category::isParent()->get(),
            'currentCategories' => collect(),
        ];

        // session()->flush();
        return view('location.create')->with($data);
    }

    /**
     * Store a location
     *
     * @param      object    $location  App\Location
     * @param      object    $request   LocationRequest
     *
     * @return     response
     */
    public function store(Location $location, LocationRequest $request)
    {
        $location = $location->create($request->all());

        $location->categories()->sync($request->get('category_ids'));

        notifyAllAdmins(new NotifyAdminOfLocationSubmission($location, $request));

        // Flash our own temp info alert
        session()->put('info-once', 'New attraction suggestion submitted, thank you!');

        return redirect(iframeUrl(route('location.index')));
    }

    /**
     * Show details for a location
     *
     * @param      object    $location  App\Location
     * @param      object    $request   LocationRequest
     *
     * @return     response
     */
    public function show($hashid, Location $location)
    {

        $location = $location->isPublished()->whereSlug($hashid)->firstOrFail();

        $this->crumbs->addCrumb('Things to see', iframeUrl(route('location.index')))
                     ->addCrumb($location->name);

        $user = auth()->user();

        $data = [
            'itineraries' => $user ? $user->itinerarySelectOptions : false,
            'location' => $location,
            'favouriteIds' => $user
                                ? $user->favourites()->pluck('location_id')
                                : collect(session()->get('favourites')),
        ];

        return view('location.show')->with($data);
    }

}
