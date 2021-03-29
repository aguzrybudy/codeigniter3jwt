<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| JSON Web Algorithms (JWA)
|--------------------------------------------------------------------------
|
| The JSON Web Algorithms (JWA) specification registers cryptographic algorithms and identifiers to be used with the JSON Web Signature (JWS)
| JSON Web Encryption (JWE), and JSON Web Key (JWK)specifications. 
| https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
|
*/

$config['jwt_algorithms'] = "HS256";

/*
|--------------------------------------------------------------------------
| JWT Secret Key
|--------------------------------------------------------------------------
|
| The key for web token
|
*/

$config['jwt_key'] = "MjUwYTEyN2VmZjA4YjBmZjFlYjNkMGZhNjU4NDc4NzA=";

/*
|--------------------------------------------------------------------------
| JWT Expired Time
|--------------------------------------------------------------------------
|
| Expired for web token in minutes
| Example Add 6 minutes
|
*/
$config['jwt_expired'] = "+6 minutes"; 

/*
|--------------------------------------------------------------------------
| JWT Domain
|--------------------------------------------------------------------------
|
| Register Domain for authentication
|
*/

$config['jwt_domain'] = "localhost";


/*
|--------------------------------------------------------------------------
| JWT Token ID
|--------------------------------------------------------------------------
|
| Token id generate by base64_encode random bytes
|
*/
$config['jwt_tokenid'] = base64_encode(random_bytes(16));