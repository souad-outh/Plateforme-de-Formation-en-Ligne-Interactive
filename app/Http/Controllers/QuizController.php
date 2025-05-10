<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResult;
use App\Services\AIQuizService;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function createQuiz() {
        $courses = Course::all();
        return view('admin.createQuiz', compact('courses'));
    }

    public function storeQuiz(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        Quiz::create([
            'name' => $request->name,
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('admin.quizzes')->with('success', 'Quiz created successfully.');
    }

    public function editQuiz($id) {
        $quiz = Quiz::findOrFail($id);
        $courses = Course::all();
        return view('admin.editQuiz', compact('quiz', 'courses'));
    }

    public function updateQuiz(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        $quiz = Quiz::findOrFail($id);
        $quiz->update([
            'name' => $request->name,
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('admin.quizzes')->with('success', 'Quiz updated successfully.');
    }

    public function deleteQuiz($id) {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('admin.quizzes')->with('success', 'Quiz deleted successfully.');
    }

    public function createQuestion($quizId) {
        return view('admin.createQuestion', compact('quizId'));
    }

    public function storeQuestion(Request $request, $quizId) {
        $request->validate([
            'question' => 'required|string|max:255',
            'answers' => 'required|string',
            'correct' => 'required|integer|min:0',
        ]);

        $answersArray = explode(',', $request->answers);

        if ($request->correct < 0 || $request->correct >= count($answersArray) + 1) {
            return redirect()->back()->withErrors(['correct' => 'The correct answer index is out of bounds.'])->withInput();
        }

        $question = new Question();
        $question->quiz_id = $quizId;
        $question->question = $request->question;
        $question->answers = $request->answers;
        $question->correct = $request->correct - 1;
        $question->save();

        return redirect()->route('admin.quizQuestions', $quizId)->with('success', 'Question added successfully.');
    }

    public function editQuestion($id) {
        $question = Question::findOrFail($id);
        return view('admin.editQuestion', compact('question'));
    }

    public function updateQuestion(Request $request, $id) {
        $request->validate([
            'question' => 'required|string|max:255',
            'answers' => 'required|string',
            'correct' => 'required|integer|min:0',
        ]);

        $answersArray = explode(',', $request->answers);

        if ($request->correct < 0 || $request->correct - 1 >= count($answersArray)) {
            return redirect()->back()->withErrors(['correct' => 'The correct answer index is out of bounds.'])->withInput();
        }

        if (count($answersArray) < 2) {
            return redirect()->back()->withErrors(['answers' => 'There must be at least two answers.'])->withInput();
        }

        $question = Question::findOrFail($id);
        $question->question = $request->question;
        $question->answers = $request->answers;
        $question->correct = $request->correct - 1;
        $question->save();

        return redirect()->route('admin.quizQuestions', $question->quiz_id)->with('success', 'Question updated successfully.');
    }

    public function deleteQuestion($id) {
        $question = Question::findOrFail($id);
        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.quizQuestions', $quizId)->with('success', 'Question deleted successfully.');
    }

    public function takeQuiz($id) {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('student.quiz', compact('quiz'));
    }

    public function submitQuiz(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        $quiz = Quiz::with('questions', 'course')->findOrFail($request->quiz_id);
        $questions = $quiz->questions;

        $correctAnswersCount = 0;
        $questionDetails = [];

        foreach ($questions as $question) {
            if (isset($request->answers[$question->id])) {
                $userAnswer = $request->answers[$question->id];
                $isCorrect = false;

                // Check if the answer is correct based on question type
                if ($question->type === 'multiple_choice' || $question->type === 'true_false' || !$question->type) {
                    // For multiple choice and true/false, we expect an integer index
                    $isCorrect = (int) $userAnswer === (int) $question->correct;
                } elseif ($question->type === 'short_answer') {
                    // For short answer, we use the isCorrect method in the Question model
                    $isCorrect = $question->isCorrect($userAnswer);
                }

                if ($isCorrect) {
                    $correctAnswersCount++;
                }

                // Store question details for feedback
                $questionDetails[] = [
                    'question' => $question->question,
                    'type' => $question->type ?? 'multiple_choice',
                    'user_answer' => $userAnswer,
                    'correct_answer' => $question->type === 'short_answer' ?
                        $question->answers :
                        $question->getFormattedAnswers()[$question->correct] ?? '',
                    'is_correct' => $isCorrect
                ];
            }
        }

        $score = $quiz->course->score;
        $totalScore = $correctAnswersCount * $score / count($questions);

        $result = new QuizResult();
        $result->user_id = auth()->id();
        $result->quiz_id = $request->quiz_id;
        $result->correct_answers = $correctAnswersCount;
        $result->answers_count = count($questions);
        $result->score = $totalScore;
        $result->details = json_encode($questionDetails); // Store detailed results
        $result->save();

        return view('student.QuizResults', [
            'quizName' => $quiz->name,
            'score' => $totalScore,
            'correctAnswers' => $correctAnswersCount,
            'totalQuestions' => count($questions),
            'questionDetails' => $questionDetails
        ]);
    }

    /**
     * Show the form for generating AI quiz
     *
     * @param int $courseId
     * @return \Illuminate\View\View
     */
    public function showGenerateAIQuiz($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('admin.generateAIQuiz', compact('course'));
    }

    /**
     * Generate quiz using AI
     *
     * @param Request $request
     * @param int $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateAIQuiz(Request $request, $courseId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'num_questions' => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
        ]);

        try {
            $course = Course::findOrFail($courseId);

            // Create the quiz
            $quiz = new Quiz();
            $quiz->name = $request->name;
            $quiz->course_id = $courseId;
            $quiz->is_ai_generated = true;
            $quiz->save();

            // Generate questions using AI
            $aiQuizService = new AIQuizService();
            $result = $aiQuizService->generateQuiz(
                $course,
                $request->num_questions,
                $request->difficulty,
                $request->question_type
            );

            if (!$result['success']) {
                return redirect()->back()->withErrors(['ai_error' => $result['message']])->withInput();
            }

            // Save the generated questions
            $success = $aiQuizService->saveQuizQuestions($quiz, $result['data'], $request->question_type);

            if (!$success) {
                return redirect()->back()->withErrors(['db_error' => 'Failed to save generated questions.'])->withInput();
            }

            return redirect()->route('admin.quizQuestions', $quiz->id)
                ->with('success', 'Quiz generated successfully with AI. Review the questions below.');

        } catch (\Exception $e) {
            Log::error('Error generating AI quiz: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while generating the quiz: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Preview AI generated questions before saving
     *
     * @param Request $request
     * @param int $courseId
     * @return \Illuminate\View\View
     */
    public function previewAIQuiz(Request $request, $courseId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'num_questions' => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
        ]);

        try {
            $course = Course::findOrFail($courseId);

            // Generate questions using AI
            $aiQuizService = new AIQuizService();
            $result = $aiQuizService->generateQuiz(
                $course,
                $request->num_questions,
                $request->difficulty,
                $request->question_type
            );

            if (!$result['success']) {
                return redirect()->back()->withErrors(['ai_error' => $result['message']])->withInput();
            }

            return view('admin.previewAIQuiz', [
                'course' => $course,
                'quizName' => $request->name,
                'questions' => $result['data'],
                'numQuestions' => $request->num_questions,
                'difficulty' => $request->difficulty,
                'questionType' => $request->question_type
            ]);

        } catch (\Exception $e) {
            Log::error('Error previewing AI quiz: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while previewing the quiz: ' . $e->getMessage()])->withInput();
        }
    }
}