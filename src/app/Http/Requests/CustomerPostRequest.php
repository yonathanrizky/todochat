<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPostRequest extends FormRequest
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
            'address' => 'required',
            'email' => 'required|unique:customers,email',
            'password' => 'required|min:6|max:12',
            'fullname' => 'required',
            'phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'address.required' => 'Alamat wajib diisi',
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
