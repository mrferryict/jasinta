<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AnnouncementModel;

class AnnouncementsSeeder extends Seeder
{
    public function run()
    {

        $data = [
            [
                'title' => 'UNDANGAN PENGARAHAN',
                'content' =>
                'Dengan hormat,
                    Sehubungan dengan
                    telah dimulainya Perkuliahan Semester Ganjil 2024/2025 dan telah
                    berlangsungnya proses perkuliahan. Dengan ini kami bermaksud mengundang
                    Mahasiswa/i STMIK Jayakarta untuk menghadiri acara yang akan diselenggarakan,
                    pada  :
                    Hari / Tanggal : Sabtu, 28 September 2024
                    Waktu : 13.00 – 15:00 Wib
                    Ruang : Aula 1 Lantai 4
                    Agenda : Pengarahan Skripsi & Tugas Akhir Semester Ganjil 2024/2025

                    Mengingat pentingnya acara di atas, diharapkan para Mahasiswa/i wajib
                    hadir pada acara tersebut. Demikian surat pemberitahuan ini kami sampaikan,
                    atas perhatian dan kerjasamanya, kami sampaikan terima kasih.',
                'creator_id' => 2,
                'created_at' => date('2024-09-27 12:10:33')
            ],
            [
                'title' => 'KETERLAMBATAN PROPOSAL',
                'content' => 'Diinformasikan kepada mahasiswa yang terlambat mengumpulkan proposal segera membuat Surat Pernyataan Terlambat dan menyerahkannya ke akademik, serta mengirimkan fotonya ke Pak Anton Sianipar via wa setelah terkonfirmasi maka bisa mengupload proposal di menu Upload Proposal Terlambat
                    Daftar dosen yang boleh dipilih (selain itu sudah full kuotanya)
                    1. Ito Riris Immasari, SKom,MMSi: 0812-9109-0816
                    2. Dimas Prasetyo, S.Kom, M.Kom : 0896-3629-7750
                    3. Rumadi Hartawan, ST, M.Kom : 0856-9743-9397
                    4. Teri Mengkasrinal, S.Kom, M.Kom : 0853-7667-3784
                    5. Tomi Loveri , S.Kom, M.Kom : 0852-7406-2001',
                'creator_id' => 2,
                'created_at' => date('2024-10-09 12:10:33')
            ],
            [
                'title' => 'PENGIRIMAN SK BIMBINGAN',
                'content' => 'Selamat Pagi Semuanya
                    SK Penunjukkan Pembimbing Materi dan Pembimbing Teknis sebagian sudah dikirim via email yang terdaftar
                    -) Bagi yang sudah mendapatkan SK segera komunikasi dengan Pembimbing Materi dan Pembimbing Teknis
                    -) Lakukan proses bimbingan dengan kedua dosen yang ada di SK
                    -) Lakukan Update tiap minggu di Google Classroom
                    -) Bagi yang belum mendapatkan SK segera konfirmasi via email : prodi@stmik.jayakarta.ac.id sesuai dengan catatan yang ada di dalam link yang di share
                    Terima kasih',
                'creator_id' => 2,
                'created_at' => date('2024-10-03 08:14:04')
            ],
        ];

        // Insert batch data ke dalam tabel settings
        $this->db->table('announcements')->insertBatch($data);
    }
}
