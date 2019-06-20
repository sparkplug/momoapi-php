<?php

namespace MomoApi;

require_once "Collection.php";

class Test
{
    public function getToken()
    {
        $coll = Collection::getToken();
        echo $coll;
    }
}


if (!debug_backtrace()) {
    $obj = new Test();
    $obj->getToken();
}
