<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use http\Env\Response;
use Illuminate\Http\Request;
use SebastianBergmann\Diff\Exception;

class ManageBooksController extends Controller
{

    /**
     * Create new Book
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBookRequest $request)
    {
        $input = $request->only(['name','annotation','authors','price','isHidden']);
        $book = new Book();
        $book->save();
        return response()->json(['response'=>'success'],201);
    }


    /**
     * Update existing book
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $input = $request->only(['name','annotation','authors','price','isHidden']);
        $book->update($input);
        return response()->json(['data'=>'updated successfully'], 201);
    }
}
