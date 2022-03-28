<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;


class BookPageController extends Controller
{
    private Book $book;

    /**
     * @var int
     */
    public const MAX_SHOW_PRODUCT_COUNT = 5;

    /**
     * @var int
     */
    public const SIMILAR_PRICE_DIFFERENCE = 20;

    /**
     * @OA\Get(
     *     path="/bookPage/book",
     *     summary="Get list of books",
     *     tags={"BookPage"},
     * @OA\Response(
     *         response=200,
     *         description="successful operation",
     * @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        $this->book = new Book();
        return response()
            ->json(
                [
                    'books' => $this->book::query()
                        ->limit(self::MAX_SHOW_PRODUCT_COUNT)
                        ->orderByDesc('id')->get()
                ]
            );
    }

    /**
     * @OA\Get(
     *     path="/bookPage/book/{book}",
     *     summary="Show book and the similar books with the same genre",
     *     tags={"BookPage"},
     * @OA\Parameter(
     *          name="book",
     *          description="book id",
     *          required=true,
     *          in="path",
     * @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     * @OA\Response(
     *         response=200,
     *         description="successful operation",
     * @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     * @OA\Response(
     *         response="404",
     *         description="Book is not found",
     *     )
     * )
     */
    public function show(Book $book): JsonResponse
    {
        $this->book = $book;
        $this->book->setBuilder();

        $priceDifference = $this->book->getPrice() * self::SIMILAR_PRICE_DIFFERENCE / 100;
        $leftPriceLimit = $this->book->getPrice() - $priceDifference;
        $rightPriceLimit = $this->book->getPrice() + $priceDifference;

        $collection =
            $this->book
                ->getSimilarByPrice($leftPriceLimit, $rightPriceLimit)
                ->getSimilarByGenre()
                ->getBuilder()
                ->orderByDesc('id')
                ->get();


        return response()->json(['book' => $book, 'similarBooks' => $collection]);
    }
}
