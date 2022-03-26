<?php
namespace App\Virtual;


/**
 * @OA\Schema (
 *  description="Auth request",
 *     type="object",
 *
 *
 * )
 */
class AuthRegisterRequest
{

    /**
     * @OA\Property (
     *     title="Email",
     *     description="User account email",
     *     format="string",
     *     example="test@mail.ru"
     * )
     */
    public $email;


    /**
     * @OA\Property (
     *     title="name",
     *     description="User account name",
     *     format="string",
     *     example="TEST_USER"
     * )
     */
    public $name;



    /**
     * @OA\Property (
     *     title="password",
     *     description="User account password",
     *     format="string",
     *     example="qwerty"
     * )
     */
    public $password;


    /**
     * @OA\Property (
     *     title="password_confirmation",
     *     description="Confirmation passowods in forms",
     *     format="string",
     *     example="qwerty"
     * )
     */
    public $password_confirmation;
}
