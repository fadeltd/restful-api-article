<?php
require_once APPPATH . '/libraries/REST_Controller.php';
require_once APPPATH . '/libraries/JWT.php';
use \Firebase\JWT\JWT;
class Users extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
    }
    public function login_post() {
        $email = $this->post('email');
        $password = $this->post('password');
        $invalidLogin = ['invalid' => $email];
        if(!$email || !$password){
            $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        }
        $id = $this->Users_model->login($email, $password);
        if($id) {
            $token['id'] = $id;
            $token['email'] = $email;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5;
            $output['id_token'] = JWT::encode($token, "Secret");
            $this->set_response($output, REST_Controller::HTTP_OK);
        } else {
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
?>