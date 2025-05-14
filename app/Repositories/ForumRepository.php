<?php

namespace App\Repositories;

use App\Models\ForumQuestion;
use App\Models\ForumAnswer;
use App\Models\CourseSection;

class ForumRepository
{
    protected $forumQuestion;
    protected $forumAnswer;

    public function __construct(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer)
    {
        $this->forumQuestion = $forumQuestion;
        $this->forumAnswer = $forumAnswer;
    }

    public function getSectionQuestions(CourseSection $section)
    {
        return $section->forumQuestions()
            ->with(['user', 'answers.user', 'likedBy'])
            ->withCount('likedBy')
            ->latest()
            ->get();
    }

    public function createQuestion(array $data)
    {
        return $this->forumQuestion->create($data);
    }

    public function createAnswer(array $data)
    {
        return $this->forumAnswer->create($data);
    }

    public function toggleLike(ForumQuestion $question, int $userId)
    {
        if ($question->likedBy()->where('user_id', $userId)->exists()) {
            $question->likedBy()->detach($userId);
            return false;
        } else {
            $question->likedBy()->attach($userId);
            return true;
        }
    }

    public function isUserEnrolledInSection(CourseSection $section, $user)
    {
        // Check if user is a student in the section
        if ($user->student && $section->students()->where('student_id', $user->student->id)->exists()) {
            return true;
        }
        
        // Check if user is a trainer in the section
        if ($user->trainer && $section->trainers()->where('trainer_id', $user->trainer->id)->exists()) {
            return true;
        }
        
        return false;
    }
} 