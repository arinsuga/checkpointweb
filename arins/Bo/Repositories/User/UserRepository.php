<?php

namespace Arins\Bo\Repositories\User;

use App\User;
use Arins\Repositories\Data\EloquentRepository;
use Arins\Bo\Repositories\User\UserRepositoryInterface;

class UserRepository extends EloquentRepository
                     implements UserRepositoryInterface

// class UserRepository implements UserRepositoryInterface
                                
{
    protected $data;

    public function __construct($parData)
    {
        $this->data = $parData;
    }

    public function all()
    {
        return $this->data->where('bo', false)->get();
        //return 'Berhasil allBo';
    }

    public function dnb()
    {
        return $this->data->where('dept', 'DNB')->get();
        //return 'Berhasil allBo';
    }

    protected function query($query, $parUserId, $parCheckpoint_dt1, $parCheckpoint_dt2) {

            if (isset($parUserId) && $parUserId > 0) {

                $query = $query->where(function ($q) use ($parUserId) {
                    $q->where('user_id', $parUserId);
                });

            }

            if (isset($parCheckpoint_dt1) && !isset($parCheckpoint_dt2)) {

                $query = $query->where(function ($q) use ($parCheckpoint_dt1) {
                        $q = $q->where('checkin_time', '>=', $parCheckpoint_dt1);
                        $q = $q->orWhere('checkout_time', '>=', $parCheckpoint_dt1);
                });

            }

            if (isset($parCheckpoint_dt1) && isset($parCheckpoint_dt2)) {

                $query = $query->where(function ($q) use ($parCheckpoint_dt1, $parCheckpoint_dt2) {
                        $q = $q->whereBetween('checkin_time', [$parCheckpoint_dt1, $parCheckpoint_dt2]);
                        $q = $q->orWhereBetween('checkout_time', [$parCheckpoint_dt1, $parCheckpoint_dt2]);
                });

            }

            return $query;

    }

    public function withAttends($parUserId = null, $parCheckpoint_dt1 = null, $parCheckpoint_dt2 = null)
    {
        $result = null;

        $result = $this->data
        ->whereHas('attends', function ($query) use ($parUserId, $parCheckpoint_dt1, $parCheckpoint_dt2) {

            $query = $this->query($query, $parUserId, $parCheckpoint_dt1, $parCheckpoint_dt2);
            
        })
        ->with(['attends' => function ($query) use ($parUserId, $parCheckpoint_dt1, $parCheckpoint_dt2) {

            $query = $this->query($query, $parUserId, $parCheckpoint_dt1, $parCheckpoint_dt2);

        }])
        ->where('bo', false)
        ->get();

        return $result;
    }

}