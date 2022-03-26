<?php
namespace App\Virtual;


use phpDocumentor\Reflection\Types\Integer;

/**
 * @OA\Schema (
 *  description="Create book request",
 *     type="object",
 *
 *
 * )
 */
class SearchBookRequest
{

    /**
     * @OA\Property (
     *     title="name",
     *     description="Book name",
     *     format="string",
     *     example="Shine"
     * )
     */
    public $name;


    /**
     * @OA\Property (
     *     title="annotation",
     *     description="Book annotation",
     *     format="string",
     *     example="annotation"
     * )
     */
    public $authors;

    /**
     * @OA\Property (
     *     title="Authors",
     *     description="Book authors",
     *     format="string",
     *     example="Authors"
     * )
     */
    public $leftPrice;

    /**
     * @OA\Property (
     *     title="price",
     *     description="Book price",
     *     format="number",
     *     example="100.5"
     * )
     */
    public $rightPrice;

    /**
     * @OA\Property (
     *     title="bool",
     *     description="Book is hidden or not",
     *     format="boolean",
     *     example="false"
     * )
     */
    public $isHidden;


    /**
     * @OA\Property (
     *     title="genreId",
     *     description="Book genre id",
     *     format="int",
     *     example="1958"
     * )
     */
    public $genreId;
}
