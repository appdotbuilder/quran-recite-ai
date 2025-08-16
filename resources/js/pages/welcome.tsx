import React, { useState, useRef } from 'react';
import { Head } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Play, Pause, Mic, Square } from 'lucide-react';

interface Surah {
    id: number;
    number: number;
    name_arabic: string;
    name_english: string;
    name_indonesian: string;
    verses_count: number;
    revelation_type: string;
}

interface Verse {
    id: number;
    verse_number: number;
    text_arabic: string;
    text_english: string;
    text_indonesian: string;
    transliteration: string;
    audio_url?: string;
}

interface Qari {
    id: number;
    name: string;
    name_arabic?: string;
    country: string;
    description?: string;
    audio_base_url: string;
    is_featured: boolean;
}

interface Props {
    surahs: Surah[];
    qaris: Qari[];
    verses?: Verse[];
    currentSurah?: Surah;
    language: string;
    [key: string]: unknown;
}

export default function Welcome({ surahs, qaris, verses, currentSurah, language }: Props) {
    const [selectedLanguage, setSelectedLanguage] = useState(language);
    const [selectedQari, setSelectedQari] = useState<Qari | null>(qaris[0] || null);
    const [currentlyPlaying, setCurrentlyPlaying] = useState<number | null>(null);
    const [isRecording, setIsRecording] = useState(false);
    const [recordedAudio, setRecordedAudio] = useState<string | null>(null);
    const audioRef = useRef<HTMLAudioElement | null>(null);
    const mediaRecorderRef = useRef<MediaRecorder | null>(null);
    const recordedChunksRef = useRef<Blob[]>([]);

    const handleSurahChange = (surahId: string) => {
        router.get(`/?surah=${surahId}&lang=${selectedLanguage}`, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleLanguageChange = (newLanguage: string) => {
        setSelectedLanguage(newLanguage);
        const params = new URLSearchParams();
        params.set('lang', newLanguage);
        if (currentSurah) {
            params.set('surah', currentSurah.id.toString());
        }
        router.get(`/?${params.toString()}`, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const playAudio = async (verseNumber: number) => {
        if (currentlyPlaying === verseNumber) {
            audioRef.current?.pause();
            setCurrentlyPlaying(null);
            return;
        }

        if (selectedQari && currentSurah) {
            // Simulate audio URL (in real app, would be actual audio files)
            const audioUrl = `${selectedQari.audio_base_url}${String(currentSurah.number).padStart(3, '0')}${String(verseNumber).padStart(3, '0')}.mp3`;
            
            if (audioRef.current) {
                audioRef.current.pause();
            }

            audioRef.current = new Audio(audioUrl);
            audioRef.current.onended = () => setCurrentlyPlaying(null);
            audioRef.current.onerror = () => {
                console.log('Audio not available for this verse');
                setCurrentlyPlaying(null);
            };

            try {
                await audioRef.current.play();
                setCurrentlyPlaying(verseNumber);
            } catch (error) {
                console.log('Could not play audio:', error);
                setCurrentlyPlaying(null);
            }
        }
    };

    const startRecording = async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorderRef.current = new MediaRecorder(stream);
            recordedChunksRef.current = [];

            mediaRecorderRef.current.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    recordedChunksRef.current.push(event.data);
                }
            };

            mediaRecorderRef.current.onstop = () => {
                const blob = new Blob(recordedChunksRef.current, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(blob);
                setRecordedAudio(audioUrl);
                
                // Here you would typically send the blob to the backend for AI analysis
                console.log('Recording completed, would send to AI for analysis');
            };

            mediaRecorderRef.current.start();
            setIsRecording(true);
        } catch (error) {
            console.error('Error accessing microphone:', error);
        }
    };

    const stopRecording = () => {
        if (mediaRecorderRef.current && isRecording) {
            mediaRecorderRef.current.stop();
            mediaRecorderRef.current.stream.getTracks().forEach(track => track.stop());
            setIsRecording(false);
        }
    };

    const getTranslation = (verse: Verse) => {
        return selectedLanguage === 'indonesian' ? verse.text_indonesian : verse.text_english;
    };

    return (
        <>
            <Head title="Al-Quran Digital - Learn, Listen, and Perfect Your Recitation" />
            
            <div className="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100">
                {/* Header */}
                <div className="bg-white shadow-sm border-b">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center space-x-3">
                                <div className="text-3xl">📖</div>
                                <div>
                                    <h1 className="text-2xl font-bold text-gray-900">Al-Quran Digital</h1>
                                    <p className="text-sm text-gray-600">Learn, Listen, and Perfect Your Recitation</p>
                                </div>
                            </div>
                            
                            <div className="flex items-center space-x-4">
                                <Select value={selectedLanguage} onValueChange={handleLanguageChange}>
                                    <SelectTrigger className="w-32">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="english">🇺🇸 English</SelectItem>
                                        <SelectItem value="indonesian">🇮🇩 Indonesian</SelectItem>
                                    </SelectContent>
                                </Select>
                                
                                <Button onClick={() => router.get('/login')} variant="outline">
                                    Login
                                </Button>
                                <Button onClick={() => router.get('/register')}>
                                    Register
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {/* Welcome Section */}
                    {!currentSurah && (
                        <div className="text-center mb-12">
                            <h2 className="text-4xl font-bold text-gray-900 mb-4">
                                🕌 Welcome to Al-Quran Digital
                            </h2>
                            <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                                Experience the Holy Quran with advanced features: multilingual translations, 
                                audio recitations from famous Qaris, and AI-powered tajwid correction to perfect your recitation.
                            </p>
                            
                            <div className="grid md:grid-cols-3 gap-6 mb-8">
                                <Card className="p-6 text-center bg-gradient-to-b from-blue-50 to-blue-100">
                                    <div className="text-4xl mb-4">🌍</div>
                                    <h3 className="text-lg font-semibold mb-2">Multilingual Support</h3>
                                    <p className="text-gray-600 text-sm">Read in Arabic with English and Indonesian translations</p>
                                </Card>
                                
                                <Card className="p-6 text-center bg-gradient-to-b from-green-50 to-green-100">
                                    <div className="text-4xl mb-4">🎵</div>
                                    <h3 className="text-lg font-semibold mb-2">Beautiful Recitations</h3>
                                    <p className="text-gray-600 text-sm">Listen to renowned Qaris from around the world</p>
                                </Card>
                                
                                <Card className="p-6 text-center bg-gradient-to-b from-purple-50 to-purple-100">
                                    <div className="text-4xl mb-4">🤖</div>
                                    <h3 className="text-lg font-semibold mb-2">AI Tajwid Coach</h3>
                                    <p className="text-gray-600 text-sm">Get instant feedback on your recitation accuracy</p>
                                </Card>
                            </div>
                        </div>
                    )}

                    {/* Controls */}
                    <div className="grid md:grid-cols-2 gap-6 mb-8">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center space-x-2">
                                    <span>📚</span>
                                    <span>Select Surah</span>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Select value={currentSurah?.id.toString() || ""} onValueChange={handleSurahChange}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Choose a Surah to read..." />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {surahs.map((surah) => (
                                            <SelectItem key={surah.id} value={surah.id.toString()}>
                                                <div className="flex items-center justify-between w-full">
                                                    <span>{surah.number}. {selectedLanguage === 'indonesian' ? surah.name_indonesian : surah.name_english}</span>
                                                    <Badge variant="outline" className="ml-2">
                                                        {surah.verses_count} verses
                                                    </Badge>
                                                </div>
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center space-x-2">
                                    <span>🎤</span>
                                    <span>Select Qari</span>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Select 
                                    value={selectedQari?.id.toString() || ""} 
                                    onValueChange={(value) => {
                                        const qari = qaris.find(q => q.id.toString() === value);
                                        setSelectedQari(qari || null);
                                    }}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Choose a Qari..." />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {qaris.map((qari) => (
                                            <SelectItem key={qari.id} value={qari.id.toString()}>
                                                <div className="flex items-center justify-between w-full">
                                                    <span>{qari.name}</span>
                                                    <Badge variant="outline" className="ml-2">
                                                        {qari.country}
                                                    </Badge>
                                                </div>
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Verses Display */}
                    {verses && currentSurah && (
                        <Card className="mb-8">
                            <CardHeader>
                                <div className="flex items-center justify-between">
                                    <CardTitle className="text-2xl">
                                        {currentSurah.name_arabic} - {selectedLanguage === 'indonesian' ? currentSurah.name_indonesian : currentSurah.name_english}
                                    </CardTitle>
                                    <Badge variant={currentSurah.revelation_type === 'meccan' ? 'default' : 'secondary'}>
                                        {currentSurah.revelation_type === 'meccan' ? '🕋 Meccan' : '🏛️ Medinan'}
                                    </Badge>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-6">
                                    {verses.map((verse) => (
                                        <div key={verse.id} className="border-l-4 border-purple-200 pl-4 py-3">
                                            <div className="flex items-start justify-between mb-3">
                                                <Badge variant="outline" className="mb-2">
                                                    Verse {verse.verse_number}
                                                </Badge>
                                                <div className="flex space-x-2">
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() => playAudio(verse.verse_number)}
                                                        className="flex items-center space-x-1"
                                                    >
                                                        {currentlyPlaying === verse.verse_number ? (
                                                            <Pause className="w-4 h-4" />
                                                        ) : (
                                                            <Play className="w-4 h-4" />
                                                        )}
                                                        <span>Play</span>
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={isRecording ? stopRecording : startRecording}
                                                        className={`flex items-center space-x-1 ${isRecording ? 'bg-red-100 border-red-300' : ''}`}
                                                    >
                                                        {isRecording ? (
                                                            <Square className="w-4 h-4 text-red-600" />
                                                        ) : (
                                                            <Mic className="w-4 h-4" />
                                                        )}
                                                        <span>{isRecording ? 'Stop' : 'Record'}</span>
                                                    </Button>
                                                </div>
                                            </div>
                                            
                                            <div className="text-right text-2xl leading-relaxed font-arabic mb-4 text-gray-800">
                                                {verse.text_arabic}
                                            </div>
                                            
                                            <div className="text-gray-600 italic mb-2">
                                                {verse.transliteration}
                                            </div>
                                            
                                            <div className="text-gray-800 leading-relaxed">
                                                {getTranslation(verse)}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </Card>
                    )}

                    {/* AI Recording Feedback */}
                    {recordedAudio && (
                        <Card className="mb-8 bg-gradient-to-r from-green-50 to-green-100 border-green-200">
                            <CardHeader>
                                <CardTitle className="flex items-center space-x-2 text-green-800">
                                    <span>🤖</span>
                                    <span>AI Recitation Analysis</span>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-4">
                                    <audio controls src={recordedAudio} className="w-full" />
                                    
                                    <div className="bg-white p-4 rounded-lg border">
                                        <h4 className="font-semibold mb-2 text-green-800">📊 Analysis Results</h4>
                                        <div className="grid md:grid-cols-3 gap-4 mb-4">
                                            <div className="text-center">
                                                <div className="text-2xl font-bold text-green-600">87%</div>
                                                <div className="text-sm text-gray-600">Accuracy Score</div>
                                            </div>
                                            <div className="text-center">
                                                <div className="text-2xl font-bold text-blue-600">2</div>
                                                <div className="text-sm text-gray-600">Tajwid Issues</div>
                                            </div>
                                            <div className="text-center">
                                                <div className="text-2xl font-bold text-purple-600">1</div>
                                                <div className="text-sm text-gray-600">Pronunciation</div>
                                            </div>
                                        </div>
                                        
                                        <div className="space-y-3">
                                            <div>
                                                <strong className="text-green-700">✅ Strengths:</strong>
                                                <ul className="list-disc list-inside text-sm text-gray-600 ml-4">
                                                    <li>Clear pronunciation</li>
                                                    <li>Good rhythm and flow</li>
                                                </ul>
                                            </div>
                                            <div>
                                                <strong className="text-orange-700">⚠️ Areas for Improvement:</strong>
                                                <ul className="list-disc list-inside text-sm text-gray-600 ml-4">
                                                    <li>Work on elongation (Madd) at 0:15</li>
                                                    <li>Focus on letter exits (Makhraj) at 0:28</li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <Button 
                                            className="mt-4" 
                                            onClick={() => alert('Sign up to track your progress!')}
                                        >
                                            📈 Save Progress & Get Detailed Feedback
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    )}

                    {/* Call to Action */}
                    <Card className="text-center bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
                        <CardContent className="py-12">
                            <h3 className="text-3xl font-bold mb-4">🚀 Ready to Perfect Your Recitation?</h3>
                            <p className="text-xl mb-8 text-purple-100 max-w-2xl mx-auto">
                                Join thousands of Muslims improving their Quran recitation with our AI-powered platform. 
                                Track your progress, get personalized feedback, and learn from the best Qaris worldwide.
                            </p>
                            <div className="space-x-4">
                                <Button 
                                    size="lg" 
                                    variant="secondary" 
                                    onClick={() => router.get('/register')}
                                    className="text-purple-600"
                                >
                                    🎯 Start Learning Free
                                </Button>
                                <Button 
                                    size="lg" 
                                    variant="outline" 
                                    onClick={() => router.get('/login')}
                                    className="border-white text-white hover:bg-white hover:text-purple-600"
                                >
                                    📊 View My Progress
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}