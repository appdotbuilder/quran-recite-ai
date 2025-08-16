import React, { useState, useRef } from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Progress } from '@/components/ui/progress';
import { Mic, Square, TrendingUp } from 'lucide-react';

interface Surah {
    id: number;
    number: number;
    name_arabic: string;
    name_english: string;
    name_indonesian: string;
    verses_count: number;
}

interface UserProgress {
    surah_id: number;
    verses_completed: number;
    average_accuracy: number;
    total_sessions: number;
    weak_areas: string[];
    last_practiced_at: string;
    surah: Surah;
}

interface Props {
    surahs: Surah[];
    userProgress: Record<number, UserProgress>;
    [key: string]: unknown;
}

export default function RecitationIndex({ surahs, userProgress }: Props) {
    const [selectedSurah, setSelectedSurah] = useState<Surah | null>(null);
    const [selectedVerse, setSelectedVerse] = useState<number>(1);
    const [isRecording, setIsRecording] = useState(false);
    const [recordedAudio, setRecordedAudio] = useState<string | null>(null);
    const [analysisResult, setAnalysisResult] = useState<{
        accuracy_score: number;
        ai_feedback?: {
            strengths?: string[];
            improvements?: string[];
        };
        tajwid_errors?: Array<{type: string; position: string; description: string}>;
        pronunciation_errors?: Array<{type: string; position: string; description: string}>;
    } | null>(null);
    const [isAnalyzing, setIsAnalyzing] = useState(false);
    
    const mediaRecorderRef = useRef<MediaRecorder | null>(null);
    const recordedChunksRef = useRef<Blob[]>([]);

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

            mediaRecorderRef.current.onstop = async () => {
                const blob = new Blob(recordedChunksRef.current, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(blob);
                setRecordedAudio(audioUrl);
                
                // Send to backend for AI analysis
                await analyzeRecitation(blob);
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

    const analyzeRecitation = async (audioBlob: Blob) => {
        if (!selectedSurah) return;
        
        setIsAnalyzing(true);
        
        const formData = new FormData();
        formData.append('audio_file', audioBlob, 'recitation.wav');
        formData.append('surah_id', selectedSurah.id.toString());
        formData.append('verse_number', selectedVerse.toString());

        try {
            const response = await fetch('/api/recitation', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });

            const result = await response.json();
            setAnalysisResult(result.session);
        } catch (error) {
            console.error('Error analyzing recitation:', error);
        } finally {
            setIsAnalyzing(false);
        }
    };

    const getAccuracyColor = (score: number) => {
        if (score >= 90) return 'text-green-600';
        if (score >= 80) return 'text-blue-600';
        if (score >= 70) return 'text-yellow-600';
        return 'text-red-600';
    };

    const getAccuracyBadgeColor = (score: number) => {
        if (score >= 90) return 'bg-green-100 text-green-800';
        if (score >= 80) return 'bg-blue-100 text-blue-800';
        if (score >= 70) return 'bg-yellow-100 text-yellow-800';
        return 'bg-red-100 text-red-800';
    };

    return (
        <AppShell>
            <Head title="Recitation Practice - Al-Quran Digital" />
            
            <div className="space-y-8">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">🎤 Recitation Practice</h1>
                        <p className="text-gray-600 mt-1">Practice your recitation and get AI-powered feedback</p>
                    </div>
                </div>

                {/* Progress Overview */}
                {Object.keys(userProgress).length > 0 && (
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-2">
                                <TrendingUp className="w-5 h-5" />
                                <span>Your Progress</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="grid md:grid-cols-3 gap-6">
                                {Object.values(userProgress).slice(0, 3).map((progress) => (
                                    <div key={progress.surah_id} className="space-y-2">
                                        <div className="flex items-center justify-between">
                                            <span className="font-medium">{progress.surah.name_english}</span>
                                            <Badge className={getAccuracyBadgeColor(progress.average_accuracy)}>
                                                {progress.average_accuracy}%
                                            </Badge>
                                        </div>
                                        <Progress 
                                            value={(progress.verses_completed / progress.surah.verses_count) * 100} 
                                            className="h-2"
                                        />
                                        <div className="text-sm text-gray-600">
                                            {progress.verses_completed}/{progress.surah.verses_count} verses • {progress.total_sessions} sessions
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Practice Section */}
                <div className="grid lg:grid-cols-2 gap-8">
                    {/* Selection Panel */}
                    <Card>
                        <CardHeader>
                            <CardTitle>📚 Select Practice Material</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Choose Surah
                                </label>
                                <Select 
                                    value={selectedSurah?.id.toString() || ""} 
                                    onValueChange={(value) => {
                                        const surah = surahs.find(s => s.id.toString() === value);
                                        setSelectedSurah(surah || null);
                                        setSelectedVerse(1);
                                    }}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select a Surah to practice..." />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {surahs.map((surah) => (
                                            <SelectItem key={surah.id} value={surah.id.toString()}>
                                                <div className="flex items-center justify-between w-full">
                                                    <span>{surah.number}. {surah.name_english}</span>
                                                    <Badge variant="outline" className="ml-2">
                                                        {surah.verses_count} verses
                                                    </Badge>
                                                </div>
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            {selectedSurah && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Verse Number
                                    </label>
                                    <Select 
                                        value={selectedVerse.toString()} 
                                        onValueChange={(value) => setSelectedVerse(parseInt(value))}
                                    >
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {Array.from({ length: selectedSurah.verses_count }, (_, i) => i + 1).map((verse) => (
                                                <SelectItem key={verse} value={verse.toString()}>
                                                    Verse {verse}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            )}

                            {selectedSurah && (
                                <div className="mt-6 p-4 bg-blue-50 rounded-lg">
                                    <h3 className="font-medium text-blue-900 mb-2">
                                        {selectedSurah.name_arabic} - {selectedSurah.name_english}
                                    </h3>
                                    <p className="text-sm text-blue-700">
                                        Ready to practice verse {selectedVerse} of {selectedSurah.verses_count}
                                    </p>
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    {/* Recording Panel */}
                    <Card>
                        <CardHeader>
                            <CardTitle>🎙️ Record Your Recitation</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            {!selectedSurah ? (
                                <div className="text-center py-8 text-gray-500">
                                    <Mic className="w-12 h-12 mx-auto mb-4 text-gray-400" />
                                    <p>Select a Surah and verse to start practicing</p>
                                </div>
                            ) : (
                                <>
                                    <div className="text-center py-6">
                                        <Button
                                            size="lg"
                                            onClick={isRecording ? stopRecording : startRecording}
                                            className={`${
                                                isRecording 
                                                    ? 'bg-red-500 hover:bg-red-600' 
                                                    : 'bg-blue-600 hover:bg-blue-700'
                                            } text-white`}
                                        >
                                            {isRecording ? (
                                                <>
                                                    <Square className="w-5 h-5 mr-2" />
                                                    Stop Recording
                                                </>
                                            ) : (
                                                <>
                                                    <Mic className="w-5 h-5 mr-2" />
                                                    Start Recording
                                                </>
                                            )}
                                        </Button>
                                    </div>

                                    {isRecording && (
                                        <div className="text-center">
                                            <div className="animate-pulse text-red-600 font-medium">
                                                🔴 Recording in progress...
                                            </div>
                                            <p className="text-sm text-gray-600 mt-2">
                                                Recite verse {selectedVerse} clearly
                                            </p>
                                        </div>
                                    )}

                                    {recordedAudio && !isAnalyzing && (
                                        <div className="space-y-4">
                                            <div className="text-center">
                                                <audio controls src={recordedAudio} className="mx-auto" />
                                            </div>
                                            <Button
                                                variant="outline"
                                                onClick={() => {
                                                    setRecordedAudio(null);
                                                    setAnalysisResult(null);
                                                }}
                                                className="w-full"
                                            >
                                                Record Again
                                            </Button>
                                        </div>
                                    )}

                                    {isAnalyzing && (
                                        <div className="text-center py-6">
                                            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                            <p className="text-blue-600 font-medium">🤖 AI is analyzing your recitation...</p>
                                            <p className="text-sm text-gray-600 mt-1">This may take a few moments</p>
                                        </div>
                                    )}
                                </>
                            )}
                        </CardContent>
                    </Card>
                </div>

                {/* Analysis Results */}
                {analysisResult && (
                    <Card className="border-green-200 bg-green-50">
                        <CardHeader>
                            <CardTitle className="text-green-800">🤖 AI Analysis Results</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="grid md:grid-cols-4 gap-4 mb-6">
                                <div className="text-center p-4 bg-white rounded-lg">
                                    <div className={`text-3xl font-bold ${getAccuracyColor(analysisResult.accuracy_score)}`}>
                                        {analysisResult.accuracy_score}%
                                    </div>
                                    <div className="text-sm text-gray-600">Overall Score</div>
                                </div>
                                
                                <div className="text-center p-4 bg-white rounded-lg">
                                    <div className="text-3xl font-bold text-orange-600">
                                        {analysisResult.tajwid_errors?.length || 0}
                                    </div>
                                    <div className="text-sm text-gray-600">Tajwid Issues</div>
                                </div>
                                
                                <div className="text-center p-4 bg-white rounded-lg">
                                    <div className="text-3xl font-bold text-red-600">
                                        {analysisResult.pronunciation_errors?.length || 0}
                                    </div>
                                    <div className="text-sm text-gray-600">Pronunciation</div>
                                </div>
                                
                                <div className="text-center p-4 bg-white rounded-lg">
                                    <div className="text-3xl font-bold text-blue-600">A+</div>
                                    <div className="text-sm text-gray-600">Grade</div>
                                </div>
                            </div>

                            {analysisResult.ai_feedback && (
                                <div className="space-y-4">
                                    <div className="bg-white p-4 rounded-lg">
                                        <h4 className="font-semibold text-green-700 mb-2">✅ Strengths</h4>
                                        <ul className="list-disc list-inside text-sm text-gray-600 space-y-1">
                                            {analysisResult.ai_feedback.strengths?.map((strength: string, index: number) => (
                                                <li key={index}>{strength}</li>
                                            ))}
                                        </ul>
                                    </div>

                                    {(analysisResult.ai_feedback?.improvements?.length ?? 0) > 0 && (
                                        <div className="bg-white p-4 rounded-lg">
                                            <h4 className="font-semibold text-orange-700 mb-2">💡 Areas for Improvement</h4>
                                            <ul className="list-disc list-inside text-sm text-gray-600 space-y-1">
                                                {analysisResult.ai_feedback?.improvements?.map((improvement: string, index: number) => (
                                                    <li key={index}>{improvement}</li>
                                                ))}
                                            </ul>
                                        </div>
                                    )}

                                    {((analysisResult.tajwid_errors?.length ?? 0) > 0 || (analysisResult.pronunciation_errors?.length ?? 0) > 0) && (
                                        <div className="bg-white p-4 rounded-lg">
                                            <h4 className="font-semibold text-red-700 mb-2">🎯 Specific Issues</h4>
                                            <div className="space-y-2">
                                                {analysisResult.tajwid_errors?.map((error, index: number) => (
                                                    <div key={index} className="text-sm">
                                                        <Badge variant="outline" className="mr-2">{error.position}</Badge>
                                                        <span className="text-orange-600">{error.type}:</span> {error.description}
                                                    </div>
                                                ))}
                                                {analysisResult.pronunciation_errors?.map((error, index: number) => (
                                                    <div key={index} className="text-sm">
                                                        <Badge variant="outline" className="mr-2">{error.position}</Badge>
                                                        <span className="text-red-600">{error.type}:</span> {error.description}
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            )}

                            <div className="mt-6 flex space-x-4">
                                <Button 
                                    onClick={() => {
                                        setRecordedAudio(null);
                                        setAnalysisResult(null);
                                    }}
                                >
                                    Practice Again
                                </Button>
                                <Button 
                                    variant="outline"
                                    onClick={() => {
                                        if (selectedVerse < (selectedSurah?.verses_count || 1)) {
                                            setSelectedVerse(selectedVerse + 1);
                                            setRecordedAudio(null);
                                            setAnalysisResult(null);
                                        }
                                    }}
                                    disabled={selectedVerse >= (selectedSurah?.verses_count || 1)}
                                >
                                    Next Verse
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppShell>
    );
}