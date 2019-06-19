<?php


namespace MomoApi;

use MomoApi\HttpClient\CurlClient;

class RemittanceTest
{
    public function testHttpClientInjection()
    {
        $reflector = new \ReflectionClass('MomoApi\\Remittance');
        $method = $reflector->getMethod('httpClient');
        $method->setAccessible(true);

        $curl = new CurlClient();
        $curl->setTimeout(10);
        Remittance::setHttpClient($curl);

        $injectedCurl = $method->invoke(new Remittance());
        $this->assertSame($injectedCurl, $curl);
    }


    public function testGetToken()
    {
        $rem = new Remittance();

        $token = $rem->getToken();

        $this->assertFalse(is_null($token->getToken()));
    }

    public function testGetBalance()
    {
        $rem = new Remittance();

        $bal = $rem->getBalance();

        $this->assertFalse(is_null($bal));
    }


    public function testTransfer()
    {
        $rem = new Remittance();

        $params = ['mobile' => "256782181656", 'payee_note' => "34", 'payer_message' => "12", 'external_id' => "ref", 'currency' => "EUR", 'amount' => "500"];

        $t = $rem->transfer($params);

        $this->assertFalse(is_null($t));

        $transaction = $rem->getTransaction($t);

        $this->assertFalse(is_null($transaction->getStatus()));
    }
}
