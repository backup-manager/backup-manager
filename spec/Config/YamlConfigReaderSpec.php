<?php

namespace spec\BackupManager\Config;

use BackupManager\Config\Config;
use BackupManager\Config\YamlConfigReader;
use BackupManager\File;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class YamlConfigReaderSpec extends ObjectBehavior {

    function it_is_initializable(FilesystemInterface $filesystem, Parser $parser, Dumper $dumper) {
        $this->beConstructedWith($filesystem, $parser, $dumper);
        $this->shouldHaveType(YamlConfigReader::class);
    }

    function it_can_read_a_file(FilesystemInterface $filesystem, Parser $parser, Dumper $dumper) {
        $file = new File('path/to/backupmanager.yml');
        $filesystem->read($file->fullPath())->shouldBeCalled();
        $parser->parse(Argument::any())->willReturn(['database' => 'test']);
        $this->beConstructedWith($filesystem, $parser, $dumper);

        $this->read($file)->shouldBeLike(new Config(['database' => 'test']));
    }
}
