<?php
/**
 * Gathering place for any little helper functions focused on the app backend
 * (rather than the blade templates). This file is autoloaded by composer.json
 *
 */

if (! function_exists('notifyAllAdmins')) {
    /**
     * Notify all the admins
     *
     * @param      object  $notification  variable App\Notifications\...
     *
     * @return     void
     */
    function notifyAllAdmins($notification)
    {
        foreach (app('App\User')->admin()->get() as $admin) {
            $admin->notify($notification);
        }
    }
}



if (! function_exists('isAjaxRequest')) {
    /**
     * Check if the current route is an Ajax request route
     *
     * @param      object  $request  Illuminate\Http\Request
     *
     * @return     bool
     */
    function isAjaxRequest($request)
    {
        return ($request->ajax() && ! $request->pjax()) || $request->wantsJson();
    }
}

if (! function_exists('emptyOrNull')) {

    /**
     * Check if the item provided is empty or null
     *
     * @param      mixed  $value
     *
     * @return     boolean
     */
    function emptyOrNull($value = null)
    {
        if (is_bool($value) || is_numeric($value)) {
            return false;
        }

        if ($value instanceof \Illuminate\Support\Collection) {
            return $value->isEmpty();
        }

        if (is_object($value) || is_array($value)) {
            return count($value) == 0;
        }

        return is_null($value) || trim($value) == '';
    }
}

if (! function_exists('implodeConfigKeys')) {
    /**
     * Implode the config keys, useful for validation "in:" rules.
     *
     * @param      string  $path
     * @param      string  $delimiter  The delimiter
     *
     * @return     object  Illuminate\Support\Collection
     */
    function implodeConfigKeys($path, $delimiter = ',')
    {
        return collectConfig($path)->keys()->implode($delimiter);
    }
}

if (! function_exists('collectConfig')) {
    /**
     * Return a config as a collection
     *
     * @param      string   $path
     * @param      boolean  $recursive  True returns a recursive collection
     *
     * @return     object   Illuminate\Support\Collection
     */
    function collectConfig($path, $recursive = false)
    {
        if ($recursive) {
            return recursivelyCollect(config($path));
        }

        return collect(config($path));
    }
}

if (!function_exists('recursivelyCollect')) {

    /**
     * Recursively collect all arrays/objects into collections
     *
     * @param      mixed          $item   An item to check
     *
     * @return     string|object
     */
    function recursivelyCollect($item)
    {
        if ($item instanceof \Carbon\Carbon) {
            return $item;
        }

        if (is_object($item) || is_array($item)) {
            $item = collect($item);
            $item->transform(
                function ($item) {
                    return recursivelyCollect($item);
                }
            );
        }

        return $item;
    }
}

if (! function_exists('dehash')) {
    /**
     * Dehash a hashid ID
     *
     * @param  string    $hashid
     * @return integer
     */
    function dehash($hashid)
    {
        $hashidArray = app('hashids')->decode($hashid);
        return !empty($hashidArray) ? $hashidArray[0] : null;
    }
}
