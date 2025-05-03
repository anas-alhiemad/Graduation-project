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

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function updatePassword($email,$password)
    {
        $recorde = $this->getByEmail($email);
        $recorde->update(['password'=>$password]);
        return true;
    }

    public function storeFcmToken($secretary,$fcmToken)
    {
        $secretary = $this->model->whereId($secretary->id)->first();
        $secretary->fcm_token =$fcmToken;
        $secretary->save();
        return "done";
    }
}