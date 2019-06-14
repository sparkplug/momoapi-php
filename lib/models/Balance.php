<?php
namespace MomoApi\models;

class Balance  implements \JsonSerializable
{

  public $description;

  public $availableBalance;

  public $currency;


    public function __construct($description,$availableBalance,$currency)
    {
        $this->description = $description;
        $this->availableBalance = $availableBalance;
        $this->currency = $currency;
    }


    public function jsonSerialize()
    {
        $data = array(
            'description' => $this->description,
            'availableBalance' => $this->availableBalance,
            'currency' => $this->currency
        );

        return $data;
    }
}