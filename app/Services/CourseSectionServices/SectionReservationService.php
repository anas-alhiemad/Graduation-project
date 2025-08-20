<?php
namespace App\Services\CourseSectionServices;

use Illuminate\Support\Facades\DB;
use App\Repositories\SecretaryRepository;
use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionStudentRepository;
use App\Services\NotificationServices\SendNotificationsService;

class SectionReservationService 
{
    protected $sectionStudentRepository;
    protected $courseSectionRepository;
    protected $sendNotificationsService;
    protected $secretaryRepository;
    public function __construct(SectionStudentRepository  $sectionStudentRepository,CourseSectionRepository $courseSectionRepository,SendNotificationsService $sendNotificationsService,SecretaryRepository  $secretaryRepository)
    {
        $this->sectionStudentRepository = $sectionStudentRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sendNotificationsService = $sendNotificationsService;
        $this->secretaryRepository = $secretaryRepository;
    }

    public function createReservation($course_section_id) 
    {
        return DB::transaction(function () use ($course_section_id) {
        $section = $this->courseSectionRepository->lockForUpdate($course_section_id);

        if ($section->reservedSeats >= $section->seatsOfNumber) {
            return response()->json(['message' => 'No available seats'], 400);
        }

        $studentId = auth()->guard('student')->id();
        $courseId = $section->courseId;
        $alreadyBooked = $this->sectionStudentRepository
            ->exists($studentId, $courseId);
        if ($alreadyBooked) {
            return response()->json(['message' => 'You have already booked here. You cannot book twice.'], 409);
        }

        $section->students()->attach(auth()->guard('student')->id(), ['is_confirmed' => false]);        
        $this->courseSectionRepository->incrementSeat($course_section_id);
        
        $secretaries = $this->secretaryRepository->secretariesHaveFcmToken();
       
        foreach ($secretaries as $secretary) 
        {
                if ($secretary->fcm_token) {
                    $title = "New Booking";
                    $body  = "A new booking has been made by: " . auth()->guard('student')->user()->name;

                    $this->sendNotificationsService->sendByFcm($secretary->fcm_token, [
                        'title' => $title,
                        'body' => $body,
                    ]);

                    $this->sendNotificationsService->storeNotification($secretary, $title, $body);
                }
        }
       
        return response()->json(['message' => 'Your reservation has been successfully completed. Please pay within 48 hours.']);});
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