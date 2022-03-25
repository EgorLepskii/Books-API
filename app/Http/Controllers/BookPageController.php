<?php

namespace App\Http\Controllers;

use App\Models\Book;

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
     * Show books with MAX_SHOW_PRODUCT_COUNT limit
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function index(): \Illuminate\Http\JsonResponse
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
     * Show book and the similar books with the same genre
     */
    public function show(Book $book): \Illuminate\Http\JsonResponse
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
