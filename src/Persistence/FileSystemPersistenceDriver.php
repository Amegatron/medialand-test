<?php

namespace Counters\Persistence;

use Counters\Core\Exceptions\CounterDoesNotExistException;
use Counters\Core\IncrementorInterface;
use Counters\Management\CounterPersistenceDriverInterface;
use Exception;

/**
 * Class FileSystemPersistenceDriver
 *
 * A specific implementation of a CounterPersistenceDriverInterface using file system
 *
 * @package Counters\Persistence
 */
class FileSystemPersistenceDriver implements CounterPersistenceDriverInterface
{
    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var FileSystemIncrementor
     */
    private $incrementor;

    public function __construct(string $rootPath)
    {
        if (file_exists($rootPath)) {
            if (!is_dir($rootPath) || !is_writable($rootPath)) {
                throw new Exception("Target path is not writeable");
            }
        } else if (!mkdir($rootPath)) {
            throw new Exception("Could not create storage directory");
        }

        $this->rootPath = $rootPath;
    }

    public function loadValue($uuid): int
    {
        $fileName = $this->getFileNameByUuid($uuid);
        if (!file_exists($fileName)) {
            throw new CounterDoesNotExistException($uuid);
        }

        return intval(file_get_contents($fileName));
    }

    public function increment($uuid, $by = 1): int
    {
        $fileName = $this->getFileNameByUuid($uuid);
        if (!file_exists($fileName)) {
            throw new CounterDoesNotExistException($uuid);
        }

        $fp = fopen($fileName, 'r+');
        flock($fp, LOCK_EX);
        $buf = "";
        while (!feof($fp)) {
            $buf .= fread($fp, 100);
        }
        $value = intval($buf);
        $value += $by;
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, $value);
        fclose($fp);

        return $value;
    }

    public function saveValue($uuid, $value)
    {
        $fileName = $this->getFileNameByUuid($uuid);
        if (!file_exists($fileName)) {
            $this->ensureDirectoryStructureExists($fileName);
        }
        file_put_contents($fileName, $value);
    }

    public function getIncrementor(): IncrementorInterface
    {
        if (is_null($this->incrementor)) {
            $this->incrementor = new FileSystemIncrementor($this);
        }
        return $this->incrementor;
    }

    private function getFileNameByUuid(string $uuid): string {
        return realpath($this->rootPath) . '/' . $uuid[0] . '/' . $uuid[1] . '/' . preg_replace('~[^a-z0-9]~is', '', $uuid);
    }

    private function ensureDirectoryStructureExists($path) {
        $rootPathReal = realpath($this->rootPath);
        $relativePath = substr($path, strlen($rootPathReal));
        $pathParts = explode('/', $relativePath);
        array_pop($pathParts);
        $testPath = $rootPathReal;
        foreach ($pathParts as $part) {
            $testPath .= '/' . $part;
            if (!file_exists($testPath)) {
                mkdir($testPath);
            }
        }
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @param mixed $rootPath
     */
    public function setRootPath(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    public function exists($uuid): bool
    {
        $fileName = $this->getFileNameByUuid($uuid);
        return file_exists($fileName);
    }
}