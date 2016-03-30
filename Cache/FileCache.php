<?php

namespace Zelenin\SmsRu\Cache;

use Zelenin\SmsRu\Exception\Exception;

class FileCache implements CacheInterface
{

    protected $path;

    /**
     * FileCache constructor.
     * @param string|null $path
     * @throws Exception
     */
    public function __construct($path = null)
    {
        $this->path = $path = empty($path)
            ? implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), 'cache'])
            : $path;

        if (!is_dir($path)) {
            $this->mkdir($path);
        }

        if (!is_writable($path)) {
            throw new Exception(sprintf('Cache directory is not writable: %s', $path));
        }
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        $cacheFile = $this->getCacheFile($key);

        return @filemtime($cacheFile) > time();
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $cacheFile = $this->getCacheFile($key);

        if (@filemtime($cacheFile) > time()) {
            $fp = @fopen($cacheFile, 'r');
            if ($fp !== false) {
                @flock($fp, LOCK_SH);
                $cacheValue = @stream_get_contents($fp);
                @flock($fp, LOCK_UN);
                @fclose($fp);

                return $cacheValue;
            }
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param integer|null $ttl
     * @return mixed
     * @throws Exception
     */
    public function set($key, $value, $ttl = null)
    {
        $cacheFile = $this->getCacheFile($key);

        if (@file_put_contents($cacheFile, $value, LOCK_EX) !== false) {
            @chmod($cacheFile, 0666);

            $ttl = (int)$ttl;
            $ttl = empty($ttl)
                ? 31536000 // 1 year
                : $ttl;

            return @touch($cacheFile, $ttl + time());
        } else {
            $error = error_get_last();

            throw new Exception(sprintf('Unable to write cache file "%s": %s', $cacheFile, $error['message']));
        }
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        $cacheFile = $this->getCacheFile($key);

        return @unlink($cacheFile);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getCacheFile($key)
    {
        return $this->path . DIRECTORY_SEPARATOR . $key . '.bin';
    }

    /**
     * @param string $path
     * @param integer $mode
     * @param bool $recursive
     * @return bool
     * @throws Exception
     */
    protected function mkdir($path, $mode = 0775, $recursive = true)
    {
        if (is_dir($path)) {
            return true;
        }

        $parentDir = dirname($path);
        // recurse if parent dir does not exist and we are not at the root of the file system.
        if ($recursive && !is_dir($parentDir) && $parentDir !== $path) {
            $this->mkdir($parentDir, $mode, true);
        }

        try {
            if (!mkdir($path, $mode)) {
                return false;
            }
        } catch (\Exception $e) {
            if (!is_dir($path)) {
                throw new Exception(sprintf('Failed to create directory "%s": %s', $path, $e->getMessage()), $e->getCode(), $e);
            }
        }

        try {
            return chmod($path, $mode);
        } catch (\Exception $e) {
            throw new Exception(sprintf('Failed to change permissions for directory "%s": %s', $path, $e->getMessage()), $e->getCode(), $e);
        }
    }
}
