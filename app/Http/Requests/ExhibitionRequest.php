<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string'],
            'description'   => ['required', 'string', 'max:255'],
            'image'         => ['required', 'image', 'mimes:jpeg,png'],
            'category_ids'  => ['required', 'array', 'min:1'],
            'category_ids.*'=> ['integer', 'exists:categories,id'],
            'condition'     => ['required', 'string'],
            'price'         => ['required', 'integer', 'min:0'],
            'brand'         => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '商品説明は255文字以内で入力してください',

            'image.required'       => '商品画像をアップロードしてください',
            'image.image'          => '商品画像には画像ファイルを指定してください',
            'image.mimes'          => '商品画像はjpegまたはpng形式でアップロードしてください',

            'category_ids.required'=> '商品のカテゴリーを選択してください',
            'category_ids.array'   => '商品のカテゴリーの選択が不正です',
            'category_ids.min'     => '商品のカテゴリーを1つ以上選択してください',

            'condition.required'   => '商品の状態を選択してください',

            'price.required'       => '商品価格を入力してください',
            'price.integer'        => '商品価格は数値で入力してください',
            'price.min'            => '商品価格は0円以上で入力してください',
        ];
    }
}
