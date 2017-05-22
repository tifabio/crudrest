<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 * @SWG\Swagger(
 *     schemes={"https"},
 *     host="testesphp-tifabio.c9.io",
 *     basePath="/crudrest",
 *     @SWG\Info(
 *         version="0.0.1",
 *         title="CRUD REST",
 *         description="Teste de crud em webservice restfull"
 *         
 *     )
 * )
 */
class Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Carrega a model
        $this->load->model('UserModel', 'user');
        $this->load->model('TokenModel', 'token');
    }
    
    /**
     * @SWG\Post(
     *     path="/auth",
     *     summary="Realiza a autenticação e retorna o token",
     *     description="Realiza a autenticação e retorna o token, para ser enviado via header nas chamadas seguintes",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Email e Senha",
     *         required=true,
     *         @SWG\Schema (
     *                  @SWG\Property(
     *                      property="username",
     *                      type="string"
     *                  ),
     *                  @SWG\Property(
     *                      property="password",
     *                      type="string"
     *                  )
     *              )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Autenticação realizada com sucesso e token criado/atualizado",
     *         @SWG\Schema (
     *                  @SWG\Property(
     *                      property="token",
     *                      type="string"
     *                  )
     *              )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Não autorizado (usuários ou senha inválidos)"
     *     )
     * )
     */
    public function index_post()
    {
        $this->user->email = $this->post('username');
        $this->user->senha = md5($this->post('password'));
        $auth = $this->user->getAuth();
        if($auth) {
            $this->response(['token' => $this->__generateToken($auth)], REST_Controller::HTTP_OK); // OK (200)
        } else {
            $this->response(null, REST_Controller::HTTP_UNAUTHORIZED); // HTTP_FORBIDDEN (401)
        }
    }
    
    private function __generateToken($auth)
    {
        $this->token->user_id = $auth->id;
        return $this->token->generateToken();
    }
}
