<?php
namespace App\Services\AdService;

use App\Repositories\AdRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class DeleteAdService
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function handle($id): JsonResponse
    {
        $ad = $this->adRepository->getById($id);

        if ($ad->photo) {
            Storage::disk('public_upload')->delete($ad->photo);
        }

        $this->adRepository->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Ad deleted successfully',
        ]);
    }
}
