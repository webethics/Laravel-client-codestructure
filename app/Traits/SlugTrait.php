<?php

namespace App\Traits;

trait SlugTrait
{
    /**
     * When any model is created we need to save a unique slug for the model
     * We cannot do it while creating as we don't yet know the id for the model
     *
     * @return void
     */
    public static function bootSlugTrait()
    {
        static::creating(
            function ($model) {
                $model->slug = $model->generateSlug();
            }
        );
    }

    /**
     * Returns a hashed id for the current model
     *
     * @return string
     */
    public function generateSlug()
    {
        return $this->slugWithNoCollisions(str_slug($this->name));
    }

    /**
     * Checks the current model for any possible collisions, and if it finds
     * any, loops through until it can generate no collisions.
     *
     * @param      string   $intendedSlug  The intended slug
     * @param      integer  $i             Incrementing integer for collisions
     * @param      string   $originalSlug  Optional: The original slug
     *
     * @return     string
     */
    public function slugWithNoCollisions($intendedSlug, $i = 1, $originalSlug = false)
    {

        // Check in this model whether there is a matching slug
        $collision = app(get_called_class())->whereSlug($intendedSlug)->first();

        // If there is a collision, then regenerate the slug and check again
        // for a collision
        if ($collision) {
            $originalSlug = $originalSlug ?: $intendedSlug;

            // Generate a new slug using the $i integer to create a variation
            // additionally increment the $i integer so that if there's another
            // collision, we can try again with a new variation
            $intendedSlug = $originalSlug . '-' . $i++;

            // Recurse back onto this function to check that the new slug
            // also doesn't collide
            return $this->slugWithNoCollisions($intendedSlug, $i, $originalSlug);
        }

        // Otherwise there are no collisions and we can just move on
        return $intendedSlug;
    }
}
