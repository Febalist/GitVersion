<?php

namespace Febalist\GitVersion;

use Carbon\Carbon;
use Jenssegers\Date\Date as JenssegersDate;

class Version
{
    protected $base_path;
    protected $cache = [];

    public function __construct($path = null)
    {
        if (!$path) {
            if (function_exists('base_path')) {
                $path = base_path();
            } else {
                $path = getcwd();
            }
        }
        $this->base_path = realpath($path);
    }

    /** @return string */
    protected function exec($command)
    {
        $key = md5($command);
        if (!array_has($this->cache, $key)) {
            $value = shell_exec("cd \"$this->base_path\" && git $command");
            $value = trim($value);
            $this->cache[$key] = $value;
        }

        return $this->cache[$key];
    }

    /** @return string */
    protected function tag($without_prefix = false, $options = null)
    {
        $version = $this->exec("describe --match=\"v*\" $options");
        if ($version && $without_prefix) {
            $version = substr($version, 1);
        }

        return $version;
    }

    /** @return string */
    public function current($without_prefix = false)
    {
        return $this->tag($without_prefix);
    }

    /** @return string */
    public function last($without_prefix = false)
    {
        return $this->tag($without_prefix, '--abbrev=0');
    }

    /** @return JenssegersDate|Carbon */
    public function date()
    {
        $timestamp = $this->exec('log -1 --pretty=format:%ct');
        if (class_exists(JenssegersDate::class)) {
            return JenssegersDate::createFromTimestamp($timestamp);
        }

        return Carbon::createFromTimestamp($timestamp);
    }

    /** @return string */
    public function hash($length = null)
    {
        $hash = $this->exec('rev-parse HEAD');
        if ($length) {
            return substr($hash, 0, $length);
        }

        return $hash;
    }
}
