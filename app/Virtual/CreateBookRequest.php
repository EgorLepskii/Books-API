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
class CreateBookRequest
{

    /**
     * @OA\Property (
     *     title="name",
     *     description="Book  name",
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
    public $annotation;

    /**
     * @OA\Property (
     *     title="Authors",
     *     description="Book authors",
     *     format="string",
     *     example="Authors"
     * )
     */
    public $authors;

    /**
     * @OA\Property (
     *     title="price",
     *     description="Book price",
     *     format="number",
     *     example="100.5"
     * )
     */
    public $price;

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
