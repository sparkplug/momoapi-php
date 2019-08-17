# MTN MoMo API PHP Client

<strong>Power your apps with our MTN MoMo API</strong>

<div>
  Join our active, engaged community: <br>
  <a href="https://momodeveloper.mtn.com/">Website</a>
  <span> | </span>
  <a href="https://spectrum.chat/momo-api-developers/">Spectrum</a>
  <br><br>
</div>

[![Build Status](https://travis-ci.com/sparkplug/momoapi-php.svg?branch=master)](https://travis-ci.com/sparkplug/momoapi-php)
[![Latest Stable Version](https://poser.pugx.org/sparkplug/momoapi-php/v/stable.svg)](https://packagist.org/packages/sparkplug/momoapi-php)
[![Total Downloads](https://poser.pugx.org/sparkplug/momoapi-php/downloads.svg)](https://packagist.org/packages/sparkplug/momoapi-php)
[![License](https://poser.pugx.org/sparkplug/momoapi-php/license.svg)](https://packagist.org/packages/sparkplug/momoapi-php)
[![Coverage Status](https://coveralls.io/repos/github/sparkplug/momoapi-php/badge.svg?branch=master)](https://coveralls.io/github/sparkplug/momoapi-php?branch=master)
[![Join the community on Spectrum](https://withspectrum.github.io/badge/badge.svg)](https://spectrum.chat/momo-api-developers/)



# Installation

You are required to have PHP 5.4.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require sparkplug/momoapi-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/sparkplug/momoapi-php/releases). Then, to use the bindings, include the `init.php` file.

```php
require_once('/path/to/momoapi-php/init.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`json`](https://secure.php.net/manual/en/book.json.php)
- [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

# Sandbox Environment

## Creating a sandbox environment API user 

Next, we need to get the `User ID` and `User Secret` and to do this we shall need to use the Primary Key for the Product to which we are subscribed, as well as specify a host. The library ships with a commandline application that helps to create sandbox credentials. It assumes you have created an account on `https://momodeveloper.mtn.com` and have your `Ocp-Apim-Subscription-Key`. 

```bash
## within the project, on the command line. In this example, our domain is akabbo.ug
$ php vendor/sparkplug/momoapi-php/lib/Provision.php
$ providerCallBackHost: https://akabbo.ug
$ Ocp-Apim-Subscription-Key: f83xx8d8xx6749f19a26e2265aeadbcdeg
```

The `providerCallBackHost` is your callback host and `Ocp-Apim-Subscription-Key` is your API key for the specific product to which you are subscribed. The `API Key` is unique to the product and you will need an `API Key` for each product you use. You should get a response similar to the following:

```bash
Here is your User Id and API secret : {'apiKey': 'b0431db58a9b41faa8f5860230xxxxxx', 'UserId': '053c6dea-dd68-xxxx-xxxx-c830dac9f401'}
```

These are the credentials we shall use for the sandbox environment. In production, these credentials are provided for you on the MTN OVA management dashboard after KYC requirements are met.



## Configuration

Before we can fully utilize the library, we need to specify global configurations. The global configuration using the requestOpts builder. By default, these are picked from environment variables,
but can be overidden using the MomoApi builder

* `BASE_URL`: An optional base url to the MTN Momo API. By default the staging base url will be used
* `ENVIRONMENT`: Optional enviroment, either "sandbox" or "production". Default is 'sandbox'
* `CURRENCY`: currency by default its EUR
* `CALLBACK_HOST`: The domain where you webhooks urls are hosted. This is mandatory.
* `COLLECTION_PRIMARY_KEY`: The collections API primary key,
* `COLLECTION_USER_ID`:  The collection User Id
* `COLLECTION_API_SECRET`:  The Collection API secret
* `REMITTANCE_USER_ID`:  The Remittance User ID
* `REMITTANCE_API_SECRET`: The Remittance API Secret
* `REMITTANCE_PRIMARY_KEY`: The Remittance Subscription Key
* `DISBURSEMENT_USER_ID`: The Disbursement User ID
* `DISBURSEMENT_API_SECRET`: The Disbursement API Secret
* `DISBURSEMENT_PRIMARY_KEY`: The Disbursement Primary Key

Once you have specified the global variables, you can now provide the product-specific variables. Each MoMo API product requires its own authentication details i.e its own `Subscription Key`, `User ID` and `User Secret`, also sometimes refered to as the `API Secret`. As such, we have to configure subscription keys for each product you will be using.

You will only need to configure the variables for the product(s) you will be using.

you can also use the MomoApi to globally set the different variables.



```php
MomoApi::setBaseUrl('base');

MomoApi::setTargetEnvironment("targetenv");

MomoApi::setCurrency("UGX");

MomoApi::setCollectionApiSecret("collection_api_secret");

MomoApi::setCollectionPrimaryKey("collection_primary_key");

MomoApi::setCollectionUserId("collection_user_id");

MomoApi::setRemittanceApiSecret("remittance_api_secret");

MomoApi::setRemittancePrimaryKey("remittance_primary_key");

MomoApi::setRemittanceUserId("remittance_user_id" );

MomoApi::setDisbursementApiSecret("disbursement_api_secret");

MomoApi::setDisbursementPrimaryKey("disbursement_primary_key");

MomoApi::setDisbursementUserId("disbursement_user_id");
```


## Collections

The collections client can be created with the following paramaters. Note that the `COLLECTION_USER_ID` and `COLLECTION_API_SECRET` for production are provided on the MTN OVA dashboard;

* `COLLECTION_PRIMARY_KEY`: Primary Key for the `Collection` product on the developer portal.
* `COLLECTION_USER_ID`: For sandbox, use the one generated with the `mtnmomo` command.
* `COLLECTION_API_SECRET`: For sandbox, use the one generated with the `mtnmomo` command.

You can create a collection client with the following:

```php
$client = Collection();
```

### Methods

1. `requestToPay`: This operation is used to request a payment from a consumer (Payer). The payer will be asked to authorize the payment. The transaction is executed once the payer has authorized the payment. The transaction will be in status PENDING until it is authorized or declined by the payer or it is timed out by the system. Status of the transaction can be validated by using `getTransactionStatus`.

2. `getTransaction`: Retrieve transaction information using the `transactionId` returned by `requestToPay`. You can invoke it at intervals until the transaction fails or succeeds. If the transaction has failed, it will throw an appropriate error. 

3. `getBalance`: Get the balance of the account.

4. `isPayerActive`: check if an account holder is registered and active in the system.

### Sample Code

```php

        $coll = new Collection($currency = "c..", $baseUrl = "url..", $targetEnvironment = "u...", $collectionApiSecret = "u...", $collectionPrimaryKey = "u...", $collectionUserId = "u..."]);

        $params = ['mobile' => "256782181656", 'payee_note' => "34", 'payer_message' => "12", 'external_id' => "ref", 'currency' => "EUR", 'amount' => "500"];

        $t = $coll->requestToPay($params);

        $transaction = $coll->getTransaction($t);

```

## Disbursement

The Disbursements client can be created with the following paramaters. Note that the `DISBURSEMENT_USER_ID` and `DISBURSEMENT_API_SECRET` for production are provided on the MTN OVA dashboard;

* `DISBURSEMENT_PRIMARY_KEY`: Primary Key for the `Disbursement` product on the developer portal.
* `DISBURSEMENT_USER_ID`: For sandbox, use the one generated with the `mtnmomo` command.
* `DISBURSEMENT_API_SECRET`: For sandbox, use the one generated with the `mtnmomo` command.

You can create a disbursements client with the following

```php

        $disbursement = new Disbursement();

        $params = ['mobile' => "256782181656", 'payee_note' => "34", 'payer_message' => "12", 'external_id' => "ref", 'currency' => "EUR", 'amount' => "500"];

        $t = $disbursement->requestToPay($params);


        $transaction = $disbursement->getTransaction($t);

```

### Methods

1. `transfer`: Used to transfer an amount from the owner’s account to a payee account. Status of the transaction can be validated by using the `getTransactionStatus` method.

2. `getTransactionStatus`: Retrieve transaction information using the `transactionId` returned by `transfer`. You can invoke it at intervals until the transaction fails or succeeds.

2. `getBalance`: Get your account balance.

3. `isPayerActive`: This method is used to check if an account holder is registered and active in the system.

#### Sample Code

```php


```


## Custom Request Timeouts

*NOTE:* We do not recommend decreasing the timeout for non-read-only calls , since even if you locally timeout, the request  can still complete.

To modify request timeouts (connect or total, in seconds) you'll need to tell the API client to use a CurlClient other than its default. You'll set the timeouts in that CurlClient.

```php
// set up your tweaked Curl client
$curl = new \MomoApi\HttpClient\CurlClient();
$curl->setTimeout(10); // default is \MomoApi\HttpClient\CurlClient::DEFAULT_TIMEOUT
$curl->setConnectTimeout(5); // default is \MomoApi\HttpClient\CurlClient::DEFAULT_CONNECT_TIMEOUT

echo $curl->getTimeout(); // 10
echo $curl->getConnectTimeout(); // 5

// tell MomoApi to use the tweaked client
\MomoApi\ApiRequest::setHttpClient($curl);

// use the Momo API client as you normally would
```

## Custom cURL Options (e.g. proxies)

Need to set a proxy for your requests? Pass in the requisite `CURLOPT_*` array to the CurlClient constructor, using the same syntax as `curl_stopt_array()`. This will set the default cURL options for each HTTP request made by the SDK, though many more common options (e.g. timeouts; see above on how to set those) will be overridden by the client even if set here.

```php
// set up your tweaked Curl client
$curl = new \MomoApi\HttpClient\CurlClient([CURLOPT_PROXY => 'proxy.local:80']);
// tell MomoApi to use the tweaked client
\MomoApi\ApiRequest::setHttpClient($curl);
```

Alternately, a callable can be passed to the CurlClient constructor that returns the above array based on request inputs. See `testDefaultOptions()` in `tests/CurlClientTest.php` for an example of this behavior. Note that the callable is called at the beginning of every API request, before the request is sent.

### Configuring a Logger

The library does minimal logging, but it can be configured
with a [`PSR-3` compatible logger][psr3] so that messages
end up there instead of `error_log`:

```php
\MomoApi\MomoApi::setLogger($logger);
```


### Configuring Automatic Retries

The library can be configured to automatically retry requests that fail due to
an intermittent network problem:

```php
\MomoApi\MomoApi::setMaxNetworkRetries(2);
```


## Development

Get [Composer][composer]. For example, on Mac OS:

```bash
brew install composer
```

Install dependencies:

```bash
composer install
```



Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), then you can run the test suite:

```bash
./vendor/bin/phpunit
```

Or to run an individual test file:

```bash
./vendor/bin/phpunit tests/UtilTest.php
```


[composer]: https://getcomposer.org/
[curl]: http://curl.haxx.se/docs/caextract.html
[psr3]: http://www.php-fig.org/psr/psr-3/
