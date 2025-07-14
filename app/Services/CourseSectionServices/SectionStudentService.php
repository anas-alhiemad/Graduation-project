<?php
namespace App\Services\CourseSectionServices;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;
use App\Repositories\StudentRepository;
use App\Repositories\WeekDayRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionStudentRepository;
use App\Repositories\SectionTrainerRepository;

class SectionStudentService 
{
    protected $sectionStudentRepository;
    protected $courseSectionRepository;
    protected $sectionTrainerRepository;
    protected $weekDayRepository;
    protected $studentRepository;
    public function __construct(
        SectionStudentRepository $sectionStudentRepository,
        CourseSectionRepository $courseSectionRepository,
        SectionTrainerRepository $sectionTrainerRepository,
        WeekDayRepository $weekDayRepository,
        StudentRepository $studentRepository
    ) {
        $this->sectionStudentRepository = $sectionStudentRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sectionTrainerRepository = $sectionTrainerRepository;
        $this->weekDayRepository = $weekDayRepository;
        $this->studentRepository = $studentRepository;
    }

    public function registerStudentToSection($request)
    {
        // $sectionStudent =$request->all() ;
        $section = $this->courseSectionRepository->getById($request->course_section_id);

        if ($section->reservedSeats >= $section->seatsOfNumber) {
            return response()->json(['message' => 'No available seats'], 400);
        }

        $exists = $this->sectionStudentRepository->exists([
            'course_section_id' => $request->course_section_id,
            'student_id' => $request->student_id
        ]);
    
        if ($exists) {
            return response()->json(['message' => 'Student already registered in this section'], 409);
        }

        $section->students()->attach($request->student_id, ['is_confirmed' => true]);        
        $this->courseSectionRepository->incrementSeat($request->course_section_id);
        return response()->json(['message' => 'Student registered successfully']);
    }



    public function getStudentsInSection($section_id)
    {
        $user = Auth::user();
        $section = $this->courseSectionRepository->getById($section_id);

        // Only check trainer authorization if the user is a trainer
        if ($user instanceof Trainer) {
            // Verify trainer is assigned to this section
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view students in this section');
            }
        }

        $studentsInSection = $this->courseSectionRepository->studentsInSection($section_id);
        return response()->json([
            'message' => "Student in section",
            'students' => $studentsInSection
        ]);
    }
    
    public function getStudentsInSectionConfirmed($section_id)
    {
        $studentsInSection = $this->courseSectionRepository->studentsInSectionConfirmed($section_id);
        return response()->json([
            'message' => "Student in section are Confirmed",
            'students' => $studentsInSection
        ]);
    }

    public function deleteStudentFromSection($request)
    {
       $section = $this->courseSectionRepository->getById($request->course_section_id); 
       $section->students()->detach($request->student_id);
       $this->courseSectionRepository->decrementSeat($request->course_section_id);
        return response()->json(['message' => 'Student removed from section']);
    }

    public function getStudentCourses($studentId)
    {
        $courses = $this->courseSectionRepository->getStudentCourses($studentId);
        return response()->json([
            'message' => "Courses that student is enrolled in",
            'courses' => $courses
        ]);
    }

    public function getStudentCoursesFinshed()
    {
        $studentId = Auth::guard('student')->id();
        $courses = $this->courseSectionRepository->getStudentCoursesIsFinished($studentId);
        return response()->json([
            'message' => "Courses that student is enrolled in",
            'courses' => $courses
        ]);
    }

    public function searchStudentsInTrainerSections(string $searchTerm): Collection
    {
        $trainer = Auth::user();
        
        if (!($trainer instanceof Trainer)) {
            throw new \Exception('Only trainers can search for students in their sections');
        }

        
        $trainerSections = $trainer->sections()->pluck('course_sections.id');

        
        return Student::whereHas('sections', function ($query) use ($trainerSections) {
                $query->whereIn('course_sections.id', $trainerSections);
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            })
            ->with(['sections' => function ($query) use ($trainerSections) {
                $query->whereIn('course_sections.id', $trainerSections)
                    ->select('course_sections.id', 'name', 'seatsOfNumber', 'reservedSeats');
            }])
            ->select('id', 'name', 'email', 'photo')
            ->get();
    }

    public function searchStudentsInSpecificSection(CourseSection $section, string $searchTerm): Collection
    {
        $trainer = Auth::user();
        
        if (!($trainer instanceof Trainer)) {
            throw new \Exception('Only trainers can search for students in their sections');
        }

        
        if (!$section->trainers()->where('trainers.id', $trainer->id)->exists()) {
            throw new \Exception('You are not authorized to search in this section');
        }

    
        return Student::whereHas('sections', function ($query) use ($section) {
                $query->where('course_sections.id', $section->id);
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            })
            ->with(['sections' => function ($query) use ($section) {
                $query->where('course_sections.id', $section->id)
                    ->select('course_sections.id', 'name', 'seatsOfNumber', 'reservedSeats');
            }])
            ->select('id', 'name', 'email', 'photo')
            ->get();
    }

    public function getStudentDetailsInSection(CourseSection $section, Student $student): array
    {
        $trainer = Auth::user();
        
        if (!($trainer instanceof Trainer)) {
            throw new \Exception('Only trainers can view student details');
        }

        
        if (!$section->trainers()->where('trainers.id', $trainer->id)->exists()) {
            throw new \Exception('You are not authorized to view details in this section');
        }

        
        if (!$section->students()->where('students.id', $student->id)->exists()) {
            throw new \Exception('Student is not enrolled in this section');
        }

        
        $attendance = $student->attendance()
            ->where('section_id', $section->id)
            ->get();

        $grades = $student->examGrades()
            ->where('section_id', $section->id)
            ->get();

        return [
            'student' => $student->only(['id', 'name', 'email', 'photo']),
            'attendance' => $attendance,
            'grades' => $grades,
            'section' => $section->only(['id', 'name', 'seatsOfNumber', 'reservedSeats'])
        ];
    }

        
    public function getStudentSchedule($name_day)
    {
        $dayNameToId = $this->weekDayRepository->dayNameToId();

        if (!isset($dayNameToId[$name_day])) {
            return response()->json(['message' => 'Invalid day name'], 400);
        }

        $dayId = $dayNameToId[$name_day];
        $student = auth()->user();

        $schedule = $this->studentRepository->getSchedule($student);

        $events = [];

        foreach ($schedule as $section) {
            foreach ($section->weekDays as $day) {
                if ($day->id != $dayId) {
                    continue;
                }

                $events[] = [
                    'course'     => $section->course,
                    'section' => collect($section)->only(['id', 'name', 'seatsOfNumber', 'reservedSeats', 'state',
                                                        'startDate', 'endDate', 'courseId', 'created_at', 'updated_at']),
                    'day'        => $day,
                    'start_time' => $day->pivot->start_time,
                    'end_time'   => $day->pivot->end_time,
                ];
            }
        }

        return response()->json([
            'message' => "Schedule your tasks today",'Events' => $events ],200);       
    }


}