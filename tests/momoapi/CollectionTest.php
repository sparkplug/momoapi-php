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

    public function testDefaultHeaders()
    {
        $reflector = new \ReflectionClass('MomoApi\\Collection');
        $method = $reflector->getMethod('_defaultHeaders');
        $method->setAccessible(true);

    }

    public function  testGetToken(){

        $coll = new Collection();

        $token = $coll->getToken();

        $this->assertSame($token->getToken(), "");

    }

    public function  testGetBalance(){

        $coll = new Collection();

        $bal = $coll->getBalance();

        $this->assertSame($bal, "");

    }




}