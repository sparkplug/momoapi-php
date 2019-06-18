<?php

namespace MomoApi;

use MomoApi\HttpClient\CurlClient;

class CollectionTest extends TestCase
{

    public function testHttpClientInjection()
    {
        $reflector = new \ReflectionClass('MomoApi\\Collection');
        $method = $reflector->getMethod('httpClient');
        $method->setAccessible(true);

        $curl = new CurlClient();
        $curl->setTimeout(10);
        Collection::setHttpClient($curl);

        $injectedCurl = $method->invoke(new Collection());
        $this->assertSame($injectedCurl, $curl);
    }



    public function  testGetToken(){

        $coll = new Collection();

        $token = $coll->getToken();

        $this->assertFalse(is_null($token->getToken()));

    }

    public function  testGetBalance(){

        $coll = new Collection();

        $bal = $coll->getBalance();

        $this->assertFalse(is_null($bal));

    }



    public function testRequestToPay(){


        $coll = new Collection();

        $params =  ['mobile'=>"256782181656",'payee_note'=> "34", 'payer_message'=> "12", 'external_id'=> "ref", 'currency' => "EUR", 'amount' => "500" ];

        $t= $coll->requestToPay($params);

        $this->assertFalse(is_null($t));

        $transaction=  $coll->getTransaction($t);

        $this->assertFalse(is_null( $transaction->getStatus()));


    }






}