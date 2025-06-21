<?php
namespace App\Services\AdService;

use App\Repositories\AdRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class UpdateAdService
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function handle($id, $request): JsonResponse
    {
        $ad = $this->adRepository->getById($id);
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            
            if ($ad->photo && file_exists(public_path($ad->photo))) {
                unlink(public_path($ad->photo));
            }

            
            $data['photo'] = $this->uploadPhoto($request->file('photo'));
        }

        $ad = $this->adRepository->update($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Ad updated successfully',
            'data' => $ad
        ]);
    }

    protected function uploadPhoto($photo): string
    {
        return 'upload/' . $photo->store('ads', 'public_upload');
    }
}
