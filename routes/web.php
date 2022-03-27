<?php

use App\Http\Controllers\BookPageController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ManageBooksController;
use App\Http\Controllers\SearchBookController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

App::setLocale('ru');


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('user.refresh');
}
);
// test 2 
Route::group(
    [
      //  'middleware' => 'admin',
        'prefix' => 'bookManage'
    ], function () {
    Route::post('/book', [ManageBooksController::class, 'store'])->name('manageBook.store');
    Route::post('/book/{book}', [ManageBooksController::class, 'update'])->name('manageBook.update');
}
);


Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'bookPage'
    ], function () {
    Route::get('/book', [BookPageController::class, 'index'])->name('bookPage.index');
    Route::get('/book/{book}', [BookPageController::class, 'show'])->name('bookPage.show');
}
);

Route::group(
    [
        'prefix' => 'bookSearch'
    ], function () {
    Route::get(
        '/book',
        [SearchBookController::class, 'index']
    )->name('bookSearch.index');
}
);




