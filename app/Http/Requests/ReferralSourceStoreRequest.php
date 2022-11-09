<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferralSourceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'referral_code.required' => 'Referral Source Code field is required.',
            'referral_code.unique' => 'Referral Source Code is already in use.',

            'description.*' => 'Description field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $referral_code_rule = 'required|unique:referral_sources,referral_code';
        if (isset($this->id)) {
            $referral_code_rule = 'required|unique:referral_sources,referral_code,'.$this->id.',id';
        }

        return [
            'referral_code' => $referral_code_rule,
            'description' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {

    }
}
