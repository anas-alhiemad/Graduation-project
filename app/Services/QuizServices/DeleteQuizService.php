<?php
namespace App\Services\QuizServices;

use App\Repositories\QuizRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\QuizQuestionRepository;
use App\Repositories\CourseSectionRepository;

class DeleteQuizService 
{
    protected $quizRepository;
    protected $quizQuestionRepository;
    protected $courseSectionRepository;

    public function __construct(QuizRepository $quizRepository,QuizQuestionRepository $quizQuestionRepository,CourseSectionRepository $courseSectionRepository)
    {
        $this->quizRepository = $quizRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->quizQuestionRepository = $quizQuestionRepository;
    }

    public function delete($quiz_id) 
    {
        $quiz = $this->quizRepository->getById($quiz_id);
        $trainer = Auth::guard('trainer')->user();
        $section = $this->courseSectionRepository->getById($quiz->course_section_id);

        if (Gate::forUser($trainer)->denies('delete', $section)) {
            abort(403, 'Unauthorized to create quiz in this section.');
        }
        $this->quizRepository->delete($quiz_id);
        return response()->json(['message' => 'Quiz deleted successfully.'], 200);
    }


    public function deleteQuestion($question_id)
    {
        $trainer = Auth::guard('trainer')->user();
        $question = $this->quizQuestionRepository->getById($question_id);

        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }

        $quiz = $this->quizRepository->getById($question->quiz_id);
        $section = $this->courseSectionRepository->getById($quiz->course_section_id);

        if (Gate::forUser($trainer)->denies('delete', $section)) {
            abort(403, 'Unauthorized to delete question from this quiz.');
        }

        $this->quizQuestionRepository->delete($question_id);

        return response()->json(['message' => 'Question deleted successfully.'], 200);
    }

}