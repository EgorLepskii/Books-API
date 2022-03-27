<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;


class ManageBooksController extends Controller
{
    private Book $book;


    /**
     * @OA\Post(
     *     path="/bookManage/book",
     *     summary="Save book",
     *     tags={"bookManage"},
     *
     *     @OA\RequestBody (
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/CreateBookRequest")
     *
     * ),
     *
     *     security={{ "apiAuth": {} }},
     *
     *
     *     @OA\Response(
     *         response=201,
     *         description="book created success",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Redirect to current page (reason:incorrect data)",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied (User is not authorized or user is not an admin)",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *
     *
     *     )
     * )
     */
    public function store(CreateBookRequest $createBookRequest): JsonResponse
    {
        $input = $createBookRequest->only(['name', 'annotation', 'authors', 'price', 'isHidden', 'genreId']);
        $this->book = new Book($input);
        $this->book->save();

        return response()->json
        (
            [
                'response' =>
                    Lang::get('manageBook.createSuccess', ['bookName' => $this->book->getName()])
            ], 201
        );
    }

    /**
     * @OA\Post(
     *     path="/bookManage/book/{book}",
     *     summary="Update book",
     *     tags={"bookManage"},
     *     @OA\Parameter(
     *          name="book",
     *          description="book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody (
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateBookRequest")
     *
     * ),
     *
     *     security={{ "apiAuth": {} }},
     *
     *
     *     @OA\Response(
     *         response=201,
     *         description="book update success",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied (User is not authorized or user is not an admin)",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *
     *
     *     )
     * )
     */
    public function update(UpdateBookRequest $updateBookRequest, Book $book): JsonResponse
    {
        $prevName = $book->getName();
        $input = $updateBookRequest->only(['name', 'annotation', 'authors', 'price', 'isHidden', 'genreId']);
        $book->update($input);

        return response()->json
        (
            [
                'response' =>
                    Lang::get('manageBook.updateSuccess', ['bookPrevName' => $prevName])
            ], 201
        );
    }
}
