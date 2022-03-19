<?php

namespace App\Http\Requests;

use Core\Constants;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
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
        $minBookNameLen = self::MIN_BOOK_NAME_LENGTH;
        $maxBookNameLen = self::MAX_BOOK_NAME_LEN;
        $minAnnotationNameLen = self::MIN_ANNOTATION_LENGTH;
        $maxAnnotationNameLen = self::MAX_ANNOTATION_LEN;
        $minAuthorsNameLen = self::MIN_AUTHORS_NAME_LENGTH;
        $maxAuthorsNameLen = self::MAX_AUTHORS_NAME_LEN;
        $minBookPrice = self::MIN_BOOK_PRICE;
        $maxBookPrice = self::MAX_BOOK_PRICE;


        return [
            'name' =>
                "required|string|min:{$minBookNameLen}|max:{$maxBookNameLen}|unique:books",
            'annotation' => "required|string|min:{$minAnnotationNameLen}|max:{$maxAnnotationNameLen}",
            'authors' => "required|string|min:{$minAuthorsNameLen}|max:{$maxAuthorsNameLen}",
            'price' => "required|numeric|between:{$minBookPrice},{$maxBookPrice}",
        ];
    }


    public function messages(): array
    {
        return
            [
                'required' => 'Данное поле не может быть пустым',
            ];
    }
}
