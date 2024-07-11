<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="TMPSSAuth Documentación",
 *      description="Temposolutions APP",
 *      @OA\Contact(
 *          email="daruiza@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="TMPSS API Server"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="API EndPoints of Auth"
 * )
 * @OA\SecuritySchemes(
 *     securityDefinition="bearer",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization"
 * )
 */
abstract class Controller
{
    //
}
