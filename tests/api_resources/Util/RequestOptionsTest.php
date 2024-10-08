<?php

namespace Telnyx\Util;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Telnyx\Util\RequestOptions::class)]

final class RequestOptionsTest extends \Telnyx\TestCase
{
    public function testParseString()
    {
        $opts = RequestOptions::parse('foo');
        static::assertSame('foo', $opts->apiKey);
        static::assertSame([], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseStringStrict()
    {
        $this->expectException(\Telnyx\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('#Do not pass a string for request options.#');

        $opts = RequestOptions::parse('foo', true);
    }

    public function testParseNull()
    {
        $opts = RequestOptions::parse(null);
        static::assertNull($opts->apiKey);
        static::assertSame([], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseArrayEmpty()
    {
        $opts = RequestOptions::parse([]);
        static::assertNull($opts->apiKey);
        static::assertSame([], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseArrayWithAPIKey()
    {
        $opts = RequestOptions::parse(
            [
                'api_key' => 'foo',
            ]
        );
        static::assertSame('foo', $opts->apiKey);
        static::assertSame([], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseArrayWithIdempotencyKey()
    {
        $opts = RequestOptions::parse(
            [
                'idempotency_key' => 'foo',
            ]
        );
        static::assertNull($opts->apiKey);
        static::assertSame(['Idempotency-Key' => 'foo'], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseArrayWithAPIKeyAndIdempotencyKey()
    {
        $opts = RequestOptions::parse(
            [
                'api_key' => 'foo',
                'idempotency_key' => 'foo',
            ]
        );
        static::assertSame('foo', $opts->apiKey);
        static::assertSame(['Idempotency-Key' => 'foo'], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseArrayWithAPIKeyAndUnexpectedKeys()
    {
        $opts = RequestOptions::parse(
            [
                'api_key' => 'foo',
                'foo' => 'bar',
            ]
        );
        static::assertSame('foo', $opts->apiKey);
        static::assertSame([], $opts->headers);
        static::assertNull($opts->apiBase);
    }

    public function testParseArrayWithAPIKeyAndUnexpectedKeysStrict()
    {
        $this->expectException(\Telnyx\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Got unexpected keys in options array: foo');

        $opts = RequestOptions::parse(
            [
                'api_key' => 'foo',
                'foo' => 'bar',
            ],
            true
        );
    }

    public function testParseArrayWithAPIBase()
    {
        $opts = RequestOptions::parse(
            [
                'api_base' => 'https://example.com',
            ]
        );
        static::assertNull($opts->apiKey);
        static::assertSame([], $opts->headers);
        static::assertSame('https://example.com', $opts->apiBase);
    }

    public function testParseWrongType()
    {
        $this->expectException(\Telnyx\Exception\InvalidArgumentException::class);

        $opts = RequestOptions::parse(5);
    }

    public function testMerge()
    {
        $baseOpts = RequestOptions::parse(
            [
                'api_key' => 'foo',
                'idempotency_key' => 'foo',
            ]
        );
        $opts = $baseOpts->merge(
            [
                'idempotency_key' => 'bar',
            ]
        );
        static::assertSame('foo', $opts->apiKey);
        static::assertNull($opts->apiBase);
    }

    public function testDiscardNonPersistentHeaders()
    {
        $opts = RequestOptions::parse(
            [
                'telnyx_account' => 'foo',
            ]
        );
        $opts->discardNonPersistentHeaders();
        static::assertSame(['Telnyx-Account' => 'foo'], $opts->headers);
    }

    public function testDebugInfo()
    {
        $opts = RequestOptions::parse(['api_key' => 'sk_test_1234567890abcdefghijklmn']);
        $debugInfo = print_r($opts, true);
        $debugLines = explode("\n", $debugInfo);
        $debugLines = array_map('trim', $debugLines);
        static::assertContains('[apiKey] => sk_test_********************klmn', $debugLines);

        $opts = RequestOptions::parse(['api_key' => 'sk_1234567890abcdefghijklmn']);
        $debugInfo = print_r($opts, true);
        $debugInfo = explode("\n", $debugInfo);
        $debugInfo = array_map('trim', $debugInfo);
        static::assertContains('[apiKey] => sk_********************klmn', $debugInfo);

        $opts = RequestOptions::parse(['api_key' => '1234567890abcdefghijklmn']);
        $debugInfo = print_r($opts, true);
        $debugInfo = explode("\n", $debugInfo);
        $debugInfo = array_map('trim', $debugInfo); 
        static::assertContains('[apiKey] => ********************klmn', $debugInfo);
    }
     

}
