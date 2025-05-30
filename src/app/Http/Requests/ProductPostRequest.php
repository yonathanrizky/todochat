<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPostRequest extends FormRequest
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
            'product_name' => 'required|max:150',
            'price' => 'required',
            'description' => 'required|',
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
            'product_name.required' => 'Nama Produk wajib diisi',
            'product_name.max' => 'Maksimal 150 karakter',
            'price.required' => 'Harga wajib diisi',
            'description.required' => 'Keterangan wajib diisi',
        ];
    }
}
