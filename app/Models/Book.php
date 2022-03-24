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
     * @return Builder[]|Collection
     */
    public function getSimilarByPrice(int $leftLimit, int $rightLimit)
    {
        return $this::query()
            ->where('id', '!=', $this->getId())
            ->whereBetween('price', [$leftLimit, $rightLimit])
            ->limit(self::MAX_SELECT_COUNT)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Get similar books, except current book
     * @return Builder[]|Collection
     */
    public function getSimilarByGenre()
    {
        return self::query()
            ->where('id', '!=', $this->getId())
            ->where('genreId', '=', $this->getGenreId())
            ->limit(self::MAX_SELECT_COUNT)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Search book by name substring
     * @param string $name
     * @return Builder[]|Collection
     */
    public function searchByName(string $name)
    {

        return Book::query()
            ->where('name','LIKE',"%".strtolower($name)."%")
            ->where('id','!=', $this->getId())
            ->limit(self::MAX_SELECT_COUNT)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Search book by authors substring
     * @param string $name
     * @return Builder[]|Collection
     */
    public function searchByAuthors(string $authors)
    {
        return Book::query()
            ->where('authors','LIKE',"%".strtolower($authors)."%")
            ->where('id','!=', $this->getId())
            ->limit(self::MAX_SELECT_COUNT)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Search book by  price range
     * @param string $name
     * @return Builder[]|Collection
     */
    public function searchByPrice(int $leftLimit, int $rightLimit)
    {
        return Book::query()
            ->whereBetween('price', [$leftLimit, $rightLimit])
            ->where('id','!=', $this->getId())
            ->limit(self::MAX_SELECT_COUNT)
            ->orderByDesc('id')
            ->get();
    }

}
