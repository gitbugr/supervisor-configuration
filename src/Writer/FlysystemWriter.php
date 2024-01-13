<?php

namespace Supervisor\Configuration\Writer;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToWriteFile;
use Supervisor\Configuration\Configuration;
use Supervisor\Configuration\Exception\WriterException;

/**
 * Write a configuration into any filesystem.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class FlysystemWriter extends AbstractWriter
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $file;

    /**
     * @param Filesystem $filesystem
     * @param string $file
     */
    public function __construct(Filesystem $filesystem, $file)
    {
        $this->filesystem = $filesystem;
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function write(Configuration $configuration): void
    {
        $ini = $this->getRenderer()->render($configuration->toArray());

        try {
            $this->filesystem->write($this->file, $ini);
        } catch (UnableToWriteFile|FilesystemException $exception) {
            throw new WriterException(
                sprintf('Cannot write configuration into file %s', $this->file),
                previous: $exception
            );
        }
    }
}
