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
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $this->name = $request->get('name') ?? Book::INCORRECT_NAME_INPUT;
        $this->authors = $request->get('authors') ?? Book::INCORRECT_AUTHORS_INPUT;
        $this->leftPrice = $request->get('leftPrice') ?? Book::INCORRECT_PRICE_INPUT;
        $this->rightPrice = $request->get('rightPrice') ?? Book::INCORRECT_PRICE_INPUT;


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
