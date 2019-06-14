<?php

namespace MomoApi\models;
class ResourceFactory
{


    static public function accessTokenFromJson($jsonData)
    {
        $accessToken = new \MomoApi\models\AccessToken($jsonData['access_token'], $jsonData['token_type'],$jsonData['expires_in']);

        return $accessToken;
    }


    static public function accountFromJson($jsonData)
    {
        $account = new \MomoApi\models\Account($jsonData['availableBalance'], $jsonData['currency']);

        return $account;
    }


    static public function balanceFromJson($jsonData)
    {
        $balance = new \MomoApi\models\Balance($jsonData['description'], $jsonData['availableBalance'],$jsonData['currency']);


        return $balance;
    }


    static public function loginBodyFromJson($jsonData)
    {
        $loginBody = new \MomoApi\models\LoginBody($jsonData['user_id'], $jsonData['api_key']);

        return $loginBody;
    }


    static public function payerFromJson($jsonData)
    {
        $payer = new \MomoApi\models\Payer($jsonData['partyIdType'], $jsonData['partyId']);

        return $payer;
    }


    static public function requestToPayFromJson($jsonData)
    {
        $requestToPay = new \MomoApi\models\RequestToPay($jsonData['payer'],$jsonData['payeeNote'],$jsonData['payerMessage'], $jsonData['externalId'],$jsonData['currency'],$jsonData['amount']);

        return $requestToPay;
    }


    static public function transactionFromJson($jsonData)
    {
        $transaction = new \MomoApi\models\Transaction($jsonData['amount'],$jsonData['currency'],$jsonData['financialTransactionId'],$jsonData['externalId'],  $jsonData['payer'],$jsonData['status'],$jsonData['reason']);

        return $transaction;
    }


    static public function transferFromJson($jsonData)
    {
        $transfer = new \MomoApi\models\Transfer($jsonData['payee'],$jsonData['payeeNote'],$jsonData['payerMessage'],$jsonData['externalId'], $jsonData['currency'], $jsonData['amount']);

        return $transfer;
    }






}