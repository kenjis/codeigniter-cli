<?php

/**
 * Migration: Create_captcha
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2015/04/29 10:38:53
 */
class Migration_Create_captcha extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'captcha_id' => array(
                'type' => 'BIGINT',
                'constraint' => 13,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'captcha_time' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
            ),
            'word' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
        ));
        $this->dbforge->add_key('captcha_id', TRUE);
        $this->dbforge->add_key('word');
        $this->dbforge->create_table('captcha');
    }

    public function down() {
        $this->dbforge->drop_table('captcha');
    }

}
