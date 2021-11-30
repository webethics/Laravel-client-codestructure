<?php

namespace App;

use DB;
use View;
use Html;
use Markdown;
use App\Traits\SlugTrait;
use App\Traits\HashidTrait;
use App\Traits\ImageCacheTrait;
use App\Traits\AddressAccessors;
use App\Traits\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes, HashidTrait, CommonModelMethods, SlugTrait, AddressAccessors, ImageCacheTrait;

    protected $fillable = [
        'name', 'slug', 'description', 'address_line_1', 'address_line_2',
        'address_line_3', 'address_line_4', 'postcode', 'city', 'state_province',
        'county', 'country', 'latitude', 'longitude', 'phone', 'email',
        'website', 'opening_hours', 'admission_fees', 'minimum_visit_time',
        'average_visit_time', 'image', 'data_quality', 'published',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Additional attributes which should be treated as Carbon objects
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Additional attributes which should be treated as Carbon objects
     *
     * @var array
     */
    protected $appends = [
        'maps_embed_popup_link', 'maps_embed_popup_link_text',
        'lat_long_decimal_and_degrees', 'categories_summary',
        'medium_html_image', 'full_address', 'public_show_url',
        'average_visit_time_hours', 'location_grid_html_image',
        'admission_fees_line_breaks', 'opening_hours_line_breaks',
        'short_description_line_breaks', 'description_line_breaks',
        'location_icon', 'map_popup_html', 'description_html', 'image_url'
    ];


    /**********************************
     * Methods
     **********************************/

    public function getRules()
    {
        return [
            'name' => 'required',
            'latitude' => 'required|latitude',
            'longitude' => 'required|longitude',
            'minimum_visit_time' => 'nullable|integer',
            'average_visit_time' => 'required|integer',
            'county' => 'in:' . implodeConfigKeys('settings.counties'),
            'country' => 'in:' . implodeConfigKeys('settings.countries'),
            'email' => 'sometimes|email',
            'website' => 'sometimes|url',
            'image_upload' => 'image',
            'data_quality' => 'in:Good,Medium,Poor',
            'published' => 'in:0,1',
        ];
    }

    /**********************************
     * Accessors & Mutators
     **********************************/

    public function getDescriptionHtmlAttribute()
    {
        return Markdown::convertToHtml($this->description);
        ;
    }


    public function getMapPopupHtmlAttribute()
    {
        return (string) View::make('location.partial.map-popup')->with(['location' => $this]);
    }

    public function getLocationIconAttribute()
    {
        return $this->categories->first()->mapMarkerUrl;
    }

    public function getLocationIconsArrayAttribute()
    {
        $categoryImagesArray = [];

        foreach ($this->categories as $category) {
            $categoryImagesArray[] = $category->mapMarkerUrl;
        }

        return $categoryImagesArray;
    }

    public function getAdmissionFeesLineBreaksAttribute()
    {
        return nl2br($this->admission_fees);
    }

    public function getOpeningHoursLineBreaksAttribute()
    {
        return nl2br($this->opening_hours);
    }

    public function getDescriptionLineBreaksAttribute()
    {
        return nl2br($this->description);
    }

    public function getShortDescriptionLineBreaksAttribute()
    {
        return nl2br(str_limit($this->description, 250));
    }

    public function getLocationThumbHtmlImageAttribute()
    {
        return $this->htmlImage('location-thumb');
    }

     public function getImageUrlAttribute()
    {
        return $this->imageUrl('location-grid');
    }

    public function getLocationGridHtmlImageAttribute()
    {
        return $this->htmlImage('location-grid');
    }


    public function getIsFavouritedAttribute()
    {
        if (!auth()->user()) {
            return null;
        }

        return $this->favouritedBy()->pluck('location_id')->contains(auth()->user()->id);
    }

    public function getFavouriteEndpointAttribute()
    {
        return auth()->check()
                ? app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('location.favourite', $this)
                : route('location.favourite', $this);
    }

    public function getPublicShowUrlAttribute()
    {
        return iframeUrl(route('location.show', $this));
    }

    public function getCategoriesLinkListAttribute()
    {
        $categories = $this->categories()->orderBy('name', 'asc')->get();

        if ($categories->isEmpty()) {
            return null;
        }

        return $categories->transform(
            function ($category) {

                $route = iframeUrl(route('location.index', ['s' => 1, 'category_slug' => $category->slug]));

                return '<a href="'. $route .'">' . $category->name . '</a>';
            }
        )->implode(', ');


        return $this->name . ' is found in ';
    }

    public function getCategoriesSummaryAttribute()
    {
        $categories = $this->categoriesLinkList;

        if (is_null($categories)) {
            return $this->name . ' is not currently assigned to any categories';
        }

        return $this->name . ' is found in ' . $categories;
    }

    public function getAverageVisitTimeHoursAttribute()
    {
        return round($this->average_visit_time / 60, 1) . ' hours';
    }

    public function getLatitudeDecimalAndDegreeAttribute()
    {
        return $this->latitude . ' / ' . convertDecimalToDegrees($this->latitude);
    }

    public function getLongitudeDecimalAndDegreeAttribute()
    {
        return $this->longitude . ' / ' . convertDecimalToDegrees($this->longitude);
    }

    public function getLatLongDecimalAndDegreesAttribute()
    {
        return $this->latitude_decimal_and_degree . ',<br>'
               . $this->longitude_decimal_and_degree;
    }

    public function getMapsEmbedRouteAttribute()
    {
        return route('location.map', $this);
    }

    public function mapsEmbedPopupLink($linkText = null)
    {
        $onClickParams = "'".$this->mapsEmbedRoute."','_blank','resizable,height=600,width=800'";
        $linkText = $linkText ? ' ' . $linkText : null;

        return '<a href="#" onClick="event.preventDefault(); window.open('.$onClickParams.'); return false;"><i class="fa fa-fw fa-map-marker"></i>'.$linkText.'</a>';
    }

    public function getMapsEmbedPopupLinkAttribute()
    {
        return $this->mapsEmbedPopupLink();
    }

    public function getMapsEmbedPopupLinkTextAttribute()
    {
        return $this->mapsEmbedPopupLink('Attraction Location');
    }

    /**
     * Gets the URL for use in the Google Maps Embed iframe
     *
     * @return     string
     */
    public function getMapsEmbedUrlAttribute()
    {
        $parameters = [
            'q' => $this->only(['latitude', 'longitude'])->implode(','),
            'key' => config('services.maps.embed_key'),
            'zoom' => 9,
        ];

        return 'https://www.google.com/maps/embed/v1/place?'
                . http_build_query($parameters);
    }

    /**
     * Gets both the email and phone number and renders separated by a line break
     *
     * @return     string
     */
    public function getEmailPhoneAttribute()
    {
        return $this->only(['mailto', 'phone'])->filter()->implode('<br>');
    }

    /**
     * Get's a mailto html link
     *
     * @return     string
     */
    public function getMailtoAttribute()
    {
        return $this->email ? (string) Html::mailto($this->email) : null;
    }

    public function getNearLatLongIds($request)
    {
        if ($request->has('latitude') && $request->has('longitude')) {
            $this->select('id')
                ->nearLatLong(
                    $request->get('latitude'),
                    $request->get('longitude'),
                    $request->get('radius', 30)
                )->pluck('id');
        }

        return $this->all()->pluck('id');
    }

    /**********************************
     * Scopes
     **********************************/

    /**
     * Scope to search the model
     *
     * @param      object  $query    Illuminate\Database\Query\Builder
     * @param      object  $request  Illuminate\Http\Request
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeSearch($query, $request)
    {
		 if ($request->has('free_text')) {
            $freeText = $request->get('free_text');
			
            $query->where(
                function ($q) use ($freeText) {
                    $q->where('name', 'LIKE', "%$freeText%")
                        ->orWhere('email', 'LIKE', "%$freeText%")
                        ->orWhere('description', 'LIKE', "%$freeText%")
                        ->orWhere('address_line_1', 'LIKE', "%$freeText%")
                        ->orWhere('address_line_2', 'LIKE', "%$freeText%")
                        ->orWhere('address_line_3', 'LIKE', "%$freeText%")
                        ->orWhere('address_line_4', 'LIKE', "%$freeText%")
                        ->orWhere('city', 'LIKE', "%$freeText%")
                        ->orWhere('postcode', 'LIKE', "%$freeText%")
                        ->orWhere('state_province', 'LIKE', "%$freeText%")
                        ->orWhere('county', 'LIKE', "%$freeText%")
                        ->orWhere('country', 'LIKE', "%$freeText%");
                }
            );
        }

        if ($request->has('county')) {
            $query->whereCounty($request->get('county'));
        }

        if ($request->has('category_slug') && !emptyOrNull($request->get('category_slug'))) {
            $query->whereHas(
                'categories',
                function ($q) use ($request) {
                    $q->whereExactOrInArray('slug', $request, 'category_slug');
                }
            );
        }

        if ($request->has('location_hashids')) {
            $query->whereExactOrInArray('hashid', $request, 'location_hashids');
        }

        if ($request->has('latitude') && $request->has('longitude')) {
            $query->nearLatLong(
                $request->get('latitude'),
                $request->get('longitude'),
                $request->get('radius', 30)
            );
        }

        if ($request->has('published')) {
            $query->wherePublished($request->get('published'));
        } 
		
		return $query;
    }

    public function scopeNearLatLong($query, $lat, $long, $radius = 30)
    {
        $distance = $radius * 0.0117;

        $latBetween = [$lat - $distance, $lat + $distance];
        $longBetween = [$long - $distance, $long + $distance];

        return $query->whereBetween('latitude', $latBetween)
                    ->whereBetween('longitude', $longBetween);

        // REMOVED THIS
        // It was causing issues with chaining of queries
        $raw = DB::raw(
            "*, (
                6371 *
                acos(cos(radians(?))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))
            ) AS distance"
        );

        return $query->select($raw)
                    ->having("distance", "<", "?")
                    ->orderBy("distance")
                    ->setBindings([$lat, $long, $lat, $radius], 'select');

    }

    /**
     * Get only published locations
     *
     * @param      object  $query  Illuminate\Database\Query\Builder
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeIsPublished($query)
    {
        return $query->wherePublished(1);
    }


    /**********************************
     * Relationships
     **********************************/

    /**
     * Define belongsToMany categories
     *
     * @return object
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    /**
     * Define belongsToMany favouritedBy
     *
     * @return object
     */
    public function favouritedBy()
    {
        return $this->belongsToMany('App\User');
    }
}
