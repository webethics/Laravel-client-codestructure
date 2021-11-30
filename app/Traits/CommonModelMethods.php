<?php

namespace App\Traits;

use Cache;
use Carbon\Carbon;
use InvalidArgumentException;

/**
 * Loaded as a trait because it's needed in the BaseModel but also directly in App\User
 *
 */
trait CommonModelMethods
{
    /**********************************
     * Methods
     **********************************/

    /**
     * Get a timestamp from the model and format it based on the config.settings
     *
     * @param      string  $column  The column
     * @param      string  $format  Optional: date_format|date_time_format|time_format
     *
     * @return     string
     */
    public function formatTimestamp($column = 'created_at', $format = 'date_format')
    {
        if ($this->{$column}) {
            return $this->{$column}->format(config('settings.' . $format));
        }
    }

    /**
     * Allows interchangability between a collection and a model so you can
     * pass either a collection or a model to a method and still use
     * $model->get($key) without worrying whether it's a collection or a model
     *
     * @param      string  $attribute  The attribute
     *
     * @return     mixed
     */
    public function get($attribute)
    {
        return $this->{$attribute};
    }

    /**
     * Get a unique cache key for any model based on the class name
     * optionally adding a prefix
     *
     * @param  string $prefix An optional prefix
     * @return string         An md5 hash of the unique cache name
     */
    public function getCacheKey($prefix = null)
    {
        $key = $prefix .
                get_class($this) .
                $this->id .
                $this->updated_at->toDateTimeString();

        return md5($key);
    }

    /**
     * Helper method for pulling out only specific attributes from a model.
     * Allows easy switching between collections and models
     *
     * @param      mixed  $attributes  The model attributes required
     *
     * @return     object  Illuminate\Support\Collection
     */
    public function only($attributes)
    {
        $attributes = is_array($attributes) ? $attributes : func_get_args();

        $attributes = collect(array_combine($attributes, $attributes));

        return $attributes->transform(
            function ($attribute) {
                return $this->{$attribute};
            }
        );
    }

    /**
     * Get the visible elements and return them as a collection
     *
     * @return     object  Illumiante\Support\Collection
     */
    public function toCollection()
    {
        return collect($this->toArray());
    }

    /**********************************
     * Scopes
     **********************************/

    /**
     * Helper for scopeSearch to allow searching an exact match or in an array
     *
     * @param      object  $query    Illuminate\Database\Query\Builder
     * @param      string  $column   The database column to check
     * @param      object  $request  Illuminate\Http\Request
     * @param      mixed   $key      Optional: The $request object key to check
     *                               against
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeWhereExactOrInArray($query, $column, $request, $key = false)
    {
        // If no key has been provided then the search parameter has the same
        // name as the database column
        $key = $key ? $key : $column;

        // If the $request doesn't have the parameter, then just return the query untouched
        if (!$request->has($key)) {
            return $query;
        }

        // Get the value of the query parameter
        $value = $request->get($key);

        // If it's an array, search 'whereIn', otherwise search 'where'
        if (is_array($value)) {
            $query->whereIn($column, $value);
        } else {
            $query->where($column, $value);
        }

        // Return the query
        return $query;
    }

    /**
     * Search the payments
     *
     * @param      object  $query    Illuminate\Database\Query\Builder
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeSearchFromTo($query, $request)
    {
        if ($request->has('from')) {
            $query->where(
                'created_at',
                '>',
                Carbon::createFromFormat('d/m/Y', $request->get('from'))->startOfDay()
            );
        }

        if ($request->has('to')) {
            $query->where(
                'created_at',
                '<',
                Carbon::createFromFormat('d/m/Y', $request->get('to'))->endOfDay()
            );
        }

        return $query;
    }
}
