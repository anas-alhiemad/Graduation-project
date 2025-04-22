<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;
use App\Models\Trainer;

class TrainerRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Trainer $model)
    {
        parent::__construct($model);
    }


    public function storeFcmToken($user,$fcmToken)
    {
        $user = $this->model->whereId($user->id)->first();
        $user->fcm_token =$fcmToken;
        $user->save();
        return "done";
    }

}