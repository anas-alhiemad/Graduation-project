<?php
namespace App\Repositories;

use App\Models\PasswordReset;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class PasswordResetRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(PasswordReset $model)
    {
        parent::__construct($model);
    }

    public function deleteRecord($email)
    {
        $record = $this->model->where('email',$email)->delete();
        return true;
    }

    public function generationToken()
    {
        do {
            $tokenModel = mt_rand(10000, 99999);
        } while ($this->model->where('token', $tokenModel)->exists());

        return  $tokenModel;
    }

    public function store(array $data)
    {
       return $codeData = $this->model->create($data);
    }

    public function getRecord($token)
    {
       $record =  $this->model->where('token', $token)->firstOrFail();
       if ($record->created_at> now()->addHour()) {
        $record->delete();
        return false;}
        return $record;
    
    }
}