<?php

namespace MomoApi;

use MomoApi\HttpClient\CurlClient;

class ApiRequestTest extends TestCase
{
    public function testHttpClientInjection()
    {
        $reflector = new \ReflectionClass('MomoApi\\ApiRequest');
        $method = $reflector->getMethod('httpClient');
        $method->setAccessible(true);

        $curl = new CurlClient();
        $curl->setTimeout(10);
        ApiRequest::setHttpClient($curl);

        $injectedCurl = $method->invoke(new ApiRequest());
        $this->assertSame($injectedCurl, $curl);
    }
}
