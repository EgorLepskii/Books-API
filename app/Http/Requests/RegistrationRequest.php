<?php

namespace App\Http\Requests;

use Core\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class RegistrationRequest extends FormRequest implements Constants
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
     * @return array{name: string, email: string, password: string}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    /**
     * @return array{required: mixed, string: mixed, min: mixed, max: mixed, unique: mixed, exists: mixed, int: mixed, between: mixed, confirmed: mixed}
     */
    public function messages(): array
    {
        return
            [

                'required' => Lang::get('FormErrors.required'),
                'string' => Lang::get('FormErrors.string'),
                'min' => Lang::get('FormErrors.min', ['minValue' => self::MIN_BOOK_NAME_LENGTH]),
                'max' => Lang::get('FormErrors.required', ['maxValue' => self::MAX_BOOK_NAME_LEN]),
                'unique' => Lang::get('FormErrors.unique'),
                'exists' => Lang::get('FormErrors.exists'),
                'int' => Lang::get('FormErrors.int'),
                'between' =>
                    Lang::get('FormErrors.between', ['left' => self::MIN_BOOK_PRICE, 'right' => self::MAX_BOOK_PRICE]),
                'confirmed' => Lang::get('FormErrors.confirmed')

            ];
    }
}
