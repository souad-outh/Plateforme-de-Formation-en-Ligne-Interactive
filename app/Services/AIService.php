<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AIService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.huggingface.api_key');
        $this->apiUrl = config('services.huggingface.api_url');
        $this->model = config('services.huggingface.model');
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
            // For long content, we'll summarize it first
            if (strlen($courseContent) > 2000) {
                $courseContent = $this->summarizeContent($courseContent);
            }
            
            $prompt = $this->buildQuizPrompt($courseContent, $numQuestions, $difficulty, $questionType);
            $response = $this->callAIModel($prompt);
            
            return $this->parseQuizResponse($response, $questionType);
        } catch (Exception $e) {
            Log::error('Error generating quiz questions: ' . $e->getMessage());
            throw new Exception('Failed to generate quiz questions. Please try again later.');
        }
    }

    /**
     * Summarize long content to fit within token limits
     */
    protected function summarizeContent(string $content): string
    {
        $prompt = "Summarize the following educational content while preserving the key concepts and important details: \n\n" . $content;
        
        try {
            return $this->callAIModel($prompt);
        } catch (Exception $e) {
            Log::warning('Failed to summarize content: ' . $e->getMessage());
            // If summarization fails, truncate the content
            return substr($content, 0, 1500) . "...";
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
     * Call the AI model API
     */
    protected function callAIModel(string $prompt): string
    {
        // If no API key is set, use a local fallback method
        if (empty($this->apiKey)) {
            return $this->localFallbackGeneration($prompt);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . $this->model, [
            'inputs' => $prompt,
            'parameters' => [
                'max_length' => 1024,
                'temperature' => 0.7,
            ]
        ]);

        if ($response->failed()) {
            Log::error('AI API error: ' . $response->body());
            throw new Exception('Failed to communicate with AI service: ' . $response->status());
        }

        return $response->body();
    }

    /**
     * Local fallback method for generating quiz questions when no API is available
     * This uses predefined templates and simple logic to create basic questions
     */
    protected function localFallbackGeneration(string $prompt): string
    {
        Log::info('Using local fallback for AI generation');
        
        // Check if this is a quiz generation request
        if (strpos($prompt, 'Generate') !== false && strpos($prompt, 'questions') !== false) {
            // Extract the course content
            $contentStart = strpos($prompt, "Here's the course content:") + 26;
            $courseContent = trim(substr($prompt, $contentStart));
            
            // Determine question type
            $isMultipleChoice = strpos($prompt, 'multiple_choice') !== false;
            $isTrueFalse = strpos($prompt, 'true_false') !== false;
            
            // Generate simple questions based on content
            $sentences = preg_split('/(?<=[.?!])\s+/', $courseContent, -1, PREG_SPLIT_NO_EMPTY);
            $questions = [];
            
            // Get number of questions requested
            preg_match('/Generate\s+(\d+)/', $prompt, $matches);
            $numQuestions = isset($matches[1]) ? (int)$matches[1] : 3;
            $numQuestions = min($numQuestions, count($sentences), 5); // Cap at 5 or available sentences
            
            $selectedSentences = array_slice($sentences, 0, $numQuestions * 2);
            
            for ($i = 0; $i < $numQuestions; $i++) {
                if (isset($selectedSentences[$i])) {
                    $sentence = $selectedSentences[$i];
                    
                    if ($isMultipleChoice) {
                        $questions[] = $this->createMultipleChoiceQuestion($sentence, $i);
                    } elseif ($isTrueFalse) {
                        $questions[] = $this->createTrueFalseQuestion($sentence, $i);
                    } else {
                        $questions[] = $this->createShortAnswerQuestion($sentence, $i);
                    }
                }
            }
            
            return json_encode($questions, JSON_PRETTY_PRINT);
        }
        
        // For summarization or other requests, return a simplified version
        return substr($prompt, 0, 500) . "...";
    }
    
    /**
     * Create a simple multiple choice question from a sentence
     */
    protected function createMultipleChoiceQuestion(string $sentence, int $index): array
    {
        // Extract a keyword from the sentence
        $words = explode(' ', $sentence);
        $keywordIndex = min(count($words) - 1, 3 + $index % 3); // Vary the position
        $keyword = $words[$keywordIndex];
        
        // Clean the keyword
        $keyword = preg_replace('/[^a-zA-Z0-9]/', '', $keyword);
        
        if (empty($keyword)) {
            $keyword = "concept";
        }
        
        // Create the question
        $questionText = str_replace($keyword, "______", $sentence);
        $questionText = "What word fills in the blank? " . $questionText;
        
        // Create options
        $options = [$keyword, $keyword . "s", "not " . $keyword, "the " . $keyword];
        
        return [
            'question_text' => $questionText,
            'options' => $options,
            'correct_answer' => $options[0]
        ];
    }
    
    /**
     * Create a simple true/false question from a sentence
     */
    protected function createTrueFalseQuestion(string $sentence, int $index): array
    {
        // Determine if we'll make a true or false question
        $isTrue = $index % 2 == 0;
        
        if ($isTrue) {
            return [
                'question_text' => "True or False: " . $sentence,
                'correct_answer' => "true"
            ];
        } else {
            // Negate the sentence to make it false
            $negations = [
                "is" => "is not",
                "are" => "are not",
                "was" => "was not",
                "were" => "were not",
                "will" => "will not",
                "can" => "cannot",
                "has" => "has not",
                "have" => "have not"
            ];
            
            $modifiedSentence = $sentence;
            foreach ($negations as $word => $negation) {
                if (strpos($modifiedSentence, " $word ") !== false) {
                    $modifiedSentence = str_replace(" $word ", " $negation ", $modifiedSentence);
                    break;
                }
            }
            
            return [
                'question_text' => "True or False: " . $modifiedSentence,
                'correct_answer' => "false"
            ];
        }
    }
    
    /**
     * Create a simple short answer question from a sentence
     */
    protected function createShortAnswerQuestion(string $sentence, int $index): array
    {
        // Extract the main subject or concept
        $words = explode(' ', $sentence);
        $conceptStart = min(count($words) - 1, 1 + $index % 2);
        $conceptEnd = min(count($words) - 1, $conceptStart + 2);
        
        $concept = implode(' ', array_slice($words, $conceptStart, $conceptEnd - $conceptStart + 1));
        
        return [
            'question_text' => "Explain the concept of " . $concept . " in your own words.",
            'sample_answer' => "Based on the text, " . $sentence
        ];
    }

    /**
     * Parse the AI response into a structured quiz format
     */
    protected function parseQuizResponse(string $response, string $questionType): array
    {
        try {
            // Try to parse as JSON directly
            $questions = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If JSON parsing fails, try to extract JSON from the response
                preg_match('/\[.*\]/s', $response, $matches);
                $jsonStr = $matches[0] ?? $response;
                
                $questions = json_decode($jsonStr, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If still fails, try to clean the response
                    $cleanedResponse = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $response);
                    $questions = json_decode($cleanedResponse, true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // If all parsing attempts fail, use fallback
                        return $this->fallbackParseQuestions($response, $questionType);
                    }
                }
            }
            
            return $questions;
        } catch (Exception $e) {
            Log::error('Error parsing quiz response: ' . $e->getMessage() . ' Response: ' . $response);
            return $this->fallbackParseQuestions($response, $questionType);
        }
    }
    
    /**
     * Fallback method to parse questions when JSON parsing fails
     */
    protected function fallbackParseQuestions(string $response, string $questionType): array
    {
        Log::warning('Using fallback parsing for quiz questions');
        
        $questions = [];
        
        // Split by numbered questions (1., 2., etc.)
        $pattern = '/(\d+\.\s*.*?)(?=\d+\.\s*|$)/s';
        preg_match_all($pattern, $response, $matches);
        
        if (!empty($matches[0])) {
            foreach ($matches[0] as $index => $questionText) {
                if ($questionType === 'multiple_choice') {
                    $questions[] = $this->parseMultipleChoiceQuestion($questionText, $index);
                } elseif ($questionType === 'true_false') {
                    $questions[] = $this->parseTrueFalseQuestion($questionText, $index);
                } else {
                    $questions[] = $this->parseShortAnswerQuestion($questionText, $index);
                }
            }
        } else {
            // If no questions found, create a default question
            $questions[] = [
                'question_text' => 'What is the main topic of this content?',
                'options' => ['Education', 'Technology', 'Science', 'History'],
                'correct_answer' => 'Education'
            ];
        }
        
        return $questions;
    }
    
    /**
     * Parse a multiple choice question from text
     */
    protected function parseMultipleChoiceQuestion(string $text, int $index): array
    {
        $lines = explode("\n", $text);
        $questionText = trim($lines[0]);
        $questionText = preg_replace('/^\d+\.\s*/', '', $questionText);
        
        $options = [];
        $correctAnswer = '';
        
        // Look for options (a), b), c), d) or A. B. C. D.)
        foreach ($lines as $line) {
            if (preg_match('/^[a-dA-D][\.|\)]?\s+(.*)$/', trim($line), $matches)) {
                $options[] = $matches[1];
                
                // If this option has "correct" or "*" in it, mark it as correct
                if (strpos(strtolower($line), 'correct') !== false || strpos($line, '*') !== false) {
                    $correctAnswer = $matches[1];
                }
            }
        }
        
        // If no correct answer was marked, use the first option
        if (empty($correctAnswer) && !empty($options)) {
            $correctAnswer = $options[0];
        }
        
        // If no options found, create some
        if (empty($options)) {
            $options = ['Option A', 'Option B', 'Option C', 'Option D'];
            $correctAnswer = $options[0];
        }
        
        return [
            'question_text' => $questionText,
            'options' => $options,
            'correct_answer' => $correctAnswer
        ];
    }
    
    /**
     * Parse a true/false question from text
     */
    protected function parseTrueFalseQuestion(string $text, int $index): array
    {
        $questionText = trim(preg_replace('/^\d+\.\s*/', '', $text));
        
        // Look for "true" or "false" in the text
        $correctAnswer = 'true';
        if (preg_match('/correct answer:?\s*(true|false)/i', $text, $matches)) {
            $correctAnswer = strtolower($matches[1]);
        } elseif (strpos(strtolower($text), 'false') !== false && strpos(strtolower($text), 'true') === false) {
            $correctAnswer = 'false';
        }
        
        return [
            'question_text' => $questionText,
            'correct_answer' => $correctAnswer
        ];
    }
    
    /**
     * Parse a short answer question from text
     */
    protected function parseShortAnswerQuestion(string $text, int $index): array
    {
        $lines = explode("\n", $text);
        $questionText = trim(preg_replace('/^\d+\.\s*/', '', $lines[0]));
        
        $sampleAnswer = '';
        foreach ($lines as $i => $line) {
            if ($i > 0 && !empty(trim($line))) {
                $sampleAnswer = trim($line);
                break;
            }
        }
        
        if (empty($sampleAnswer)) {
            $sampleAnswer = "Sample answer for: " . $questionText;
        }
        
        return [
            'question_text' => $questionText,
            'sample_answer' => $sampleAnswer
        ];
    }
}
