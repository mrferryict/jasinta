<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AssignmentSeeder extends Seeder
{
   public function run()
   {
      $db = \Config\Database::connect();

      // Ambil semua mahasiswa yang sudah melewati minimal stage 4
      $students = $db->query("
        SELECT DISTINCT u.id AS student_id, t.id AS thesis_id
        FROM users u
        JOIN theses t ON t.student_id = u.id
        JOIN user_stages us ON us.user_id = u.id
        WHERE u.division = 'STUDENT'
        AND u.status = 'ACTIVE'
        AND us.stage_id >= 4
    ")->getResultArray();

      // Ambil daftar semua dosen
      $lecturers = $db->query("SELECT id FROM users WHERE division = 'LECTURER'")->getResultArray();
      $lecturerIds = array_column($lecturers, 'id'); // Ubah ke array ID

      $assignmentLecturersData = [];
      $number = 1;

      foreach ($students as $student) {
         if (count($lecturerIds) < 2) {
            continue; // Pastikan ada cukup dosen untuk dipilih
         }

         shuffle($lecturerIds); // Acak urutan dosen
         $supervisor1 = $lecturerIds[0]; // Dosen pembimbing materi
         $supervisor2 = $lecturerIds[1]; // Dosen pembimbing teknis

         // Buat entry di tabel assignments
         $assignmentsData = [
            'thesis_id' => $student['thesis_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'number' => str_pad($number++, 6, '0', STR_PAD_LEFT),
         ];

         $db->table('assignments')->insert($assignmentsData);
         $assignmentId = $db->insertID(); // Ambil ID terbaru yang baru saja dimasukkan

         // Tambahkan dosen pembimbing ke assignment_lecturers
         $assignmentLecturersData[] = [
            'assignment_id' => $assignmentId,
            'lecturer_id' => $supervisor1,
            'role' => 'MATERIAL_SUPERVISOR',
         ];
         $assignmentLecturersData[] = [
            'assignment_id' => $assignmentId,
            'lecturer_id' => $supervisor2,
            'role' => 'TECHNICAL_SUPERVISOR',
         ];
      }

      // Masukkan semua data dosen pembimbing sekaligus
      if (!empty($assignmentLecturersData)) {
         $db->table('assignment_lecturers')->insertBatch($assignmentLecturersData);
      }
   }
}
