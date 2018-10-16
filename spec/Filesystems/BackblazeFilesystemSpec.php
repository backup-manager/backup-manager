<?php

namespace spec\BackupManager\Filesystems;

use BackblazeB2\Http\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;

class BackblazeFilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Filesystems\BackblazeFilesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['b2', 'B2'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_provide_an_instance_of_an_b2_filesystem() {
        $this->get($this->getConfig())->getAdapter()->shouldHaveType('Mhetreramesh\Flysystem\BackblazeAdapter');
    }

    function getConfig() {
        return [
            'key'       => 'test_key',
            'accountId' => 'test_id',
            'bucket'    => 'bucket',
            'options'   => ['client' => $this->getMockClient()]
        ];
    }

    function getMockClient() {
        $handler = new HandlerStack(new MockHandler([$this->getMockAuthorizationResponse()]));

        return new Client(['handler' => $handler]);
    }

    function getMockAuthorizationResponse() {
        $body = '{
                    "accountId: "test_id", 
                    "apiUrl": "https://api900.backblaze.com", 
                    "authorizationToken": "testAuthToken, 
                    "downloadUrl": "https://f900.backblaze.com"
                }';

        return new Response(200, [], $body);
    }
}