<?php
namespace App\Services\AdService;

use App\Repositories\AdRepository;
use Illuminate\Http\JsonResponse;

class DisplayAdService
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function getAllAds(): JsonResponse
    {
        $ads = $this->adRepository->getAll();
        return response()->json([
            'status' => 'success',
            'message' => 'Ads retrieved successfully',
            'data' => $ads
        ]);
    }

    public function getAdById($id): JsonResponse
    {
        $ad = $this->adRepository->getById($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Ad retrieved successfully',
            'data' => $ad
        ]);
    }

    public function getActiveAds(): JsonResponse
    {
        $ads = $this->adRepository->getActiveAds();
        return response()->json([
            'status' => 'success',
            'message' => 'Active ads retrieved successfully',
            'data' => $ads
        ]);
    }
}
