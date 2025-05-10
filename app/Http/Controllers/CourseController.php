<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Content;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function storeCourse(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'score' => 'required|integer|min:1',
            'category' => 'required|exists:categories,id',
            'content_type' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
            'youtube_link' => 'nullable|url',
        ]);

        $user = auth()->id();

        $course = new Course();
        $course->title = $request->title;
        $course->description = $request->description;
        $course->score = $request->score;
        $course->creator_id = $user;
        $course->category_id = $request->category;
        $course->save();

        $content = new Content();
        $content->course_id = $course->id;

        if ($request->content_type === 'pdf' && $request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('pdfs', 'public');
            $content->type = 'pdf';
            $content->file = $path;
        } elseif ($request->content_type === 'youtube') {
            $content->type = 'youtube';
            $content->file = $request->youtube_link;
        }

        $content->save();

        return redirect()->route('admin.courses')->with('success', 'Course created successfully.');
    }

    public function editCourse($id) {
        $course = Course::with('contents')->findOrFail($id);
        $categories = Category::all();
        return view('admin.editCourse', compact('course', 'categories'));
    }

    public function updateCourse(Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'score' => 'required|integer|min:1',
            'category' => 'required|exists:categories,id',
            'content_type' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
            'youtube_link' => 'nullable|url',
        ]);

        $course = Course::findOrFail($id);
        $course->title = $request->title;
        $course->description = $request->description;
        $course->score = $request->score;
        $course->category_id = $request->category;
        $course->save();

        $content = $course->contents->first();

        if ($request->content_type === 'pdf' && $request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('pdfs' , 'public');
            $content->type = 'pdf';
            $content->file = $path;
        } elseif ($request->content_type === 'youtube') {
            $content->type = 'youtube';
            $content->file = $request->youtube_link;
        }

        $content->save();

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully.');
    }

    public function deleteCourse($id) {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully.');
    }

}
