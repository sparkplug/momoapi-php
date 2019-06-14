<?php

namespace MomoApi;

/**
 * Class MomoApi
 *
 * @package momoApi
 */
class MomoApi


{


    // @var string the base url of the API
    public static $baseUrl;


    //@var string target environment
    public static $targetEnvironment;


    // @var string the currency of http calls
    public static $currency;



    // @var string The MomoApi Collections API Secret.
    public static $collectionApiSecret;

    // @var string The MomoApi collections primary Key
    public static $collectionPrimaryKey;

    // @var string The MomoApi collections User Id
    public static $collectionUserId ;




    // @var string The MomoApi remittance API Secret.
    public static $remittanceApiSecret;

    // @var string The MomoApi remittance primary Key
    public static $remittancePrimaryKey;

    // @var string The MomoApi remittance User Id
    public static $remittanceUserId ;




    // @var string The MomoApi disbursements API Secret.
    public static $disbursementApiSecret;

    // @var string The MomoApi disbursements primary Key
    public static $disbursementPrimaryKey;

    // @var string The MomoApi disbursements User Id
    public static $disbursementUserId;




    // @var boolean Defaults to true.
    public static $verifySslCerts = false;



    // @var Util\LoggerInterface|null The logger to which the library will
    //   produce messages.
    public static $logger = null;

    // @var int Maximum number of request retries
    public static $maxNetworkRetries = 0;


    // @var float Maximum delay between retries, in seconds
    private static $maxNetworkRetryDelay = 2.0;

    // @var float Initial delay between retries, in seconds
    private static $initialNetworkRetryDelay = 0.5;

    const VERSION = '6.35.2';




    /**
     * @return string The Base Url.
     */
    public static function getBaseUrl()
    {
        return self::$baseUrl || getenv("BASE_URL") || "https://ericssonbasicapi2.azure-api.net" ;
    }


    /**
     * Sets the baseUrl.
     *
     * @param string $baseUrl
     */
    public static function setBaseUrl($baseUrl)
    {
        self::$baseUrl = $baseUrl;
    }



    /**
     * @return string The currency.
     */
    public static function getCurrency()
    {
        return self::$currency || getenv("CURRENCY") || "UGX" ;
    }


    /**
     * Sets the currency.
     *
     * @param string $currency
     */
    public static function setCurrency($currency)
    {
        self::$currency = $currency;
    }


    /**
     * @return string The target environment.
     */
    public static function getTargetEnvironment()
    {
        return self::$targetEnvironment || getenv("TARGET_ENVIRONMENT") || "sandbox" ;;
    }


    /**
     * Sets the $targetEnvironment.
     *
     * @param string $targetEnvironment
     */
    public static function setTargetEnvironment($targetEnvironment)
    {
        self::$targetEnvironment = $targetEnvironment;
    }



    /**
     * @return string The collectionApiSecret.
     */
    public static function getCollectionApiSecret()
    {
        return self::$collectionApiSecret || getenv("COLLECTION_API_SECRET");
    }


    /**
     * Sets the collectionApiSecret.
     *
     * @param string $collectionApiSecret
     */
    public static function setCollectionApiSecret($collectionApiSecret)
    {
        self::$collectionApiSecret = $collectionApiSecret;
    }


    /**
     * @return string The collectionPrimaryKey.
     */
    public static function getCollectionPrimaryKey()
    {
        return self::$collectionPrimaryKey || getenv("COLLECTION_PRIMARY_KEY");
    }




    /**
     * Sets the collectionPrimaryKey.
     *
     * @param string $collectionPrimaryKey
     */
    public static function setCollectionPrimaryKey($collectionPrimaryKey)
    {
        self::$collectionPrimaryKey = $collectionPrimaryKey;
    }


    /**
     * @return string The collectionUserId.
     */
    public static function getCollectionUserId()
    {
        return self::$collectionUserId || getenv("COLLECTION_USER_ID");
    }



    /**
     * Sets the collectionUserId.
     *
     * @param string $collectionUserId
     */
    public static function setCollectionUserId($collectionUserId)
    {
        self::$collectionUserId = $collectionUserId;
    }



