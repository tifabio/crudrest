<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user extends CI_Migration {

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
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('user');
        }

        public function down()
        {
                $this->dbforge->drop_table('user');
        }
}