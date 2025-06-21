<?php

namespace App\Http\Controllers;

use App\Services\AdService\CreateAdService;
use App\Services\AdService\UpdateAdService;
use App\Services\AdService\DeleteAdService;
use App\Services\AdService\DisplayAdService;
use App\Http\Requests\AdRequest\CreateAdRequest;
use App\Http\Requests\AdRequest\UpdateAdRequest;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    protected $createService;
    protected $updateService;
    protected $deleteService;
    protected $displayService;

    public function __construct(
        CreateAdService $createService,
        UpdateAdService $updateService,
        DeleteAdService $deleteService,
        DisplayAdService $displayService
    ) {
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
        $this->displayService = $displayService;
    }

    public function index(): JsonResponse
    {
        return $this->displayService->getAllAds();
    }

    public function show($id): JsonResponse
    {
        return $this->displayService->getAdById($id);
    }

    public function store(CreateAdRequest $request): JsonResponse
    {
        return $this->createService->handle($request);
    }

    public function update(UpdateAdRequest $request, $id): JsonResponse
    {
        return $this->updateService->handle($id, $request);
    }

    public function destroy($id): JsonResponse
    {
        return $this->deleteService->handle($id);
    }

    public function active(): JsonResponse
    {
        return $this->displayService->getActiveAds();
    }
}
