<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdaptiveLearningService
{
    /**
     * Generate a personalized learning path for a student
     *
     * @param User $student
     * @param Course $course
     * @return array
     */
    public function generateLearningPath(User $student, Course $course)
    {
        // Get student's performance in this course
        $performance = $this->getStudentCoursePerformance($student, $course);
        
        // Identify strengths and weaknesses
        $weakAreas = $this->identifyWeakAreas($student, $course);
        
        // Determine appropriate difficulty level
        $recommendedDifficulty = $this->determineRecommendedDifficulty($performance);
        
        // Get recommended content
        $recommendedContent = $this->getRecommendedContent($course, $weakAreas);
        
        // Get recommended practice questions
        $recommendedQuestions = $this->getRecommendedQuestions($course, $weakAreas, $recommendedDifficulty);
        
        return [
            'performance' => $performance,
            'weak_areas' => $weakAreas,
            'recommended_difficulty' => $recommendedDifficulty,
            'recommended_content' => $recommendedContent,
            'recommended_questions' => $recommendedQuestions,
        ];
    }
    
    /**
     * Get student's performance in a specific course
     *
     * @param User $student
     * @param Course $course
     * @return array
     */
    private function getStudentCoursePerformance(User $student, Course $course)
    {
        $quizResults = QuizResult::whereHas('quiz', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->where('user_id', $student->id)->get();
        
        $totalQuizzes = $course->quizzes->count();
        $completedQuizzes = $quizResults->pluck('quiz_id')->unique()->count();
        $averageScore = $quizResults->avg('score') ?? 0;
        $highestScore = $quizResults->max('score') ?? 0;
        
        // Calculate mastery level (0-100)
        $masteryLevel = min(100, ($averageScore * 0.7) + ($completedQuizzes / max(1, $totalQuizzes) * 30));
        
        return [
            'total_quizzes' => $totalQuizzes,
            'completed_quizzes' => $completedQuizzes,
            'average_score' => round($averageScore, 2),
            'highest_score' => round($highestScore, 2),
            'mastery_level' => round($masteryLevel, 2),
        ];
    }
    
    /**
     * Identify weak areas based on quiz performance
     *
     * @param User $student
     * @param Course $course
     * @return array
     */
    private function identifyWeakAreas(User $student, Course $course)
    {
        $weakAreas = [];
        
        // Get all quizzes for this course
        $quizzes = $course->quizzes;
        
        foreach ($quizzes as $quiz) {
            // Get student's results for this quiz
            $results = QuizResult::where('user_id', $student->id)
                ->where('quiz_id', $quiz->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$results || $results->score < 70) {
                // If score is below 70%, consider it a weak area
                $weakAreas[] = [
                    'quiz_id' => $quiz->id,
                    'quiz_name' => $quiz->name,
                    'score' => $results ? $results->score : 0,
                    'questions' => $this->getQuestionTypes($quiz),
                ];
            }
        }
        
        return $weakAreas;
    }
    
    /**
     * Get question types from a quiz
     *
     * @param Quiz $quiz
     * @return array
     */
    private function getQuestionTypes(Quiz $quiz)
    {
        $types = [];
        
        foreach ($quiz->questions as $question) {
            if (!isset($types[$question->type])) {
                $types[$question->type] = 0;
            }
            
            $types[$question->type]++;
        }
        
        return $types;
    }
    
    /**
     * Determine recommended difficulty level based on performance
     *
     * @param array $performance
     * @return int
     */
    private function determineRecommendedDifficulty(array $performance)
    {
        $masteryLevel = $performance['mastery_level'];
        
        if ($masteryLevel < 40) {
            return 1; // Easy
        } elseif ($masteryLevel < 75) {
            return 2; // Medium
        } else {
            return 3; // Hard
        }
    }
    
    /**
     * Get recommended content based on weak areas
     *
     * @param Course $course
     * @param array $weakAreas
     * @return array
     */
    private function getRecommendedContent(Course $course, array $weakAreas)
    {
        $recommendedContent = [];
        
        // If no weak areas, recommend general course content
        if (empty($weakAreas)) {
            foreach ($course->contents as $content) {
                $recommendedContent[] = [
                    'content_id' => $content->id,
                    'type' => $content->type,
                    'file' => $content->file,
                    'relevance' => 'general',
                ];
            }
            
            return $recommendedContent;
        }
        
        // Get quiz IDs from weak areas
        $weakQuizIds = array_column($weakAreas, 'quiz_id');
        
        // Get content related to weak quizzes
        // In a real implementation, this would use more sophisticated content-quiz relationships
        // For this demo, we'll use a simple approach
        foreach ($course->contents as $index => $content) {
            $relevance = ($index % count($weakQuizIds));
            $recommendedContent[] = [
                'content_id' => $content->id,
                'type' => $content->type,
                'file' => $content->file,
                'relevance' => 'related to ' . $weakAreas[$relevance]['quiz_name'],
            ];
        }
        
        return $recommendedContent;
    }
    
    /**
     * Get recommended practice questions based on weak areas and difficulty
     *
     * @param Course $course
     * @param array $weakAreas
     * @param int $difficulty
     * @return array
     */
    private function getRecommendedQuestions(Course $course, array $weakAreas, int $difficulty)
    {
        $recommendedQuestions = [];
        
        // If no weak areas, recommend general questions
        if (empty($weakAreas)) {
            $questions = Question::whereHas('quiz', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('difficulty', $difficulty)
              ->inRandomOrder()
              ->take(5)
              ->get();
            
            foreach ($questions as $question) {
                $recommendedQuestions[] = [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'difficulty' => $question->getDifficultyName(),
                    'relevance' => 'general practice',
                ];
            }
            
            return $recommendedQuestions;
        }
        
        // Get quiz IDs from weak areas
        $weakQuizIds = array_column($weakAreas, 'quiz_id');
        
        // Get questions from weak quizzes with appropriate difficulty
        $questions = Question::whereIn('quiz_id', $weakQuizIds)
            ->where('difficulty', $difficulty)
            ->inRandomOrder()
            ->take(5)
            ->get();
        
        foreach ($questions as $question) {
            $quizName = $question->quiz->name;
            
            $recommendedQuestions[] = [
                'question_id' => $question->id,
                'question' => $question->question,
                'type' => $question->type,
                'difficulty' => $question->getDifficultyName(),
                'relevance' => 'related to ' . $quizName,
            ];
        }
        
        return $recommendedQuestions;
    }
    
    /**
     * Generate immediate feedback for a question
     *
     * @param Question $question
     * @param mixed $answer
     * @return array
     */
    public function generateFeedback(Question $question, $answer)
    {
        $isCorrect = $question->isCorrect($answer);
        
        $feedback = [
            'is_correct' => $isCorrect,
            'explanation' => $question->explanation ?? 'No explanation available.',
        ];
        
        if (!$isCorrect) {
            $feedback['correct_answer'] = $this->getCorrectAnswerDisplay($question);
        }
        
        return $feedback;
    }
    
    /**
     * Get correct answer display
     *
     * @param Question $question
     * @return mixed
     */
    private function getCorrectAnswerDisplay(Question $question)
    {
        switch ($question->type) {
            case 'multiple_choice':
                $answers = explode(',', $question->answers);
                return $answers[$question->correct] ?? '';
                
            case 'true_false':
                return $question->correct === 0 ? 'True' : 'False';
                
            case 'matching':
                return $question->options['matches'] ?? [];
                
            case 'drag_drop':
                return $question->options['correct_order'] ?? [];
                
            case 'fill_blank':
                return $question->options['blanks'] ?? [];
                
            default:
                return $question->correct;
        }
    }
}
