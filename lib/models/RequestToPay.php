<?php

namespace MomoApi\models;
class RequestToPay  implements JsonSerializable
{

public $payer;

public $payeeNote;

public  $payerMessage;

public $externalId;
public $currency;
public $amount;


    public function __construct($payer,$payeeNote,$payerMessage, $externalId,$currency,$amount)
    {
        $this->payer = $payer;
        $this->payeeNote = $payeeNote;
        $this->payerMessage = $payerMessage;
        $this->externalId = $externalId;
        $this->currency = $currency;
        $this->amount = $amount;
    }


    public function jsonSerialize()
    {
        $data = array(
            'payer' => array($this->payer->partyIdType, $this->payer->partyId),
            'payeeNote' => $this->payeeNote,
            'payerMessage' => $this->payerMessage,
            'externalId' => $this->externalId,
            'currency' => $this->currency,
            'amount' => $this->amount

        );

        return $data;
    }

}