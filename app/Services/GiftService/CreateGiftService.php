<?php

namespace App\Services\GiftService;

use App\Repositories\GiftRepository;

class CreateGiftService
{
    protected $giftRepository;

    public function __construct(GiftRepository $giftRepository)
    {
        $this->giftRepository = $giftRepository;
    }

    public function handle($request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = 'upload/' . $request->file('photo')->store('giftPhotos', 'public_upload');
        }

        $gift = $this->giftRepository->create($data);

        return response()->json([
            "message" => "Gift has been created successfully",
            "gift" => $gift
        ], 200);
    }
}
