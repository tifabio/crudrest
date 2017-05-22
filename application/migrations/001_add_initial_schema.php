<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_initial_schema extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'nome' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100
                        ),
                        'email' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100
                        ),
                        'sexo' => array(
                                'type' => 'ENUM("m","f")',
                                'null' => TRUE
                        ),
                        'nascimento' => array(
                                'type' => 'DATE',
                                'null' => TRUE
                        ),
                        'senha' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 32,
                                'null' => TRUE
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('user');
                
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'user_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE
                        ),
                        'token' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 40
                        ),
                        'level' => array(
                                'type' => 'INT',
                                'constraint' => 2,
                                'unsigned' => TRUE
                        ),
                        'ignore_limits' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'unsigned' => TRUE,
                                'default' => '0'
                        ),
                        'is_private_key' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'unsigned' => TRUE,
                                'default' => '0'
                        ),
                        'ip_addresses' => array(
                                'type' => 'TEXT',
                                'null' => TRUE
                        ),
                        'date_created' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('token');
        }

        public function down()
        {
                $this->dbforge->drop_table('user');
                $this->dbforge->drop_table('token');
        }
}