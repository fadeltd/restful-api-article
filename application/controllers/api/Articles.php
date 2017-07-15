<?php
require_once APPPATH . '/libraries/REST_Controller.php';
require_once APPPATH . '/libraries/JWT.php';
use \Firebase\JWT\JWT;
class Articles extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->model('Articles_model');
    }

    public function verify($jwt){
        $token = (array) JWT::decode($jwt, "Secret", array('HS256'));
        $id = $this->Users_model->verify($token["id"], $token["email"]);
        if(isset($token["id"])){
            return $id;
        } else {
            $this->set_response($token, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    
    // - endpoint must be protected by user JWT token
    // - title & content is required, min 30 chars (title)
    // - success response must be 200 http code & return article data
    // - failed validation response must be 422 http code

    // POST: http://localhost/restful-api-s/api/articles/create
    // @param   {header}    Authorization   Generated Json Web Token
    // @param   {header}    Content-Type    Application JSON
    // Sample:
    // Content-Type:application/json
    // Authorization:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEiLCJlbWFpbCI6ImFkbWluQGdtYWlsLmNvbSIsImlhdCI6MTUwMDA4NDg4MywiZXhwIjoxNTAwMTAyODgzfQ.XdS1H8163Alt22IhK0z05EU-ih1WSIPrkM_5zIVLmQ8
    // 
    // @param   {body}      title           Title of the Article
    // @param   {body}      content         Content of the Article
    // Sample:
    // {
	//     "title": "How to Create RESTful-API with PHP and JWT"",
	//     "content": "<ul><li>Download Code Igniter</li><li>Download php-jwt</li>"
    // }
    //
    // @success Return Array of Created Article
    // Sample:
    // Status 200
    // {
    //     "title": "How to Create RESTful-API with PHP and JWT",
    //     "content": "<ul><li>Download Code Igniter</li><li>Download php-jwt</li>"
    // }
    // @error
    // Sample:
    // Status 422
    // {
    //     "message" : "Title is required, minimum 30 characters"
    // }
    public function create_post() {
        $headers = $this->input->request_headers();
        $id = $this->verify($headers["Authorization"]);
        if($id){
            $title = $this->post('title');
            $content = $this->post('content');
            if(!isset($title) || strlen($title) < 30){
                $this->response(["message" => "Title is required, minimum 30 characters"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
            } else if(!isset($content)){
                $this->response(["message" => "Content is required"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
            } else{
                $this->Articles_model->create($id, $title, $content);
                $this->set_response(["title" => $title, "content" => $content], REST_Controller::HTTP_OK);
            }
        }
    }
    
    // - endpoint must be protected bu user JWT token
    // - user is able to paginate data by 10 article / page
    // - only user's article is displayed
    // - success response must be 200 http code & return paginated article data
    
    // GET: http://localhost/restful-api-s/api/articles/list
    // @param limit (optional)
    // Sample: http://localhost/restful-api-s/api/articles/list?limit=0
    //
    // Return Array of All Articles
    // Sample:
    // [
    //     {
    //         "id": "1",
    //         "id_user": "1",
    //         "title": "How to Create RESTful-API with PHP and JWT",
    //         "content": "<ul><li>Download Code Igniter</li><li>Download php-jwt</li>"
    //     }
    // ]
    public function list_get() {
        $headers = $this->input->request_headers();
        $id = $this->verify($headers["Authorization"]);
        if($id){
            $result = $this->Articles_model->list($id);
            $limit = $this->input->get('limit');
            if(isset($limit)){
                $result = $this->Articles_model->list($id, $limit);
            }
            $this->set_response($result, REST_Controller::HTTP_OK);
        }
    }

    // - endpoint must be protected by user JWT token
    // - only article's owner is authorized to do edit article
    // - title & content is required, min 30 chars (title)
    // - success response must be 200 http code & return article data
    // - failed validation response must be 422 http code
    
    // PUT: http://localhost/restful-api-s/api/articles/edit
    public function edit_put() {
        $headers = $this->input->request_headers();
        $id = $this->verify($headers["Authorization"]);
        if($id){
            $article_id =  $this->put('article_id');
            $title = $this->put('title');
            $content = $this->put('content');
            if(!isset($title) || strlen($title) < 30) {
                $this->response(["message" => "Title is required, minimum 30 characters"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
            } else if(!isset($content)) {
                $this->response(["message" => "Content is required"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
            } else if(!isset($article_id)) {
                $this->response(["message" => "Article id is required"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $check = $this->Articles_model->check($article_id, $id);
                if($check){
                    if($this->Articles_model->edit($article_id, $title, $content)){
                        $this->set_response(["title" => $title, "content" => $content], REST_Controller::HTTP_OK);
                    } else {
                        $this->response(["message" => "Failed to update article, article not found"], REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response(["message" => "You are not allowed to edit this article"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        }
    }

    // - [user delete article] (DELETE)
    // - endpoint must be protected by user JWT token
    // - only article's owner is authorized to do delete article
    // - article id must be exist
    // - success response must be 200 http code & return article data
    // - failed validation response must be 422 http code

    // PUT: http://localhost/restful-api-s/api/articles/delete
    public function delete_delete() {
        $headers = $this->input->request_headers();
        $id = $this->verify($headers["Authorization"]);
        if($id){
            $article_id =  $this->delete('article_id');
            if(!isset($article_id)){
                $this->response(["message" => "Article id is required"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $check = $this->Articles_model->check($article_id, $id);
                if($check){
                    if($this->Articles_model->delete($article_id)){
                        $this->set_response($check[0], REST_Controller::HTTP_OK);
                    } else {
                        $this->response(["message" => "Failed to delete article, article not found"], REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response(["message" => "You are not allowed to delete this article"], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
            
        }
    }
}
?>