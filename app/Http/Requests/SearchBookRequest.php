<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchBookRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $redirectRoute = "bookPage.index";

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
     * @return array{name: string, authors: string, leftPrice: string, rightPrice: string}
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'authors' => 'string',
            'leftPrice' => 'numeric',
            'rightPrice' => 'numeric'
        ];
    }
}
