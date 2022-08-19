<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaOfAuditStoreRequest extends FormRequest
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
            'area_of_audit_code.required' => 'Audit Code field is required.',
            'area_of_audit_code.max' => 'Audit Code field size cannot be more than 3 characters.',
            'area_of_audit_code.unique' => 'Audit Code is already in use.',

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
        $audit_code_rule = 'required|max:3|unique:area_of_audits,area_of_audit_code';
        if (isset($this->id)) {
            $audit_code_rule = 'required|max:3|unique:area_of_audits,area_of_audit_code,' . $this->id . ',id';
        }

        return [
            'area_of_audit_code' => $audit_code_rule,
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
        if (isset($this->area_of_audit_code)) {
            $this->merge(['area_of_audit_code' => mb_strtoupper($this->area_of_audit_code)]);
        }
    }
}
