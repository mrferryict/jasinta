<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExaminersSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Ambil semua mahasiswa yang sudah melewati minimal stage 10
        $students = $db->query("
        SELECT DISTINCT u.id AS student_id, t.id AS thesis_id, u.major_id
        FROM users u
        JOIN theses t ON t.student_id = u.id
        JOIN user_stages us ON us.user_id = u.id
        WHERE u.division = 'STUDENT'
        AND u.status = 'ACTIVE'
        AND us.stage_id >= 10
    ")->getResultArray();

        // Ambil daftar semua dosen
        $lecturers = $db->query("SELECT id FROM users WHERE division = 'LECTURER'")->getResultArray();
        $lecturerIds = array_column($lecturers, 'id'); // Ubah ke array ID

        $assignmentLecturersData = [];

        foreach ($students as $student) {
            // Tentukan jumlah dosen penguji berdasarkan program studi
            $majorId = $student['major_id'];
            $major = $db->table('majors')->where('id', $majorId)->get()->getRowArray();
            $isD3 = strpos($major['name'], 'D3') !== false; // Cek apakah program studi D3
            $numExaminers = $isD3 ? 2 : 3; // 2 untuk D3, 3 untuk S1

            if (count($lecturerIds) < $numExaminers) {
                continue; // Pastikan ada cukup dosen untuk dipilih
            }

            shuffle($lecturerIds); // Acak urutan dosen

            // Generate nomor acak 6 digit yang unik
            $uniqueNumber = $this->generateUniqueNumber($db);

            // Buat entry di tabel assignments
            $assignmentsData = [
                'thesis_id' => $student['thesis_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'number' => $uniqueNumber, // Gunakan nomor acak unik
            ];

            $db->table('assignments')->insert($assignmentsData);
            $assignmentId = $db->insertID(); // Ambil ID terbaru yang baru saja dimasukkan

            // Tambahkan dosen penguji ke assignment_lecturers
            $assignmentLecturersData[] = [
                'assignment_id' => $assignmentId,
                'lecturer_id' => $lecturerIds[0],
                'role' => 'EXAMINER_CHIEF', // Dosen Penguji Ketua
            ];

            for ($i = 1; $i < $numExaminers; $i++) {
                $assignmentLecturersData[] = [
                    'assignment_id' => $assignmentId,
                    'lecturer_id' => $lecturerIds[$i],
                    'role' => 'EXAMINER_MEMBER', // Dosen Penguji Anggota
                ];
            }
        }

        // Masukkan semua data dosen penguji sekaligus
        if (!empty($assignmentLecturersData)) {
            $db->table('assignment_lecturers')->insertBatch($assignmentLecturersData);
        }
    }

    /**
     * Fungsi untuk menghasilkan nomor acak 6 digit yang unik.
     *
     * @param object $db Koneksi database
     * @return string Nomor acak 6 digit yang unik
     */
    private function generateUniqueNumber($db)
    {
        do {
            // Generate nomor acak 6 digit
            $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // Cek apakah nomor sudah digunakan di tabel assignments
            $exists = $db->table('assignments')
                ->where('number', $randomNumber)
                ->countAllResults();
        } while ($exists > 0); // Ulangi jika nomor sudah digunakan

        return $randomNumber;
    }
}
