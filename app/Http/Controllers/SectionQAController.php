<?php

namespace App\Http\Controllers;

use App\Services\CourseSectionServices\SectionQAService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionQAController extends Controller
{
    protected $sectionQAService;

    public function __construct(SectionQAService $sectionQAService)
    {
        $this->sectionQAService = $sectionQAService;
    }

    public function getSectionQuestions($sectionId): JsonResponse
    {
        $questions = $this->sectionQAService->getSectionQuestions($sectionId);
        return response()->json($questions);
    }

    public function createQuestion(Request $request): JsonResponse
    {
        $request->validate([
           
            'content' => 'required|string',
            'course_section_id' => 'required|exists:course_sections,id',
        ]);

        $question = $this->sectionQAService->createQuestion($request->all());
        return response()->json($question, 201);
    }

    public function updateQuestion(Request $request, $questionId): JsonResponse
    {
        $request->validate([
           
            'content' => 'required|string'
        ]);

        $question = $this->sectionQAService->updateQuestion($questionId, $request->all());
        
        if (!$question) {
            return response()->json(['message' => 'Question not found or unauthorized'], 404);
        }

        return response()->json($question);
    }

   public function deleteQuestion($questionId): JsonResponse
{
    $deleted = $this->sectionQAService->deleteQuestion($questionId);
    
    if (!$deleted) {
        return response()->json(['message' => 'Question not found or unauthorized'], 404);
    }

    return response()->json(['message' => ' Question Deleted successfully'], 200);
}


    public function getQuestionAnswers($questionId): JsonResponse
    {
        $answers = $this->sectionQAService->getQuestionAnswers($questionId);
        return response()->json($answers);
    }

    public function createAnswer(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'question_id' => 'required|exists:questions,id',
            'course_section_id' => 'required|exists:course_sections,id',
        ]);

        $answer = $this->sectionQAService->createAnswer($request->all());
        return response()->json($answer, 201);
    }

    public function updateAnswer(Request $request, $answerId): JsonResponse
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $answer = $this->sectionQAService->updateAnswer($answerId, $request->all());
        
        if (!$answer) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json($answer);
    }
public function deleteAnswer($answerId): JsonResponse
{
    $deleted = $this->sectionQAService->deleteAnswer($answerId);
    
    if (!$deleted) {
        return response()->json(['message' => 'Answer not found or unauthorized'], 404);
    }

    return response()->json(['message' => 'Answer deleted successfully'], 200);
}

    public function likeQuestion($questionId): JsonResponse
    {
        $like = $this->sectionQAService->likeQuestion($questionId);
        
        if (!$like) {
            return response()->json(['message' => 'Question not found or already liked'], 404);
        }

        return response()->json($like);
    }

   public function unlikeQuestion($questionId): JsonResponse
{
    $removed = $this->sectionQAService->unlikeQuestion($questionId);
    
    if (!$removed) {
        return response()->json(['message' => 'Question not found or not liked'], 404);
    }

    return response()->json(['message' => 'Dislike removed successfully.'], 200);
}

    public function likeAnswer($answerId): JsonResponse
    {
        $like = $this->sectionQAService->likeAnswer($answerId);
        
        if (!$like) {
            return response()->json(['message' => 'Answer not found or already liked'], 404);
        }

        return response()->json($like);
    }

    public function unlikeAnswer($answerId): JsonResponse
{
    $removed = $this->sectionQAService->unlikeAnswer($answerId);
    
    if (!$removed) {
        return response()->json(['message' => 'Answer not found or not liked'], 404);
    }

    return response()->json(['message' => 'Answer unliked successfully'], 200);
}

    public function acceptAnswer($answerId): JsonResponse
    {
        $accepted = $this->sectionQAService->acceptAnswer($answerId);
        
        if (!$accepted) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Answer accepted successfully']);
    }

    public function unacceptAnswer($answerId): JsonResponse
    {
        $unaccepted = $this->sectionQAService->unacceptAnswer($answerId);
        
        if (!$unaccepted) {
            return response()->json(['message' => 'Answer not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Answer unaccepted successfully']);
    }
}