<?php

/**
 * Migration: Create_bbs
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2015/04/29 08:10:27
 */
class Migration_Create_bbs extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '64',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE,
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => TRUE,
            ),
            'body' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '39',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('bbs');
    }

    public function down() {
        $this->dbforge->drop_table('bbs');
    }

}
