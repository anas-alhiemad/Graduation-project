<?php
namespace App\Services\QuizServices;

use App\Repositories\QuizRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\QuizQuestionRepository;
use App\Repositories\CourseSectionRepository;
use App\Repositories\QuizQuestionOptionRepository;

class DisplayQuizService 
{
    protected $quizRepository;
    protected $courseSectionRepository;
    protected $quizQuestionOptionRepository;
    public function __construct(QuizRepository $quizRepository,CourseSectionRepository $courseSectionRepository,QuizQuestionOptionRepository $quizQuestionOptionRepository)
    {
        $this->quizRepository = $quizRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->quizQuestionOptionRepository = $quizQuestionOptionRepository;

    }


    public function showById($quiz_id)
    {
        $quiz = $this->quizRepository->getById($quiz_id);
        $user = Auth::guard('trainer')->user() ?? Auth::guard('student')->user();
        $section = $this->courseSectionRepository->getById($quiz->course_section_id);

        if (Gate::forUser($user)->denies('view', $section)) {
            abort(403, 'Unauthorized to create quiz in this section.');
        }

        
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found.'], 404);
        }

        return response()->json([
            'message' => 'This Quiz is to test your understanding of the previous stages.',
            'quiz' => $quiz->load('quizQuestion.quizQuestionOption')
        ], 200);
    }


    public function listByIdSection($course_section_id) 
    {
        $user = Auth::guard('trainer')->user() ?? Auth::guard('student')->user();
        $section = $this->courseSectionRepository->getById($course_section_id);

        if (Gate::forUser($user)->denies('view', $section)) {
            abort(403, 'Unauthorized to create quiz in this section.');
        }


        $quizzes = $this->quizRepository->getAllBySectionId($course_section_id);
        return response()->json([
            "message" => "All Quizzes in the section.",
            "Quizzes" => $quizzes]);

    }


    public function answerQuestion($option_id)
    {
        $option = $this->quizQuestionOptionRepository->getById($option_id);
        $option->increment('selected_count');
        $questionOptions = $this->quizQuestionOptionRepository->getAllQuestionOptions($option->quiz_question_id);
        $totalVotes = $questionOptions->sum('selected_count');
        $optionsWithPercentages = $questionOptions->map(function ($opt) use ($totalVotes) {
            $percentage = $totalVotes > 0 ? round(($opt->selected_count / $totalVotes) * 100, 2) : 0;

            return [
                'option_id' => $opt->id,
                'option_text' => $opt->option,
                'is_correct' => $opt->is_correct,
                'selected_count' => $opt->selected_count,
                'percentage' => $percentage,
            ];
        });


        return response()->json([
            'is_correct' => $option->is_correct,
            'options' => $optionsWithPercentages,
        ],200);
    }
}
    