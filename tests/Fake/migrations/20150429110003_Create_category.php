<?php

/**
 * Migration: Create_category
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2015/04/29 10:46:11
 */
class Migration_Create_category extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('category');

        $this->db->insert('category', ['name' => 'Book']);
        $this->db->insert('category', ['name' => 'CD']);
        $this->db->insert('category', ['name' => 'DVD']);
    }

    public function down() {
        $this->dbforge->drop_table('category');
    }

}
