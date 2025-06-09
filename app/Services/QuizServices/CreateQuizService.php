<?php
namespace App\Services\QuizServices;

use App\Repositories\QuizRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\QuizQuestionRepository;
use App\Repositories\CourseSectionRepository;
use App\Repositories\QuizQuestionOptionRepository;

class CreateQuizService 
{
    protected $quizRepository;
    protected $quizQuestionRepository;
    protected $quizQuestionOptionRepository;
    protected $courseSectionRepository;

    public function __construct(QuizRepository $quizRepository,QuizQuestionRepository $quizQuestionRepository,QuizQuestionOptionRepository $quizQuestionOptionRepository,CourseSectionRepository $courseSectionRepository)
    {
        $this->quizRepository = $quizRepository;
        $this->quizQuestionRepository = $quizQuestionRepository;
        $this->quizQuestionOptionRepository = $quizQuestionOptionRepository;
        $this->courseSectionRepository = $courseSectionRepository;

    }

    public function store($request) 
    {
        $trainer = Auth::guard('trainer')->user();
        $section = $this->courseSectionRepository->getById($request->course_section_id);

        if (Gate::forUser($trainer)->denies('create', $section)) {
            abort(403, 'Unauthorized to create quiz in this section.');
        }

        $quizData = [
            'title' => $request->title,
            'course_section_id' => $request->course_section_id
        ];

        $quiz = $this->quizRepository->create($quizData);


        foreach ($request->questions as $questionData) {
            $question = $this->quizQuestionRepository->create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
            ]);


        foreach ($questionData['options'] as $optionData) {
            $this->quizQuestionOptionRepository->create([
                'quiz_question_id' => $question->id,
                'option' => $optionData['option'],
                'is_correct' => $optionData['is_correct'],
            ]);
        }
    }    
    
        return response()->json([
        'message' => 'Quiz created successfully.',
        'quiz' => $quiz->load('quizQuestion.quizQuestionOption')], 200);
    }





}