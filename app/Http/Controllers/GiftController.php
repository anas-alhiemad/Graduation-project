<?php

namespace App\Http\Controllers;

use App\Services\GiftService\GiftService;
use App\Repositories\StudentRepository;
use App\Repositories\SecretaryRepository;
use App\Http\Requests\GiftRequest\CreateGiftRequest;
use App\Http\Requests\GiftRequest\UpdateGiftRequest;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    protected $giftService;
    protected $studentRepository;
    protected $secretaryRepository;

    public function __construct(
        GiftService $giftService,
        StudentRepository $studentRepository,
        SecretaryRepository $secretaryRepository
    ) {
        $this->giftService = $giftService;
        $this->studentRepository = $studentRepository;
        $this->secretaryRepository = $secretaryRepository;
    }

    public function index()
    {
        return $this->giftService->getAll();
    }

    public function store(CreateGiftRequest $request)
    {
        return $this->giftService->create($request);
    }

    public function show($id)
    {
        return $this->giftService->getById($id);
    }

    public function update(UpdateGiftRequest $request, $id)
    {
        return $this->giftService->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->giftService->delete($id);
    }

    public function studentGifts()
    {
        try {
            $student = $this->studentRepository->getByEmail(Auth::user()->email);
            return $this->giftService->getStudentGifts($student->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    public function secretaryGifts()
    {
        try {
            $secretary = $this->secretaryRepository->getByEmail(Auth::user()->email);
            return $this->giftService->getSecretaryGifts($secretary->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Secretary not found'], 404);
        }
    }
}
