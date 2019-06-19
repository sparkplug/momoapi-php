<?php


namespace MomoApi;

use MomoApi\HttpClient\CurlClient;

class DisbursementTest
{

    public function testHttpClientInjection()
    {
        $reflector = new \ReflectionClass('MomoApi\\Disbursement');
        $method = $reflector->getMethod('httpClient');
        $method->setAccessible(true);

        $curl = new CurlClient();
        $curl->setTimeout(10);
        Disbursement::setHttpClient($curl);

        $injectedCurl = $method->invoke(new Disbursement());
        $this->assertSame($injectedCurl, $curl);
    }


    public function testGetToken()
    {

        $disb = new Collection();

        $token = $disb->getToken();

        $this->assertFalse(is_null($token->getToken()));

    }

    public function testGetBalance()
    {

        $disb = new Disbursement();

        $bal = $disb->getBalance();

        $this->assertFalse(is_null($bal));

    }


    public function testTransfer()
    {


        $coll = new Collection();

        $params = ['mobile' => "256782181656", 'payee_note' => "34", 'payer_message' => "12", 'external_id' => "ref", 'currency' => "EUR", 'amount' => "500"];

        $t = $coll->requestToPay($params);

        $this->assertFalse(is_null($t));

        $transaction = $coll->getTransaction($t);

        $this->assertFalse(is_null($transaction->getStatus()));


    }

}