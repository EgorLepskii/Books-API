<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    use HasFactory;

    public $fillable =
        [
           'name'
        ];


    public function getId()
    {
        return $this->id;
    }

    public function books(): hasMany
    {
        return $this->hasMany(Book::class, 'genreId', 'id');
    }
}
