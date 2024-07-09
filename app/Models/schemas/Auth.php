<?php

/**
 * @OA\Schema(
 *      title="Auth",
 *      description="Auth body data",
 *      type="object"
 * )
 */
class Auth
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the auth",
     *      example="super-admin"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="email",
     *      description="Email of the auth",
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
