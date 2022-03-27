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
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array{name: string, annotation: string, authors: string, price: string, genreId: string}
     */
    public function rules(Request $request): array
    {
        $minFieldLen = self::MIN_FIELD_LENGTH;
        $maxFieldLen = self::MAX_FIELD_LENGTH;
        $minBookPrice = self::MIN_BOOK_PRICE;
        $maxBookPrice = self::MAX_BOOK_PRICE;

        return [
            'name' => sprintf('required|string|min:%d|max:%d|unique:books', $minFieldLen, $maxFieldLen),
            'annotation' => sprintf('required|string|min:%d|max:%d', $minFieldLen, $maxFieldLen),
            'authors' => sprintf('required|string|min:%d|max:%d', $minFieldLen, $maxFieldLen),
            'price' => sprintf('required|numeric|between:%d,%s', $minBookPrice, $maxBookPrice),
            'genreId' => 'required|integer|exists:genres,id'
        ];
    }


    /**
     * @return array{required: mixed, string: mixed, min: mixed, max: mixed, unique: mixed, exists: mixed, integer: mixed, numeric: mixed, between: mixed}
     */
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
