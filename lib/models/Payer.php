<?php
namespace MomoApi\models;

class Payer implements JsonSerializable
{

public $partyIdType;

public $partyId;


    public function __construct($partyIdType,$partyId)
    {
        $this->partyIdType = $partyIdType;
        $this->partyId = $partyId;

    }


    public function jsonSerialize()
    {
        $data = array(
            'partyIdType' => $this->partyIdType,
            'partyId' => $this->partyId,

        );

        return $data;
    }

}