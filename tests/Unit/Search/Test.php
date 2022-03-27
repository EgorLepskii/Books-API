<?php

namespace Search;

use App\Models\Book;
use Tests\TestCase;
use function dd;

class Test extends TestCase
{

    public function test_incorrect_authors()
    {
        $book = new Book();
        $book->setBuilder();
        $afterSearchBookModel = $book->searchByAuthors(Book::INCORRECT_AUTHORS_INPUT)->getBuilder();
        $this->assertEquals(Book::all(), $afterSearchBookModel->get());

    }public function test_incorrect_name()
    {
        $book = new Book();
        $book->setBuilder();
        $afterSearchBookModel = $book->searchByName(Book::INCORRECT_NAME_INPUT)->getBuilder();
        $this->assertEquals(Book::all(), $afterSearchBookModel->get());

    }public function test_incorrect_price()
    {
        $book = new Book();
        $book->setBuilder();
        $afterSearchBookModel = $book
            ->searchByPrice(Book::INCORRECT_PRICE_INPUT, Book::INCORRECT_PRICE_INPUT)
            ->getBuilder();
        $this->assertEquals(Book::all(), $afterSearchBookModel->get());

    }
}
