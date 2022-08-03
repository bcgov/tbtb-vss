<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StaffEditRequest extends FormRequest
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
            'disabled.boolean' => 'Status field is invalid',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = User::find($this->id);
        return [
            'user_id' => 'required|string|max:4|unique:users,user_id,' . $user->id . ',id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'start_date' => 'required|string|max:255',
            'access_type' => 'required|string|max:1|in:A,U',
            'disabled' => 'required|boolean',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . ',id',
            'password' => ['sometimes', 'confirmed'],

        ];
    }


    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if(isset($this->disabled)){
            $this->merge(['disabled' => filter_var($this->disabled, FILTER_VALIDATE_BOOLEAN)]);

            //remove end date if user is activated
            if($this->disabled == false){
                $this->merge(['end_date' => null]);
            }else{
                //add end date if the user is being de-activated and no end-date provided
                if(!isset($this->end_date)){
                    $this->merge(['end_date' => date('Y-m-d', strtotime('now'))]);
                }
            }
        }

        if(isset($this->user_id)){
            $this->merge(['user_id' => Str::upper($this->user_id)]);
        }
        if(isset($this->access_type)){
            $this->merge(['access_type' => Str::upper($this->access_type)]);
        }
        if(isset($this->first_name)){
            $this->merge(['first_name' => Str::title($this->first_name)]);
        }
        if(isset($this->last_name)){
            $this->merge(['last_name' => Str::title($this->last_name)]);
        }
        if(isset($this->email)){
            $this->merge(['email' => Str::lower($this->email)]);
        }

        if(isset($this->start_date)){
            $this->merge(['start_date' => date('Y-m-d', strtotime($this->start_date))]);
        }
        if(isset($this->end_date)){
            $this->merge(['end_date' => date('Y-m-d', strtotime($this->end_date))]);
        }


    }
}
