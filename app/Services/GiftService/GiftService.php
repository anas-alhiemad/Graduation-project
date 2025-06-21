<?php

namespace App\Services\GiftService;

use App\Repositories\GiftRepository;
use Illuminate\Support\Facades\Storage;

class GiftService
{
    protected $giftRepository;

    public function __construct(GiftRepository $giftRepository)
    {
        $this->giftRepository = $giftRepository;
    }

    public function getAll()
    {
        $gifts = $this->giftRepository->all();
        return response()->json([
            "message" => "All gifts in the System.",
            "gifts" => $gifts->items(),
            "pagination" => [
                "total" => $gifts->total(),
                "per_page" => $gifts->perPage(),
                "current_page" => $gifts->currentPage(),
                "last_page" => $gifts->lastPage(),
                "from" => $gifts->firstItem(),
                "to" => $gifts->lastItem()
            ]
        ]);
    }

    public function getById($id)
    {
        $gift = $this->giftRepository->find($id);
        return response()->json([
            "message" => "The gift details.",
            "gift" => $gift
        ]);
    }

    public function create($request)
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

    public function update($id, $request)
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

    public function delete($id)
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

    public function getStudentGifts($studentId)
    {
        $gifts = $this->giftRepository->getStudentGifts($studentId);
        return response()->json([
            "message" => "Student gifts retrieved successfully",
            "gifts" => $gifts->items(),
            "pagination" => [
                "total" => $gifts->total(),
                "per_page" => $gifts->perPage(),
                "current_page" => $gifts->currentPage(),
                "last_page" => $gifts->lastPage(),
                "from" => $gifts->firstItem(),
                "to" => $gifts->lastItem()
            ]
        ]);
    }

    public function getSecretaryGifts($secretaryId)
    {
        $gifts = $this->giftRepository->getSecretaryGifts($secretaryId);
        return response()->json([
            "message" => "Secretary gifts retrieved successfully",
            "gifts" => $gifts->items(),
            "pagination" => [
                "total" => $gifts->total(),
                "per_page" => $gifts->perPage(),
                "current_page" => $gifts->currentPage(),
                "last_page" => $gifts->lastPage(),
                "from" => $gifts->firstItem(),
                "to" => $gifts->lastItem()
            ]
        ]);
    }
}
