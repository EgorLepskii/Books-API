<?php

namespace Tests\Integration\Search;

use App\Models\Book;
use App\Models\Genre;
use Core\Constants;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class NameTest extends \Tests\TestCase implements Constants
{
    use WithoutMiddleware;

    private $faker;
    private Genre $genre;
    private Book $book;
    private const TEST_NAME = "iFUGpX1bCO29+A";


    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = Factory::create();
        DB::beginTransaction();

        $this->genre = new Genre(['name' => $this->faker->name]);
        $this->genre->save();
        $this->book = new Book
        (
            [
                'name' => self::TEST_NAME,
                'annotation' => $this->faker->name,
                'authors' => $this->faker->name,
                'price' => 0,
                'isHidden' => false,
                'genreId' => $this->genre->getId()
            ]
        );

        $this->book->setBuilder();
        $this->book->save();
    }


    /**
     * Test books with names, that contains string of searching name
     * @dataProvider dataProvider
     * @return void
     */
    public function test_similar_books_correct(array $data)
    {
        $data['genreId'] = $this->genre->getId();
        $book = new Book($data);
        $book->save();

        $books = $this->book->searchByName($this->book->getName())->getBuilder()->get();
        $this->assertEquals($books[0]->getName(), $book->getName());
    }

    public function dataProvider()
    {
        $faker = Factory::create();
        return
            [
                'substr_in_begin' =>
                    [
                        [
                            'name' => self::TEST_NAME . $faker->lexify('?????'),
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => 0,
                            'isHidden' => false,
                            'genreId' => 0
                        ]
                    ],

                'substr_in_middle' =>
                    [
                        [
                            'name' =>
                                $faker->lexify('???') . self::TEST_NAME . $faker->lexify('?????'),
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => 0,
                            'isHidden' => false,
                            'genreId' => 0
                        ]
                    ],

                'substr_in_end' =>
                    [
                        [
                            'name' => $faker->lexify('?????').self::TEST_NAME,
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => 0,
                            'isHidden' => false,
                            'genreId' => 0
                        ]
                    ],

                'substr_lower_case' =>
                [
                    [
                        'name' => strtolower($faker->lexify('?????').self::TEST_NAME),
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => 0,
                        'isHidden' => false,
                        'genreId' => 0
                    ]
                ]
            ];
    }


    /**
     * Test books with names, that not contains string of searching name
     * @dataProvider dataProviderIncorrect
     * @return void
     */
    public function test_similar_books_incorrect(array $data)
    {
        $data['genreId'] = $this->genre->getId();
        $book = new Book($data);
        $book->save();

        $books = $this->book->searchByName($this->book->getName())->getBuilder()->get();
        $this->assertEmpty($books->toArray());
    }

    public function dataProviderIncorrect()
    {
        $faker = Factory::create();
        return
            [
                'incorrect_substr' =>
                    [
                        [
                            'name' => $faker->lexify('?????'),
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => 0,
                            'isHidden' => false,
                            'genreId' => 0
                        ]
                    ],

            ];
    }


    /**
     * @return void
     */
    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
