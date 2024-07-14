<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: "1.0.0", 
        description: "TMPSSAuth APP", 
        title: "TMPSSAuth Documentación"),

    OA\Server(
        url: 'http://127.0.0.1:8001/api', 
        description: "local server"),
    
    OA\Tag(
        name:"Auth",
        description:"API EndPoints of Auth"),
        
    OA\SecurityScheme( 
        securityScheme: 'bearer', 
        type: "apiKey", 
        name: "Authorization", 
        in: "header", 
        scheme: "bearer"
    ),
] 

abstract class Controller
{
    //
}
