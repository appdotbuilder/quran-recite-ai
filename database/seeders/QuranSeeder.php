<?php

namespace Database\Seeders;

use App\Models\Qari;
use App\Models\Surah;
use App\Models\Verse;
use Illuminate\Database\Seeder;

class QuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some sample Qaris
        $qaris = [
            [
                'name' => 'Sheikh Abdul Rahman Al-Sudais',
                'name_arabic' => 'الشيخ عبد الرحمن السديس',
                'country' => 'Saudi Arabia',
                'description' => 'Imam of the Grand Mosque in Mecca',
                'audio_base_url' => 'https://example.com/audio/sudais/',
                'is_featured' => true,
            ],
            [
                'name' => 'Sheikh Saad Al-Ghamdi',
                'name_arabic' => 'الشيخ سعد الغامدي',
                'country' => 'Saudi Arabia',
                'description' => 'Renowned Qari with beautiful voice',
                'audio_base_url' => 'https://example.com/audio/ghamdi/',
                'is_featured' => true,
            ],
            [
                'name' => 'Sheikh Mishary Rashid Alafasy',
                'name_arabic' => 'الشيخ مشاري راشد العفاسي',
                'country' => 'Kuwait',
                'description' => 'Popular Qari known for his melodious recitation',
                'audio_base_url' => 'https://example.com/audio/alafasy/',
                'is_featured' => true,
            ],
        ];

        foreach ($qaris as $qari) {
            Qari::create($qari);
        }

        // Seed some sample Surahs
        $surahs = [
            [
                'number' => 1,
                'name_arabic' => 'الفاتحة',
                'name_english' => 'Al-Fatiha',
                'name_indonesian' => 'Pembuka',
                'verses_count' => 7,
                'revelation_type' => 'meccan',
                'description_english' => 'The Opening - The first chapter of the Quran',
                'description_indonesian' => 'Pembuka - Surah pertama dalam Al-Quran',
            ],
            [
                'number' => 2,
                'name_arabic' => 'البقرة',
                'name_english' => 'Al-Baqarah',
                'name_indonesian' => 'Sapi Betina',
                'verses_count' => 286,
                'revelation_type' => 'medinan',
                'description_english' => 'The Cow - The longest chapter in the Quran',
                'description_indonesian' => 'Sapi Betina - Surah terpanjang dalam Al-Quran',
            ],
            [
                'number' => 3,
                'name_arabic' => 'آل عمران',
                'name_english' => 'Ali Imran',
                'name_indonesian' => 'Keluarga Imran',
                'verses_count' => 200,
                'revelation_type' => 'medinan',
                'description_english' => 'The Family of Imran',
                'description_indonesian' => 'Keluarga Imran',
            ],
            [
                'number' => 112,
                'name_arabic' => 'الإخلاص',
                'name_english' => 'Al-Ikhlas',
                'name_indonesian' => 'Keikhlasan',
                'verses_count' => 4,
                'revelation_type' => 'meccan',
                'description_english' => 'The Sincerity - Declaration of the Unity of Allah',
                'description_indonesian' => 'Keikhlasan - Pernyataan tentang Keesaan Allah',
            ],
            [
                'number' => 113,
                'name_arabic' => 'الفلق',
                'name_english' => 'Al-Falaq',
                'name_indonesian' => 'Waktu Subuh',
                'verses_count' => 5,
                'revelation_type' => 'meccan',
                'description_english' => 'The Daybreak - Seeking refuge from evil',
                'description_indonesian' => 'Waktu Subuh - Memohon perlindungan dari kejahatan',
            ],
            [
                'number' => 114,
                'name_arabic' => 'الناس',
                'name_english' => 'An-Nas',
                'name_indonesian' => 'Manusia',
                'verses_count' => 6,
                'revelation_type' => 'meccan',
                'description_english' => 'The People - Protection from whispers of Satan',
                'description_indonesian' => 'Manusia - Perlindungan dari bisikan setan',
            ],
        ];

        foreach ($surahs as $surahData) {
            Surah::create($surahData);
        }

        // Seed verses for Al-Fatiha (complete)
        $fatihaVerses = [
            [
                'verse_number' => 1,
                'text_arabic' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'text_english' => 'In the name of Allah, the Most Gracious, the Most Merciful.',
                'text_indonesian' => 'Dengan nama Allah Yang Maha Pengasih, Maha Penyayang.',
                'transliteration' => 'Bismillahi arrahmani arraheem',
            ],
            [
                'verse_number' => 2,
                'text_arabic' => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                'text_english' => 'All praise is due to Allah, Lord of all the worlds.',
                'text_indonesian' => 'Segala puji bagi Allah, Tuhan seluruh alam.',
                'transliteration' => 'Alhamdu lillahi rabbi al-alameen',
            ],
            [
                'verse_number' => 3,
                'text_arabic' => 'الرَّحْمَٰنِ الرَّحِيمِ',
                'text_english' => 'The Most Gracious, the Most Merciful.',
                'text_indonesian' => 'Yang Maha Pengasih, Maha Penyayang.',
                'transliteration' => 'Arrahmani arraheem',
            ],
            [
                'verse_number' => 4,
                'text_arabic' => 'مَالِكِ يَوْمِ الدِّينِ',
                'text_english' => 'Master of the Day of Judgment.',
                'text_indonesian' => 'Pemilik hari pembalasan.',
                'transliteration' => 'Maliki yawmi addeen',
            ],
            [
                'verse_number' => 5,
                'text_arabic' => 'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ',
                'text_english' => 'You alone we worship, and You alone we ask for help.',
                'text_indonesian' => 'Hanya kepada Engkaulah kami menyembah dan hanya kepada Engkaulah kami mohon pertolongan.',
                'transliteration' => 'Iyyaka na\'budu wa-iyyaka nasta\'een',
            ],
            [
                'verse_number' => 6,
                'text_arabic' => 'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                'text_english' => 'Guide us to the straight path.',
                'text_indonesian' => 'Tunjukilah kami jalan yang lurus.',
                'transliteration' => 'Ihdina assirata almustaqeem',
            ],
            [
                'verse_number' => 7,
                'text_arabic' => 'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ',
                'text_english' => 'The path of those You have blessed, not of those who have incurred Your wrath, nor of those who have gone astray.',
                'text_indonesian' => 'Yaitu jalan orang-orang yang telah Engkau beri nikmat kepadanya; bukan (jalan) mereka yang dimurkai dan bukan (pula jalan) mereka yang sesat.',
                'transliteration' => 'Sirata allatheena an\'amta \'alayhim ghayri almaghdoobi \'alayhim wala addalleen',
            ],
        ];

        $fatiha = Surah::where('number', 1)->first();
        foreach ($fatihaVerses as $verseData) {
            Verse::create([
                'surah_id' => $fatiha->id,
                ...$verseData,
            ]);
        }

        // Seed verses for Al-Ikhlas (complete)
        $ikhlasVerses = [
            [
                'verse_number' => 1,
                'text_arabic' => 'قُلْ هُوَ اللَّهُ أَحَدٌ',
                'text_english' => 'Say: He is Allah, the One.',
                'text_indonesian' => 'Katakanlah: "Dia-lah Allah, Yang Maha Esa.',
                'transliteration' => 'Qul huwa Allahu ahad',
            ],
            [
                'verse_number' => 2,
                'text_arabic' => 'اللَّهُ الصَّمَدُ',
                'text_english' => 'Allah, the Eternal, Absolute.',
                'text_indonesian' => 'Allah adalah Tuhan yang bergantung kepada-Nya segala sesuatu.',
                'transliteration' => 'Allahu assamad',
            ],
            [
                'verse_number' => 3,
                'text_arabic' => 'لَمْ يَلِدْ وَلَمْ يُولَدْ',
                'text_english' => 'He begets not, nor is He begotten.',
                'text_indonesian' => 'Dia tiada beranak dan tiada pula diperanakkan.',
                'transliteration' => 'Lam yalid walam yoolad',
            ],
            [
                'verse_number' => 4,
                'text_arabic' => 'وَلَمْ يَكُنْ لَهُ كُفُوًا أَحَدٌ',
                'text_english' => 'And there is none like unto Him.',
                'text_indonesian' => 'Dan tidak ada seorangpun yang setara dengan Dia."',
                'transliteration' => 'Walam yakun lahu kufuwan ahad',
            ],
        ];

        $ikhlas = Surah::where('number', 112)->first();
        foreach ($ikhlasVerses as $verseData) {
            Verse::create([
                'surah_id' => $ikhlas->id,
                ...$verseData,
            ]);
        }
    }
}