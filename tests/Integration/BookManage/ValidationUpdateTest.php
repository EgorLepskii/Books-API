<?php

namespace Tests\Integration\BookManage;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin;
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

class ValidationUpdateTest extends \Tests\TestCase implements Constants
{


    private $faker;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = Factory::create();
        DB::beginTransaction();

        $admin = new Admin(
            [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => $this->faker->password
            ]
        );

        $admin->save();

        auth()->login($admin);


    }


    /**
     * Test update book with correct data
     * Assert status 201.
     * Assert, that book model will be save in database
     *
     * @dataProvider updateBookCorrectDataProvider
     * @param        array<string, true>|array<string, float>|array<string, int>|array<string, null>|array<string, string> $data
     */
    public function testCorrectData(array $data): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        if (!isset($data['genreId'])) {
            $genre = new Genre(['name' => $this->faker->name]);
            $genre->save();
            $data['genreId'] = $genre->getId();
        }

        $book = new Book($data);
        $book->save();

        $data['name'] = $this->faker->firstName;
        $this->post(route('manageBook.update', $book->getId()), $data)->assertStatus(201);
        $this->assertEquals(
            $data['name'],
            $book::query()->where('id', '=', $book->getId())->first()->name
        );
    }


    /**
     * @return array<int, array<int, array{name: string, annotation: string, authors: string, price: float|int, isHidden: bool, genreId: null}>>
     */
    public function updateBookCorrectDataProvider(): array
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
                        'isHidden' => false,
                        'genreId' => null

                    ]
                ],

                [
                    [
                        'name' => $faker->firstName,
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'isHidden' => true,
                        'genreId' => null

                    ]
                ],

            ];
    }

    /**
     * Test manageBook update method with incorrect data
     * Assert, that session will have errors and model will not be updated
     *
     * @dataProvider createBookIncorrectDataProvider
     * @param        array                                                                                                                      $data
     * @param        array<string, float>|array<string, int>|array<string, mixed>|array<string, null>|array<string, string>|array<string, true> $incorrectData
     */
    public function testIncorrectData(array $incorrectData): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $genre = new Genre(['name' => $this->faker->name]);
        $genre->save();

        if (!isset($incorrectData['genreId'])) {
            $incorrectData['genreId'] = $genre->getId();
        }

        $correctData =
            [
                'name' => $this->faker->name,
                'annotation' => $this->faker->name,
                'authors' => $this->faker->name,
                'price' => $this->faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                'isHidden' => true,
                'genreId' => $genre->getId()
            ];

        $book = new Book($correctData);
        $book->save();
        $this->post(route('manageBook.update', $book->getId()), $incorrectData)->assertSessionHasErrors();
        $this->assertEquals(
            $book->getName(),
            $book::query()->where('id', '=', $book->getId())->first()->name
        );

    }


    /**
     * @return array{empty_name: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, empty_annotation: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, empty_authors: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, empty_price: array<int, array{name: string, annotation: string, authors: string, price: string, genreId: null}>, name_length_exceeded: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, annotation_length_exceeded: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, authors_length_exceeded: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, max_price_exceeded: array<int, array{name: string, annotation: string, authors: string, price: float, isHidden: true, genreId: null}>, price_less_than_min_price_value: array<int, array{name: string, annotation: string, authors: string, price: int, isHidden: true, genreId: null}>, hidden_field_incorrect: array<int, array{name: string, annotation: string, authors: string, price: int, isHidden: string, genreId: null}>, hidden_field_empty: array<int, array{name: string, annotation: string, authors: string, price: int, isHidden: true, genreId: null}>, empty_genre: array<int, array{name: string, annotation: string, authors: string, price: int, isHidden: true, genreId: string}>, incorrect_genre: array<int, array{name: string, annotation: string, authors: string, price: int, isHidden: true, genreId: mixed}>}
     */
    public function createBookIncorrectDataProvider(): array
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
                        'isHidden' => true,
                        'genreId' => null

                    ]
                ], 'empty_annotation' => [
                [
                    'name' => $faker->name,
                    'annotation' => '',
                    'authors' => $faker->streetName,
                    'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                    'isHidden' => true,
                    'genreId' => null
                ]
                ], 'empty_authors' => [
                [
                    'name' => $faker->name,
                    'annotation' => $faker->streetName,
                    'authors' => '',
                    'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                    'isHidden' => true,
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
                'name_length_exceeded' => [
                    [
                        'name' => $faker->sentence(self::MAX_BOOK_NAME_LEN + 1),
                        'annotation' => $faker->streetName,
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'isHidden' => true,
                        'genreId' => null
                    ]
                ],
                'annotation_length_exceeded' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->sentence(self::MAX_ANNOTATION_LEN + 1),
                        'authors' => $faker->streetName,
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'isHidden' => true,
                        'genreId' => null


                    ]
                ],
                'authors_length_exceeded' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->sentence(self::MAX_AUTHORS_NAME_LEN + 1),
                        'price' => $faker->randomFloat(4, self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                        'isHidden' => true,
                        'genreId' => null


                    ]
                ],
                'max_price_exceeded' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MAX_BOOK_PRICE + 1,
                        'isHidden' => true,
                        'genreId' => null
                    ]
                ],

                'price_less_than_min_price_value' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MIN_BOOK_PRICE - 1,
                        'isHidden' => true,
                        'genreId' => null


                    ]
                ],

                'hidden_field_incorrect' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MIN_BOOK_PRICE,
                        'isHidden' => $faker->email,
                        'genreId' => null


                    ]
                ], 'hidden_field_empty' => [

                [
                    'name' => $faker->name,
                    'annotation' => $faker->name,
                    'authors' => $faker->name,
                    'price' => self::MIN_BOOK_PRICE - 1,
                    'isHidden' => true,
                    'genreId' => null


                ]
                ],
                'empty_genre' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MIN_BOOK_PRICE - 1,
                        'isHidden' => true,
                        'genreId' => ''
                    ]
                ],

                'incorrect_genre' => [
                    [
                        'name' => $faker->name,
                        'annotation' => $faker->name,
                        'authors' => $faker->name,
                        'price' => self::MIN_BOOK_PRICE - 1,
                        'isHidden' => true,
                        'genreId' => $this->faker->email
                    ]
                ],


            ];
    }


    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
