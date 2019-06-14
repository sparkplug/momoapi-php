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

    public function testDefaultHeaders()
    {
        $reflector = new \ReflectionClass('MomoApi\\ApiRequest');
        $method = $reflector->getMethod('_defaultHeaders');
        $method->setAccessible(true);

        // no way to stub static methods with PHPUnit 4.x :(
        MomoApi::setAppInfo('MyTestApp', '1.2.34', 'https://mytestapp.example', 'partner_1234');
        $apiKey = 'sk_test_notarealkey';
        $clientInfo = ['httplib' => 'testlib 0.1.2'];

        $headers = $method->invoke(null, $apiKey, $clientInfo);

        $ua = json_decode($headers['X-MomoApi-Client-User-Agent']);
        $this->assertSame($ua->application->name, 'MyTestApp');
        $this->assertSame($ua->application->version, '1.2.34');
        $this->assertSame($ua->application->url, 'https://mytestapp.example');
        $this->assertSame($ua->application->partner_id, 'partner_1234');

        $this->assertSame($ua->httplib, 'testlib 0.1.2');

        $this->assertSame(
            $headers['User-Agent'],
            'MomoApi/v1 PhpBindings/' . MomoApi::VERSION . ' MyTestApp/1.2.34 (https://mytestapp.example)'
        );

        $this->assertSame($headers['Authorization'], 'Bearer ' . $apiKey);
    }

    /**
     * @expectedException \MomoApi\Error\Authentication
     * @expectedExceptionMessageRegExp #No API key provided#
     */
    public function testRaisesAuthenticationErrorWhenNoApiKey()
    {
        MomoApi::setApiKey(null);
        Collection::requestTopay();
    }

    public function testRaisesInvalidRequestErrorOn400()
    {
        $this->stubRequest(
            'POST',
            '/v1/charges',
            [],
            null,
            false,
            [
                'error' => [
                    'type' => 'invalid_request_error',
                    'message' => 'Missing id',
                    'param' => 'id',
                ],
            ],
            400
        );

        try {
            Collection::requestTopay();
            $this->fail("Did not raise error");
        } catch (Error\InvalidRequest $e) {
            $this->assertSame(400, $e->getHttpStatus());
            $this->assertTrue(is_array($e->getJsonBody()));
            $this->assertSame('Missing id', $e->getMessage());
            $this->assertSame('id', $e->getMomoApiParam());
        } catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
    }



    public function testRaisesAuthenticationErrorOn401()
    {
        $this->stubRequest(
            'POST',
            '/v1/charges',
            [],
            null,
            false,
            [
                'error' => [
                    'type' => 'invalid_request_error',
                    'message' => 'You did not provide an API key.',
                ],
            ],
            401
        );

        try {
            Collection::requestTopay();
            $this->fail("Did not raise error");
        } catch (Error\Authentication $e) {
            $this->assertSame(401, $e->getHttpStatus());
            $this->assertTrue(is_array($e->getJsonBody()));
            $this->assertSame('You did not provide an API key.', $e->getMessage());
        } catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
    }

    public function testRaisesCardErrorOn402()
    {
        $this->stubRequest(
            'POST',
            '/v1/charges',
            [],
            null,
            false,
            [
                'error' => [
                    'type' => 'card_error',
                    'message' => 'Your card was declined.',
                    'code' => 'card_declined',
                    'decline_code' => 'generic_decline',
                    'charge' => 'ch_declined_charge',
                    'param' => 'exp_month',
                ],
            ],
            402
        );

        try {
            Collection::requestTopay();
            $this->fail("Did not raise error");
        } catch (Error\Card $e) {
            $this->assertSame(402, $e->getHttpStatus());
            $this->assertTrue(is_array($e->getJsonBody()));
            $this->assertSame('Your card was declined.', $e->getMessage());
            $this->assertSame('card_declined', $e->getMomoApiCode());
            $this->assertSame('generic_decline', $e->getDeclineCode());
            $this->assertSame('exp_month', $e->getMomoApiParam());
        } catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
    }

    public function testRaisesPermissionErrorOn403()
    {
        $this->stubRequest(
            'GET',
            '/v1/accounts/foo',
            [],
            null,
            false,
            [
                'error' => [
                    'type' => 'invalid_request_error',
                    'message' => "The provided key 'sk_test_********************1234' does not have access to account 'foo' (or that account does not exist). Application access may have been revoked.",
                ],
            ],
            403
        );

        try {
            Account::retrieve('foo');
            $this->fail("Did not raise error");
        } catch (Error\Permission $e) {
            $this->assertSame(403, $e->getHttpStatus());
            $this->assertTrue(is_array($e->getJsonBody()));
            $this->assertSame("The provided key 'sk_test_********************1234' does not have access to account 'foo' (or that account does not exist). Application access may have been revoked.", $e->getMessage());
        } catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
    }

    public function testRaisesInvalidRequestErrorOn404()
    {
        $this->stubRequest(
            'GET',
            '/v1/charges/foo',
            [],
            null,
            false,
            [
                'error' => [
                    'type' => 'invalid_request_error',
                    'message' => 'No such charge: foo',
                    'param' => 'id',
                ],
            ],
            404
        );

        try {
            Collection::requestTopay();
            $this->fail("Did not raise error");
        } catch (Error\InvalidRequest $e) {
            $this->assertSame(404, $e->getHttpStatus());
            $this->assertTrue(is_array($e->getJsonBody()));
            $this->assertSame('No such charge: foo', $e->getMessage());
            $this->assertSame('id', $e->getMomoApiParam());
        } catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
    }



    public function testHeaderMomoApiAccountGlobal()
    {
        MomoApi::setAccountId('acct_123');
        $this->stubRequest(
            'POST',
            '/v1/charges',
            [],
            [
                'MomoApi-Account: acct_123',
            ],
            false,
            [
                'id' => 'ch_123',
                'object' => 'charge',
            ]
        );
        Collection::requestTopay();
    }

    public function testHeaderMomoApiAccountRequestOptions()
    {
        $this->stubRequest(
            'POST',
            '/v1/charges',
            [],
            [
                'MomoApi-Account: acct_123',
            ],
            false,
            [
                'id' => 'ch_123',
                'object' => 'charge',
            ]
        );
        Collection::requestTopay();
    }
}
