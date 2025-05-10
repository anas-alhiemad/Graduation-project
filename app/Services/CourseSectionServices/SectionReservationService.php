<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionStudentRepository;

class SectionReservationService 
{
    protected $sectionStudentRepository;
    protected $courseSectionRepository;
    
    public function __construct(SectionStudentRepository  $sectionStudentRepository,CourseSectionRepository $courseSectionRepository)
    {
        $this->sectionStudentRepository = $sectionStudentRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function createReservation($course_section_id) 
    {
            $section = $this->courseSectionRepository->getById($course_section_id);

        if ($section->reservedSeats >= $section->seatsOfNumber) {
            return response()->json(['message' => 'No available seats'], 400);
        }

        $exists = $this->sectionStudentRepository->exists([
            'course_section_id' => $course_section_id,
            'student_id' => auth()->guard('student')->id()
        ]);
    
        if ($exists) {
            return response()->json(['message' => 'You have already booked here. You cannot book twice.'], 409);
        }

        $section->students()->attach(auth()->guard('student')->id() , ['is_confirmed' => false]);        
        $this->courseSectionRepository->incrementSeat($course_section_id);
        return response()->json(['message' => 'Your reservation has been successfully completed. Please pay within 48 hours.']);
    }


    public function confirmReservation($reservationId) 
    {
        $is_confirmed['is_confirmed'] = true;
        $this->sectionStudentRepository->update($reservationId,$is_confirmed);
        return response()->json([
            'message' => 'Reservation has been confirmed successfully.',
        ], 200);
    }

    public function CancelReservation($reservationId) 
    {
       $record = $this->sectionStudentRepository->getById($reservationId);
       if ($record->student_id != auth()->guard('student')->id()) {
            return response()->json(['message'=>'You are not authorized',401]);
       }
       $this->sectionStudentRepository->delete($reservationId);
       $this->courseSectionRepository->decrementSeat($record->course_section_id);

        return response()->json([
            'message' => 'The Reservation has been Cancel successfully',
        ], 200);

    }


    public function  showReservations($course_section_id) 
    {
        $reservations = $this->courseSectionRepository->showAllReservation($course_section_id);
        return response()->json(['message'=>'All Reservation in Section',"Reservations"=>$reservations]);
    }

    public function showReservation($reservation_id)
    {
        $reservation = $this->sectionStudentRepository->showReservation($reservation_id);
        return response()->json(['message'=>'this Reservation in Section',"Reservations"=>$reservation]);
    }
}