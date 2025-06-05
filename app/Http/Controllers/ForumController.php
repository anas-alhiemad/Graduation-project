<?php

namespace App\Http\Controllers;

use App\Models\ForumQuestion;
use App\Models\CourseSection;
use App\Models\ForumAnswer;
use App\Services\ForumService\ForumService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    protected $forumService;

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    public function getSectionQuestions(CourseSection $section): JsonResponse
    {
        try {
            $questions = $this->forumService->getSectionQuestions($section);
            return response()->json($questions);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function getQuestionsWithStats(CourseSection $section): JsonResponse
    {
        try {
            $questions = $this->forumService->getQuestionsWithStats($section);
            return response()->json($questions);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function getQuestionLikes(ForumQuestion $question): JsonResponse
    {
        try {
            $likes = $this->forumService->getQuestionLikes($question);
            return response()->json($likes);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function getAnswerLikes(ForumQuestion $question): JsonResponse
    {
        try {
            $likes = $this->forumService->getAnswerLikes($question);
            return response()->json($likes);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function createQuestion(Request $request, CourseSection $section): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            $data = array_merge($validated, [
                'section_id' => $section->id,
                'user_id' => Auth::id(),
                'user_type' => Auth::user() instanceof \App\Models\Trainer ? 'trainer' : 'student'
            ]);

            \Log::info("Inserting forum question with data: " . json_encode($data));
            $question = $this->forumService->createQuestion($data);
            return response()->json($question, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function createAnswer(Request $request, ForumQuestion $question): JsonResponse
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string'
            ]);

            $data = array_merge($validated, [
                'question_id' => $question->id,
                'user_id' => Auth::id(),
                'user_type' => Auth::user() instanceof \App\Models\Trainer ? 'trainer' : 'student'
            ]);

            $answer = $this->forumService->createAnswer($data);
            return response()->json($answer, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function deleteQuestion(ForumQuestion $question): JsonResponse
{
    try {
        $this->forumService->deleteQuestion($question);
        return response()->json(['message' => 'Deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 403);
    }
}


    public function toggleLike(ForumQuestion $question): JsonResponse
    {
        try {
            $user_id = Auth::id();
            $user_type = Auth::user() instanceof \App\Models\Trainer ? 'trainer' : 'student';
            
            $isLiked = $this->forumService->toggleLike($question, $user_id, $user_type);
            return response()->json(['is_liked' => $isLiked]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function markAnswerAsAccepted(ForumAnswer $answer): JsonResponse
    {
        try {
            $this->forumService->markAnswerAsAccepted($answer);
            return response()->json(['message' => 'Answer status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function toggleAnswerLike(ForumAnswer $answer): JsonResponse
    {
        try {
            $user_id = Auth::id();
            $user_type = Auth::user() instanceof \App\Models\Trainer ? 'trainer' : 'student';
            
            $isLiked = $this->forumService->toggleAnswerLike($answer, $user_id, $user_type);
            return response()->json(['is_liked' => $isLiked]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
} 