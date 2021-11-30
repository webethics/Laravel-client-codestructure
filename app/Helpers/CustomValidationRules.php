<?php

namespace App\Helpers;

/**
 * Any custom validation rules can be added here. In order to activate them you
 * need to add the public function to App\Providers\AppServiceProvider::boot()
 *
 */
class CustomValidationRules
{
    /**
     * Validate latitude
     *
     * @param      string   $attribute
     * @param      mixed    $value
     * @param      array    $parameters
     * @param      object   $validator
     *
     * @return     boolean
     */
    public function latitude($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', trim($value)) === 1;
    }

    /**
     * Validate latitude
     *
     * @param      string   $attribute
     * @param      mixed    $value
     * @param      array    $parameters
     * @param      object   $validator
     *
     * @return     boolean
     */
    public function longitude($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', trim($value)) === 1;
    }

    /**
     * Validate password strength
     *
     * @param      string   $attribute
     * @param      mixed    $value
     * @param      array    $parameters
     * @param      object   $validator
     *
     * @return     boolean
     */
    public function passwordStrength($attribute, $value, $parameters, $validator)
    {
        return $this->isPasswordStrongEnough($value);
    }

    /**
     * Validate password strength
     *
     * @param      string   $password
     *
     * @return     boolean
     */
    private function isPasswordStrongEnough($password)
    {
        // 8 Chars, 1 Upper, 1 lower, 1 special, 1 digit
        // $rule = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*$/';

        // 8 Chars, 1 Upper, 1 lower, 1 special
        $rule = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])\S*$/';

        if (preg_match($rule, $password)) {
            return true;
        } else {
            return false;
        }
    }
}
