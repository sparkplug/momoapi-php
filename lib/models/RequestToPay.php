<?php

namespace MomoApi\models;

class RequestToPay implements \JsonSerializable
{
    public $payer;

    public $payeeNote;

    public $payerMessage;

    public $externalId;
    public $currency;
    public $amount;

    public $status;

    public $financialTransactionId;


    public function __construct($payer, $payeeNote, $payerMessage, $externalId, $currency, $amount, $status, $financialTransactionId)
    {
        $this->payer = $payer;
        $this->payeeNote = $payeeNote;
        $this->payerMessage = $payerMessage;
        $this->externalId = $externalId;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->status = $status;
        $this->financialTransactionId = $financialTransactionId;
    }


    public function jsonSerialize()
    {
        $data = array(
            'payer' => array($this->payer->partyIdType, $this->payer->partyId),
            'payeeNote' => $this->payeeNote,
            'payerMessage' => $this->payerMessage,
            'externalId' => $this->externalId,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'status' => $this->status,
            'financialTransactionId' => $this->financialTransactionId

        );

        return $data;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
