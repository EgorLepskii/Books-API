<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use phpDocumentor\Reflection\Types\True_;

class Book extends Model
{
    use HasFactory;
    private const PRICE_SIMILAR_DIFFERENCE = 20;
    private Genre $genre;
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
        return $this->id  ?? -1;
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
        return $this->hasOne(Genre::class,'id','genreId');
    }

    /**
     * Get similar books between price, except current book
     * @param int $leftLimit
     * @param int $rightLimit
     */
    public function getSimilarByPrice(int $leftLimit, int $rightLimit)
    {
        return $this::query()
            ->where('id','!=', $this->getId())
            ->whereBetween('price',[$leftLimit, $rightLimit])
            ->get();
    }

    /**
     * Get similar books, except current book
     */
    public function getSimilarByGenre()
    {
        return self::query()
            ->where('id','!=', $this->getId())
            ->where('genreId','=', $this->getGenreId())
            ->get();
    }

}
