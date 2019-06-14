<?php

namespace MomoApi;

class RequestOptionsTest extends TestCase
{
    public function testStringAPIKey()
    {
        $opts = Util\RequestOptions::parse("foo");
        $this->assertSame("foo", $opts->apiKey);
        $this->assertSame([], $opts->headers);
    }

    public function testNull()
    {
        $opts = Util\RequestOptions::parse(null);
        $this->assertSame(null, $opts->apiKey);
        $this->assertSame([], $opts->headers);
    }

    public function testEmptyArray()
    {
        $opts = Util\RequestOptions::parse([]);
        $this->assertSame(null, $opts->apiKey);
        $this->assertSame([], $opts->headers);
    }

    public function testAPIKeyArray()
    {
        $opts = Util\RequestOptions::parse(
            [
                'api_key' => 'foo',
            ]
        );
        $this->assertSame('foo', $opts->apiKey);
        $this->assertSame([], $opts->headers);
    }


}
