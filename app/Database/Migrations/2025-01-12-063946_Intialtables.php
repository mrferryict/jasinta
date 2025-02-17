<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Initialtables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID semester (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'comment' => 'Nama semester (e.g., Ganjil 2024/2025)'],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true, 'comment' => 'Status semester (aktif atau tidak aktif)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('semesters');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID jurusan (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false, 'comment' => 'Nama jurusan (e.g., S1 Teknik Informatika)'],
            'coordinator_id' => ['type' => 'INT', 'comment' => 'ID koordinator jurusan (foreign key ke tabel persons)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('majors');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'comment' => 'ID person (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'comment' => 'Nama lengkap person'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true, 'comment' => 'Alamat email person (unique)'],
            'number' => ['type' => 'VARCHAR', 'constraint' => 8, 'unique' => true, 'comment' => 'NIM (jika student), NIDN (jika lecturer), atau NIP (jika admin)'],
            'major_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'comment' => 'ID jurusan (foreign key ke tabel majors)'],
            'division' => ['type' => 'ENUM', 'constraint' => ['STUDENT', 'LECTURER', 'ADMIN'], 'default' => 'STUDENT', 'null' => false, 'comment' => 'Divisi person (student, lecturer, atau admin)'],
            'semester_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1, 'null' => true, 'comment' => 'ID semester (foreign key ke tabel semesters)'],
            'is_repeating' => ['type' => 'BOOLEAN', 'default' => false, 'null' => false, 'comment' => 'Apakah person mengulang semester?'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('major_id', 'majors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('persons');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'comment' => 'ID role (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => false, 'comment' => 'Nama role (e.g., Administrator, Mahasiswa)']
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('roles');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID user (primary key)'],
            'person_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'comment' => 'ID person (foreign key ke tabel persons, bisa NULL jika belum diapprove)'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Password user (terenkripsi)'],
            'token' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true, 'comment' => 'Token untuk verifikasi atau reset password'],
            'token_expired_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu token kadaluarsa'],
            'verified_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu verifikasi email'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir']
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'comment' => 'ID user role (primary key)'],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'comment' => 'ID user (foreign key ke tabel users)'],
            'role_id' => ['type' => 'INT', 'constraint' => 11, 'comment' => 'ID role (foreign key ke tabel roles)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_roles');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'comment' => 'ID stage (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'comment' => "['PENDAFTARAN', 'SYARAT PROPOSAL', 'PROPOSAL', 'BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'SYARAT SIDANG', 'SIDANG', 'REVISI', 'SYARAT AKHIR', 'SKLS']"],
            'route' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Route to controllers'],
            'deadline_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu deadline stage'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('stages');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID Tugas Akhir (primary key)'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Judul Tugas Akhir'],
            'student_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID student (foreign key ke tabel persons)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('thesis');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID message (primary key)'],
            'content' => ['type' => 'TEXT', 'comment' => 'Isi pesan'],
            'sender_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID pengirim (foreign key ke tabel persons)'],
            'receiver_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID penerima (foreign key ke tabel persons)'],
            'read_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pesan dibaca'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sender_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('messages');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID progress (primary key)'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'stage_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID stage (foreign key ke tabel stages)'],
            'description' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Deskripsi progress'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('stage_id', 'stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('progress');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID appointment (primary key)'],
            'number' => ['type' => 'VARCHAR', 'constraint' => 50, 'comment' => 'Nomor surat penunjukan'],
            'stage_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID stage (foreign key ke tabel stages)'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('stage_id', 'stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointments');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID appointment detail (primary key)'],
            'appointment_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID appointment (foreign key ke tabel appointments)'],
            'person_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID person (foreign key ke tabel persons)'],
            'task_type' => ['type' => 'ENUM', 'constraint' => ['EXAMINERS', 'MATERIAL SUPERVISORS', 'TECHNICAL SUPERVISORS'], 'null' => false, 'comment' => 'Jenis tugas (EXAMINERS, MATERIAL SUPERVISORS, TECHNICAL SUPERVISORS)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('person_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointment_details');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score (primary key)'],
            'thesis_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'evaluator_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID evaluator (foreign key ke tabel persons)'],
            'score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'comment' => 'Nilai sidang'],
            'comments' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Komentar evaluator'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('evaluator_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('scores');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID score matrix (primary key)'],
            'thesis_id' => ['type' => 'INT', 'comment' => 'ID thesis (foreign key ke tabel thesis)'],
            'criteria' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Kriteria penilaian'],
            'weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'comment' => 'Bobot kriteria'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('scores_matrix');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID log (primary key)'],
            'actor_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID actor (foreign key ke tabel persons)'],
            'thesis_id' => ['type' => 'INT', 'null' => true, 'comment' => 'ID thesis (foreign key ke tabel thesis, bisa NULL)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address actor'],
            'action' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Jenis aksi yang dilakukan'],
            'description' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Deskripsi log'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('actor_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('logs');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID setting (primary key)'],
            'key' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Key setting'],
            'value' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Value setting'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');

        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'ID session (primary key)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address user'],
            'timestamp' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Waktu session terakhir diakses'],
            'data' => ['type' => 'TEXT', 'null' => false, 'comment' => 'Data session']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('timestamp');
        $this->forge->createTable('ci_sessions', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'comment' => 'ID announcement (primary key)'],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Judul pengumuman'],
            'content' => ['type' => 'TEXT', 'null' => false, 'comment' => 'Isi pengumuman'],
            'admin_id' => ['type' => 'INT', 'null' => false, 'comment' => 'ID administrator (foreign key ke tabel persons)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu pembaruan catatan terakhir'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'comment' => 'Waktu penghapusan catatan (soft delete)']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('admin_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('announcements');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'ID temporary user (primary key)'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'comment' => 'Nama lengkap pendaftar'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true, 'comment' => 'Alamat email pendaftar (unique)'],
            'nim' => ['type' => 'CHAR', 'constraint' => 8, 'null' => false, 'comment' => 'NIM pendaftar'],
            'is_repeating' => ['type' => 'BOOLEAN', 'default' => false, 'null' => false, 'comment' => 'Apakah pendaftar mengulang semester?'],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'comment' => 'Password pendaftar (terenkripsi)'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => false, 'comment' => 'IP address pendaftar saat registrasi'],
            'activation_token' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => false, 'unique' => true, 'comment' => 'Token aktivasi pendaftar (unique)'],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu pembuatan catatan'],
            'expired_at' => ['type' => 'DATETIME', 'null' => false, 'comment' => 'Waktu token aktivasi kadaluarsa']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('temporary_users');
    }

    public function down()
    {
        $this->forge->dropTable('semesters');
        $this->forge->dropTable('majors');
        $this->forge->dropTable('persons');
        $this->forge->dropTable('roles');
        $this->forge->dropTable('users');
        $this->forge->dropTable('user_roles');
        $this->forge->dropTable('stages');
        $this->forge->dropTable('thesis');
        $this->forge->dropTable('messages');
        $this->forge->dropTable('progress');
        $this->forge->dropTable('appointments');
        $this->forge->dropTable('appointment_details');
        $this->forge->dropTable('scores');
        $this->forge->dropTable('scores_matrix');
        $this->forge->dropTable('logs');
        $this->forge->dropTable('settings');
        $this->forge->dropTable('ci_sessions');
        $this->forge->dropTable('announcements');
        $this->forge->dropTable('temporary_users');
    }
}
