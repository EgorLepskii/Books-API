<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;

class Test extends TestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function test_example()
    {
        $book = new Book(['name' => 'name']);
        dd($book);
    }
}
