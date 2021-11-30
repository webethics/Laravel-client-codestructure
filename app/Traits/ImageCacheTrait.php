<?php

namespace App\Traits;

use Html;

trait ImageCacheTrait
{
    /**
     * Get the icon url
     *
     * @param      string  $size   icon-small|icon-large, default: icon-small
     *
     * @return     string
     */
    public function iconUrl($size = 'icon-small')
    {
        if ($this->icon) {
            return url('img/cache/' . $size . '/' . $this->icon);
        }
    }

    /**
     * Get the Html image element for the icon
     *
     * @param      string  $size        icon-small|icon-large, default:
     *                                  icon-small
     * @param      array   $attributes  Optional: image attributes
     *
     * @return     string
     */
    public function htmlIcon($size = 'icon-small', $attributes = ['class' => 'img-responsive'])
    {
        if ($this->icon) {
            return (string) Html::image($this->iconUrl($size), null, $attributes);
        }
    }

    /**
     * Get the image url
     *
     * @param      string  $size   Optional: small|medium|large, default: small
     *
     * @return     string
     */
    public function imageUrl($size = 'small')
    {
        if ($this->image) {
            return url('img/cache/' . $size . '/' . $this->image);
        }
    }

    /**
     * Get the Html image element
     *
     * @param      string  $size        Optional: small|medium|large, default: small
     * @param      array   $attributes  Optional: image attributes
     *
     * @return     string
     */
    public function htmlImage($size = 'small', $attributes = ['class' => 'img-responsive'])
    {
        if ($this->image) {
            return (string) Html::image($this->imageUrl($size), null, $attributes);
        }
    }

    public function getMediumHtmlImageAttribute()
    {
        return $this->htmlImage('medium');
    }
}
