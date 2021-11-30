<?php

namespace App;

use App\Traits\SlugTrait;
use App\Traits\HashidTrait;
use App\Traits\ImageCacheTrait;
use App\Traits\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, HashidTrait, CommonModelMethods, SlugTrait, ImageCacheTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_category_id', 'name', 'slug', 'description', 'icon', 'image',
        'published',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'parent_category_id', 'updated_at', 'deleted_at'
    ];

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
         'icon_url'
    ];

    /**********************************
     * Accessors & Mutators
     **********************************/

    public function getMapMarkerUrlAttribute()
    {
        if ($this->mapMarkerUrl()) {
            return $this->mapMarkerUrl();
        }

        if ($this->parent_category_id && $this->parent->mapMarkerUrl()) {
            return $this->parent->mapMarkerUrl();
        }

        return elixir('images/default-map-marker.png');
    }

    public function getIconUrlAttribute()
    {
        return $this->iconUrl('icon-small');
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
                        ->orWhere('slug', 'LIKE', "%$freeText%")
                        ->orWhere('description', 'LIKE', "%$freeText%");
                }
            );
        }

        if ($request->has('published')) {
            $query->wherePublished($request->get('published'));
        }


        if ($request->has('parent')) {
            $query->whereHas(
                'parent',
                function ($q) use ($request) {
                    $q->whereHashid($request->get('parent'));
                }
            );
        } elseif ($request->has('root_only')) {
            $query->isParent();
        }

        return $query;
    }

    /**
     * Scope to search the model
     *
     * @param      object  $query  Illuminate\Database\Query\Builder
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeIsParent($query)
    {
        return $query->whereNull('parent_category_id');
    }

	public function scopeIsPublished($query)
    {
        return $query->where('published',1);
    }

    /**
     * Scope to search the model
     *
     * @param      object  $query   Illuminate\Database\Query\Builder
     * @param      mixed  $parent   Accepts App\Category, ID, or Hashid
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeChildOf($query, $parent)
    {
        return $query->whereHas(
            'parent',
            function ($q) use ($parent) {
                if ($parent instanceof \App\Category) {
                    $q->whereId($parent->id);
                } elseif (is_integer($parent)) {
                    $q->whereId($parent);
                } elseif (is_string($parent)) {
                    $q->whereHashid($parent);
                }
            }
        );
    }


    /**********************************
     * Relationships
     **********************************/

    /**
     * Define hasMany children
     *
     * @return object
     */
    public function children()
    {
        return $this->hasMany('App\Category', 'parent_category_id');
    }

    /**
     * Define hasOne parent
     *
     * @return object
     */
    public function parent()
    {
        return $this->hasOne('App\Category', 'id', 'parent_category_id');
    }

    /**
     * Define belongsToMany locations
     *
     * @return object
     */
    public function locations()
    {
        return $this->belongsToMany('App\Location');
    }
}
