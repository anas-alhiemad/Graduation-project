<?php
namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class NotificationRepository extends BaseRepository implements RepositoryInterface 
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }
}