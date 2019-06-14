<?php

namespace MomoApi\models;
class Transaction  implements JsonSerializable
{

public $amount;
public $currency;

public $financialTransactionId;

public $externalId;
public $payer;
public $status;
public $reason;


    public function __construct($amount,$currency,$financialTransactionId,$externalId,  $payer,$status,$reason)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->financialTransactionId = $financialTransactionId;

        $this->externalId = $externalId;
        $this->payer = $payer;
        $this->status = $status;
        $this->reason = $reason;


    }


    public function jsonSerialize()
    {
        $data = array(
            'amount' => $this->amount,
            'currency' => $this->currency,
            'financialTransactionId' => $this->financialTransactionId,
            'externalId' => $this->externalId,
            'payer' => $this-> payer,
            'status' => $this-> status,
            'reason' => $this -> reason


        );

        return $data;
    }

}