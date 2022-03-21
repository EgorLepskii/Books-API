<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? "";
    }

    protected $fillable =
        [
            'name',
            'genreId',
            'annotation',
            'authors',
            'price'
        ];

    public function genre()
    {
        return $this->hasOne('genres','genreId','id');
    }
}
