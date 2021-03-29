<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class JwtWeb extends CI_Controller {

    public function index()
	{
        //$this->load->view('jwt');
        //echo $this->jwtauth->generatekey("JwtAuth");
        $jwt_data = array(
            "username" => "johndoe"
        );
        echo $this->jwtauth->authenticate($jwt_data);
       //echo $this->jwtauth->decode("");
    }

	public function authenticate()
	{
        $hasValidCredentials = true;

        if ($hasValidCredentials) {
            $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
            $tokenId    = base64_encode(random_bytes(16));
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
            $serverName = "your.domain.name";
            $username   = "adminjwt";                                           // Retrieved from filtered POST data
        
            // Create the token as an array
            $data = [
                'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
                'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss'  => $serverName,       // Issuer
                'nbf'  => $issuedAt->getTimestamp(),      // Not before
                'exp'  => $expire,           // Expire
                'data' => [                  // Data related to the signer user
                    'userName' => $username, // User name
                ]
            ];
        
            // Encode the array to a JWT string.
            echo JWT::encode(
                $data,      //Data to be encoded in the JWT
                $secretKey, // The signing key
                'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
            );
        }

        
	}

    public function resource()
    {
        // Attempt to extract the token from the Bearer header
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        }

        $jwt = $matches[1];
        if (! $jwt) {
            // No token was able to be extracted from the authorization header
            header('HTTP/1.0 400 Bad Request');
            exit;
        }

        $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        $token = JWT::decode((string)$jwt, $secretKey, ['HS512']);
        $now = new DateTimeImmutable();
        $serverName = "your.domain.name";

        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }

       echo var_dump("Current timestamp is %s",$token->data);
        
    }

    public function validate()
    {
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        }
        
        $jwt = $matches[1];
        if (! $jwt) {
            // No token was able to be extracted from the authorization header
            header('HTTP/1.0 400 Bad Request');
            exit;
        }
        
        $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        $token = JWT::decode($jwt, $secretKey, ['HS512']);
        $now = new DateTimeImmutable();
        $serverName = "your.domain.name";
        
        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
        
        // Show the page
    }

}


/*
$secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        $tokenId    = base64_encode(random_bytes(16));
        $issuedAt   = new DateTimeImmutable(); //date("Y-m-d H:i:s"), new DateTimeZone('Asia/Jakarta')
        $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();
        $serverName = "http://localhost/cijwt/";
        $username   = "adminjwt";
        $payload = array(
            'jti'  => $tokenId,        
            "iss" => $serverName,
            "aud" => $serverName,
            "iat" => $issuedAt->getTimestamp(),
            "nbf" => $issuedAt->getTimestamp(),
            'exp'  => $expire, 
            'data' => [ 
                'userName' => $username,
            ]
        );

        $jwt = JWT::encode($payload, $secretKey);
        $decoded = JWT::decode($jwt, $secretKey, array('HS256'));

        print_r($decoded);
        */