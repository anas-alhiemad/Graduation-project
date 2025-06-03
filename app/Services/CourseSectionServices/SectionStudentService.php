<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionStudentRepository;
use App\Repositories\SectionTrainerRepository;
use App\Models\Trainer;
use App\Models\Student;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class SectionStudentService 
{
    protected $sectionStudentRepository;
    protected $courseSectionRepository;
    protected $sectionTrainerRepository;
    
    public function __construct(
        SectionStudentRepository $sectionStudentRepository,
        CourseSectionRepository $courseSectionRepository,
        SectionTrainerRepository $sectionTrainerRepository
    ) {
        $this->sectionStudentRepository = $sectionStudentRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sectionTrainerRepository = $sectionTrainerRepository;
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
    //    $this->sectionStudentRepository->removeStudentFromSection($request->course_section_id,$request->student_id) ;
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

    public function searchStudentsInTrainerSections(string $searchTerm): Collection
    {
        $trainer = Auth::user();
        
        if (!($trainer instanceof Trainer)) {
            throw new \Exception('Only trainers can search for students in their sections');
        }

        // Get all sections where the trainer is assigned
        $trainerSections = $trainer->sections()->pluck('course_sections.id');

        // Search for students in those sections
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

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $trainer->id)->exists()) {
            throw new \Exception('You are not authorized to search in this section');
        }

        // Search for students in the specific section
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

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $trainer->id)->exists()) {
            throw new \Exception('You are not authorized to view details in this section');
        }

        // Verify student is in this section
        if (!$section->students()->where('students.id', $student->id)->exists()) {
            throw new \Exception('Student is not enrolled in this section');
        }

        // Get student's attendance records
        $attendance = $student->attendance()
            ->where('section_id', $section->id)
            ->get();

        // Get student's exam grades
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
}