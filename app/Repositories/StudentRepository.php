<?php

namespace App\Repositories;

use App\Models\Student;
use App\Interfaces\RepositoryInterface;

class StudentRepository extends BaseRepository implements RepositoryInterface
{
    
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->firstOrFail();
    }

    public function updatePassword($email,$password)
    {
        $recorde = $this->getByEmail($email);
        $recorde->update(['password'=>$password]);
        return true;
    }

    public function storeFcmToken($user,$fcmToken)
    {
        $user = $this->model->whereId($user->id)->first();
        $user->fcm_token =$fcmToken;
        $user->save();
        return "done";
    }
  
}
