<?php
namespace MomoApi;

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * Base class for MomoApi test cases.
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    public $_baseUrl;


    //@var string target environment
    public $_targetEnvironment;


    // @var string the currency of http calls
    public $_currency;


    // @var string The MomoApi Collections API Secret.
    public $_collectionApiSecret;

    // @var string The MomoApi collections primary Key
    public $_collectionPrimaryKey;

    // @var string The MomoApi collections User Id
    public $_collectionUserId;


    // @var string The MomoApi remittance API Secret.
    public $_remittanceApiSecret;

    // @var string The MomoApi remittance primary Key
    public $_remittancePrimaryKey;

    // @var string The MomoApi remittance User Id
    public $_remittanceUserId;


    // @var string The MomoApi disbursements API Secret.
    public $_disbursementApiSecret;

    // @var string The MomoApi disbursements primary Key
    public $_disbursementPrimaryKey;

    // @var string The MomoApi disbursements User Id
    public $_disbursementUserId;


    protected function setUp()
    {
        // Save original values so that we can restore them after running tests
        $this->_baseUrl = MomoApi::getBaseUrl();

        $this->_targetEnvironment = MomoApi::getTargetEnvironment();


        $this->_currency = MomoApi::getCurrency();


        $this->_collectionApiSecret = MomoApi::getCollectionApiSecret();

        $this->_collectionPrimaryKey = MomoApi::getCollectionPrimaryKey();

        $this->_collectionUserId = MomoApi::getCollectionUserId();

        $this->_remittanceApiSecret = MomoApi::getRemittanceApiSecret();

        $this->_remittancePrimaryKey = MomoApi::getRemittancePrimaryKey();
        $this->_remittanceUserId = MomoApi::getRemittanceUserId();

        $this->_disbursementApiSecret = MomoApi::getDisbursementApiSecret();

        $this->_disbursementPrimaryKey = MomoApi::getDisbursementPrimaryKey();

        $this->_disbursementUserId = MomoApi::getDisbursementUserId();


        // Set up the HTTP client mocker
        $this->clientMock = $this->getMock('\MomoApi\HttpClient\ClientInterface');

        // By default, use the real HTTP client
        ApiRequest::setHttpClient(HttpClient\CurlClient::instance());
    }

    protected function tearDown()
    {
        // Restore original values


        MomoApi::setBaseUrl($this->_baseUrl);

        MomoApi::setTargetEnvironment($this->_targetEnvironment);


        MomoApi::setCurrency($this->_currency);


        MomoApi::setCollectionApiSecret($this->_collectionApiSecret);

        MomoApi::setCollectionPrimaryKey($this->_collectionPrimaryKey);

        MomoApi::setCollectionUserId($this->_collectionUserId);

        MomoApi::setRemittanceApiSecret($this->_remittanceApiSecret);

        MomoApi::setRemittancePrimaryKey($this->_remittancePrimaryKey);
        MomoApi::setRemittanceUserId($this->_remittanceUserId);

        MomoApi::setDisbursementApiSecret($this->_disbursementApiSecret);

        MomoApi::setDisbursementPrimaryKey($this->_disbursementPrimaryKey);

        MomoApi::setDisbursementUserId($this->_disbursementUserId);
    }

    /**
     * Sets up a request expectation with the provided parameters. The request
     * will actually go through and be emitted.
     *
     * @param string        $method  HTTP method (e.g. 'post', 'get', etc.)
     * @param string        $path    relative path (e.g. '/v1/charges')
     * @param array|null    $params  array of parameters. If null, parameters will
     *                               not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *                               exhaustive. If null, headers are not checked.
     * @param bool          $hasFile Whether the request parameters contains a file.
     *                               Defaults to false.
     * @param string|null   $base    base URL
     */
    protected function expectsRequest($method, $path, $params = null, $headers = null, $hasFile = false, $base = null)
    {
        $this->prepareRequestMock($method, $path, $params, $headers, $hasFile, $base)
            ->will(
                $this->returnCallback(
                    function ($method, $absUrl, $headers, $params, $hasFile) {
                        $curlClient = HttpClient\CurlClient::instance();
                        ApiRequest::setHttpClient($curlClient);
                        return $curlClient->request($method, $absUrl, $headers, $params, $hasFile);
                    }
                )
            );
    }

    /**
     * Prepares the client mocker for an invocation of the `request` method.
     * This helper method is used by both `expectsRequest` and `stubRequest` to
     * prepare the client mocker to expect an invocation of the `request` method
     * with the provided arguments.
     *
     * @param string        $method  HTTP method (e.g. 'post', 'get', etc.)
     * @param string        $path    relative path (e.g. '/v1/charges')
     * @param array|null    $params  array of parameters. If null, parameters will
     *                               not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *                               exhaustive. If null, headers are not checked.
     * @param bool          $hasFile Whether the request parameters contains a file.
     *                               Defaults to false.
     * @param string|null   $base    base URL (e.g. 'https://api.MomoApi.com')
     *
     * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    private function prepareRequestMock($method, $path, $params = null, $headers = null, $hasFile = false, $base = null)
    {
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
                $headers === null ? $this->anything() : $this->callback(
                    function ($array) use ($headers) {
                        foreach ($headers as $header) {
                            if (!in_array($header, $array)) {
                                return false;
                            }
                        }
                        return true;
                    }
                ),
                $params === null ? $this->anything() : $this->identicalTo($params),
                $this->identicalTo($hasFile)
            );
    }

    /**
     * Sets up a request expectation with the provided parameters. The request
     * will not actually be emitted, instead the provided response parameters
     * will be returned.
     *
     * @param string        $method   HTTP method (e.g. 'post', 'get', etc.)
     * @param string        $path     relative path (e.g. '/v1/charges')
     * @param array|null    $params   array of parameters. If null, parameters will
     *                                not be checked.
     * @param string[]|null $headers  array of headers. Does not need to be
     *                                exhaustive. If null, headers are not
     *                                checked.
     * @param bool          $hasFile  Whether the request parameters contains a file.
     *                                Defaults to false.
     * @param array         $response
     * @param integer       $rcode
     * @param string|null   $base
     *
     * @return array
     */
    protected function stubRequest($method, $path, $params = null, $headers = null, $hasFile = false, $response = [], $rcode = 200, $base = null)
    {
        $this->prepareRequestMock($method, $path, $params, $headers, $hasFile, $base)
            ->willReturn([json_encode($response), $rcode, []]);
    }
}
