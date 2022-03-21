<?php

namespace Tests\Integration\BookManage;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Core\Constants;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use function _PHPStan_ae8980142\RingCentral\Psr7\parse_request;
use function bcrypt;
use function route;

class ValidationCreateTest extends \Tests\TestCase implements Constants
{
    use WithoutMiddleware;

    private $faker;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = Factory::create();
        DB::beginTransaction();

    }


    /**
     * Test manageBook store method with correct data
     * Assert status 201 and that model will store
     * @dataProvider createBookCorrectDataProvider
     * @param array $data
     * @return void
     */
    public function testCorrectData(array $data)
    {
        $genre = new Genre(['name' => $this->faker->name]);
        $genre->save();
        $data['genreId'] = $genre->id;

        $this->post(route('manageBook.store'), $data)->assertStatus(201);
        $book = new Book();

        $this->assertEquals($data['name'], $book::query()->where('name', '=', $data['name'])->first()->name);
    }


    public function createBookCorrectDataProvider()
    {
        $faker = Factory::create();


        return
            [
                [
                    [
                        'name' => $faker->firstName,
                        'annotation' => $faker->streetName,
                        'authors' => $faker->name,
                        'price' => $faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                    ]
                ],

                [
                    [
                        'name' => $faker->firstName,
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                    ]
                ],

            ];
    }


    /**
     * Test manageBook store method with incorrect data
     * Assert, that session will have errors and model will not be store
     * @dataProvider createBookIncorrectDataProvider
     * @param array $data
     * @return void
     */
    public function testIncorrectData(array $data)
    {
        if (!isset($data['genreId'])) {
            $genre = new Genre(['name' => $this->faker->name]);
            $genre->save();
            $data['genreId'] = $genre->id;
        }


        $this->post(route('manageBook.store'), $data)->assertSessionHasErrors();
        $book = new Book();
        $book = $book::query()->where('name', '=', $data['name'])->first();

        $this->assertEmpty($book);

    }


    public function createBookIncorrectDataProvider()
    {
        $faker = Factory::create();
        return
            [
                'empty_name' => [
                    [
                        'name' => '',
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'genreId' => null

                    ]
                ], 'empty_annotation' => [
                [
                    'name' => $faker->name,
                    'annotation' => '',
                    'authors' => $faker->streetName,
                    'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                    'genreId' => null

                ]
            ], 'empty_authors' => [
                [
                    'name' => $faker->name,
                    'annotation' => $faker->streetName,
                    'authors' => '',
                    'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                    'genreId' => null

                ]
            ],
                'empty_price' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => '',
                        'genreId' => null

                    ]
                ],

                'empty_genre' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => '',
                        'genreId' => ''

                    ]
                ],

                'incorrect_genre' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => '',
                        'genreId' => $faker->email

                    ]
                ],


                'name_length_exceeded' => [
                    [
                        'name' => $faker->sentence(self::MAX_BOOK_NAME_LEN + 1),
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'genreId' => null

                    ]
                ],
                'annotation_length_exceeded' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->sentence(self::MAX_ANNOTATION_LEN + 1),
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'genreId' => null

                    ]
                ],
                'authors_length_exceeded' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->sentence(self::MAX_AUTHORS_NAME_LEN + 1),
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'genreId' => null

                    ]
                ],
                'max_price_exceeded' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MAX_BOOK_PRICE + 1,
                        'genreId' => null

                    ]
                ],

                'price_less_than_min_price_value' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MIN_BOOK_PRICE - 1,
                        'genreId' => null
                    ]
                ],


            ];
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
