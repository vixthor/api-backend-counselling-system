<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\DeepSeekController;
use App\Http\Controllers\Api\GeminiController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CounselorController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ConversationController;

// Route::post('/students/schedule', [StudentController::class, 'scheduleAppointment']);
Route::post('/counselors/{counselor}/availability', [CounselorController::class, 'setAvailability']);
Route::get('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::get('/appointments/{id}', [AppointmentController::class, 'showAppointment']);
Route::get('/appointmentslist', [AppointmentController::class, 'index']);
// Route::post('/conversations/start', [ConversationController::class, 'start']);
// Route::post('/ai/process', [AIController::class, 'processInput']);

// Route::post('/ask', [GeminiController::class, 'askGemini']);

Route::post('/ask-openai', [AIController::class, 'askOpenAI']);
Route::post('/ask-gemini', [AIController::class, 'askGemini']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/messages/students-with-history/{counselorId}', [MessageController::class, 'studentsWithHistory']);

Route::get('/students/{id}', [StudentController::class, 'show']);
// Route::prefix('conversations')->group(function () {
//     Route::post('/start', [ConversationController::class, 'startConversation']); // Start a conversation
//     Route::get('/{id}', [ConversationController::class, 'getConversation']); // Get conversation details
//     Route::post('/send-message', [ConversationController::class, 'sendMessage']); // Send a message
//     Route::get('/{id}/messages', [ConversationController::class, 'getMessages']); // Get messages
// });
// Route::middleware(['auth:api'])->group(function () {
//     Route::get('/goals', [GoalController::class, 'index']); // Get all goals
//     Route::post('/goals', [GoalController::class, 'store']); // Create a new goal
//     Route::put('/goals/{id}', [GoalController::class, 'update']); // Update a goal
//     Route::delete('/goals/{id}', [GoalController::class, 'destroy']); // Delete a goal
//     Route::get('/goals/{id}/progress', [GoalController::class, 'trackProgress']); // Track progress
// });
// Route::post('/ai/gemini-25', [AIController::class, 'gemini25Chat']);

// Route::post('/chat', [AIController::class, 'chat'])->middleware('throttle:ai-chat');;

Route::get('/counselors', [CounselorController::class, 'index']);
Route::get('/counselors/{id}', [CounselorController::class, 'show']);
Route::patch('/counselors/{id}/status', [CounselorController::class, 'updateStatus']);

Route::post('/messages', [MessageController::class, 'send']);
Route::get('/messages/{user_id}', [MessageController::class, 'index']);
Route::middleware('auth:sanctum')->patch('/student/settings/{id}', [AuthController::class, 'updateStudentSettingsById']);
// Route::post('/deepseek/chat', [DeepSeekController::class, 'chat']);
// Route::prefix('auth')->group(function () {
//     Route::post('login', [AuthController::class, 'login']);

//     Route::middleware('auth:sanctum')->group(function () {
//         Route::get('me', [AuthController::class, 'me']);
//         Route::post('logout', [AuthController::class, 'logout']);
//     });
// });
Route::get('/appointments/user/{id}', [AppointmentController::class, 'indexById']);
Route::patch('/appointments/{id}', [AppointmentController::class, 'update']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);