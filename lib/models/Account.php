<?php

namespace MomoApi\models;


class Account  implements \JsonSerializable
{


    public $availableBalance;

    public $currency;


    public function __construct($availableBalance,$currency)
    {
        $this->availableBalance = $availableBalance;
        $this->currency = $currency;
    }


    public function jsonSerialize()
    {
        $data = array(
            'availableBalance' => $this->availableBalance,
            'currency' => $this->currency
        );

        return $data;
    }

}