<?php

namespace MomoApi;


use MomoApi\models\ResourceFactory;




class Collection extends ApiRequest
{


    // @var string The MomoApi Collections API Secret.
    public static $collectionApiSecret;

    // @var string The MomoApi collections primary Key
    public static $collectionPrimaryKey;

    // @var string The MomoApi collections User Id
    public static $collectionUserId;

    public $headers;





    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return AccessToken The OAuth Token.
     */
    public static function getToken($params = null, $options = null)
    {


        $url = "/collection/token/";


        $encodedString = base64_encode(
            MomoApi::getCollectionUserId() . ':' . MomoApi::getCollectionApiSecret()
        );
        $headers = [
            'Authorization' => 'Basic ' . $encodedString,
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => MomoApi::getCollectionPrimaryKey()
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

    }


    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Transaction The transaction.
     */
    public function getTransaction($params = null, $options = null)
    {

    }


    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Charge The refunded charge.
     */
    public function requestToPay($params = null, $options = null)
    {

        self::_validateParams($params);
        $url = "/collection/v1_0/requesttopay";

        $headers=[];


        $response = self::request('post', $url, $params, $headers);


        $obj = \Stripe\Util\Util::convertToStripeObject($response->json, $opts);

        return $obj;

    }


    /**
     * @param array|null|mixed $params The list of parameters to validate
     *
     * @throws \MomoApi\Error\Api if $params exists and is not an array
     */
    protected static function _validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = "You must pass an array as the first argument to MomoApi API "
                . "method calls.  (HINT: an example call to create a charge "
                . "would be: \"MomoApi\\Charge::create(['amount' => 100, "
                . "'currency' => 'usd', 'source' => 'tok_1234'])\")";
            throw new \MomoApi\Error\Api($message);
        }
    }







}