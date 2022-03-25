<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class SearchBookController extends Controller
{
    private Book $book;

    private string $name;

    private string $authors;

    private float $leftPrice;

    private float $rightPrice;


    /**
     * Filter books by properties
     */
    public function index(SearchRequest $searchRequest): \Illuminate\Http\JsonResponse
    {
        $this->name = $searchRequest->get('name') ?? Book::INCORRECT_NAME_INPUT;
        $this->authors = $searchRequest->get('authors') ?? Book::INCORRECT_AUTHORS_INPUT;
        $this->leftPrice = $searchRequest->get('leftPrice') ?? Book::INCORRECT_PRICE_INPUT;
        $this->rightPrice = $searchRequest->get('rightPrice') ?? Book::INCORRECT_PRICE_INPUT;


        $this->book = new Book();
        $this->book->setBuilder();

        $books = $this->book
            ->searchByName($this->name)
            ->searchByAuthors($this->authors)
            ->searchByPrice($this->leftPrice, $this->rightPrice)
            ->getBuilder()
            ->get();

        return response()->json(['books' => $books]);
    }


}
