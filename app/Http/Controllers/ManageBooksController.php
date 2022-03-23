<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;
use SebastianBergmann\Diff\Exception;

/**
 * Controller for manage book.
 */
class ManageBooksController extends Controller
{
    private Book $book;

    /**
     * Create new book
     *
     */
    public function store(CreateBookRequest $request)
    {
        $input = $request->only(['name','annotation','authors','price','isHidden','genreId']);
        $this->book = new Book($input);
        $this->book->save();

        return response(['response' =>
            Lang::get('manageBook.createSuccess',['bookName' => $this->book->getName()])],201);
    }

    /**
     * Update existing book
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $prevName = $book->getName();
        $input = $request->only(['name','annotation','authors','price','isHidden', 'genreId']);
        $book->update($input);

        return response()->json(['response'=>
            Lang::get('manageBook.updateSuccess',['bookPrevName' => $prevName])],201);
    }
}
