<?php

namespace App\Http\Controllers;

use Breadcrumbs;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $crumbs;

    /**
     * Instantiate the controller
     * Here we are sharing the AuthUser with every view
     * As well as setting the default pagination count
     *
     * @return void
     */
    public function __construct()
    {
        //set breadcrumbs defaults
        $this->crumbs = Breadcrumbs::setCssClasses('breadcrumb')
                                        ->setListElement('ol')
                                        ->setDivider(null);

        $this->crumbs->addCrumb('Home', config('app.parent_url'));
    }

    /**
     * Placeholder function for later adapation to get either the
     * user pagination setting or the account pagination setting
     */
    protected function paginationCount($override = false)
    {
        if ($override) {
            return $override;
        }

        return 10;
    }
}
