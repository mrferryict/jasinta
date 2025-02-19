<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Initialtables extends Migration
{
    public function up()
    {
        // TABEL SEMESTERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID semester (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'comment' => 'Nama semester (e.g., Ganjil 2024/2025)'],
            'status' => ['type' => 'BOOLEAN', 'default' => true, 'comment' => 'Status semester aktif atau tidak'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('semesters');

        // TABEL MAJORS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID jurusan (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false, 'comment' => 'Nama jurusan (e.g., S1 Teknik Informatika)'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('majors');

        // TABEL USERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID user (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'comment' => 'Nama lengkap user'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true, 'comment' => 'Alamat email'],
            'number' => ['type' => 'VARCHAR', 'constraint' => 8, 'unique' => true, 'comment' => 'NIM (jika student), NIDN (jika lecturer), atau NIP (jika admin)'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Password user (terenkripsi)'],
            'division' => ['type' => 'ENUM', 'constraint' => ['STUDENT', 'LECTURER', 'ADMIN'], 'default' => 'STUDENT', 'null' => false, 'comment' => 'Divisi person (student, lecturer, atau admin)'],
            'major_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'comment' => 'ID jurusan (foreign key ke tabel majors)'],
            'semester_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1, 'null' => true, 'comment' => 'ID semester (foreign key ke tabel semesters)'],
            'is_repeating' => ['type' => 'BOOLEAN', 'default' => false, 'null' => false, 'comment' => 'Apakah person mengulang semester?'],
            'token' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true, 'comment' => 'Token untuk verifikasi atau reset password'],
            'token_expired_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu token kadaluarsa'],
            'verified_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu verifikasi email'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'status' => ['type' => 'BOOLEAN', 'default' => true, 'null' => false, 'comment' => 'Status user aktif atau tidak']
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('major_id', 'majors', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('semester_id', 'semesters', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('users');

        // TABEL COORDINATORS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID jurusan (primary key)'],
            'major_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID major (foreign key ke tabel majors)'],
            'user_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID student (foreign key ke tabel users)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('major_id', 'majors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('coordinators');

        // TABEL STAGES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'comment' => 'ID stage (primary key)'],
            'name' => ['type' => 'ENUM', 'constraint' => ['PENDAFTARAN', 'SYARAT PROPOSAL', 'PROPOSAL', 'BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'SYARAT SIDANG', 'SIDANG', 'REVISI', 'SYARAT AKHIR', 'SKLS'], 'null' => false, 'comment' => 'Jenis stages'],
            'route' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Route to controllers'],
            'deadline_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu deadline stage'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('stages');

        // TABEL THESIS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID Tugas Akhir (primary key)'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Judul Tugas Akhir'],
            'student_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID student (foreign key ke tabel users)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('thesis');

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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chats');

        // TABEL PROGRESS (mencatat progress THESIS sudah sampai STAGE mana)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID progress (primary key)'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'stage_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID stage (foreign key ke tabel stages)'],
            'description' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Deskripsi progress'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stage_id', 'stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('progress');

        // TABEL APPOINTMENTS (penunjukan LECTURER sebagai Dosen Pembimbing Materi/Teknis atau sebagai Dosen Penguji)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID appointment (primary key)'],
            'user_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID user (foreign key ke tabel users)'],
            'number' => ['type' => 'VARCHAR', 'constraint' => 50, 'comment' => 'Nomor surat penunjukan'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointments');

        // TABEL APPOINTMENT DETAILS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID appointment detail (primary key)'],
            'appointment_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID appointment (foreign key ke tabel appointments)'],
            'task_type' => ['type' => 'ENUM', 'constraint' => ['EXAMINERS', 'MATERIAL_SUPERVISORS', 'TECHNICAL_SUPERVISORS'], 'null' => false, 'comment' => 'Jenis tugas'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointment_details');

        // TABEL SCORE_TYPES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score matrix (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Tipe score'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('score_types');

        // TABEL SCORE_MATRICES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score matrix (primary key)'],
            'score_type_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID score (foreign key ke tabel score_types'],
            'criteria' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Kriteria penilaian'],
            'weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'comment' => 'Bobot kriteria'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('score_matrices');

        // TABEL SCORES (menyimpan nilai sidang)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score (primary key)'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'score_matrix_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID score matrix (foreign key ke tabel score_matrices)'],
            'evaluator_id' => ['type' => 'INT', 'null' => true, 'comment' => 'ID evaluator (foreign key ke tabel users)'],
            'score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'comment' => 'Nilai sidang'],
            'comments' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Komentar evaluator'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('score_matrix_id', 'score_matrices', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('evaluator_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('scores');


        // TABEL LOGS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID log (primary key)'],
            'user_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID user (foreign key ke tabel users)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address'],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'comment' => 'User Agent'],
            'action' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'comment' => 'Action'],
            'description' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Deskripsi log'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('logs');

        // TABEL SETTINGS (menyimpan nilai pengaturan aplikasi)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID setting (primary key)'],
            'key' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Key setting'],
            'value' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Value setting'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');

        // TABEL CI_SESSIONS (internal CodeIgniter)
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'ID session (primary key)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address user'],
            'timestamp' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Waktu session terakhir diakses'],
            'data' => ['type' => 'TEXT', 'null' => false, 'comment' => 'Data session']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('timestamp');
        $this->forge->createTable('ci_sessions', true);

        // TABEL ANNOUNCEMENTS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID announcement (primary key)'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Judul pengumuman'],
            'content' => ['type' => 'TEXT', 'null' => false, 'comment' => 'Isi pengumuman'],
            'creator_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID user dari creator (foreign key ke tabel users)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('creator_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('announcements');

        // TABEL TEMPORARY USERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'ID temporary user (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Nama lengkap pendaftar'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true, 'comment' => 'Alamat email pendaftar (unique)'],
            'nim' => ['type' => 'CHAR', 'constraint' => 8, 'null' => false, 'comment' => 'NIM pendaftar'],
            'is_repeating' => ['type' => 'BOOLEAN', 'default' => false, 'null' => false, 'comment' => 'Apakah pendaftar mengulang semester?'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Password pendaftar (terenkripsi)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address pendaftar saat registrasi'],
            'activation_token' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => false, 'unique' => true, 'comment' => 'Token aktivasi pendaftar (unique)'],
            'expired_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu token aktivasi kadaluarsa'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('temporary_users');
    }

    public function down()
    {
        $this->forge->dropTable('ci_sessions');
        $this->forge->dropTable('settings');
        $this->forge->dropTable('temporary_users');
        $this->forge->dropTable('announcements');
        $this->forge->dropTable('logs');
        $this->forge->dropTable('chats');
        $this->forge->dropTable('progress');
        $this->forge->dropTable('appointment_details');
        $this->forge->dropTable('appointments');
        $this->forge->dropTable('score_matrices');
        $this->forge->dropTable('score_types');
        $this->forge->dropTable('scores');
        $this->forge->dropTable('thesis');
        $this->forge->dropTable('coordinators');
        $this->forge->dropTable('stages');
        $this->forge->dropTable('users');
        $this->forge->dropTable('majors');
        $this->forge->dropTable('semesters');
    }
}
