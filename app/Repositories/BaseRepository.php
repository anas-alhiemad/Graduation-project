<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->paginate(10);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getId($id)
    {
        return $this->model->first('id',$id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function verification_tokenToken($token)
    {
        $record = $this->model->where('verification_token',$token)->first();
        if (!$record){ return $record;}
            $record->verification_token = null;
            $record->email_verified_at = now();
            $record->save();
            return $record;
    }

    function getVerificationToken($email)
    {
        $user = $this->model->whereEmail($email)->first();
        $verified = $user->email_verified_at;
        return $verified;
    }

    public function storeToken($emailModel)
    {
        $record = $this ->model->whereEmail($emailModel)->first();
        do {
            $tokenModel = mt_rand(10000, 99999);
        } while ($this->model->where('verification_token', $tokenModel)->exists());

        $record ->verification_token = $tokenModel ;
        $record ->save();
        return $record;
    }


    public function delete($id)
    {
        $record = $this->model->findOrFail($id);
        $record->delete();
        return true;
    }
}
