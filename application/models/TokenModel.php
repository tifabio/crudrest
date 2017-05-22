<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TokenModel extends CI_Model
{
    const table = 'token';
    
    public $id;
    public $user_id;
    public $token;
    public $level = 1;
    public $ignore_limits = 1;
    public $is_private_key = 0;
    public $ip_addresses;
    public $date_created;
    
        
    public function generateToken() 
    {
        $this->db->where('user_id', $this->user_id);
        $query = $this->db->get(self::table);
        
        $row = $query->row();
        
        if($row) {
            $this->id = $row->id;
            $this->updateToken();
        } else {
            $this->insertToken();
        }
        
        return $this->token;
    }
    
    private function insertToken() 
    {
        $this->generateHash();
        $this->date_created = function_exists('now') ? now() : time(); 
        
        $data = get_object_vars($this);
        
        $this->db->insert(self::table, $data);
    }
    
    private function updateToken() 
    {
        $this->generateHash();
        $this->date_created = function_exists('now') ? now() : time();
        
        $data = get_object_vars($this);
        
        $this->db->where('id', $data['id']);
        $this->db->update(self::table, $data);
    }
    
    private function generateHash() 
    {
        $salt = hash('sha256', time() . mt_rand() . $this->user_id);
        $this->token = substr($salt, 0, config_item('rest_key_length'));
    }
}
