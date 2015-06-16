<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class RackspaceFilesystemSpec
 *
 * This driver can't be tested because the library is poorly written
 * and the constructor immediately tries to connect.
 *
 * @package spec\BackupManager\Filesystems
 */
class RackspaceFilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Filesystems\RackspaceFilesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['rackspace', 'RackSpace', 'RACKSPACE'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function getConfig() {
        return [
            'username' => 'username',
            'key'      => 'key',
            'root'     => 'root',
            'zone'     => 'zone',
            'endpoint' => 0
        ];
    }
}
