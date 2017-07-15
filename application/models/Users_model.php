<?php
class Users_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 1) {
            $result = $query->result();
            return $result[0]->id;
        }
        return false;
    }
    
    public function verify($id, $email){
        $sql = "SELECT * FROM users WHERE id='$id' AND email = '$email'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 1) {
            $result = $query->result();
            return $result[0]->id;
        }
        return false;
    }
}
?>