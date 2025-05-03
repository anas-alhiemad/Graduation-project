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


    public function search($search)
    {
       
        $trainer = $this->model::query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->paginate(10);

        return $trainer;
    }

}