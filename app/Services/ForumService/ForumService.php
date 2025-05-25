<?php

namespace App\Services\ForumService;

use App\Models\ForumQuestion;
use App\Models\ForumAnswer;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ForumService
{
    private function checkSectionAccess(CourseSection $section): void
    {
        if (!$section) {
            throw new \Exception('Section not found');
        }

        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Please log in to access the forum');
        }

        // Get user type and ID
        $userType = $user instanceof \App\Models\Trainer ? 'trainer' : 'student';
        $userId = $user->id;

        // Check if user is a member of the section
        $isMember = false;
        if ($userType === 'trainer') {
            $isMember = $section->trainers()->where('trainers.id', $userId)->exists();
        } else {
            $isMember = $section->students()->where('students.id', $userId)->exists();
        }

        if (!$isMember) {
            throw new \Exception('You are not authorized to access this section\'s forum');
        }
    }

    public function getSectionQuestions(CourseSection $section): Collection
    {
        $this->checkSectionAccess($section);
        return $section->questions()
            ->with(['answers', 'likes', 'section'])
            ->latest()
            ->get();
    }

    public function getQuestionsWithStats(CourseSection $section): Collection
    {
        $this->checkSectionAccess($section);
        return $section->questions()
            ->with(['section'])
            ->withCount(['likes', 'answers'])
            ->latest()
            ->get();
    }

    public function getQuestionLikes(ForumQuestion $question): \Illuminate\Database\Eloquent\Collection
    {
        if (!$question) {
            throw new \Exception('Question not found');
        }

        $this->checkSectionAccess($question->section);
       return $question->likes()
    ->select('forum_question_likes.user_id', 'forum_question_likes.user_type', 'forum_question_likes.created_at')
    ->get();

    }

public function getAnswerLikes(ForumQuestion $question): \Illuminate\Database\Eloquent\Collection
{
    if (!$question) {
        throw new \Exception('Question not found');
    }

    $this->checkSectionAccess($question->section);

 
    $likes = $question->answers()
        ->with('likes')
        ->get()
        ->flatMap(function ($answer) {
            return $answer->likes;
        });

    return new \Illuminate\Database\Eloquent\Collection($likes);
}

    public function createQuestion(array $data): ForumQuestion
    {
        $section = CourseSection::findOrFail($data['section_id']);
        $this->checkSectionAccess($section);
        
        $user = Auth::user();
        $userType = $user instanceof \App\Models\Trainer ? 'trainer' : 'student';
        
        $question = ForumQuestion::create([
            'section_id' => $data['section_id'],
            'user_id' => $user->id,
            'user_type' => $userType,
            'title' => $data['title'],
            'content' => $data['content']
        ]);

        return $question->load('section');
    }

    public function createAnswer(array $data): ForumAnswer
    {
        $question = ForumQuestion::with('section')->findOrFail($data['question_id']);
        $this->checkSectionAccess($question->section);
        
        $user = Auth::user();
        $userType = $user instanceof \App\Models\Trainer ? 'trainer' : 'student';
        
        $answer = ForumAnswer::create([
            'question_id' => $data['question_id'],
            'user_id' => $user->id,
            'user_type' => $userType,
            'content' => $data['content']
        ]);

        return $answer->load('question.section');
    }

    public function deleteQuestion(ForumQuestion $question): array
    {
        if (!$question) {
            throw new \Exception('Question not found');
        }

        $this->checkSectionAccess($question->section);
        
        if ($question->user_id !== Auth::id()) {
            throw new \Exception('Unauthorized to delete this question');
        }
        
        $question->delete();
        return ['message' => 'Question has been deleted successfully'];
    }

    public function toggleLike(ForumQuestion $question, int $user_id, string $user_type): bool
    {
        if (!$question) {
            throw new \Exception('Question not found');
        }

        $this->checkSectionAccess($question->section);
        
        $user = Auth::user();
        $userType = $user instanceof \App\Models\Trainer ? 'trainer' : 'student';
        
        if ($question->likes()
            ->where('forum_question_likes.user_id', $user->id)
            ->where('forum_question_likes.user_type', $userType)
            ->exists()) {
            $question->likes()->detach($user->id, ['user_type' => $userType]);
            return false;
        }
        $question->likes()->attach($user->id, ['user_type' => $userType]);
        return true;
    }

    
    public function markAnswerAsAccepted(ForumAnswer $answer): void
    {
        if (!$answer || !$answer->question) {
            throw new \Exception('Answer or associated question not found');
        }

        $this->checkSectionAccess($answer->question->section);
        
        $user = Auth::user();
        if (!($user instanceof \App\Models\Trainer)) {
            throw new \Exception('Only trainers can mark answers as accepted');
        }

        // If answer is already accepted, unaccept it
        if ($answer->is_accepted) {
            $answer->update(['is_accepted' => false]);
            $answer->question->update(['is_resolved' => false]);
            return;
        }

        // First, unmark any previously accepted answers for this question
        ForumAnswer::where('question_id', $answer->question_id)
            ->where('is_accepted', true)
            ->update(['is_accepted' => false]);

        // Mark the new answer as accepted
        $answer->update(['is_accepted' => true]);

        // Mark the question as resolved
        $answer->question->update(['is_resolved' => true]);
    }


    public function getQuestionsBySection(CourseSection $section)
    {
        return $section->questions()
            ->with(['answers', 'likes'])
            ->latest()
            ->get();
    }

    public function toggleAnswerLike(ForumAnswer $answer, int $user_id, string $user_type): bool
    {
        if (!$answer) {
            throw new \Exception('Answer not found');
        }

        $this->checkSectionAccess($answer->question->section);
        
        $user = Auth::user();
        $userType = $user instanceof \App\Models\Trainer ? 'trainer' : 'student';
        
        if ($answer->likes()
            ->where('forum_answer_likes.user_id', $user->id)
            ->where('forum_answer_likes.user_type', $userType)
            ->exists()) {
            $answer->likes()->detach($user->id, ['user_type' => $userType]);
            return false;
        }
        $answer->likes()->attach($user->id, ['user_type' => $userType]);
        return true;
    }
} 