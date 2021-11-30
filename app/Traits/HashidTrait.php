<?php

namespace App\Traits;

use Hashids;

trait HashidTrait
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
       // return 'slug';
         return 'hashid';
    }

    /**
     * When any model is created we need to save a unique hashid for the model
     * We cannot do it while creating as we don't yet know the id for the model
     *
     * @return void
     */
    public static function bootHashidTrait()
    {
        static::created(
            function ($model) {
                $model->hashid = $model->generateHashid();
                $model->save();
            }
        );
    }

    /**
     * Get a unique ID for the current Model. We look for a key
     * in the config/modelids.php file If it doesn't exist
     * then the current model just gets a default ID of 1
     *
     * @return integer
     */
    public function getModelIdAttribute()
    {
        return config('modelids.' . get_called_class(), 1);
    }

    /**
     * Returns a hashed id for the current model
     *
     * @return string
     */
    public function generateHashid()
    {
        $id = $this->id;
        $modelId = isset($this->modelId) ? $this->modelId : 0;

        return $this->hashidWithNoCollisions($id, $modelId, Hashids::encode([$id, $modelId]));
    }

    /**
     * Checks the current model for any possible collisions, and if it finds
     * any, loops through until it can generate no collisions.
     *
     * @param      int      $id              The current model->id
     * @param      int      $modelId         A unique id for the model class
     *                                       (see config.modelids)
     * @param      string   $intendedHashid  The intended hashid
     * @param      integer  $i               Incrementing integer for collisions
     *
     * @return     string
     */
    public function hashidWithNoCollisions($id, $modelId, $intendedHashid, $i = 0)
    {
        // Check in this model whether there is a matching hashid
        $collision = app(get_called_class())->whereHashid($intendedHashid)->first();

        // If there is a collision, then regenerate the hashid and check again
        // for a collision
        if ($collision) {
            // Generate a new hashid using the $i integer to create a variation
            // additionally increment the $i integer so that if there's another
            // collision, we can try again with a new variation
            $intendedHashid = Hashids::encode([$id, $modelId, $i++]);

            // Recurse back onto this function to check that the new hashid
            // also doesn't collide
            return $this->hashidWithNoCollisions($id, $modelId, $intendedHashid, $i);
        }

        // Otherwise there are no collisions and we can just move on
        return $intendedHashid;
    }
}
