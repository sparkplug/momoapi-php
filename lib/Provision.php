<?php
namespace MomoApi;


use MomoApi\Util\Util;

require_once("Util/Util.php");

class Provision
{
    function getCredentials(){

        echo 'providerCallbackHost:';
        $host = fgets(STDIN);


        echo 'Ocp-Apim-Subscription-Key: ';
        $apiKey = fgets(STDIN);



        $data = json_encode( array("providerCallbackHost" =>  trim($host)));

        $url = 'https://ericssonbasicapi2.azure-api.net/v1_0/apiuser';

        $token = Util\Util::uuid();
        echo $data;
        $ch = curl_init();

        $userUrl = "https://ericssonbasicapi2.azure-api.net/v1_0/apiuser/".$token ."/apikey";

//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
//curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type: application/json',
                'X-Reference-Id: ' . $token,
                'Accept: application/json',
                'Ocp-Apim-Subscription-Key: '. trim($apiKey)
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if($result) {
            curl_setopt($ch, CURLOPT_URL, $userUrl);

            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array('Content-Type: application/json',
                    'Accept: application/json',
                    'Ocp-Apim-Subscription-Key: ' . trim($apiKey)
                )
            );


            $result2 = curl_exec($ch);


            curl_close($ch);
            echo $result;
            echo $result2;
            $res = json_decode($result2, true);


            echo "Here is your User Id and API secret : {UserId:" . $token . " , APISecret: " . $res["apiKey"] . " }";


        }

    }
}

if (!debug_backtrace()) {

    $obj = new Provision();
    $obj->getCredentials()();
}



