<?php

namespace App\Models;

use Core\Constants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use phpDocumentor\Reflection\Types\True_;

class Book extends Model implements Constants
{
    use HasFactory;

    public Builder $builder;
    public const INCORRECT_NAME_INPUT = "";
    public const INCORRECT_AUTHORS_INPUT = "";
    public const INCORRECT_PRICE_INPUT = -1;

    protected $fillable =
        [
            'name',
            'genreId',
            'annotation',
            'authors',
            'price'
        ];

    /**
     * @return string
     */
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

    /**
     * @return HasOne
     */
    public function genre(): hasOne
    {
        return $this->hasOne(Genre::class, 'id', 'genreId');
    }

    /**
     * Get similar books between price, except current book
     * @param int $leftLimit
     * @param int $rightLimit
     * @return Book
     */
    public function getSimilarByPrice(float $leftLimit, float $rightLimit)
    {
        $this->builder
            ->where('id', '!=', $this->getId())
            ->whereBetween('price', [$leftLimit, $rightLimit])
            ->limit(self::MAX_SELECT_COUNT);

        return $this;
    }

    /**
     * Get similar books, except current book
     * @return Book
     */
    public function getSimilarByGenre()
    {
        $this->builder
            ->where('id', '!=', $this->getId())
            ->where('genreId', '=', $this->getGenreId())
            ->limit(self::MAX_SELECT_COUNT);

        return $this;
    }

    /**
     * @return void
     */
    public function setBuilder()
    {
        $this->builder = Book::query();
    }

    /**
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Search book by name substring. Update builder for further requests
     * @param string $name
     * @return Book
     */
    public function searchByName(string $name)
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
     * @param string $name
     * @return Book
     */
    public function searchByAuthors(string $authors)
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
     * @param float $leftLimit
     * @param float $rightLimit
     * @return Book
     */
    public function searchByPrice(float $leftLimit, float $rightLimit)
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
