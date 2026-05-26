<?php

namespace App\Services;

class ChatbotService
{
    /**
     * Pattern → [response, topic]
     */
    private array $patterns = [
        // Sapaan
        ['pattern' => '/halo|hai|hi|hello|selamat/i',
         'response' => "Halo! 👋 Saya EduBot, asisten kesehatan psikososialmu. Saya bisa membantu kamu tentang:\n\n1. 📱 Penggunaan gadget sehat\n2. 🧠 Kesehatan psikososial\n3. ⏰ Manajemen waktu\n4. 🌟 Aktivitas positif\n5. ❓ Pertanyaan umum (FAQ)\n\nMau tanya apa hari ini?",
         'topic'    => 'greeting'],

        // Gadget sehat
        ['pattern' => '/gadget|hp|handphone|smartphone|layar|screen|media sosial|sosmed|tiktok|instagram|youtube/i',
         'response' => "📱 **Tips Penggunaan Gadget Sehat:**\n\n• Batasi screen time maksimal **2-3 jam/hari** di luar keperluan belajar\n• Terapkan aturan **no gadget 1 jam sebelum tidur**\n• Gunakan fitur **Digital Wellbeing** untuk pantau penggunaan\n• Istirahatkan mata setiap 20 menit (aturan 20-20-20)\n• Matikan notifikasi media sosial saat belajar\n\nApa yang ingin kamu tahu lebih lanjut?",
         'topic'    => 'gadget'],

        // Kecanduan gadget
        ['pattern' => '/kecanduan|adiksi|susah berhenti|tidak bisa lepas|terus pegang/i',
         'response' => "🔄 **Mengatasi Kecanduan Gadget:**\n\n1. **Kenali pemicunya** – Kapan kamu cenderung mengambil HP?\n2. **Buat jadwal screen-free** – misalnya saat makan dan belajar\n3. **Cari aktivitas pengganti** – olahraga, hobi, atau ngobrol langsung\n4. **Aktifkan mode grayscale** – layar abu-abu mengurangi daya tarik\n5. **Bicara dengan orang tua atau guru BK** jika sudah sangat mengganggu\n\nKamu bisa mulai dari langkah kecil dulu ya! 💪",
         'topic'    => 'gadget'],

        // Tidur
        ['pattern' => '/tidur|insomnia|begadang|susah tidur|ngantuk/i',
         'response' => "😴 **Tips Tidur yang Berkualitas:**\n\n• Tidur **7-9 jam** setiap malam untuk remaja\n• Hindari gadget minimal **1 jam sebelum tidur** (cahaya biru ganggu melatonin)\n• Buat rutinitas tidur yang konsisten\n• Kamar tidur = zona bebas gadget\n• Hindari kafein sore/malam hari\n\nTidur cukup sangat penting untuk kesehatan mental dan belajar! 🌙",
         'topic'    => 'kesehatan'],

        // Stres & kesehatan mental
        ['pattern' => '/stres|stress|cemas|anxiet|takut|khawatir|sedih|depresi|mental|psikolog/i',
         'response' => "🧠 **Menjaga Kesehatan Psikososial:**\n\n• **Bicara** dengan orang yang kamu percaya\n• **Tulis jurnal** untuk ekspresikan perasaan\n• **Olahraga** minimal 30 menit/hari – terbukti kurangi stres\n• **Pernapasan dalam** – tarik napas 4 detik, tahan 4, hembuskan 6\n• **Batasi berita negatif** di media sosial\n\n> Jika kamu merasa sangat tertekan, jangan ragu konsultasi dengan guru BK atau orang tua ya! 💙",
         'topic'    => 'kesehatan'],

        // Manajemen waktu
        ['pattern' => '/waktu|jadwal|produktif|belajar|pr|tugas|deadline|manajemen/i',
         'response' => "⏰ **Manajemen Waktu untuk Pelajar:**\n\n**Teknik Pomodoro:**\n• Belajar 25 menit → istirahat 5 menit\n• Setelah 4 sesi, istirahat 15-30 menit\n\n**Tips lainnya:**\n• Buat to-do list malam hari untuk esok\n• Prioritaskan tugas dengan metode **Eisenhower Matrix**\n• Tetapkan jam belajar yang sama setiap hari\n• Matikan notifikasi HP saat belajar\n• Beri reward kecil setelah menyelesaikan tugas\n\nMau tahu lebih detail tentang teknik belajar tertentu? 📚",
         'topic'    => 'waktu'],

        // Aktivitas positif
        ['pattern' => '/aktivitas|olahraga|hobi|kegiatan|positif|bosan|jenuh/i',
         'response' => "🌟 **Aktivitas Positif yang Bisa Dicoba:**\n\n🏃 **Fisik:** jogging, bersepeda, renang, yoga\n🎨 **Kreatif:** menggambar, musik, memasak, kerajinan\n📖 **Intelektual:** baca buku, belajar bahasa baru, koding\n👥 **Sosial:** bergabung komunitas, volunteer, bantu teman\n🌿 **Relaksasi:** meditasi, berkebun, jalan-jalan di alam\n\nAktivitas positif bisa mengurangi ketergantungan pada gadget secara alami! Coba pilih satu yang menarik bagimu 😊",
         'topic'    => 'aktivitas'],

        // SDQ
        ['pattern' => '/sdq|strength|difficulties|kesulitan|kekuatan/i',
         'response' => "📋 **SDQ (Strengths and Difficulties Questionnaire):**\n\nSDQ adalah alat skrining perilaku yang mengukur:\n• **Emosi** – kecemasan, depresi\n• **Perilaku** – kenakalan, agresivitas\n• **Hiperaktivitas** – konsentrasi, impulsivitas\n• **Teman sebaya** – kemampuan bersosialisasi\n• **Perilaku prososial** – empati, kepedulian\n\nSkor total 0-15 = Normal, 16-19 = Borderline, 20-40 = Perlu perhatian\n\nSudah mengisi assessment SDQ? Cek hasilnya di menu Riwayat! 📊",
         'topic'    => 'assessment'],

        // PSC-17
        ['pattern' => '/psc|pediatric|symptom|gejala/i',
         'response' => "📋 **PSC-17 (Pediatric Symptom Checklist):**\n\nPSC-17 mengukur masalah psikososial pada anak/remaja dalam 3 subskala:\n• **Internalizing** – masalah emosi (sedih, cemas)\n• **Attention** – masalah perhatian dan konsentrasi\n• **Externalizing** – masalah perilaku (agresif)\n\nSkor total ≥15 atau salah satu subskala tinggi = perlu perhatian lebih\n\nAssessment ini membantu mendeteksi masalah sejak dini! 🔍",
         'topic'    => 'assessment'],

        // SAS-SV
        ['pattern' => '/sas|sassv|smartphone addiction|kecanduan hp/i',
         'response' => "📱 **SAS-SV (Smartphone Addiction Scale - Short Version):**\n\nAlat ukur kecanduan smartphone dengan 10 pertanyaan. Mengukur seberapa besar smartphone mengganggu kehidupan sehari-hari.\n\n**Cutoff skor:**\n• Laki-laki: ≥31 = indikasi kecanduan\n• Perempuan: ≥33 = indikasi kecanduan\n\nPenting diketahui lebih awal agar bisa ditangani! 💪",
         'topic'    => 'assessment'],

        // Hasil
        ['pattern' => '/hasil|skor|nilai|assessment|kuesioner|tes/i',
         'response' => "📊 **Tentang Hasil Assessmentmu:**\n\nHasil assessment EduCare menggabungkan 3 instrumen:\n• **SDQ** – kesehatan mental & perilaku\n• **PSC-17** – gejala psikososial\n• **SAS-SV** – adiksi smartphone\n\n**Level Risiko:**\n🟢 **Rendah** – kondisi baik, pertahankan!\n🟡 **Sedang** – perlu perhatian, edukasi aktif\n🔴 **Tinggi** – konsultasi guru BK\n🚨 **Sangat Tinggi** – rujuk profesional\n\nKamu bisa lihat hasil lengkap di menu **Riwayat** ya! 📋",
         'topic'    => 'assessment'],

        // Guru BK
        ['pattern' => '/guru bk|konselor|konseling|curhat/i',
         'response' => "👩‍🏫 **Kapan Harus ke Guru BK?**\n\nJangan ragu konsultasi dengan guru BK jika:\n• Merasa sangat stres atau tertekan\n• Punya masalah dengan teman atau keluarga\n• Nilai akademis turun drastis\n• Merasa tidak termotivasi berkepanjangan\n• Hasil assessment menunjukkan risiko sedang/tinggi\n\nGuru BK adalah sahabatmu, bukan hanya untuk yang \"bermasalah\"! Semua orang butuh dukungan 💙",
         'topic'    => 'konseling'],

        // Terima kasih
        ['pattern' => '/terima kasih|makasih|thanks|tq|oke|ok|mantap|bagus|keren/i',
         'response' => "Sama-sama! 😊 Senang bisa membantu. Jika ada pertanyaan lain seputar kesehatan psikososial atau penggunaan gadget, jangan sungkan tanya ya!\n\nIngat: **kesehatan mentalmu sama pentingnya dengan kesehatan fisik!** 💙",
         'topic'    => 'general'],
    ];

    public function respond(string $message): array
    {
        $message = trim($message);

        foreach ($this->patterns as $item) {
            if (preg_match($item['pattern'], $message)) {
                return [
                    'response' => $item['response'],
                    'topic'    => $item['topic'],
                ];
            }
        }

        // Default fallback
        return [
            'response' => "Hmm, saya belum familiar dengan pertanyaan itu 🤔\n\nCoba tanyakan tentang:\n• **Penggunaan gadget sehat**\n• **Manajemen waktu belajar**\n• **Kesehatan psikososial**\n• **Aktivitas positif**\n• **Assessment SDQ / PSC-17 / SAS-SV**\n\nAtau ketik **halo** untuk melihat menu lengkap! 😊",
            'topic'    => 'unknown',
        ];
    }

    public function quickReplies(): array
    {
        return [
            '📱 Tips gadget sehat',
            '⏰ Manajemen waktu',
            '🧠 Kesehatan mental',
            '🌟 Aktivitas positif',
            '📋 Tentang assessment',
            '👩‍🏫 Kapan ke guru BK?',
        ];
    }
}
