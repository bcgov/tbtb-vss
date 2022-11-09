<?php

namespace App\Http\Requests;

use App\Models\SanctionType;
use Illuminate\Foundation\Http\FormRequest;

class SanctionTypeStoreRequest extends FormRequest
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
            'sanction_code.required' => 'Sanction Code field is required.',
            'sanction_code.unique' => 'Sanction Code is already in use.',

            'short_description.*' => 'Short Description field is required.',
            'description.*' => 'Description field is required.',
            'disabled.*' => 'Status field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $sanction_code_rule = 'required|unique:sanction_types,sanction_code';
        if (isset($this->id)) {
            $sanction_code_rule = 'required|unique:sanction_types,sanction_code,'.$this->id.',id';
        }

        return [
            'sanction_code' => $sanction_code_rule,
            'description' => 'required',
            'short_description' => 'required',
            'disabled' => 'required|boolean',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //if we are creating new record
        if (! isset($this->id)) {
            $last = SanctionType::select('sanction_code')->orderBy('sanction_code', 'desc')->first();
            $this->merge(['sanction_code' => $last->sanction_code + 1]);
        }
    }
}
