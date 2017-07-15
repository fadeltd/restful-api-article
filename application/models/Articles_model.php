<?php
class Articles_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
    
    public function check($id_article, $id_user){
        $sql = "SELECT * FROM `articles` WHERE `id` = '$id_article' AND `id_user` = $id_user";
        $query = $this->db->query($sql);
        if ($query) {
            $result = $query->result();
            return $result;
        } 
        return false;
    }

    public function create($id_user, $title, $content) {
        $sql = "INSERT INTO `articles` (`id_user`, `title`, `content`) VALUES ($id_user, '$title', '$content');";
        $query = $this->db->query($sql);
        return $query;
    }
    
    public function list($id_user, $limit = 10) {
        $sql = "SELECT * FROM `articles` WHERE `id_user`=$id_user LIMIT $limit";
        $query = $this->db->query($sql);
        if ($query) {
            $result = $query->result();
            return $result;
        }
        return false;
    }

    public function edit($id, $title, $content) {
        $sql = "UPDATE `articles` SET `title`='$title', `content`='$content' WHERE `id`='$id'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function delete($id) {
        $sql = "DELETE FROM articles WHERE `id`='$id'";
        $query = $this->db->query($sql);
        return $query;
    }
}
?>