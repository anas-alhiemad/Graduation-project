<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;
use App\Models\Secretary;

class SecretaryRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Secretary $model)
    {
        parent::__construct($model);
    }




    public function storeFcmToken($secretary,$fcmToken)
    {
        $secretary = $this->model->whereId($secretary->id)->first();
        $secretary->fcm_token =$fcmToken;
        $secretary->save();
        return "done";
    }
}