<?php

/**
 * Migration: Create_product
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2015/04/29 11:38:36
 */
class Migration_Create_product extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'category_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 64,
            ),
            'detail' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'price' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'img' => array(
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('product');
    }

    public function down() {
        $this->dbforge->drop_table('product');
    }

}
