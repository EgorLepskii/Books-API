<?php

namespace App\Http\Requests;

use Core\Constants;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class CreateBookRequest extends FormRequest implements Constants
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
     * @return array
     */
    public function rules(Request $request)
    {
        $minFieldLen = self::MIN_FIELD_LENGTH;
        $maxFieldLen = self::MAX_FIELD_LENGTH;
        $minBookPrice = self::MIN_BOOK_PRICE;
        $maxBookPrice = self::MAX_BOOK_PRICE;

        return [
            'name' => "required|string|min:{$minFieldLen}|max:{$maxFieldLen}|unique:books",
            'annotation' => "required|string|min:{$minFieldLen}|max:{$maxFieldLen}",
            'authors' => "required|string|min:{$minFieldLen}|max:{$maxFieldLen}",
            'price' => "required|numeric|between:{$minBookPrice},{$maxBookPrice}",
            'genreId' => 'required|integer|exists:genres,id'
        ];
    }


    public function messages(): array
    {
        return
            [
                'required' => Lang::get('FormErrors.required'),
                'string' => Lang::get('FormErrors.string'),
                'min' => Lang::get('FormErrors.min', ['minValue' => self::MIN_FIELD_LENGTH]),
                'max' => Lang::get('FormErrors.max', ['maxValue' => self::MAX_FIELD_LENGTH]),
                'unique' => Lang::get('FormErrors.unique'),
                'exists' => Lang::get('FormErrors.exists'),
                'integer' => Lang::get('FormErrors.integer'),
                'numeric' => Lang::get('FormErrors.numeric'),
                'between' =>
                    Lang::get('FormErrors.between', ['left' => self::MIN_BOOK_PRICE, 'right' => self::MAX_BOOK_PRICE])
            ];
    }
}
