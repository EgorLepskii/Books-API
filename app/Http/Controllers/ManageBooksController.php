<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

/**
 * Controller for manage book.
 */
class ManageBooksController extends Controller
{
    private Book $book;

    /**
     * Create new Book
     */
    public function store(CreateBookRequest $createBookRequest): \Illuminate\Http\JsonResponse
    {
        $input = $createBookRequest->only(['name', 'annotation', 'authors', 'price', 'isHidden', 'genreId']);
        $this->book = new Book($input);
        $this->book->save();

        return response()->json(
            ['response' =>
            Lang::get('manageBook.createSuccess', ['bookName' => $this->book->getName()])], 201
        );
    }

    /**
     * Update existing book
     */
    public function update(UpdateBookRequest $updateBookRequest, Book $book): \Illuminate\Http\JsonResponse
    {
        $prevName = $book->getName();
        $input = $updateBookRequest->only(['name', 'annotation', 'authors', 'price', 'isHidden', 'genreId']);
        $book->update($input);

        return response()->json(
            ['response' =>
            Lang::get('manageBook.updateSuccess', ['bookPrevName' => $prevName])], 201
        );
    }
}
