<?php
namespace App\Services\AdService;

use App\Repositories\AdRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class CreateAdService
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

   public function handle($request): JsonResponse
{
    $data = $request->validated();

    if ($request->hasFile('photo')) {
        
        $data['photo'] = $this->uploadPhoto($request->file('photo'));
    }

    $ad = $this->adRepository->create($data);

    return response()->json([
        'status' => 'success',
        'message' => 'Ad created successfully',
        'data' => $ad
    ], 201);
}

       protected function uploadPhoto($photo): string
    {
        return 'upload/' . $photo->store('ads', 'public_upload');
    }
}
