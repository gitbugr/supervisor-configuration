<?php

namespace spec\Supervisor\Configuration\Writer;

use League\Flysystem\Filesystem;
use League\Flysystem\UnableToWriteFile;
use PhpSpec\ObjectBehavior;
use Supervisor\Configuration\Configuration;
use Supervisor\Configuration\Writer\FlysystemWriter;
use Supervisor\Configuration\Writer\WriterInterface;
use Supervisor\Configuration\Exception\WriterException;

class FlysystemWriterSpec extends ObjectBehavior
{
    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem, 'file');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FlysystemWriter::class);
    }

    function it_is_a_writer()
    {
        $this->shouldImplement(WriterInterface::class);
    }

    function it_writes_a_configuration_to_a_file(Filesystem $filesystem, Configuration $configuration)
    {
        $configuration->toArray()
            ->willReturn([]);

        $filesystem->write('file', '');

        $this->write($configuration)
            ->shouldReturn(null);
    }

    function it_throws_an_exception_when_configuration_cannot_be_written(
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $configuration->toArray()
            ->willReturn([]);

        $filesystem->write('file', '')->willThrow(new UnableToWriteFile('Failed'));

        $this->shouldThrow(WriterException::class)
            ->duringWrite($configuration);
    }
}
