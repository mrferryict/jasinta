<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Initialtables extends Migration
{
    public function up()
    {

        // Tabel semesters
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('semesters');

        // Tabel majors
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'level_id'  => ['type' => 'INT'],
            'coordinator_id' => ['type' => 'INT'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('majors');

        // **Tabel Persons (Identitas Orang dalam Sistem)**
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'number'     => ['type' => 'VARCHAR', 'constraint' => 8, 'unique' => true], // NIM utk mahasiswa, NIDN utk Dosen
            'major_id'   => ['type' => 'INT', 'constraint' => 11, 'null' => true], // Null untuk dosen/admin
            'role_type'  => ['type' => 'ENUM', 'constraint' => ['mahasiswa', 'dosen', 'administrator'], 'default' => 'mahasiswa'],
            'semester_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1, 'null' => true], // Null untuk dosen/admin
            'created_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('major_id', 'majors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('persons');

        // **Tabel Roles (Hak Akses dalam Sistem)**
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('roles');

        // Tabel User
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'auto_increment' => true],
            'email'               => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'              => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'pending'], 'default' => 'pending'],
            'token'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'token_expired_at'    => ['type' => 'DATETIME', 'null' => true],
            'registered_ip'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'verified_at'         => ['type' => 'DATETIME', 'null' => true],
            'approved_by_admin_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'          => ['type' => 'DATETIME', 'null' => false],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');

        // Tabel User Roles (Relasi Users dengan Roles)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11],
            'user_id'    => ['type' => 'INT', 'constraint' => 11],
            'role_id'    => ['type' => 'INT', 'constraint' => 11],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_roles');

        // Tabel stages
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 30],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('stages');

        // Tabel thesis
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'student_id' => ['type' => 'INT', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('thesis');

        // Tabel messages
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'content'    => ['type' => 'TEXT'],
            'sender_id'  => ['type' => 'INT', 'null' => false],
            'receiver_id' => ['type' => 'INT', 'null' => false],
            'read_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sender_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('messages');

        // Tabel progress
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'thesis_id'  => ['type' => 'INT', 'null' => false],
            'person_id'  => ['type' => 'INT', 'null' => false],
            'stage_id'   => ['type' => 'INT', 'null' => false],
            'description' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('person_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stage_id', 'stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('progress');

        // Tabel tasks
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tasks');

        // Tabel appointments
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'number'     => ['type' => 'VARCHAR', 'constraint' => 50],
            'stage_id'   => ['type' => 'INT', 'null' => false],
            'thesis_id'  => ['type' => 'INT', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('stage_id', 'stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointments');

        // Tabel appointment_details
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'appointment_id' => ['type' => 'INT', 'null' => false],
            'person_id'  => ['type' => 'INT', 'null' => false],
            'task_id'    => ['type' => 'INT', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('person_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointment_details');

        // Tabel scores
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'thesis_id'  => ['type' => 'INT', 'null' => false],
            'evaluator_id' => ['type' => 'INT', 'null' => false],
            'score'      => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'comments'   => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('evaluator_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('scores');

        // Tabel scores_matrix
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'thesis_id'  => ['type' => 'INT'],
            'criteria'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'weight' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('scores_matrix');

        // Tabel logs
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'actor_id'   => ['type' => 'INT', 'null' => false],
            'thesis_id'  => ['type' => 'INT', 'null' => true],
            'ip' => ['type' => 'VARCHAR', 'constraint' => 50],
            'action' => ['type' => 'VARCHAR', 'constraint' => 50],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('actor_id', 'persons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('thesis_id', 'thesis', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('logs');

        // Tabel settings
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'key'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'value'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');
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
        $this->forge->dropTable('tasks');
        $this->forge->dropTable('appointments');
        $this->forge->dropTable('appointment_details');
        $this->forge->dropTable('scores');
        $this->forge->dropTable('scores_matrix');
        $this->forge->dropTable('logs');
        $this->forge->dropTable('settings');
    }
}
