<?php

namespace App\Traits;

/**
 * Trait for address accessors used in App\User
 *
 */
trait AddressAccessors
{
    /**
     * Column names for the available address lines
     *
     * @var array
     */
    protected $addressLines = [
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'address_line_4',
        'city',
        'postcode',
        'state_province',
        'county',
        'country',
    ];

    /**
     * Get the full address as an array
     *
     * @return     object
     */
    public function getAddressArrayAttribute()
    {
        return $this->only($this->addressLines)->filter();
    }

    /**
     * Get the full address collapsed to a comma separated string
     *
     * @return     string
     */
    public function getFullAddressWithLineBreaksAttribute()
    {
        return $this->addressArray->implode(',<br> ');
    }

    /**
     * Get the full address collapsed to a comma separated string
     *
     * @return     string
     */
    public function getFullAddressAttribute()
    {
        return $this->addressArray->implode(', ');
    }

    /**
     * Get the full address collapsed to a comma separated string
     *
     * @return     string
     */
    public function getCityAddressAttribute()
    {
        return $this->only(
            [
                'address_line_1',
                'address_line_2',
                'address_line_3',
                'address_line_4',
                'city',
                // 'postcode',
            ]
        )->filter()
        ->implode(', ');
    }
}
