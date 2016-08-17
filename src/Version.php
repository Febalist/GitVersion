<?php
namespace Febalist\GitVersion;

use Carbon\Carbon;
use DateTime;
use Jenssegers\Date\Date as JenssegersDate;

class Version
{
    protected $base_path;
    protected $master_branch;
    protected $cache = [];

    public function __construct($path = null, $master = 'master')
    {
        if (!$path) {
            if (function_exists('base_path')) {
                $path = base_path();
            } else {
                $path = getcwd();
            }
        }
        $this->base_path     = realpath($path);
        $this->master_branch = $master;
    }

    /** @return string */
    protected function exec($command)
    {
        $key = md5($command);
        if (!array_has($this->cache, $key)) {
            $value             = shell_exec("cd \"$this->base_path\" && git $command");
            $value             = trim($value);
            $this->cache[$key] = $value;
        }
        return $this->cache[$key];
    }

    /** @return string */
    public function current($without_prefix = false)
    {
        $version = $this->exec("describe $this->master_branch --match=\"v*\"");
        if ($without_prefix && starts_with($version, 'v')) {
            $version = substr($version, 1);
        }
        return $version;
    }

    /** @return array */
    protected function parts()
    {
        $version = $this->current(true);
        return explode('.', $version);
    }

    /** @return integer */
    public function part($index)
    {
        $part = $this->parts()[$index];
        return intval($part);
    }

    /** @return integer */
    public function major()
    {
        return $this->part(0);
    }

    /** @return integer */
    public function minor()
    {
        return $this->part(1);
    }

    /** @return integer */
    public function patch()
    {
        return $this->part(2);
    }

    /** @return string */
    public function branch()
    {
        return $this->exec('rev-parse --abbrev-ref HEAD');
    }

    /** @return boolean */
    public function isMaster()
    {
        return $this->branch() == $this->master_branch;
    }

    /** @return JenssegersDate|Carbon|DateTime */
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