    /**
     * @return string The remittanceApiSecret.
     */
    public static function getRemittanceApiSecret()
    {
        return self::$remittanceApiSecret  || getenv("REMITTANCE_API_SECRET");
    }


    /**
     * Sets the remittanceApiSecret .
     *
     * @param string $remittanceApiSecret
     */
    public static function setRemittanceApiSecret ($remittanceApiSecret )
    {
        self::$remittanceApiSecret  = $remittanceApiSecret ;
    }


    /**
     * @return string The remittancePrimaryKey.
     */
    public static function getRemittancePrimaryKey()
    {
        return self::$remittancePrimaryKey || getenv("REMITTANCE_PRIMARY_KEY");
    }


    /**
     * Sets the remittancePrimaryKey.
     *
     * @param string $remittancePrimaryKey
     */
    public static function setRemittancePrimaryKey($remittancePrimaryKey)
    {
        self::$remittancePrimaryKey = $remittancePrimaryKey;
    }


    /**
     * @return string The remittanceUserId .
     */
    public static function getRemittanceUserId ()
    {
        return self::$remittanceUserId || getenv("REMITTANCE_USER_ID") ;
    }


    /**
     * Sets the remittanceUserId.
     *
     * @param string $remittanceUserId
     */
    public static function setRemittanceUserId($remittanceUserId)
    {
        self::$remittanceUserId = $remittanceUserId;
    }


    /**
     * @return string The disbursementApiSecret.
     */
    public static function getDisbursementApiSecret()
    {
        return self::$disbursementApiSecret || getenv("DISBURSEMENT_API_SECRET");
    }


    /**
     * Sets the disbursementApiSecret.
     *
     * @param string $disbursementApiSecret
     */
    public static function setDisbursementApiSecret($disbursementApiSecret)
    {
        self::$disbursementApiSecret = $disbursementApiSecret;
    }


    /**
     * @return string The disbursementPrimaryKey.
     */
    public static function getDisbursementPrimaryKey()
    {
        return self::$disbursementPrimaryKey || getenv("DISBURSEMENT_PRIMARY_KEY");
    }



    /**
     * Sets the disbursementPrimaryKey.
     *
     * @param string $disbursementPrimaryKey
     */
    public static function setDisbursementPrimaryKey($disbursementPrimaryKey)
    {
        self::$disbursementPrimaryKey = $disbursementPrimaryKey;
    }


    /**
     * @return string The disbursementUserId .
     */
    public static function getDisbursementUserId ()
    {
        return self::$disbursementUserId || getenv("DISBURSEMENT_USER_ID");
    }




    /**
     * Sets the disbursementUserId.
     *
     * @param string $disbursementUserId
     */
    public static function setDisbursementUserId($disbursementUserId)
    {
        self::$disbursementUserId = $disbursementUserId;
    }



    /**
     * @return Util\LoggerInterface The logger to which the library will
     *   produce messages.
     */
    public static function getLogger()
    {
        if (self::$logger == null) {
            return new Util\DefaultLogger();
        }
        return self::$logger;
    }

    /**
     * @param Util\LoggerInterface $logger The logger to which the library
     *   will produce messages.
     */
    public static function setLogger($logger)
    {
        self::$logger = $logger;
    }



    /**
     * @return int Maximum number of request retries
     */
    public static function getMaxNetworkRetries()
    {
        return self::$maxNetworkRetries;
    }

    /**
     * @param int $maxNetworkRetries Maximum number of request retries
     */
    public static function setMaxNetworkRetries($maxNetworkRetries)
    {
        self::$maxNetworkRetries = $maxNetworkRetries;
    }

    /**
     * @return float Maximum delay between retries, in seconds
     */
    public static function getMaxNetworkRetryDelay()
    {
        return self::$maxNetworkRetryDelay;
    }

    /**
     * @return float Initial delay between retries, in seconds
     */
    public static function getInitialNetworkRetryDelay()
    {
        return self::$initialNetworkRetryDelay;
    }


}
