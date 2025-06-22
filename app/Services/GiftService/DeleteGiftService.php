<?php

namespace App\Services\GiftService;

use App\Repositories\GiftRepository;
use Illuminate\Support\Facades\Storage;

class DeleteGiftService
{
    protected $giftRepository;

    public function __construct(GiftRepository $giftRepository)
    {
        $this->giftRepository = $giftRepository;
    }

    public function handle($id)
    {
        $gift = $this->giftRepository->find($id);

        if ($gift->photo) {
            Storage::disk('public_upload')->delete(str_replace('upload/', '', $gift->photo));
        }

        $this->giftRepository->delete($id);

        return response()->json([
            'message' => 'Gift has been deleted successfully'
        ], 200);
    }
}
