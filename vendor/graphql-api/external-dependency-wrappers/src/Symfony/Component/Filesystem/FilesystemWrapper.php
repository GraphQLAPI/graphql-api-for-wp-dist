<?php

declare (strict_types=1);
namespace GraphQLAPI\ExternalDependencyWrappers\Symfony\Component\Filesystem;

use RuntimeException;
use PrefixedByPoP\Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use PrefixedByPoP\Symfony\Component\Filesystem\Filesystem;
/**
 * Wrapper for Symfony\Component\Filesystem\Filesystem
 */
class FilesystemWrapper
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fileSystem;
    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }
    /**
     * Removes files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws RuntimeException When removal fails
     */
    public function remove($files) : void
    {
        try {
            $this->fileSystem->remove($files);
        } catch (IOExceptionInterface $e) {
            // Throw own exception
            throw new RuntimeException($e->getMessage());
        }
    }
}
