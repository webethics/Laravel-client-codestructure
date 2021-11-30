<?php

namespace App\Http\Requests;

use Storage;
use App\Http\Requests\Request;

/**
 * Validation of registration requests
 *
 */
class LocationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = app('App\Location')->getRules();

        return array_merge(
            $rules,
            [
                'submitter_name' => 'required',
                'submitter_email' => 'required|email',
                'categories' => 'array',
                'categories.*' => 'exists:categories,hashid',
            ]
        );
    }


    /**
     * Modify the input that should be fed to the validator.
     *
     * @return array
     */
    public function modifyInput()
    {
        $input = $this->all();

        if ($this->hasFile('image_upload')) {
            $input['image'] = Storage::disk('public')->putFile(null, $this->file('image_upload'));
        }

        $categories = $this->get('categories');

        $input['category_ids'] = !emptyOrNull($categories)
                                    ? array_map('dehash', $categories)
                                    : [];

        $input['published'] = 0;

        $this->replace($input);
    }

    /**
     * Add some custom validation rules to the validator instance
     *
     * @return \Illuminate\Validation\Validator
     */
    public function getValidatorInstance()
    {
        // Modify data before it gets sent to the validator
        $this->modifyInput();

        $validator = parent::getValidatorInstance();
        $validator->after(
            function ($validator) {
                //
            }
        );

        return $validator;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
