<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @SWG\Definition(required={"nome", "email"}, type="object", @SWG\Xml(name="User"))
 */
class UserModel extends CI_Model
{
    const table = 'user';
    
    /**
     * @SWG\Property()
     * @var int
     */
    public $id;
    /**
     * @SWG\Property()
     * @var string
     */
    public $nome;
    /**
     * @SWG\Property()
     * @var string
     */
    public $email;
    /**
     * @SWG\Property(enum={"m", "f"})
     * @var string
     */
    public $sexo;
     /**
     * @SWG\Property(format="date")
     * @var string
     */
    public $nascimento;
    
    public function getAll() 
    {
        $query = $this->db->get(self::table);
        return $query->result();
    }
    
    public function getById() 
    {
        $this->db->where('id', $this->id);
        $query = $this->db->get(self::table);
        return $query->row();
    }
    
    public function save()
    {
        $data = get_object_vars($this);
        
        if($data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update(self::table, $data);
        } else {
            $this->db->insert(self::table, $data);
            $data['id'] = $this->db->insert_id();
        }
        
        return $data;
    }
    
    public function delete()
    {
        $data = ['id' => $this->id];
        
        $this->db->where('id', $data['id']);
        $this->db->delete(self::table);
        
        return $data;
    }
}
