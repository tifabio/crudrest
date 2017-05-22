<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Carrega a model
        $this->load->model('UserModel', 'user');
    }
    
    /**
     * @SWG\Get(
     *     path="/user",
     *     summary="Retorna a lista de usuários",
     *     description="Retorna a lista de usuários",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Token do usuário",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Retorna a lista de usuários",
     *         @SWG\Schema(ref="#/definitions/UserModel")
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Acesso Negado"
     *     )
     * )
     * @SWG\Get(
     *     path="/user/{id}",
     *     summary="Busca usuário pelo ID",
     *     description="Retorna o usuário de acordo com o ID informado",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Token do usuário",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID do usuário",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Retorna o usuário",
     *         @SWG\Schema(ref="#/definitions/UserModel")
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Acesso Negado"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Nenhum usuário encontrado com o identificador informado"
     *     )
     * )
     */
    public function index_get($id = 0)
    {
        if ($id == 0) {
            $users = $this->user->getAll();
            
            header('Access-Control-Expose-Headers: X-Total-Count');
            header('X-Total-Count: ' . count($users));

            // Retorna a resposta e o código http
            $this->response($users, REST_Controller::HTTP_OK); // OK (200)
        } else {
            $this->user->id = $id;
            $user = $this->user->getById();
            
            // Verifica se encontrou o usuário
            if ($user) {
                // Retorna a resposta e o código http
                $this->response($user, REST_Controller::HTTP_OK); // OK (200)
            } else {
                // Retorna a resposta e o código http
                $this->response([
                    'status' => FALSE,
                    'message' => 'Nenhum usuario encontrado com o identificador informado'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
            } 
        }
    }

    /**
     * @SWG\Post(
     *     path="/user",
     *     summary="Adiciona um usuário",
     *     description="Adiciona um usuário",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Token do usuário",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Dados do usuário",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UserModel")
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @SWG\Schema(ref="#/definitions/UserModel")
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Campos obrigatórios não enviados"
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Acesso Negado"
     *     )
     * )
     */
    public function index_post()
    {
        $this->user->nome = $this->post('nome');
        $this->user->email = $this->post('email');
        $this->user->sexo = $this->post('sexo');
        $this->user->nascimento = $this->post('nascimento');
        if(trim($this->post('senha')) != '') {
            $this->user->senha = md5($this->post('senha'));            
        }
        
        $this->form_validation->set_data(get_object_vars($this->user));
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            // Retorna a resposta e o código http
            $this->response([
                'status' => FALSE,
                'message' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST); // BAD REQUEST (400)
        }
        else
        {
            $user = $this->user->save();
            // Retorna a resposta e o código http
            $this->response($user, REST_Controller::HTTP_CREATED); // CREATED (201)
        }
    }
    
    /**
     * @SWG\Put(
     *     path="/user/{id}",
     *     summary="Edita um usuário",
     *     description="Edita um usuário de acordo com o ID informado",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Token do usuário",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID do usuário",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Dados do usuário",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UserModel")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Usuário editado com sucesso",
     *         @SWG\Schema(ref="#/definitions/UserModel")
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Campos obrigatórios não enviados"
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Acesso Negado"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Nenhum usuário encontrado com o identificador informado"
     *     )
     * )
     */
    public function index_put($id = 0)
    {
        if($id == 0) {
            // Retorna a resposta e o código http
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum usuario encontrado com o identificador informado'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
        }
        
        $this->user->id = $id;
        $this->user->nome = $this->put('nome');
        $this->user->email = $this->put('email');
        $this->user->sexo = $this->put('sexo');
        $this->user->nascimento = $this->put('nascimento');
        if(trim($this->put('senha')) != '') {
            $this->user->senha = md5($this->put('senha'));            
        }
        
        $this->form_validation->set_data(get_object_vars($this->user));
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            // Retorna a resposta e o código http
            $this->response([
                'status' => FALSE,
                'message' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST); // BAD REQUEST (400)
        }
        else
        {
            $user = $this->user->save();
            // Retorna a resposta e o código http
            $this->response($user, REST_Controller::HTTP_OK); // CREATED (201)
        }
    }
    
    /**
     * @SWG\Delete(
     *     path="/user/{id}",
     *     summary="Remove um usuário",
     *     description="Remove um usuário de acordo com o ID informado",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Token do usuário",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID do usuário",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Usuário removido com sucesso"
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Acesso Negado"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Nenhum usuário encontrado com o identificador informado"
     *     )
     * )
     */
    public function index_delete($id = 0)
    {
        if($id == 0) {
            // Retorna a resposta e o código http
            $this->response([
                'status' => FALSE,
                'message' => 'Nenhum usuario encontrado com o identificador informado'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
        }
        
        $this->user->id = $id;
        
        $user = $this->user->delete();

        // Retorna a resposta e o código http
        $this->response($user, REST_Controller::HTTP_OK); // OK (200)
    }
    
    public function index_options()
    {
        // Retorna a resposta e o código http
        $this->response(null, REST_Controller::HTTP_OK); // OK (200)
    }
}
