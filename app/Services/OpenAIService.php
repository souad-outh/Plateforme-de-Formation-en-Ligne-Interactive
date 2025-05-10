<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
        $this->model = config('services.openai.model', 'gpt-3.5-turbo');
    }

    /**
     * Generate quiz questions based on course content
     *
     * @param string $courseContent The course content to generate questions from
     * @param int $numQuestions Number of questions to generate
     * @param string $difficulty Difficulty level (easy, medium, hard)
     * @param string $questionType Type of questions (multiple_choice, true_false, short_answer)
     * @return array Generated questions
     */
    public function generateQuizQuestions(string $courseContent, int $numQuestions = 5, string $difficulty = 'medium', string $questionType = 'multiple_choice'): array
    {
        try {
            $prompt = $this->buildQuizPrompt($courseContent, $numQuestions, $difficulty, $questionType);
            $response = $this->callOpenAI($prompt);
            
            return $this->parseQuizResponse($response, $questionType);
        } catch (Exception $e) {
            Log::error('Error generating quiz questions: ' . $e->getMessage());
            throw new Exception('Failed to generate quiz questions. Please try again later.');
        }
    }

    /**
     * Build the prompt for quiz generation
     */
    protected function buildQuizPrompt(string $courseContent, int $numQuestions, string $difficulty, string $questionType): string
    {
        $prompt = "Generate {$numQuestions} {$difficulty} {$questionType} questions based on the following course content. ";
        
        if ($questionType === 'multiple_choice') {
            $prompt .= "For each question, provide 4 options with one correct answer. ";
            $prompt .= "Format the response as a JSON array with each question having: question_text, options (array of 4 options), and correct_answer (the correct option). ";
        } elseif ($questionType === 'true_false') {
            $prompt .= "Format the response as a JSON array with each question having: question_text and correct_answer (true or false). ";
        } elseif ($questionType === 'short_answer') {
            $prompt .= "Format the response as a JSON array with each question having: question_text and sample_answer. ";
        }
        
        $prompt .= "Here's the course content: \n\n{$courseContent}";
        
        return $prompt;
    }

    /**
     * Call the OpenAI API
     */
    protected function callOpenAI(string $prompt): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/chat/completions', [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an educational assistant that creates quiz questions based on course content.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        if ($response->failed()) {
            Log::error('OpenAI API error: ' . $response->body());
            throw new Exception('Failed to communicate with AI service: ' . $response->status());
        }

        return $response->json('choices.0.message.content');
    }

    /**
     * Parse the OpenAI response into a structured quiz format
     */
    protected function parseQuizResponse(string $response, string $questionType): array
    {
        try {
            // Extract JSON from the response (in case there's additional text)
            preg_match('/\[.*\]/s', $response, $matches);
            $jsonStr = $matches[0] ?? $response;
            
            $questions = json_decode($jsonStr, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If JSON parsing fails, try to clean the response
                $cleanedResponse = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $response);
                $questions = json_decode($cleanedResponse, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Failed to parse AI response: ' . json_last_error_msg());
                }
            }
            
            return $questions;
        } catch (Exception $e) {
            Log::error('Error parsing quiz response: ' . $e->getMessage() . ' Response: ' . $response);
            throw new Exception('Failed to parse generated questions. Please try again.');
        }
    }

    /**
     * Generate feedback for a student's quiz answers
     *
     * @param array $questions The quiz questions
     * @param array $studentAnswers The student's answers
     * @return array Feedback for each question
     */
    public function generateQuizFeedback(array $questions, array $studentAnswers): array
    {
        try {
            $prompt = $this->buildFeedbackPrompt($questions, $studentAnswers);
            $response = $this->callOpenAI($prompt);
            
            return $this->parseFeedbackResponse($response);
        } catch (Exception $e) {
            Log::error('Error generating quiz feedback: ' . $e->getMessage());
            throw new Exception('Failed to generate feedback. Please try again later.');
        }
    }

    /**
     * Build the prompt for feedback generation
     */
    protected function buildFeedbackPrompt(array $questions, array $studentAnswers): string
    {
        $prompt = "Generate personalized feedback for a student's quiz answers. ";
        $prompt .= "For each question, provide: whether the answer is correct, an explanation why, and a tip for improvement if needed. ";
        $prompt .= "Format the response as a JSON array with each feedback having: question_id, is_correct, explanation, and improvement_tip. ";
        
        $prompt .= "Here are the questions and the student's answers: \n\n";
        
        foreach ($questions as $index => $question) {
            $questionId = $question['id'] ?? $index;
            $questionText = $question['question_text'];
            $correctAnswer = $question['correct_answer'];
            $studentAnswer = $studentAnswers[$questionId] ?? 'No answer provided';
            
            $prompt .= "Question {$index + 1}: {$questionText}\n";
            $prompt .= "Correct answer: {$correctAnswer}\n";
            $prompt .= "Student's answer: {$studentAnswer}\n\n";
        }
        
        return $prompt;
    }

    /**
     * Parse the feedback response
     */
    protected function parseFeedbackResponse(string $response): array
    {
        try {
            // Extract JSON from the response
            preg_match('/\[.*\]/s', $response, $matches);
            $jsonStr = $matches[0] ?? $response;
            
            $feedback = json_decode($jsonStr, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If JSON parsing fails, try to clean the response
                $cleanedResponse = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $response);
                $feedback = json_decode($cleanedResponse, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Failed to parse AI feedback: ' . json_last_error_msg());
                }
            }
            
            return $feedback;
        } catch (Exception $e) {
            Log::error('Error parsing feedback response: ' . $e->getMessage() . ' Response: ' . $response);
            throw new Exception('Failed to parse generated feedback. Please try again.');
        }
    }
}
