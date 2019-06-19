<?php

namespace MomoApi\models;

class LoginBody implements \JsonSerializable
{

    public $user_id;

    public $api_key;


    public function __construct($user_id, $api_key)
    {
        $this->user_id = $user_id;
        $this->api_key = $api_key;
    }


    public function jsonSerialize()
    {
        $data = array(
            'user_id' => $this->user_id,
            'api_key' => $this->api_key,

        );

        return $data;
    }
}