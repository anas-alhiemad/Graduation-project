<?php

namespace App\Services\GiftService;

use App\Repositories\GiftRepository;

class UpdateGiftService
{
    protected $giftRepository;

    public function __construct(GiftRepository $giftRepository)
    {
        $this->giftRepository = $giftRepository;
    }

    public function handle($id, $request)
    {
        $gift = $this->giftRepository->find($id);

        if ($request->hasFile('photo')) {
            if ($gift->photo && file_exists(public_path($gift->photo))) {
                unlink(public_path($gift->photo));
            }
            $data = $request->validated();
            $data['photo'] = 'upload/' . $request->file('photo')->store('giftPhotos', 'public_upload');
        } else {
            $data = $request->validated();
        }

        $updatedGift = $this->giftRepository->update($id, $data);

        return response()->json([
            "message" => "Gift has been updated successfully",
            "gift" => $updatedGift
        ], 200);
    }
}
