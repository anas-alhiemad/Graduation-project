<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiftRequest\CreateGiftRequest;
use App\Http\Requests\GiftRequest\UpdateGiftRequest;
use App\Services\GiftService\CreateGiftService;
use App\Services\GiftService\DeleteGiftService;
use App\Services\GiftService\DisplayGiftService;
use App\Services\GiftService\UpdateGiftService;

class GiftController extends Controller
{
    protected $createGiftService;
    protected $deleteGiftService;
    protected $displayGiftService;
    protected $updateGiftService;

    public function __construct(
        CreateGiftService $createGiftService,
        DeleteGiftService $deleteGiftService,
        DisplayGiftService $displayGiftService,
        UpdateGiftService $updateGiftService
    ) {
        $this->createGiftService = $createGiftService;
        $this->deleteGiftService = $deleteGiftService;
        $this->displayGiftService = $displayGiftService;
        $this->updateGiftService = $updateGiftService;
    }

    public function index()
    {
        return $this->displayGiftService->getAll();
    }

    public function show($id)
    {
        return $this->displayGiftService->getById($id);
    }

    public function store(CreateGiftRequest $request)
    {
        return $this->createGiftService->handle($request);
    }

    public function update(UpdateGiftRequest $request, $id)
    {
        return $this->updateGiftService->handle($id, $request);
    }

    public function destroy($id)
    {
        return $this->deleteGiftService->handle($id);
    }

    public function studentGifts()
    {
        $studentId = auth()->id();
        return $this->displayGiftService->getStudentGifts($studentId);
    }

    public function secretaryGifts()
    {
        $secretaryId = auth()->id();
        return $this->displayGiftService->getSecretaryGifts($secretaryId);
    }
}
