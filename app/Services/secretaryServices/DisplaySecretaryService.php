<?php
namespace App\Services\secretaryServices;

use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use App\Repositories\SecretaryRepository;
use Illuminate\Support\Facades\Validator;

class DisplaySecretaryService 
{
    protected $secretaryRepository;
    
    public function __construct(SecretaryRepository  $secretaryRepository)
    {
        $this->secretaryRepository = $secretaryRepository;
    }

    public function getSecretaryPoints($secretaryId)
    {
        $points = $this->secretaryRepository->getPoints($secretaryId);
        return response()->json([
            "message" => "Secretary points retrieved successfully",
            "points" => $points
        ]);
    }
}