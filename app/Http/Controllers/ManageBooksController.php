<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
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
        $input = $request->input();
        $book = new Book($input);
        $book->save();
        return response()->json(['response'=>'success'],201);
    }


    public function update(UpdateBookRequest $request, Book $book)
    {
        $input = $request->input();
        $book->fill($input)->save();
    }
}
