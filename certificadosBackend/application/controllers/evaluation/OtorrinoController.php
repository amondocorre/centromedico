<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OtorrinoController extends CI_Controller {
  public function __construct() {
      parent::__construct();
      $this->load->database(); 
      $this->load->model('evaluation/OtorrinoModel');
  } 

  public function create() {
    if (!validate_http_method($this, ['POST'])) return;
    $res = verifyTokenAccess();
    if(!$res) return;
    $user = $res->user;
    $idUsuario = $user->id_usuario;
    $data = $this->input->post();
    $file = $_FILES['file'] ?? null;
    unset($data['nombre_completo']);

    $id = $this->OtorrinoModel->create($data, $idUsuario);
    if($id) {
      if($file){
        $url = guardarArchivo($id, $file, 'assets/evaluacion_otorrino/');
        if(!$url){
          $response = ['status' => 'success', 'message' => 'Ocurrió un error al guardar la foto.'];
          return _send_json_response($this, 200, $response);
        }
        $this->OtorrinoModel->updateFoto($url, $id);
      } 
      $url = getHttpHost();
      $res = $this->OtorrinoModel->findIdentity($id); 
      $res->foto = $res->foto ? $url.$res->foto : '';
      $res->nombre_completo = $res->nombre.' '.$res->ap_paterno.' '.$res->ap_materno;
      $response = ['status' => 'success', 'message' => 'Se guardó correctamente la información.', 'data' => $res];
      return _send_json_response($this, 200, $response);
    } else {
      $response = ['status' => 'error', 'message' => array_values($this->form_validation->error_array())];
      return _send_json_response($this, 400, $response);
    }
  }

  public function update($id) {
    if (!validate_http_method($this, ['POST'])) return; 
    $res = verifyTokenAccess();
    if(!$res) return;
    $user = $res->user;
    $idUsuario = $user->id_usuario;
    $data = $this->input->post();
    $file = $_FILES['file'] ?? null;
    unset($data['nombre_completo']);

    if ($this->OtorrinoModel->update($id, $data, $idUsuario)) {
      if($file){
        $url = guardarArchivo($id, $file, 'assets/evaluacion_otorrino/');
        if(!$url){
          $response = ['status' => 'success', 'message' => 'Ocurrió un error al guardar la foto.'];
          return _send_json_response($this, 200, $response);
        }
        $this->OtorrinoModel->updateFoto($url, $id);
      }
      $url = getHttpHost();
      $res = $this->OtorrinoModel->findIdentity($id); 
      $res->foto = $res->foto ? $url.$res->foto : '';
      $res->nombre_completo = $res->nombre.' '.$res->ap_paterno.' '.$res->ap_materno;
      $response = ['status' => 'success', 'message' => 'Se guardó correctamente la información.', 'data' => $res];
      return _send_json_response($this, 200, $response);
    } else {
      $response = ['status' => 'error', 'message' => array_values($this->form_validation->error_array())];
      return _send_json_response($this, 400, $response);
    }
  }

  public function activate($id) {
    if (!validate_http_method($this, ['PUT'])) return; 
    $res = verifyTokenAccess();
    if(!$res) return;
    $user = $res->user;
    $idUsuario = $user->id_usuario;
    if ($this->OtorrinoModel->activate($id, $idUsuario)) {
      $url = getHttpHost();
      $res = $this->OtorrinoModel->findIdentity($id); 
      $res->foto = $res->foto ? $url.$res->foto : '';
      $res->nombre_completo = $res->nombre.' '.$res->ap_paterno.' '.$res->ap_materno;
      $response = ['status' => 'success', 'message' => 'Se guardó correctamente la información.', 'data' => $res];
      return _send_json_response($this, 200, $response);
    } else {
      $response = ['status' => 'error', 'message' => array_values($this->form_validation->error_array())];
      return _send_json_response($this, 400, $response);
    }
  }

  public function search() {
      if (!validate_http_method($this, ['GET'])) return; 
      $res = verifyTokenAccess();
      if(!$res) return; 
      $q = $this->input->get('q');
      if (empty($q)) {
          return _send_json_response($this, 400, ['status' => 'error', 'message' => 'Parámetros incompletos']);
      }
      $data = $this->OtorrinoModel->search($q);
      $response = ['status' => 'success', 'data' => $data];
      return _send_json_response($this, 200, $response);
  }

  public function findIdentity($id) {
      if (!validate_http_method($this, ['GET'])) return; 
      $res = verifyTokenAccess();
      if(!$res) return; 
      $url = getHttpHost();
      $res = $this->OtorrinoModel->findIdentity($id); 
      $res->foto = $res->foto ? $url.$res->foto : '';
      $res->nombre_completo = $res->nombre.' '.$res->ap_paterno.' '.$res->ap_materno;
      $response = ['status' => 'success', 'data' => $res];
      return _send_json_response($this, 200, $response);
  }
}
