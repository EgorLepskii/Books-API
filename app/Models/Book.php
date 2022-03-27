<?php

namespace App\Models;

use Core\Constants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @OA\Schema (
 *  @OA\Property(
 *      property="name",
 *      type="string",
 *       description="Book name(unique)"
 *  ),
 *  @OA\Property(
 *      property="id",
 *      type="integer",
 *     description="Book id"
 *
 *  ),
 *  @OA\Property(
 *      property="genreId",
 *      type="integer",
 *     description="Book genre (hasOne relation)"
 *
 *  ),
 *   @OA\Property(
 *      property="builder",
 *      type=" \Illuminate\Database\Eloquent\Builder"
 *  ),
 *     @OA\Property(
 *      property="annotation",
 *      type="string"
 *  ),
 *     @OA\Property(
 *      property="authors",
 *      type="string"
 *  ),
 * )
 */

class Book extends Model implements Constants
{
    use HasFactory;

    public Builder $builder;

    /**
     * @var string
     */
    public const INCORRECT_NAME_INPUT = "";

    /**
     * @var string
     */
    public const INCORRECT_AUTHORS_INPUT = "";

    /**
     * @var int
     */
    public const INCORRECT_PRICE_INPUT = -1;

    /**
     * @var string[]
     */
    protected $fillable =
        [
            'name',
            'genreId',
            'annotation',
            'authors',
            'price',
            'isAdmin'
        ];

    public function getName(): string
    {
        return $this->name ?? "";
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->id ?? -1;
    }

    /**
     * @return int|mixed
     */
    public function getGenreId()
    {
        return $this->genreId ?? -1;
    }

    /**
     * @return int|mixed
     */
    public function getPrice()
    {
        return $this->price ?? -1;
    }

    public function genre(): hasOne
    {
        return $this->hasOne(Genre::class, 'id', 'genreId');
    }

    /**
     * Get similar books between price, except current book
     */
    public function getSimilarByPrice(float $leftLimit, float $rightLimit): self
    {
        $this->builder
            ->where('id', '!=', $this->getId())
            ->whereBetween('price', [$leftLimit, $rightLimit])
            ->limit(self::MAX_SELECT_COUNT);

        return $this;
    }

    /**
     * Get similar books, except current book
     */
    public function getSimilarByGenre(): self
    {
        $this->builder
            ->where('id', '!=', $this->getId())
            ->where('genreId', '=', $this->getGenreId())
            ->limit(self::MAX_SELECT_COUNT);

        return $this;
    }

    public function setBuilder(): void
    {
        $this->builder = Book::query();
    }

    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * Search book by name substring. Update builder for further requests
     */
    public function searchByName(string $name): self
    {
        if ($name == self::INCORRECT_NAME_INPUT) {
            return $this;
        }

        $this->builder
            ->where('name', 'LIKE', "%" . $name . "%")
            ->where('id', '!=', $this->getId())
            ->limit(self::MAX_SELECT_COUNT);

        return $this;
    }

    /**
     * Search book by name authors. Update builder for further requests
     *
     * @param string $name
     */
    public function searchByAuthors(string $authors): self
    {

        if ($authors == self::INCORRECT_AUTHORS_INPUT) {
            return $this;
        }

        $this->builder
            ->where('authors', 'LIKE', "%" . strtolower($authors) . "%")
            ->where('id', '!=', $this->getId())
            ->limit(self::MAX_SELECT_COUNT);

        return $this;

    }

    /**
     * Search book by price substring. Update builder for further requests
     */
    public function searchByPrice(float $leftLimit, float $rightLimit): self
    {
        if ($leftLimit == self::INCORRECT_PRICE_INPUT || $rightLimit == self::INCORRECT_PRICE_INPUT) {
            return $this;
        }

        $this->builder
            ->whereBetween('price', [$leftLimit, $rightLimit])
            ->where('id', '!=', $this->getId())
            ->limit(self::MAX_SELECT_COUNT);


        return $this;

    }

}
