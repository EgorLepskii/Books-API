<?php

namespace Tests\Integration\Patterns;

use \App\Models\Book;
use \App\Models\Genre;
use \Core\Constants;
use \Dflydev\DotAccessData\Data;
use \Faker\Factory;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Lang;
use \PhpParser\Node\Stmt\DeclareDeclare;

class CreateIncorrectDataTest extends \Tests\TestCase implements \Core\Constants
{
    use WithoutMiddleware;
    private $faker;

    private Genre $genre;

    private Book $book;

    /**
     * @var int
     */
    private const PRICE_FOR_TEST = 100;

    private array $languages = ['en', 'ru'];

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = Factory::create();
        DB::beginTransaction();

        $this->genre = new Genre(['name' => $this->faker->name]);
        $this->genre->save();

        $this->book = new Book(
            [
                'name' => $this->faker->firstName,
                'annotation' => $this->faker->streetName,
                'authors' => $this->faker->name,
                'price' => self::PRICE_FOR_TEST,
                'isHidden' => false,
                'genreId' => $this->genre->getId()
            ]
        );

        $this->book->save();

    }


    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createApplication();

    }

    /**
     * Test en and rus patterns for incorrect data
     *
     * @dataProvider IncorrectDataProviderEn
     */
    public function testIncorrectDataEn(array $data, array $expectedSessionErrorFields): void
    {
        Lang::setLocale('en');

        $this->post(\route('manageBook.store'), $data)->assertSessionHasErrors(
            $expectedSessionErrorFields
        );
    }

    /**
     * Test en and rus patterns for incorrect data
     *
     * @dataProvider IncorrectDataProviderRu
     */
    public function testIncorrectDataRu(array $data, array $expectedSessionErrorFields): void
    {
        Lang::setLocale('ru');

        $this->post(\route('manageBook.store'), $data)->assertSessionHasErrors(
            $expectedSessionErrorFields
        );


    }


    /**
     * @return array<string, array<int, array{name?: mixed, annotation?: mixed, authors?: mixed, price?: mixed, genreId?: mixed}>>
     */
    public function IncorrectDataProviderEn(): array
    {
        Lang::setLocale('en');
        return $this->IncorrectDataProvider();
    }

    /**
     * @return array<string, array<int, array{name?: mixed, annotation?: mixed, authors?: mixed, price?: mixed, genreId?: mixed}>>
     */
    public function IncorrectDataProviderRu(): array
    {
        Lang::setLocale('ru');
        return $this->IncorrectDataProvider();

    }

    /**
     * @return array
     */
    public function IncorrectDataProvider(): array
    {
        $faker = Factory::create();

        return
            [
                'all_empty_fields' =>
                    [
                        [
                            'name' => '',
                            'annotation' => '',
                            'authors' => '',
                            'price' => '',
                            'genreId' => '',
                        ],
                        [
                            'name' => Lang::get('FormErrors.required'),
                            'genreId' => Lang::get('FormErrors.required'),
                            'annotation' => Lang::get('FormErrors.required'),
                            'authors' => Lang::get('FormErrors.required'),
                            'price' => Lang::get('FormErrors.required'),
                        ]
                    ],

                'incorrect_price_type' =>
                    [
                        [
                            'name' => $faker->name,
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => $faker->name,
                            'genreId' => 0,

                        ],
                        [
                            'price' => Lang::get('FormErrors.numeric'),
                        ]
                    ],

                'incorrect_genre_type' =>
                    [
                        [
                            'name' => $faker->name,
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => $faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                            'genreId' => $faker->name,

                        ],
                        [
                            'genreId' => Lang::get('FormErrors.integer'),
                        ]
                    ],

                'name_len_overflow' =>
                    [
                        [
                            'name' => $faker->lexify(\str_repeat('?', self::MAX_FIELD_LENGTH + 1)),
                            'annotation' => $faker->name,
                            'authors' => $faker->name,
                            'price' => $faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                            'genreId' => 0,

                        ],
                        [
                            'name' => Lang::get('FormErrors.max', ['maxValue'=>self::MAX_FIELD_LENGTH]),
                        ]
                    ],
                'annotation_len_overflow' =>
                    [
                        [
                            'name' => $faker->name,
                            'annotation' => $faker->lexify(\str_repeat('?', self::MAX_FIELD_LENGTH + 1)),
                            'authors' => $faker->name,
                            'price' => $faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                            'genreId' => 0,

                        ],
                        [
                            'annotation' => Lang::get('FormErrors.max', ['maxValue'=>self::MAX_FIELD_LENGTH]),
                        ]
                    ],
                'authors_len_overflow' =>
                    [
                        [
                            'name' => $faker->name,
                            'annotation' => $faker->name,
                            'authors' => $faker->lexify(\str_repeat('?', self::MAX_FIELD_LENGTH + 1)),
                            'price' => $faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_AUTHORS_NAME_LEN),
                            'genreId' => 0,

                        ],
                        [
                            'authors' => Lang::get('FormErrors.max', ['maxValue'=>self::MAX_FIELD_LENGTH]),
                        ]
                    ],

            ];
    }


    /**
     * Assert, that session will have error if saving book name already exists
     */
    public function testBookExist(): void
    {
        $data =
            [
                'name' => $this->book->getName(),
                'annotation' => $this->faker->name,
                'authors' => $this->faker->name,
                'price' => $this->faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                'genreId' => $this->genre->getId(),

            ];

        foreach ($this->languages as $language) {
            Lang::setLocale($language);

            $this->post(\route('manageBook.store'), $data)->assertSessionHasErrors(
                [
                    'name' => Lang::get('FormErrors.unique')
                ]
            );
        }
    }


    /**
     * Assert, that session will have errors if saving book will contain genre, that not exist in DataBase
     */
    public function testGenreNotExists(): void
    {
        $data =
            [
                'name' => $this->faker->name,
                'annotation' => $this->faker->name,
                'authors' => $this->faker->name,
                'price' => $this->faker->numberBetween(self::MIN_BOOK_PRICE, self::MAX_BOOK_PRICE),
                'genreId' => -1,

            ];

        foreach ($this->languages as $language) {
            Lang::setLocale($language);

            $this->post(\route('manageBook.store'), $data)->assertSessionHasErrors(
                [
                    'genreId' => Lang::get('FormErrors.exists')
                ]
            );
        }
    }


    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
