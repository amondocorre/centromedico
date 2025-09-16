<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExplorationController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database(); 
        $this->load->model('evaluation/ExplorationModel');
    } 
    public function create() {
      if (!validate_http_method($this, ['POST'])) {
        return;
      }
      $res = verifyTokenAccess();
      if(!$res){
        return;
      }
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $this->ExplorationModel->create($data);
      if ($id) {
        $response = ['status' => 'success','message'=>'Se guardo correctamente la informacion'];
        return _send_json_response($this, 200, $response);
      } else {
        $response = ['status' => 'error', 'message' =>  'Ocurrio un error inesperado.'];
        return _send_json_response($this, 400, $response);
      }
    }  
    public function search() {
      if (!validate_http_method($this, ['GET'])) return; 
      $res = verifyTokenAccess();
      if(!$res) return; 
      $q = $this->input->get('q');
      $tipo = $this->input->get('tipo');
      if (empty($q) || empty($tipo)) {
          return _send_json_response($this, 400, ['status' => 'error', 'message' => 'Parámetros incompletos']);
      }
      $data = $this->ExplorationModel->search($q, $tipo);
      $response = ['status' => 'success','data'=>$data];
      return _send_json_response($this, 200, $response);
    }
}
