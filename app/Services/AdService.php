<?php

namespace App\Services;

use App\Repositories\AdRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class AdService
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function getAllAds(): JsonResponse
    {
        try {
            $ads = $this->adRepository->getAll();
            return response()->json([
                'status' => 'success',
                'message' => 'Ads retrieved successfully',
                'data' => $ads
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve ads',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAdById($id): JsonResponse
    {
        try {
            $ad = $this->adRepository->getById($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Ad retrieved successfully',
                'data' => $ad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve ad',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function createAd(array $data): JsonResponse
    {
        try {
            if (isset($data['photo']) && $data['photo']) {
                $data['photo'] = $this->uploadPhoto($data['photo']);
            }
            
            $ad = $this->adRepository->create($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Ad created successfully',
                'data' => $ad
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create ad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAd($id, array $data): JsonResponse
    {
        try {
            if (isset($data['photo']) && $data['photo']) {
                $ad = $this->adRepository->getById($id);
                if ($ad && $ad->photo) {
                    Storage::delete($ad->photo);
                }
                $data['photo'] = $this->uploadPhoto($data['photo']);
            }

            $ad = $this->adRepository->update($id, $data);
            return response()->json([
                'status' => 'success',
                'message' => 'Ad updated successfully',
                'data' => $ad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update ad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAd($id): JsonResponse
    {
        try {
            $ad = $this->adRepository->getById($id);
            if ($ad && $ad->photo) {
                Storage::delete($ad->photo);
            }
            $this->adRepository->delete($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Ad deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete ad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function uploadPhoto($photo): string
    {
        return $photo->store('ads', 'public');
    }

    public function getActiveAds(): JsonResponse
    {
        try {
            $ads = $this->adRepository->getActiveAds();
            return response()->json([
                'status' => 'success',
                'message' => 'Active ads retrieved successfully',
                'data' => $ads
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve active ads',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 