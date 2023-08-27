<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            'firstname' => ['required','string','max:50','min:3'],
            'fathername' => ['required','string','max:50','min:3'],
            'lastname' => ['required','string','max:50','min:3'],
            'birthdate' => ['required','date'], /* 'after_or_equal:1930-01-01' */

            'gender' => ['required','string','max:6'], /*'in:ذكر,أنثى'*/
            'phone' => ['required','string','max:12','regex:/^0\d{9}$/','unique:users,phone'],
            'address' => ['required','string','max:255','unique:users,address'],

            'email' => ['required','string','email','max:255','unique:users'],
            'password' => ['required','confirmed',Password::defaults()],

        ];
    }
}
