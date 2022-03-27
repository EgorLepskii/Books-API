<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @OA\Schema (
 *  @OA\Property(
 *      property="name",
 *      type="string"
 *  ),
 *     @OA\Property(
 *      property="id",
 *      type="integer"
 *  )
 * )
 */

class Genre extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    public $fillable =
        [
           'name'
        ];


    public function getId(): int
    {
        return $this->id ?? -1;
    }

    public function books(): hasMany
    {
        return $this->hasMany(Book::class, 'genreId', 'id');
    }
}
