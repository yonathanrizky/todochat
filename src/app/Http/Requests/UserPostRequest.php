<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPostRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nik' => 'required|unique:users,nik',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|max:12',
            'fullname' => 'required',
            'phone' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'fullname.required' => 'Nama Pegawai Wajib Diisi',
            'phone.required' => 'Nomor Telpon Pegawai Wajib Diisi',
            'password.required' => 'Password Wajib Diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password minimal 12 karakter'
        ];
    }
}
