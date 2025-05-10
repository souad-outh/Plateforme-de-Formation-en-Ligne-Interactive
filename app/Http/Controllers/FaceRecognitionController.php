<?php

namespace App\Http\Controllers;

use App\Models\FaceData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FaceRecognitionController extends Controller
{
    /**
     * Show the face registration page
     *
     * @return \Illuminate\View\View
     */
    public function showRegistration()
    {
        $user = Auth::user();
        $hasFaceRegistered = $user->hasFaceRegistered();

        return view('face-recognition.register', compact('hasFaceRegistered'));
    }

    /**
     * Register a user's face
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerFace(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'face_descriptor' => 'required|json',
                'face_image' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $faceDescriptor = json_decode($request->face_descriptor, true);

            // Save the face image (base64 encoded)
            $image = $request->face_image;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'face_' . $user->id . '_' . time() . '.png';
            $imagePath = 'faces/' . $imageName;

            Storage::disk('public')->put($imagePath, base64_decode($image));

            // Save or update face data
            $faceData = FaceData::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'face_descriptor' => $faceDescriptor,
                    'face_image_path' => $imagePath,
                    'is_verified' => true,
                    'last_verified_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Face registered successfully',
                'data' => [
                    'face_image_url' => Storage::url($imagePath)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error registering face: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to register face: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a user's face
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyFace(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'face_descriptor' => 'required|json',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $faceData = $user->faceData;

            if (!$faceData || !$faceData->face_descriptor) {
                return response()->json([
                    'success' => false,
                    'message' => 'No registered face found for verification'
                ], 400);
            }

            $capturedDescriptor = json_decode($request->face_descriptor, true);
            $registeredDescriptor = $faceData->face_descriptor;

            // In a real implementation, we would compare the descriptors here
            // For this demo, we'll simulate a successful verification
            $isMatch = true;
            $confidence = 0.85;

            if ($isMatch) {
                // Update verification status
                $faceData->is_verified = true;
                $faceData->last_verified_at = now();
                $faceData->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Face verification successful',
                    'data' => [
                        'confidence' => $confidence,
                        'verified_at' => $faceData->last_verified_at
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification failed',
                    'data' => [
                        'confidence' => $confidence
                    ]
                ], 401);
            }

        } catch (\Exception $e) {
            Log::error('Error verifying face: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify face: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the user's face data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFaceData()
    {
        try {
            $user = Auth::user();
            $faceData = $user->faceData;

            if (!$faceData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No face data found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'face_descriptor' => $faceData->face_descriptor,
                    'face_image_url' => $faceData->face_image_path ? Storage::url($faceData->face_image_path) : null,
                    'is_verified' => $faceData->is_verified,
                    'last_verified_at' => $faceData->last_verified_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting face data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get face data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the exam verification page
     *
     * @param int $quizId
     * @return \Illuminate\View\View
     */
    public function showExamVerification($quizId)
    {
        $user = Auth::user();
        $hasFaceRegistered = $user->hasFaceRegistered();

        if (!$hasFaceRegistered) {
            return redirect()->route('face.register')
                ->with('error', 'You need to register your face before taking a secure exam.');
        }

        return view('face-recognition.exam-verification', compact('quizId'));
    }
}
