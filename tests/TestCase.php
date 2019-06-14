<?php

namespace MomoApi;

/**
 * Base class for MomoApi test cases.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /** @var string original API base URL */
    protected $origApiBase;

    /** @var string original API key */
    protected $origApiKey;

    /** @var string original client ID */
    protected $origClientId;

    /** @var string original API version */
    protected $origApiVersion;

    /** @var string original account ID */
    protected $origAccountId;

    /** @var object HTTP client mocker */
    protected $clientMock;

    protected function setUp()
    {
        // Save original values so that we can restore them after running tests
        $this->origApiBase = MomoApi::$apiBase;
        $this->origApiKey = MomoApi::getApiKey();
        $this->origClientId = MomoApi::getClientId();
        $this->origApiVersion = MomoApi::getApiVersion();
        $this->origAccountId = MomoApi::getAccountId();

        // Set up host and credentials for MomoApi-mock
        MomoApi::$apiBase = "localhost";
        MomoApi::setApiKey("sk_test_123");
        MomoApi::setClientId("ca_123");
        MomoApi::setApiVersion(null);
        MomoApi::setAccountId(null);

        // Set up the HTTP client mocker
        $this->clientMock = $this->getMock('\MomoApi\HttpClient\ClientInterface');

        // By default, use the real HTTP client
        ApiRequest::setHttpClient(HttpClient\CurlClient::instance());
    }

    protected function tearDown()
    {
        // Restore original values
        MomoApi::$apiBase = $this->origApiBase;
        MomoApi::setEnableTelemetry(false);
        MomoApi::setApiKey($this->origApiKey);
        MomoApi::setClientId($this->origClientId);
        MomoApi::setApiVersion($this->origApiVersion);
        MomoApi::setAccountId($this->origAccountId);
    }

    /**
     * Sets up a request expectation with the provided parameters. The request
     * will actually go through and be emitted.
     *
     * @param string $method HTTP method (e.g. 'post', 'get', etc.)
     * @param string $path relative path (e.g. '/v1/charges')
     * @param array|null $params array of parameters. If null, parameters will
     *   not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *   exhaustive. If null, headers are not checked.
     * @param bool $hasFile Whether the request parameters contains a file.
     *   Defaults to false.
     * @param string|null $base base URL (e.g. 'https://api.MomoApi.com')
     */
    protected function expectsRequest(
        $method,
        $path,
        $params = null,
        $headers = null,
        $hasFile = false,
        $base = null
    ) {
        $this->prepareRequestMock($method, $path, $params, $headers, $hasFile, $base)
            ->will($this->returnCallback(
                function ($method, $absUrl, $headers, $params, $hasFile) {
                    $curlClient = HttpClient\CurlClient::instance();
                    ApiRequest::setHttpClient($curlClient);
                    return $curlClient->request($method, $absUrl, $headers, $params, $hasFile);
                }
            ));
    }

    /**
     * Sets up a request expectation with the provided parameters. The request
     * will not actually be emitted, instead the provided response parameters
     * will be returned.
     *
     * @param string $method HTTP method (e.g. 'post', 'get', etc.)
     * @param string $path relative path (e.g. '/v1/charges')
     * @param array|null $params array of parameters. If null, parameters will
     *   not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *   exhaustive. If null, headers are not checked.
     * @param bool $hasFile Whether the request parameters contains a file.
     *   Defaults to false.
     * @param array $response
     * @param integer $rcode
     * @param string|null $base
     *
     * @return array
     */
    protected function stubRequest(
        $method,
        $path,
        $params = null,
        $headers = null,
        $hasFile = false,
        $response = [],
        $rcode = 200,
        $base = null
    ) {
        $this->prepareRequestMock($method, $path, $params, $headers, $hasFile, $base)
            ->willReturn([json_encode($response), $rcode, []]);
    }

    /**
     * Prepares the client mocker for an invocation of the `request` method.
     * This helper method is used by both `expectsRequest` and `stubRequest` to
     * prepare the client mocker to expect an invocation of the `request` method
     * with the provided arguments.
     *
     * @param string $method HTTP method (e.g. 'post', 'get', etc.)
     * @param string $path relative path (e.g. '/v1/charges')
     * @param array|null $params array of parameters. If null, parameters will
     *   not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *   exhaustive. If null, headers are not checked.
     * @param bool $hasFile Whether the request parameters contains a file.
     *   Defaults to false.
     * @param string|null $base base URL (e.g. 'https://api.MomoApi.com')
     *
     * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    private function prepareRequestMock(
        $method,
        $path,
        $params = null,
        $headers = null,
        $hasFile = false,
        $base = null
    ) {
        ApiRequest::setHttpClient($this->clientMock);

        if ($base === null) {
            $base = MomoApi::$apiBase;
        }
        $absUrl = $base . $path;

        return $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo(strtolower($method)),
                $this->identicalTo($absUrl),
                // for headers, we only check that all of the headers provided in $headers are
                // present in the list of headers of the actual request
                $headers === null ? $this->anything() : $this->callback(function ($array) use ($headers) {
                    foreach ($headers as $header) {
                        if (!in_array($header, $array)) {
                            return false;
                        }
                    }
                    return true;
                }),
                $params === null ? $this->anything() : $this->identicalTo($params),
                $this->identicalTo($hasFile)
            );
    }
}
