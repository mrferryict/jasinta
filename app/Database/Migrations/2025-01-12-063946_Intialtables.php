<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Initialtables extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        $db->transStart(); // ✅ Mulai transaksi

        // TABEL CI_SESSIONS (internal CodeIgniter)
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'ID session (primary key)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address user'],
            'timestamp' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Waktu session terakhir diakses'],
            'data' => ['type' => 'TEXT', 'null' => false, 'comment' => 'Data session']
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('timestamp');
        $this->forge->createTable('ci_sessions', true);

        // TABEL SETTINGS (menyimpan nilai pengaturan aplikasi)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID setting (primary key)'],
            'key' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Key setting'],
            'value' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Value setting'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('settings');

        // TABEL SEMESTERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID semester (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'comment' => 'Nama semester (e.g., Ganjil 2024/2025)'],
            'status' => ['type' => 'ENUM', 'constraint' => ['ACTIVE', 'INACTIVE'], 'default' => 'ACTIVE', 'comment' => 'Status semester aktif atau tidak'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('semesters');

        // TABEL MAJORS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID jurusan (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'comment' => 'Nama jurusan (e.g., S1 Teknik Informatika)'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('majors');

        // TABEL STAGES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'comment' => 'ID stage (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'comment' => "Jenis stages  ['PENDAFTARAN', 'SYARAT PROPOSAL', 'PROPOSAL', 'BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'SYARAT SIDANG', 'SIDANG', 'REVISI', 'SYARAT AKHIR', 'SKLS']"],
            'order' => ['type' => 'INT', 'comment' => 'Urutan tahapan'],
            'deadline_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu deadline stage'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('stages');

        // TABEL USERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID user (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'comment' => 'Nama lengkap user'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'comment' => 'Alamat email'],
            'number' => ['type' => 'VARCHAR', 'constraint' => 8, 'comment' => 'NIM (jika student), NIDN (jika lecturer), atau NIP (jika admin)'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Password user (terenkripsi)'],
            'division' => ['type' => 'ENUM', 'constraint' => ['STUDENT', 'LECTURER', 'ADMIN'], 'default' => 'STUDENT', 'null' => false, 'comment' => 'Divisi person (student, lecturer, atau admin)'],
            'major_id' => ['type' => 'INT', 'null' => true, 'comment' => 'ID jurusan (foreign key ke tabel majors)'],
            'semester_id' => ['type' => 'INT', 'null' => true, 'comment' => 'ID semester (foreign key ke tabel semesters)'],
            'academic_status' => ['type' => 'ENUM', 'constraint' => ['NEW', 'REPEAT'], 'default' => 'NEW', 'comment' => 'Status mahasiswa mengulang atau baru'],
            'status' => ['type' => 'ENUM', 'constraint' => ['ACTIVE', 'INACTIVE'], 'default' => 'ACTIVE', 'null' => false, 'comment' => 'Status user aktif atau tidak'],
            'token' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true, 'comment' => 'Token untuk verifikasi atau reset password'],
            'token_expired_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu token kadaluarsa'],
            'verified_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu verifikasi email'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('number');
        $this->forge->addForeignKey('major_id', 'majors', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('semester_id', 'semesters', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('users');

        // TABEL COORDINATORS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID coordinator (primary key)'],
            'major_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID major (foreign key ke tabel majors)'],
            'lecturer_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID user (foreign key ke tabel users)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('major_id', 'majors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lecturer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('coordinators');

        // TABEL THESES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID Tugas Akhir (primary key)'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Judul Tugas Akhir'],
            'student_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID student (foreign key ke tabel users)'],
            'supervisor1_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID dosen pembimbing pilihan 1 (foreign key ke tabel users)'],
            'supervisor2_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID dosen pembimbing pilihan 2 (foreign key ke tabel users)'],
            'proposed_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan proposal'],
            'approved_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu persetujuan proposal'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('theses');

        // TABEL CHATS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID message (primary key)'],
            'message' => ['type' => 'TEXT', 'comment' => 'Isi pesan'],
            'file' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => 'Nama file'],
            'sender_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID pengirim (foreign key ke tabel users)'],
            'receiver_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID penerima (foreign key ke tabel users)'],
            'read_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pesan dibaca'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chats');

        // TABEL USER_STAGES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'comment' => 'ID Student'],
            'stage_id' => ['type' => 'INT', 'comment' => 'ID Stage'],
            'started_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu stage ini mulai'],
            'passed_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu stage ini selesai'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stage_id', 'stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_stages');

        // TABEL ASSIGNMENTS (penunjukan LECTURER sebagai Dosen Pembimbing Materi/Teknis atau sebagai Dosen Penguji)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID appointment (primary key)'],
            'number' => ['type' => 'VARCHAR', 'constraint' => 50, 'comment' => 'Nomor surat penunjukan'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('number');
        $this->forge->addForeignKey('thesis_id', 'theses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assignments');

        // TABEL ASSIGNMENT_LECTURERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'assignment_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID assignment (foreign key ke tabel assignments)'],
            'lecturer_id' => ['type' => 'INT', 'comment' => 'ID Lecturer'],
            'role' => ['type' => 'VARCHAR', 'constraint' => 50, 'comment' => "Peran dosen ['MATERIAL_SUPERVISOR', 'TECHNICAL_SUPERVISOR', 'EXAMINER_CHIEF', 'EXAMINER_MEMBER']"],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('assignment_id');
        $this->forge->addForeignKey('assignment_id', 'assignments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lecturer_id', 'users', 'id');
        $this->forge->createTable('assignment_lecturers');

        // TABEL SCORE_TYPES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score matrix (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Tipe score'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('score_types');

        // TABEL SCORE_MATRICES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score matrix (primary key)'],
            'score_type_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID score type (foreign key ke tabel score_types)'],
            'criteria' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Kriteria penilaian'],
            'weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'comment' => 'Bobot kriteria'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('score_type_id', 'score_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('score_matrices');

        // TABEL SCORES (menyimpan nilai sidang)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score (primary key)'],
            'assignment_lecturer_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID assignment_lecturer (foreign key ke tabel assignment_lecturers)'],
            'score_matrix_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID score matrix (foreign key ke tabel score_matrices)'],
            'score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'comment' => 'Nilai sidang'],
            'comments' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Komentar lecturer'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('score_matrix_id', 'score_matrices', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assignment_lecturer_id', 'assignment_lecturers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('scores');

        // TABEL LOGS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID log (primary key)'],
            'user_id' => ['type' => 'INT', 'null' => true, 'comment' => 'ID user (foreign key ke tabel users)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address'],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'User Agent'],
            'action' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'comment' => 'Action'],
            'description' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Deskripsi log'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('logs');

        // TABEL ANNOUNCEMENTS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID announcement (primary key)'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Judul pengumuman'],
            'content' => ['type' => 'TEXT', 'null' => false, 'comment' => 'Isi pengumuman'],
            'creator_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID user dari creator (foreign key ke tabel users)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('creator_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('announcements');

        // TABEL SUPERVISIONS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID supervision (primary key)'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID Thesis (foreign key ke tabel theses'],
            'chapter' => ['type' => 'ENUM', 'null' => false, 'constraint' => ['1', '2', '3', '4', '5'], 'comment' => 'Bab TA yang dibimbing (1-5)'],
            'comment' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Isi komentar'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('thesis_id');
        $this->forge->addForeignKey('thesis_id', 'theses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('supervisions');

        // TABEL TEMPORARY USERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'ID temporary user (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Nama lengkap pendaftar'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Alamat email pendaftar'],
            'nim' => ['type' => 'CHAR', 'constraint' => 8, 'null' => false, 'comment' => 'NIM pendaftar'],
            'academic_status' => ['type' => 'ENUM', 'constraint' => ['NEW', 'REPEAT'], 'default' => 'NEW', 'comment' => 'Status mahasiswa mengulang atau baru'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Password pendaftar (terenkripsi)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address pendaftar saat registrasi'],
            'activation_token' => ['type' => 'VARCHAR', 'constraint' => 32, 'default' => null, 'comment' => 'Token aktivasi pendaftar (unique)'],
            'expired_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu token aktivasi kadaluarsa'],
            'created_at' => ['type' => 'DATETIME', 'default' => '2025-01-01 00:00:00', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('temporary_users');

        $db->transComplete();
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $tables = $db->listTables();

        $db->disableForeignKeyChecks();

        $db->transStart();

        foreach ($tables as $table) {
            if ($table != 'migrations') {
                $db->query("DROP TABLE IF EXISTS `$table`");
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('Gagal menghapus tabel-tabel di database.');
        }

        $db->enableForeignKeyChecks();
    }
}
