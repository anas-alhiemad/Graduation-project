<?php

namespace App\Services\GiftService;

use App\Repositories\GiftRepository;

class DisplayGiftService
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
