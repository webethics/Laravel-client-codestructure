<?php

if (!function_exists('iframeUrl')) {
    /**
     * Translate the url to link to the parent iframe
     *
     * @param      string  $url  The laravel url
     *
     * @return     string
     */
    function iframeUrl($url)
    {
        if (app()->environment('testing')) {
            return $url;
        }

        // Don't alter admin url's
        $isAdmin = app('Illuminate\Support\Str')->is('*/admin*', rawurldecode($url))
        // Parse the provided URL
        $url = parse_url($url);

        // Get the Parent URL from the config
        $parentUrl = parse_url(config('app.parent_url'));

        // Override the schema/base elements of the $url with the schema/base
        // of the $parentUrl
        $url = array_merge($url, $parentUrl);



        // Reset the path where required
        switch ($url['path']) {

            case '/login':
                $url['path'] = '/account';
                break;
            case '/favourites':
                $url['path'] = '/favorites';
                break;
            case '/location':
                $url['path'] = '/things-to-see';
                break;
            case '/location/create':
                $url['path'] = '/things-to-see/add-attraction';
                break;
            // No need for a default, just leave the parse_url array alone
        }

        $output = null;

        if (app('Illuminate\Support\Str')->is('/password/reset/*', $url['path'])) {
            $parts = explode('/', $url['path']);
            $url['path'] = '/account/password-reset/';
            $params = ['token' => $parts[3]];
            $url['query'] = http_build_query($params);
        }

        if (app('Illuminate\Support\Str')->is('/email/verify/*', $url['path'])) {
            $parts = explode('/', $url['path']);
            $url['path'] = '/account/email/';
            $params = ['token' => $parts[3]];
            $url['query'] = http_build_query($params);
        }

        if (app('Illuminate\Support\Str')->is('/location/*/show', $url['path'])) {
            $parts = explode('/', $url['path']);
            $url['path'] = '/things-to-see/show/';
            $params = ['location' => $parts[2]];
            $url['query'] = http_build_query($params);
        }

        // Return the newly setup url
        return http_build_url($url);
    }
}


if (!function_exists('firstLinesOfAddress')) {
    /**
     * Geo-lookup addresses are very long, this shortens them to the first two
     * elements of the address
     *
     * @param      string  $address  The full address
     *
     * @return     string
     */
    function firstLinesOfAddress($address)
    {
        return collect(explode(', ', $address))->take(2)->implode(', ');
    }
}

if (!function_exists('convertDecimalToDegrees')) {
    /**
     * Convert a decimal latitude to DMS
     *
     * @param      float  $decimal  The decimal
     *
     * @return     string
     */
    function convertDecimalToDegrees($decimal)
    {
        // Converts decimal format to DMS ( Degrees / minutes / seconds )
        $vars = explode(".", $decimal);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];

        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = $tempma - ($min*60);

        return $deg . '°, ' . $min . '\', ' . $sec . '"';
    }
}

if (!function_exists('parentAndChildCategories')) {
    /**
     * If a form field has errors, add the has-error class to it
     *
     * @param      string  $field   The name of the field to check
     * @param      object  $errors  The errors object
     *
     * @return     mixed   string or null
     */
    function parentAndChildCategories()
    {
		
				$parents = app('App\Category')->isParent()->isPublished()->get();

                $complete = collect();

                $parents->each(
                    function ($parent) use ($complete) {
                        $complete->push(
                            [
                                'value' => $parent->slug,
                                'label' => $parent->name,
                                'children' => $parent->children->where('published',1)->pluck('name', 'slug')->mapFromAssoc()->toArray(),
                            ]
                        );
                    }
                );
			
                return $complete;
				
       /*  return cache()->rememberForever(
            'parent_and_child_categories',
            function () {
                
            }
        ); */
    }
}

if (!function_exists('errorClass')) {
    /**
     * If a form field has errors, add the has-error class to it
     *
     * @param      string  $field   The name of the field to check
     * @param      object  $errors  The errors object
     *
     * @return     mixed   string or null
     */
    function errorClass($field, $errors)
    {
        
        return $errors->has($field) ? 'has-error' : '';
    }
}

if (!function_exists('renderFieldErrors')) {
    /**
     * Renders a errors help-block explaining what was wrong
     *
     * @param      string  $field   The name of the field to check
     * @param      object  $errors  The errors object
     *
     * @return     mixed   string or null
     */
    function renderFieldErrors($field, $errors)
    {
        $html = null;

        if ($errors->has($field)) {
            $html = '<p class="help-block">'
                    . '<strong>'. $errors->first($field) .'</strong>'
                    . '</p>';
        }
        return $html;
    }
}

if (! function_exists('getActive')) {
    /**
     * Get the active state for the nav item
     * @param  string $path  The path to query
     * @param  string $class The class to return
     * @return mixed         string or null
     */
    function getActive($path, $class = 'active')
    {
        if (is_array($path)) {
            foreach ($path as $singlePath) {
                if (app('request')->is($singlePath)) {
                    return $class;
                }
            }
            return null;
        }
        return app('request')->is($path) ? $class : null;
    }
}

