<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Admin;
use App\Repositories\BaseRepository;

class AdminRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function adminsHaveFcmToken() 
    {
         return $this->model->whereNotNull('fcm_token')->get();
    }
}