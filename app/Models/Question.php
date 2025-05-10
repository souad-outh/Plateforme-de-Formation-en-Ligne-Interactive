<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'type',
        'question',
        'answers',
        'options',
        'correct',
        'explanation',
        'difficulty',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the formatted answers based on question type
     *
     * @return array
     */
    public function getFormattedAnswers()
    {
        switch ($this->type) {
            case 'multiple_choice':
                return explode(',', $this->answers);

            case 'true_false':
                return ['True', 'False'];

            case 'short_answer':
                // For short answer, we return the sample answer as a single-item array
                return [$this->answers];

            case 'matching':
            case 'drag_drop':
            case 'fill_blank':
                return $this->options['answers'] ?? [];

            default:
                return explode(',', $this->answers);
        }
    }

    /**
     * Check if an answer is correct
     *
     * @param mixed $answer
     * @return bool
     */
    public function isCorrect($answer)
    {
        switch ($this->type) {
            case 'multiple_choice':
                return (int) $answer === (int) $this->correct;

            case 'true_false':
                return (strtolower($answer) === 'true' && $this->correct === 0) ||
                       (strtolower($answer) === 'false' && $this->correct === 1);

            case 'short_answer':
                // For short answer, we use a simple similarity check
                // This is a basic implementation - in a real system, you might want to use
                // more sophisticated NLP techniques to evaluate short answers
                $userAnswer = strtolower(trim($answer));
                $sampleAnswer = strtolower(trim($this->answers));

                // Check if the answer contains key phrases from the sample answer
                $keyPhrases = $this->extractKeyPhrases($sampleAnswer);
                $matchCount = 0;

                foreach ($keyPhrases as $phrase) {
                    if (strpos($userAnswer, $phrase) !== false) {
                        $matchCount++;
                    }
                }

                // If the user's answer matches at least 50% of key phrases, consider it correct
                return $matchCount >= count($keyPhrases) * 0.5;

            case 'matching':
                if (!is_array($answer)) return false;
                $correctMatches = $this->options['matches'] ?? [];
                foreach ($answer as $key => $value) {
                    if (!isset($correctMatches[$key]) || $correctMatches[$key] !== $value) {
                        return false;
                    }
                }
                return true;

            case 'drag_drop':
                if (!is_array($answer)) return false;
                $correctOrder = $this->options['correct_order'] ?? [];
                return $answer === $correctOrder;

            case 'fill_blank':
                if (!is_array($answer)) return false;
                $blanks = $this->options['blanks'] ?? [];
                foreach ($answer as $index => $value) {
                    if (!isset($blanks[$index]) || strtolower(trim($value)) !== strtolower(trim($blanks[$index]))) {
                        return false;
                    }
                }
                return true;

            default:
                return (int) $answer === (int) $this->correct;
        }
    }

    /**
     * Extract key phrases from a sample answer
     *
     * @param string $sampleAnswer
     * @return array
     */
    protected function extractKeyPhrases($sampleAnswer)
    {
        // Split the sample answer into sentences
        $sentences = preg_split('/[.!?]+/', $sampleAnswer);
        $keyPhrases = [];

        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (empty($sentence)) continue;

            // For short sentences, use the whole sentence as a key phrase
            if (str_word_count($sentence) <= 5) {
                $keyPhrases[] = $sentence;
                continue;
            }

            // For longer sentences, extract noun phrases or important segments
            // This is a simplified approach - in a real system, you might use NLP
            $words = explode(' ', $sentence);
            $chunks = array_chunk($words, 3);

            foreach ($chunks as $chunk) {
                if (count($chunk) >= 2) {
                    $keyPhrases[] = implode(' ', $chunk);
                }
            }
        }

        return array_unique($keyPhrases);
    }

    /**
     * Get difficulty level name
     *
     * @return string
     */
    public function getDifficultyName()
    {
        switch ($this->difficulty) {
            case 1:
                return 'Easy';
            case 2:
                return 'Medium';
            case 3:
                return 'Hard';
            default:
                return 'Medium';
        }
    }
}
