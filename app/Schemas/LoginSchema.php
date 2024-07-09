<?php

/**
 * @OA\Schema(
 *      title="LoginSchema",
 *      description="LoginSchema body data",
 *      type="object"
 * )
 */
class LoginSchema
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the user",
     *      example="super-admin"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="email",
     *      description="Email of the user",
     *      example="super@mail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="password",
     *      description="Password of the user",
     *      example="0000"
     * )
     *
     * @var string
     */
    public $password;
}
