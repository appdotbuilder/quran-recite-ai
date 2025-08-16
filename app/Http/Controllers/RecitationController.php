<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecitationSession;
use App\Models\Surah;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RecitationController extends Controller
{
    /**
     * Display recitation practice page.
     */
    public function index()
    {
        $surahs = Surah::orderBy('number')->get();
        $userProgress = [];
        
        if (Auth::check()) {
            $userProgress = UserProgress::where('user_id', Auth::id())
                ->with('surah')
                ->get()
                ->keyBy('surah_id');
        }
        
        return Inertia::render('recitation/index', [
            'surahs' => $surahs,
            'userProgress' => $userProgress,
        ]);
    }

    /**
     * Store a new recitation session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'surah_id' => 'required|exists:surahs,id',
            'verse_number' => 'required|integer|min:1',
            'audio_file' => 'required|file|mimes:wav,mp3,m4a|max:10240',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $audioFile = $request->file('audio_file');
        $filename = 'recitations/' . Auth::id() . '/' . time() . '.' . $audioFile->getClientOriginalExtension();
        $path = $audioFile->storeAs('public', $filename);

        // Simulate AI analysis (in real app, this would call an AI service)
        $accuracyScore = random_int(70, 95);
        
        $feedback = [
            'overall' => 'Good recitation! Keep practicing to improve your tajwid.',
            'strengths' => ['Clear pronunciation', 'Good rhythm'],
            'improvements' => ['Work on elongation', 'Focus on letter exits'],
        ];

        $tajwidErrors = [];
        $pronunciationErrors = [];

        // Simulate some random errors for demonstration
        if ($accuracyScore < 85) {
            $tajwidErrors = [
                ['type' => 'ghunnah', 'position' => '0:15', 'description' => 'Missing nasal sound'],
                ['type' => 'madd', 'position' => '0:32', 'description' => 'Elongation too short'],
            ];
        }

        if ($accuracyScore < 80) {
            $pronunciationErrors = [
                ['type' => 'makhraj', 'position' => '0:28', 'description' => 'Letter exit point incorrect'],
            ];
        }

        $aiAnalysis = [
            'accuracy_score' => $accuracyScore,
            'feedback' => $feedback,
            'tajwid_errors' => $tajwidErrors,
            'pronunciation_errors' => $pronunciationErrors,
        ];

        $session = RecitationSession::create([
            'user_id' => Auth::id(),
            'surah_id' => $request->surah_id,
            'verse_number' => $request->verse_number,
            'audio_file_path' => $path,
            'ai_feedback' => $aiAnalysis['feedback'],
            'accuracy_score' => $aiAnalysis['accuracy_score'],
            'tajwid_errors' => $aiAnalysis['tajwid_errors'],
            'pronunciation_errors' => $aiAnalysis['pronunciation_errors'],
            'status' => 'analyzed',
        ]);

        // Update user progress using the update method
        $this->update(new Request(), $session);

        return response()->json([
            'session' => $session,
            'message' => 'Recitation analyzed successfully!'
        ]);
    }

    /**
     * Show user's recitation history.
     */
    public function show(string $userId = 'history')
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        if ($userId === 'history') {
            $sessions = RecitationSession::where('user_id', Auth::id())
                ->with('surah')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json($sessions);
        }

        // For specific user (admin functionality)
        $sessions = RecitationSession::where('user_id', $userId)
            ->with('surah')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($sessions);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecitationSession $session)
    {
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $session->user_id,
                'surah_id' => $session->surah_id,
            ],
            [
                'verses_completed' => 0,
                'average_accuracy' => 0,
                'total_sessions' => 0,
                'weak_areas' => [],
            ]
        );

        // Update statistics
        $progress->total_sessions += 1;
        $progress->last_practiced_at = now();

        // Recalculate average accuracy
        $allSessions = RecitationSession::where('user_id', $session->user_id)
            ->where('surah_id', $session->surah_id)
            ->whereNotNull('accuracy_score')
            ->get();

        $totalAccuracy = $allSessions->sum('accuracy_score');
        $sessionCount = $allSessions->count();
        $progress->average_accuracy = $sessionCount > 0 ? intval($totalAccuracy / $sessionCount) : 0;

        // Update weak areas based on errors
        $weakAreas = [];
        if (!empty($session->tajwid_errors)) {
            foreach ($session->tajwid_errors as $error) {
                $weakAreas[] = $error['type'];
            }
        }
        if (!empty($session->pronunciation_errors)) {
            foreach ($session->pronunciation_errors as $error) {
                $weakAreas[] = $error['type'];
            }
        }
        $progress->weak_areas = array_unique(array_merge($progress->weak_areas ?? [], $weakAreas));

        $progress->save();

        return response()->json(['message' => 'Progress updated successfully']);
    }
}