<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBookRequest;
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
     * @OA\Get(
     *     path="/bookSearch/book",
     *     summary="Get books by propertirs",
     *     tags={"bookSearch"},
     *
     *
     *
     *         @OA\Parameter(
     *          name="name",
     *          description="book name",
     *
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),@OA\Parameter(
     *          name="authors",
     *          description="book authors",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),@OA\Parameter(
     *          name="leftPrice",
     *          description="book left price limit",
     *
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),@OA\Parameter(
     *          name="rightPrice",
     *          description="book right price limit",
     *
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *
     *      @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *
     *
     *     )
     * )
     */
    public function index(SearchBookRequest $searchRequest): \Illuminate\Http\JsonResponse
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

        return response()->json(['books' => $books], 200);
    }


}
