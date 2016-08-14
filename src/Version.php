<?php
namespace Febalist\GitVersion;

use Carbon\Carbon;
use Gitonomy\Git\Repository;

class Version
{
    /** @var Repository */
    protected $repository;

    public function __construct()
    {
        $path             = base_path();
        $this->repository = new Repository($path);
    }

    protected function head()
    {
        return $this->repository->getHeadCommit();
    }

    public function hash($length = null)
    {
        if ($length) {
            return $this->head()->getFixedShortHash($length);
        }
        return $this->head()->getHash();
    }

    public function date()
    {
        $date = $this->head()->getCommitterDate();
        return new Carbon($date);
    }

    public function tag()
    {
        $references = $this->repository->getReferences();
        $tags       = $references->resolveTags($this->head());
        return array_first($tags);
    }

}
