<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class JwtAuth {

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function authenticate($jwt_data)
    {
        $secretKey  = $this->ci->config->item('jwt_key');
        $tokenId    = $this->ci->config->item('jwt_tokenid');
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify( $this->ci->config->item('jwt_expired'))->getTimestamp();     
        $serverName = $this->ci->config->item('jwt_domain');                                     
    
        // Create the token as an array
        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $issuedAt->getTimestamp(),      // Not before
            'exp'  => $expire,           // Expire
            'data' => $jwt_data
        ];
    
        // Encode the array to a JWT string.
        return JWT::encode(
            $data,      //Data to be encoded in the JWT
            $secretKey, // The signing key
            $this->ci->config->item('jwt_algorithms')     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );
    }

    public function validate()
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

        $secretKey  = $this->ci->config->item('jwt_key');
        $token = JWT::decode((string)$jwt, $secretKey, [$this->ci->config->item('jwt_algorithms')]);
        $now = new DateTimeImmutable();
        $serverName = $this->ci->config->item('jwt_domain');

        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }

        //printf("Current timestamp is %s", (new \DateTimeImmutable())->getTimestamp());
        return $token->exp;
        
    }

    public function decode($token)
    {
        $secretKey  = $this->ci->config->item('jwt_key');
        return JWT::decode((string)$token, $secretKey, [$this->ci->config->item('jwt_algorithms')]);
    }

    public function generatekey($string)
    {
        return base64_encode(md5($string));
    }

}