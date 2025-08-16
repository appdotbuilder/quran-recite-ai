<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Qari;
use App\Models\Surah;
use App\Models\Verse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuranController extends Controller
{
    /**
     * Display the main Quran page.
     */
    public function index(Request $request)
    {
        $language = $request->get('lang', 'english');
        $selectedSurah = $request->get('surah');
        
        $surahs = Surah::orderBy('number')->get();
        $featuredQaris = Qari::featured()->limit(5)->get();
        
        $verses = null;
        $currentSurah = null;
        
        if ($selectedSurah) {
            $currentSurah = Surah::with('verses')->find($selectedSurah);
            $verses = $currentSurah?->verses()->orderBy('verse_number')->get();
        }
        
        return Inertia::render('welcome', [
            'surahs' => $surahs,
            'qaris' => $featuredQaris,
            'verses' => $verses,
            'currentSurah' => $currentSurah,
            'language' => $language,
        ]);
    }

    /**
     * Get verses for a specific surah.
     */
    public function show(Surah $surah, Request $request)
    {
        $language = $request->get('lang', 'english');
        
        $verses = $surah->verses()->orderBy('verse_number')->get();
        $surahs = Surah::orderBy('number')->get();
        $featuredQaris = Qari::featured()->limit(5)->get();
        
        return Inertia::render('welcome', [
            'surahs' => $surahs,
            'qaris' => $featuredQaris,
            'verses' => $verses,
            'currentSurah' => $surah,
            'language' => $language,
        ]);
    }


}