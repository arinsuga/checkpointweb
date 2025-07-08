<?php

namespace Arins\Bo\Repositories\User;

use Arins\Repositories\Data\DataRepositoryInterface;

interface UserRepositoryInterface extends DataRepositoryInterface
{
    function all();
    function dnb();
    function withAttends($parUserId = null, $parCheckpoint_dt1 = null, $parCheckpoint_dt2 = null);
    
}