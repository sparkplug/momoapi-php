<?php
namespace MomoApi\models;

class AccessToken implements \JsonSerializable
{

public $access_token;

public $token_type;

public  $expires_in;


    public function __construct($access_token, $token_type,$expires_in)
    {
        $this->access_token = $access_token;
        $this->token_type = $token_type;
        $this->expires_in = $expires_in;
    }


    public function jsonSerialize()
    {
        $data = array(
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in
        );

        return $data;
    }

    public function getToken(){
        return $this->access_token;
    }

}