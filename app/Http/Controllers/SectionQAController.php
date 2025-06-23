<?php

namespace App\Http\Controllers;

use App\Services\ForumServices\CreateQuestionAnswerService;
use App\Services\ForumServices\UpdateQuestionAnswerService;
use App\Services\ForumServices\DeleteQuestionAnswerService;
use App\Services\ForumServices\DisplayForumService;
use App\Services\ForumServices\LikeAnswerQuestionService;

use App\Http\Requests\ForumRequest\CreateQuestionRequest;
use App\Http\Requests\ForumRequest\UpdateQuestionRequest;
use App\Http\Requests\ForumRequest\CreateAnswerRequest;
use App\Http\Requests\ForumRequest\UpdateAnswerRequest;

use Illuminate\Http\JsonResponse;

class SectionQAController extends Controller
{
    protected $createService;
    protected $updateService;
    protected $deleteService;
    protected $displayService;
    protected $likeService;

    public function __construct(
        CreateQuestionAnswerService $createService,
        UpdateQuestionAnswerService $updateService,
        DeleteQuestionAnswerService $deleteService,
        DisplayForumService $displayService,
        LikeAnswerQuestionService $likeService
    ) {
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
        $this->displayService = $displayService;
        $this->likeService = $likeService;
    }

    // ===== Questions =====

    public function getSectionQuestions($sectionId): JsonResponse
    {
        $questions = $this->displayService->getSectionQuestions($sectionId);
        return response()->json($questions);
    }

    public function createQuestion(CreateQuestionRequest $request): JsonResponse
    {
        $question = $this->createService->createQuestion($request->validated());
        return response()->json($question, 201);
    }

    public function updateQuestion(UpdateQuestionRequest $request, $questionId): JsonResponse
    {
        $question = $this->updateService->updateQuestion($questionId, $request->validated());

        if (!$question) {
            return response()->json(['message' => 'Question not found or unauthorized'], 404);
        }

        return response()->json($question);
    }

    public function deleteQuestion($questionId): JsonResponse
    {
        $deleted = $this->deleteService->deleteQuestion($questionId);

        if (!$deleted) {
            return response()->json(['message' => 'Question not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Question deleted successfully'], 200);
    }

    // ===== Answers =====

    public function getQuestionAnswers($questionId): JsonResponse
    {
        $answers = $this->displayService->getQuestionAnswers($questionId);
        return response()->json($answers);
    }

    public function createAnswer(CreateAnswerRequest $request): JsonResponse
    {
        $answer = $this->createService->createAnswer($request->validated());
        return response()->json($answer, 201);
    }

    public function updateAnswer(UpdateAnswerRequest $request, $answerId): JsonResponse
    {
        $answer = $this->updateService->updateAnswer($answerId, $request->validated());

        if (!$answer) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json($answer);
    }

    public function deleteAnswer($answerId): JsonResponse
    {
        $deleted = $this->deleteService->deleteAnswer($answerId);

        if (!$deleted) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Answer deleted successfully'], 200);
    }

    // ===== Likes =====

    public function likeQuestion($questionId): JsonResponse
    {
        $like = $this->likeService->likeQuestion($questionId);

        if (!$like) {
            return response()->json(['message' => 'Question not found or already liked'], 404);
        }

        return response()->json($like);
    }

    public function unlikeQuestion($questionId): JsonResponse
    {
        $removed = $this->likeService->unlikeQuestion($questionId);

        if (!$removed) {
            return response()->json(['message' => 'Question not found or not liked'], 404);
        }

        return response()->json(['message' => 'Dislike removed successfully.'], 200);
    }

    public function likeAnswer($answerId): JsonResponse
    {
        $like = $this->likeService->likeAnswer($answerId);

        if (!$like) {
            return response()->json(['message' => 'Answer not found or already liked'], 404);
        }

        return response()->json($like);
    }

    public function unlikeAnswer($answerId): JsonResponse
    {
        $removed = $this->likeService->unlikeAnswer($answerId);

        if (!$removed) {
            return response()->json(['message' => 'Answer not found or not liked'], 404);
        }

        return response()->json(['message' => 'Answer unliked successfully'], 200);
    }

    // ===== Accept / Unaccept Answer =====

    public function acceptAnswer($answerId): JsonResponse
    {
        $accepted = $this->likeService->acceptAnswer($answerId);

        if (!$accepted) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Answer accepted successfully']);
    }

    public function unacceptAnswer($answerId): JsonResponse
    {
        $unaccepted = $this->likeService->unacceptAnswer($answerId);

        if (!$unaccepted) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Answer unaccepted successfully']);
    }
}
