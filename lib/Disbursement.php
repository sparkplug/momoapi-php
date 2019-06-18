<?php

namespace MomoApi;

use MomoApi\HttpClient\ClientInterface;
use MomoApi\models\ResourceFactory;
use MomoApi\Util\Util;

class Disbursement extends  ApiRequest
{

    public $headers;


    public $authToken;



    public  $_baseUrl;


    //@var string target environment
    public  $_targetEnvironment;


    // @var string the currency of http calls
    public  $_currency;



    // @var string The MomoApi disbursements API Secret.
    public  $_disbursementApiSecret;

    // @var string The MomoApi disbursements primary Key
    public  $_disbursementPrimaryKey;

    // @var string The MomoApi disbursements User Id
    public  $_disbursementUserId ;







    /**
     * @var HttpClient\ClientInterface
     */
    private static $_httpClient;




    /**
     * Disbursement constructor.
     *
     * @param string|null $currency
     * @param string|null $baseUrl
     */
    public function __construct($currency=null,$baseUrl=null,$targetEnvironment=null, $disbursementApiSecret=null,  $disbursementPrimaryKey=null,$disbursementUserId=null)
    {

        if (!$currency) {
            $currency = MomoApi::getCurrency();
        }
        $this->_currency = $currency;


        if (!$baseUrl) {
            $baseUrl = MomoApi::getBaseUrl();
        }
        $this->_baseUrl = $baseUrl;


        if (!$targetEnvironment) {
            $targetEnvironment = MomoApi::getTargetEnvironment();
        }
        $this->_targetEnvironment = $targetEnvironment;


        if (!$disbursementApiSecret) {
            $disbursementApiSecret = MomoApi::getDisbursementApiSecret();
        }
        $this->_disbursementApiSecret = $disbursementApiSecret;


        if (!$disbursementPrimaryKey) {
            $disbursementPrimaryKey = MomoApi::getDisbursementPrimaryKey();
        }
        $this->_disbursementPrimaryKey = $disbursementPrimaryKey;


        if (!$disbursementUserId) {
            $disbursementUserId = MomoApi::getDisbursementUserId();
        }
        $this->_disbursementUserId = $disbursementUserId;
    }



    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return AccessToken The OAuth Token.
     */
    public function getToken($params = null, $options = null)
    {


        $url = $this->_baseUrl . '/disbursement/token/';


        $encodedString = base64_encode(
            MomoApi::getDisbursementUserId() . ':' . MomoApi::getDisbursementApiSecret()
        );
        $headers = [
            'Authorization' => 'Basic ' . $encodedString,
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => MomoApi::getDisbursementPrimaryKey()
        ];


        $response = self::request('post', $url, $params, $headers);




        $obj = ResourceFactory::accessTokenFromJson($response->json);

        return $obj;


    }





    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Balance The account balance.
     */
    public function getBalance($params = null, $options = null)
    {

        $url = $this->_baseUrl . "/disbursement/v1_0/account/balance";

        $token = $this->getToken()->getToken();



        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            "X-Target-Environment" => $this->_targetEnvironment,
            'Ocp-Apim-Subscription-Key' => MomoApi::getDisbursementPrimaryKey()
        ];


        $response = self::request('get', $url, $params, $headers);

        return $response;




        $obj = ResourceFactory::balanceFromJson($response->json);

        return $obj;

    }




    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Transaction The transaction.
     */
    public function getTransaction($trasaction_id,$params=null)
    {
        $url =  $this->_baseUrl ."/disbursement/v1_0/transfer/". $trasaction_id;

        $token = $this->getToken()->getToken();

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            "X-Target-Environment" => $this->_targetEnvironment,
            'Ocp-Apim-Subscription-Key' => MomoApi::getDisbursementPrimaryKey(),
        ];

        $response = self::request('get', $url, $params, $headers);

        $obj = ResourceFactory::transferFromJson($response->json);

        return $obj;


    }



    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Charge The refunded charge.
     */
    public function transfer($params, $options = null)
    {
        self::_validateParams($params);
        $url =  $this->_baseUrl . "/disbursement/v1_0/transfer";

        $token = $this->getToken()->getToken();

        $transaction =  Util\Util::uuid();

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            "X-Target-Environment" => $this->_targetEnvironment,
            'Ocp-Apim-Subscription-Key' => MomoApi::getDisbursementPrimaryKey(),
            "X-Reference-Id" =>  $transaction
        ];



        $data = [
            "payee" =>  [
                "partyIdType" => "MSISDN",
                "partyId" => $params['mobile']],
            "payeeNote" => $params['payee_note'],
            "payerMessage" =>  $params['payer_message'],
            "externalId" => $params['external_id'],
            "currency" =>  $params['currency'],
            "amount" => $params['amount']];



        $response = self::request('post', $url, $data, $headers);



        return  $transaction;

    }


    public function isActive($mobile,$params=null){

        $token = $this->getToken()->getToken();


        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            "X-Target-Environment" => $this->_targetEnvironment,
            'Ocp-Apim-Subscription-Key' => MomoApi::getDisbursementPrimaryKey()
        ];


        $url =  $this->_baseUrl . "/disbursement/v1_0/accountholder/MSISDN/".$mobile ."/active";



        $response = self::request('get', $url, $params, $headers);

        return $response;


    }




    /**
     * @param array|null|mixed $params The list of parameters to validate
     *
     * @throws \MomoApi\Error\MomoApiError if $params exists and is not an array
     */
    protected static function _validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = "You must pass an array as the first argument to MomoApi API "
                . "method calls.  (HINT: an example call to create a charge "
                . "would be: \"MomoApi\\Charge::create(['amount' => 100, "
                . "'currency' => 'usd', 'source' => 'tok_1234'])\")";
            throw new \MomoApi\Error\MomoApiError($message);
        }
    }









}
