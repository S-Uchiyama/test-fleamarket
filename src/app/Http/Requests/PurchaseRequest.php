<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:convenience_store,card'],
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'  => ['required', 'string'],
            'building' => ['nullable', 'string'],
            'shipping_address' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.in'       => '支払い方法を正しく選択してください',
            'postcode.required'       => '郵便番号を入力してください',
            'postcode.regex'          => '郵便番号はハイフンあり8文字で入力してください',
            'address.required'        => '住所を入力してください',
        ];
    }
}
