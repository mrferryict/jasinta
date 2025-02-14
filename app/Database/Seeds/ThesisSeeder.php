<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ThesisSeeder extends Seeder
{
   public function run()
   {
      $theses = [];

      // Daftar judul tugas akhir dalam bahasa Indonesia
      $titles = [
         'Analisis Sistem Informasi Manajemen Sekolah Berbasis Web',
         'Rancang Bangun Aplikasi E-Commerce untuk UMKM',
         'Implementasi Algoritma Machine Learning untuk Prediksi Harga Saham',
         'Pengembangan Sistem Monitoring Kualitas Air Berbasis IoT',
         'Perancangan Aplikasi Manajemen Proyek Berbasis Agile',
         'Sistem Deteksi Plagiarisme pada Dokumen Teks Menggunakan Algoritma Rabin-Karp',
         'Aplikasi Pembelajaran Bahasa Inggris Berbasis Mobile untuk Anak-Anak',
         'Sistem Rekomendasi Buku Berdasarkan Minat Pembaca Menggunakan Collaborative Filtering',
         'Pengembangan Aplikasi Manajemen Inventaris Barang Berbasis Web',
         'Analisis Sentimen pada Media Sosial Twitter Menggunakan Algoritma Naive Bayes',
      ];

      // Loop untuk 10 mahasiswa (student_id 1-10)
      for ($studentId = 6; $studentId <= 15; $studentId++) {
         $theses[] = [
            'title'       => $titles[$studentId - 6], // Ambil judul dari array
            'student_id'  => $studentId,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => null,
            'deleted_at'  => null,
         ];
      }

      // Insert data ke tabel thesis
      $this->db->table('thesis')->insertBatch($theses);
   }
}
