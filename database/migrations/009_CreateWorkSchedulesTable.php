<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'monday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'monday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'tuesday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'tuesday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'wednesday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'wednesday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'thursday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'thursday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'friday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'friday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'saturday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'saturday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'sunday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'sunday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'is_default' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('work_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('work_schedules');
    }
}
