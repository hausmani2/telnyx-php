<?php

namespace Telnyx;
 
use PHPUnit\Framework\Attributes\CoversClass;

 
#[CoversClass(\Telnyx\MessagingHostedNumberOrder::class)]

final class MessagingHostedNumberOrderTest extends \Telnyx\TestCase
{
    const TEST_RESOURCE_ID = '86f58db9-0fe3-4adc-9d1f-46e66e6e9323';
    // const TEST_RESOURCE_ID = 'quae';

    public function testIsListable()
    {
        $this->expectsRequest(
            'get',
            '/v2/messaging_hosted_number_orders'
        );
        $resources = MessagingHostedNumberOrder::all();
        $this->assertInstanceOf(\Telnyx\Collection::class, $resources);
        $this->assertInstanceOf(\Telnyx\MessagingHostedNumberOrder::class, $resources['data'][0]);
    }

    public function testIsCreatable()
    {
        $this->expectsRequest(
            'post',
            '/v2/messaging_hosted_number_orders'
        );
        $resource = MessagingHostedNumberOrder::create(["messaging_profile_id" => self::TEST_RESOURCE_ID]);
        $this->assertInstanceOf(\Telnyx\MessagingHostedNumberOrder::class, $resource);
    }

    public function testIsRetrievable()
    {
        $this->expectsRequest(
            'get',
            '/v2/messaging_hosted_number_orders/' . urlencode(self::TEST_RESOURCE_ID)
        );
        $resource = MessagingHostedNumberOrder::retrieve(self::TEST_RESOURCE_ID);
        $this->assertInstanceOf(\Telnyx\MessagingHostedNumberOrder::class, $resource);
    }

    /*
    public function testIsDeletable()
    {
        $resource = MessagingHostedNumberOrder::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'delete',
            '/v2/messaging_hosted_number_orders/' . urlencode(self::TEST_RESOURCE_ID)
        );
        $resource->delete();
        $this->assertInstanceOf(\Telnyx\MessagingHostedNumberOrder::class, $resource);
    }
    */

    // public function testFileUploadWithFileHandle()
    // {
         
    //     $call = MessagingHostedNumberOrder::retrieve(self::TEST_RESOURCE_ID);

    //     $this->expectsRequest(
    //         'post',
    //         '/v2/messaging_hosted_number_orders/' . urlencode(self::TEST_RESOURCE_ID) . '/actions/file_upload',
    //         null,
    //         ['Content-Type: multipart/form-data'],
    //         true
    //     );

    //     $fp = \fopen(__DIR__ . '/../data/test.png', 'rb');
        
    //     $resource = $call->file_upload(['bill' => $fp]);
    //     var_dump($resource); 
    //     $this->assertInstanceOf(\Telnyx\TelnyxObject::class, $resource);
    //     fclose($fp);
    // }

    // public function testFileUploadWithCURLFile()
    // {
         
    //     $call = MessagingHostedNumberOrder::retrieve(self::TEST_RESOURCE_ID);

    //     $this->expectsRequest(
    //         'post',
    //         '/v2/messaging_hosted_number_orders/' . urlencode(self::TEST_RESOURCE_ID) . '/actions/file_upload',
    //         null,
    //         ['Content-Type: multipart/form-data'],
    //         true
    //     );

    //     $curlFile = new \CURLFile(__DIR__ . '/../data/test.png');

    //     $resource = $call->file_upload(['bill' => $curlFile]);
    //     $this->assertInstanceOf(\Telnyx\TelnyxObject::class, $resource);
    // }
}
