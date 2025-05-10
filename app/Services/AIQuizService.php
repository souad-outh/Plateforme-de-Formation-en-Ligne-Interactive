<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Services\AIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIQuizService
{
    /**
     * The AI service instance
     */
    protected $aiService;

    /**
     * Create a new AI Quiz Service instance
     */
    public function __construct()
    {
        $this->aiService = new AIService();
    }

    /**
     * Generate a quiz based on course content
     *
     * @param Course $course
     * @param int $numQuestions
     * @param string $difficulty
     * @param string $questionType
     * @return array
     */
    public function generateQuiz(Course $course, int $numQuestions = 5, string $difficulty = 'medium', string $questionType = 'multiple_choice')
    {
        try {
            // Get course content
            $content = $this->extractCourseContent($course);

            if (empty($content)) {
                return [
                    'success' => false,
                    'message' => 'No content available to generate quiz'
                ];
            }

            // Use the AIService to generate questions
            try {
                $questions = $this->aiService->generateQuizQuestions(
                    $content,
                    $numQuestions,
                    $difficulty,
                    $questionType
                );

                // Format the questions based on the question type
                $formattedQuestions = $this->formatQuestions($questions, $questionType);

                return [
                    'success' => true,
                    'data' => $formattedQuestions
                ];
            } catch (\Exception $e) {
                Log::error('Error from AI service: ' . $e->getMessage());

                // Fallback to traditional method if AI service fails
                $prompt = $this->preparePrompt($content, $numQuestions, $difficulty, $questionType);
                $response = $this->callAIApi($prompt);

                if (!$response['success']) {
                    return $response;
                }

                // Parse AI response into quiz questions
                $questions = $this->parseAIResponse($response['data'], $questionType);

                return [
                    'success' => true,
                    'data' => $questions
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error generating quiz: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to generate quiz: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format questions based on question type
     *
     * @param array $questions
     * @param string $questionType
     * @return array
     */
    protected function formatQuestions(array $questions, string $questionType): array
    {
        $formattedQuestions = [];

        foreach ($questions as $question) {
            if ($questionType === 'multiple_choice') {
                // Find the index of the correct answer
                $correctIndex = array_search($question['correct_answer'], $question['options']);

                $formattedQuestions[] = [
                    'question' => $question['question_text'],
                    'options' => $question['options'],
                    'correct_index' => $correctIndex !== false ? $correctIndex : 0
                ];
            } elseif ($questionType === 'true_false') {
                $formattedQuestions[] = [
                    'question' => $question['question_text'],
                    'options' => ['True', 'False'],
                    'correct_index' => strtolower($question['correct_answer']) === 'true' ? 0 : 1
                ];
            } elseif ($questionType === 'short_answer') {
                $formattedQuestions[] = [
                    'question' => $question['question_text'],
                    'sample_answer' => $question['sample_answer'],
                    'correct_index' => 0 // Not applicable for short answer, but needed for consistency
                ];
            }
        }

        return $formattedQuestions;
    }

    /**
     * Extract content from a course
     *
     * @param Course $course
     * @return string
     */
    protected function extractCourseContent(Course $course)
    {
        $content = '';

        // Get course description
        $content .= $course->description . "\n\n";

        // Get course contents
        foreach ($course->contents as $courseContent) {
            if ($courseContent->type === 'text') {
                $content .= $courseContent->file . "\n\n";
            }
            // For PDF and other file types, we would need a text extraction service
            // For YouTube links, we might need a transcript service
        }

        return $content;
    }

    /**
     * Prepare the prompt for the AI
     *
     * @param string $content
     * @param int $numQuestions
     * @param string $difficulty
     * @param string $questionType
     * @return string
     */
    protected function preparePrompt(string $content, int $numQuestions, string $difficulty, string $questionType = 'multiple_choice')
    {
        $prompt = "Based on the following educational content, generate {$numQuestions} {$questionType} questions at {$difficulty} difficulty level. ";

        if ($questionType === 'multiple_choice') {
            $prompt .= "For each question, provide 4 options with one correct answer. Format the response as a JSON array where each question object has 'question', 'options' (array of 4 strings), and 'correct_index' (0-based index of correct answer).";
        } elseif ($questionType === 'true_false') {
            $prompt .= "For each question, indicate whether the statement is true or false. Format the response as a JSON array where each question object has 'question' and 'correct_answer' (either 'true' or 'false').";
        } elseif ($questionType === 'short_answer') {
            $prompt .= "For each question, provide a sample answer. Format the response as a JSON array where each question object has 'question' and 'sample_answer'.";
        }

        $prompt .= "\n\nContent:\n{$content}";

        return $prompt;
    }

    /**
     * Call the AI API
     *
     * @param string $prompt
     * @return array
     */
    protected function callAIApi(string $prompt)
    {
        try {
            // Extract the number of questions and difficulty from the prompt
            preg_match('/generate\s+(\d+)\s+multiple-choice\s+questions.*?at\s+(\w+)\s+difficulty/i', $prompt, $matches);
            $numQuestions = $matches[1] ?? 5;
            $difficulty = $matches[2] ?? 'medium';

            // Extract the content from the prompt
            $contentStart = strpos($prompt, "Content:\n") + 9;
            $content = substr($prompt, $contentStart);

            // Use the AIService to generate questions
            $questions = $this->aiService->generateQuizQuestions(
                $content,
                (int)$numQuestions,
                $difficulty,
                'multiple_choice'
            );

            // Convert the questions to the expected format if needed
            $formattedQuestions = [];
            foreach ($questions as $question) {
                // Find the index of the correct answer
                $correctIndex = array_search($question['correct_answer'], $question['options']);

                $formattedQuestions[] = [
                    'question' => $question['question_text'],
                    'options' => $question['options'],
                    'correct_index' => $correctIndex !== false ? $correctIndex : 0
                ];
            }

            return [
                'success' => true,
                'data' => json_encode($formattedQuestions)
            ];
        } catch (\Exception $e) {
            Log::error('Error calling AI API: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to call AI service: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Parse the AI response into quiz questions
     *
     * @param string $aiResponse
     * @param string $questionType
     * @return array
     */
    protected function parseAIResponse(string $aiResponse, string $questionType = 'multiple_choice')
    {
        try {
            // Extract JSON from the response (in case there's additional text)
            preg_match('/\[.*\]/s', $aiResponse, $matches);
            $jsonStr = $matches[0] ?? $aiResponse;

            // Parse JSON
            $questions = json_decode($jsonStr, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Try to clean the response
                $cleanedResponse = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $aiResponse);
                $questions = json_decode($cleanedResponse, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Error parsing AI response: ' . json_last_error_msg());
                    Log::error('AI response: ' . $aiResponse);
                    return [];
                }
            }

            // Format questions based on question type
            $formattedQuestions = [];

            foreach ($questions as $question) {
                if ($questionType === 'multiple_choice') {
                    // Check if the question has the expected format
                    if (isset($question['question']) && isset($question['options']) && isset($question['correct_index'])) {
                        $formattedQuestions[] = $question;
                    } elseif (isset($question['question_text']) && isset($question['options']) && isset($question['correct_answer'])) {
                        // Convert from AIService format
                        $correctIndex = array_search($question['correct_answer'], $question['options']);
                        $formattedQuestions[] = [
                            'question' => $question['question_text'],
                            'options' => $question['options'],
                            'correct_index' => $correctIndex !== false ? $correctIndex : 0
                        ];
                    }
                } elseif ($questionType === 'true_false') {
                    // Format true/false questions
                    if (isset($question['question']) && isset($question['correct_answer'])) {
                        $formattedQuestions[] = [
                            'question' => $question['question'],
                            'options' => ['True', 'False'],
                            'correct_index' => strtolower($question['correct_answer']) === 'true' ? 0 : 1
                        ];
                    } elseif (isset($question['question_text']) && isset($question['correct_answer'])) {
                        $formattedQuestions[] = [
                            'question' => $question['question_text'],
                            'options' => ['True', 'False'],
                            'correct_index' => strtolower($question['correct_answer']) === 'true' ? 0 : 1
                        ];
                    }
                } elseif ($questionType === 'short_answer') {
                    // Format short answer questions
                    if (isset($question['question']) && isset($question['sample_answer'])) {
                        $formattedQuestions[] = [
                            'question' => $question['question'],
                            'sample_answer' => $question['sample_answer'],
                            'correct_index' => 0 // Not applicable, but needed for consistency
                        ];
                    } elseif (isset($question['question_text']) && isset($question['sample_answer'])) {
                        $formattedQuestions[] = [
                            'question' => $question['question_text'],
                            'sample_answer' => $question['sample_answer'],
                            'correct_index' => 0
                        ];
                    }
                }
            }

            return $formattedQuestions;
        } catch (\Exception $e) {
            Log::error('Error parsing AI response: ' . $e->getMessage());
            Log::error('AI response: ' . $aiResponse);
            return [];
        }
    }

    /**
     * Save generated questions to the database
     *
     * @param Quiz $quiz
     * @param array $questions
     * @param string $questionType
     * @return bool
     */
    public function saveQuizQuestions(Quiz $quiz, array $questions, string $questionType = 'multiple_choice')
    {
        try {
            foreach ($questions as $questionData) {
                $question = new Question();
                $question->quiz_id = $quiz->id;
                $question->question = $questionData['question'];
                $question->type = $questionType;

                if ($questionType === 'multiple_choice') {
                    $question->answers = implode(',', $questionData['options']);
                    $question->correct = $questionData['correct_index'];
                } elseif ($questionType === 'true_false') {
                    $question->answers = 'True,False';
                    $question->correct = $questionData['correct_index'];
                } elseif ($questionType === 'short_answer') {
                    // For short answer questions, we store the sample answer in the answers field
                    $question->answers = $questionData['sample_answer'];
                    $question->correct = 0; // Not applicable for short answer
                }

                $question->save();
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error saving quiz questions: ' . $e->getMessage());
            return false;
        }
    }
}
