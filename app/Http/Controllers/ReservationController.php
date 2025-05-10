<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest\ReservationRequest;
use App\Services\CourseSectionServices\SectionReservationService;

class ReservationController extends Controller
{
     protected $sectionReservationService;

    public function __construct(SectionReservationService $sectionReservationService)
    {

        $this->sectionReservationService = $sectionReservationService;
    }

    public function ShowAllReservation($section_id) 
    {
        return $this->sectionReservationService->showReservations($section_id);
    }

    public function ShowReservation($reservation_id) 
    {
        return $this->sectionReservationService->showReservation($reservation_id);
    }

    public function CreateReservation($section_id) 
    {
        return $this->sectionReservationService->createReservation($section_id);
    }

    public function CancelReservation($reservation_id) 
    {
        return $this->sectionReservationService->CancelReservation($reservation_id);
    }

    public function ConfirmReservation($reservation_id) 
    {
        return $this->sectionReservationService->ConfirmReservation($reservation_id);
    }
}
