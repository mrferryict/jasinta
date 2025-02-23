<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ThesesSeeder extends Seeder
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
         'Rancang Bangun Aplikasi Parkir di PT XYZ',
         'Sistam Pakar untuk Pengambilan Keputusan di Bursa Efek Jakarta',
      ];

      // Loop untuk 10 mahasiswa (student_id 1-10)
      for ($studentId = 2; $studentId <= 13; $studentId++) {
         $theses[] = [
            'title'       => $titles[$studentId - 2], // Ambil judul dari array
            'student_id'  => $studentId,
            'proposed_at'  => date('Y-m-d H:i:s'),
            'approved_at'  => date('Y-m-d H:i:s'),
         ];
      }

      // Insert data ke tabel thesis
      $this->db->table('theses')->insertBatch($theses);
   }
}
