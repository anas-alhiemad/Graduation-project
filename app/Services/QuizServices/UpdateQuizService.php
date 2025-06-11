<?php
namespace App\Services\QuizServices;

use App\Repositories\QuizRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\QuizQuestionRepository;
use App\Repositories\CourseSectionRepository;
use App\Repositories\QuizQuestionOptionRepository;

class UpdateQuizService 
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


        public function updateTitle($quiz_id, $request)
        {
            $trainer = Auth::guard('trainer')->user();
            $quiz = $this->quizRepository->getById($quiz_id);

            if (!$quiz) {
                return response()->json(['message' => 'Quiz not found'], 404);
            }

            $section = $this->courseSectionRepository->getById($quiz->course_section_id);

            if (Gate::forUser($trainer)->denies('update', $section)) {
                abort(403, 'Unauthorized to update quiz title in this section.');
            }

            $this->quizRepository->update($quiz_id, $request->validated());

            return response()->json([
                'message' => 'Quiz title updated successfully.',
            ], 200);
        }

        public function updateQuestion($question_id, $data)
        {
            $trainer = Auth::guard('trainer')->user();
            $question = $this->quizQuestionRepository->getById($question_id);

            if (!$question) {
                return response()->json(['message' => 'Question not found'], 404);
            }

            $quiz = $this->quizRepository->getById($question->quiz_id);
            $section = $this->courseSectionRepository->getById($quiz->course_section_id);

            if (Gate::forUser($trainer)->denies('update', $section)) {
                abort(403, 'Unauthorized to update question in this section.');
            }


            $this->quizQuestionRepository->update($question_id, [
                'question' => $data['question']
            ]);

            $sentOptionIds = [];

            foreach ($data['options'] as $optionData) {
                if (isset($optionData['id'])) {

                    $option = $this->quizQuestionOptionRepository->getById($optionData['id']);
                    if ($option && $option->quiz_question_id == $question_id) {
                        $this->quizQuestionOptionRepository->update($option->id, [
                            'option' => $optionData['option'],
                            'is_correct' => $optionData['is_correct'],
                        ]);
                        $sentOptionIds[] = $option->id;
                    }
                } else {

                    $newOption = $this->quizQuestionOptionRepository->create([
                        'quiz_question_id' => $question_id,
                        'option' => $optionData['option'],
                        'is_correct' => $optionData['is_correct'],
                    ]);
                    $sentOptionIds[] = $newOption->id;
                }
            }


            $question->quizQuestionOption()
                ->whereNotIn('id', $sentOptionIds)
                ->each(function ($option) {
                    $option->delete();
                });


            return response()->json([
                'message' => 'Question and options updated successfully.'
            ]);
        }

}