if (!function_exists('escapeUrl')) {
    /**
     * Make sure a url has http:// at the start
     * If $url already has http:// or https://, then $url is returned unchanged
     *
     * @param  string $url URL to be escaped
     * @return string      Properly formed URL
     */
    function escapeUrl($url)
    {
        if ($ret = parse_url($url) && !isset($ret["scheme"])) {
            $url = "http://{$url}";
        }

        return $url;
    }
}

if (!function_exists('checkboxHidden')) {
    /**
     * Generate a hidden input to get around issue with Laravel
     * where unchecked checkboxes for booleans aren't saved unless there's
     * a hidden input with a value of 0
     *
     * @param  string $name The input name
     * @return string       HTML hidden input with a value of 0
     */
    function checkboxHidden($name)
    {
        return '<input type="hidden" name="'. $name .'" value="0">';
    }
}


if (!function_exists('renderPhoto')) {
    /**
     * Renders an image using intervention/imagecache
     * @param  string $fileName The name of the image file
     * @param  string $size     The size template to use (see config/packages/intervention/imagecache/config.php)
     * @param  string $class    Any classes to add to the image
     * @return string           A HTML img element
     */
    function renderPhoto($fileName, $size = 'small', $class = 'cp-image', $alt = null, $title = null)
    {
        if (!$fileName) {
            return null;
        }

        $title = isset($title) ? ' title="'. $title .'"' : null;
        $alt = isset($alt) ? ' alt="'. $alt .'"' : null;


        return '<img src="' . asset("img/cache/$size/" . $fileName). '" class="'. $class . '"' . $alt . $title . '>';
    }
}

if (!function_exists('renderPhotoModel')) {
    /**
     * Renders an image using intervention/imagecache
     * @param  object $fileName \Illuminate\Eloquent\Model
     * @param  string $size     The size template to use (see config/packages/intervention/imagecache/config.php)
     * @param  string $class    Any classes to add to the image
     * @return string           A HTML img element
     */
    function renderPhotoModel($photo, $size = 'small', $class = 'cp-image')
    {
        if (!$photo) {
            return null;
        }

        $title = !empty($photo->title) ? ' title="'. $photo->title .'"' : null;
        $alt = !empty($photo->alt) ? ' alt="'. $photo->alt .'"' : null;

        return '<img src="' . asset("img/cache/$size/" . $photo->file). '" class="'. $class . '"' . $alt . $title . '>';
    }
}

if (!function_exists('photoUrl')) {
    /**
     * Provides the URL to view the intervention/imagecache image
     * @param  string $fileName The name of the image file
     * @param  string $size     The size template to use (see config/imagecache.php)
     * @return string           Absolute URL to the image
     */
    function photoUrl($fileName, $size = 'small')
    {
        if (!$fileName) {
            return null;
        }

        return asset("img/cache/$size/" . $fileName);
    }
}


if (!function_exists('renderPagination')) {
    /**
     * Renders the pagination for the provided collection
     * @param  object $collection Illuminate\Database\Eloquent\Collection
     * @return string             Pagination links
     */
    function renderPagination($collection)
    {
        return $collection->appends(Request::except(['page', 'body-only', 'modal']))->render();
    }
}

if (!function_exists('deleteButton')) {
    /**
     * Renders a delete button in a form to the specified route
     * @param  array  $route Array to be passed to the route parameter of Form::open
     * @return string        HTML form for echoing
     */
    function deleteButton(array $route, $confirmText = '<strong>Are you sure?</strong>', $extra_classes = '')
    {
        return Form::open(
            [
                'route' => $route,
                'method' => 'DELETE',
                'class' => 'delete-form',
                'data-confirm-text' => $confirmText,
            ]
        ) .
        '<button type="submit"class="btn btn-danger '. $extra_classes .'">
            <i class="fa fa-trash"></i> Delete
        </button>' .
        Form::close();
    }
}

if (!function_exists('shortenString')) {
    /**
     * Returns the first $wordsreturned out of $string.  If string
     * contains more words than $wordsreturned, the entire string
     * is returned.
     * @param String $string The string to check
     * @param int $wordsreturned Max number of words to include
     */
    function shortenString($string, $wordsreturned)
    {
        $retval = $string;
        $array = explode(' ', $string);
        if (count($array)<= $wordsreturned) {
            $retval = $string;
        } else {
            array_splice($array, $wordsreturned);
            $retval = implode(' ', $array).' ...';
        }
        return $retval;
    }
}

if (!function_exists('prefixSelect')) {
    /**
     * Prefixes an array with a null element for select dropdowns
     *
     * @param  array $request An array of key/value pairs for Form::select creations
     * @param  string $text   The value of the prepended element
     * @return array
     */
    function prefixSelect($array, $text = 'Select')
    {
        return array_merge(['' => $text], $array);
    }
}

if (!function_exists('numericArrayValuesToKeys')) {
    /**
     * Converts a numeric array to an associative array using the values as keys
     *
     * @param  array $request An array of key/value pairs for Form::select creations
     *
     * @return array
     */
    function numericArrayValuesToKeys($array)
    {
        return array_combine($array, $array);
    }
}
