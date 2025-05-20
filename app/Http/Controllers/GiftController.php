<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GiftService\GiftService;
use App\Repositories\StudentRepository;
use App\Repositories\SecretaryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // Admin CRUD Operations
    public function index()
    {
        return $this->giftService->getAll();
    }

    public function store(Request $request)
    {
        return $this->giftService->create($request);
    }

    public function show($id)
    {
        return $this->giftService->getById($id);
    }

    public function update(Request $request, $id)
    {
        return $this->giftService->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->giftService->delete($id);
    }

    // Student specific endpoint
    public function studentGifts()
    {
        try {
            $student = $this->studentRepository->getByEmail(Auth::user()->email);
            return $this->giftService->getStudentGifts($student->id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Student not found'
            ], 404);
        }
    }

    // Secretary specific endpoint
    public function secretaryGifts()
    {
        try {
            $secretary = $this->secretaryRepository->getByEmail(Auth::user()->email);
            return $this->giftService->getSecretaryGifts($secretary->id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Secretary not found'
            ], 404);
        }
    }
} 