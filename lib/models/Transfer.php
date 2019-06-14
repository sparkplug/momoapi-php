<?php

namespace MomoApi\models;
class Transfer  implements \JsonSerializable
{


public $payee;

public $payeeNote;

public $payerMessage;

public $externalId;
public $currency;
public $amount;


    public function __construct( $payee,$payeeNote,$payerMessage,$externalId, $currency, $amount )
    {
        $this->payee = $payee;
        $this->payeeNote = $payeeNote;
        $this->currency = $currency;

        $this->payerMessage = $payerMessage;
        $this->externalId = $externalId;
        $this->amount = $amount;
    }


    public function jsonSerialize()
    {
        $data = array(
            'payee' => array($this->payer->partyIdType, $this->payer->partyId),
            'payeeNote' => $this->payeeNote,
            'currency' => $this->currency,

            'payerMessage' => $this->payerMessage,
            'externalId' => $this->externalId,
            'amount' => $this->amount,
        );

        return $data;
    }

}