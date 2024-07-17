<?php

namespace App\Schemas;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      title="PasswordLogin",
 *      description="Login body data",
 *      type="object"
 * )
 */
class PasswordLogin extends JsonResource
{
    /**
     * @OA\Property(
     *      title="key",
     *      description="Key of the acces",
     *      example="key"
     * )
     *
     * @var string
     */
    public $key;

    /**
     * @OA\Property(
     *      title="secret",
     *      description="Secret of the acces",
     *      example="secret"
     * )
     *
     * @var string
     */
    public $secret;
    
}
