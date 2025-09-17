<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {

  public function getArrivalsDepartures() {
    $this->load->model('dashboard/DashboardModel');
    $this->load->model('RentModel');
    $data = $this->DashboardModel->fetch_arrivals_departures();
    echo json_encode($data);
  }

  public function getOccupation() {
    $this->load->model('dashboard/DashboardModel');
    $data = $this->DashboardModel->fetch_occupation();
    echo json_encode($data);
  }

  public function getTotalEvaluations($id_sucursal) {
    $this->load->model('dashboard/DashboardModel');
    $objeto = new stdClass();
    $objeto->medical = $this->DashboardModel->getTotalEvaMedical($id_sucursal);
    $objeto->psychological = $this->DashboardModel->getTotalEvaPsychological($id_sucursal);
    echo json_encode($objeto);
  }

  public function getMascotasEstancia() {
    $this->load->model('dashboard/DashboardModel');
    $data = $this->DashboardModel->get_mascotas_estancia();
    echo json_encode($data);
  }

  public function getIngresosDiarios($id_sucursal) {
    $this->load->model('dashboard/DashboardModel');
    $data = $this->DashboardModel->get_ingresos_diarios($id_sucursal);
    echo json_encode($data);
  }
  public function listEvaMedical($idSucursal) {
    $this->load->model('evaluation/MedicalModel');
    $page = $q = $this->input->get('page') ?? 1;
    $limit = $q = $this->input->get('limit') ?? 10;
    $offset = ($page - 1) * $limit;
    $data = $this->MedicalModel->getEvaluations($limit, $offset,$idSucursal);
    $total = $this->MedicalModel->getEvaluationsTotal($idSucursal);
    $response = [
      'data' => $data,
      'pagination' => [
        'total' => $total,
        'page' => (int) $page,
        'limit' => (int) $limit,
        'totalPages' => ceil($total / $limit)
      ]
    ];
    echo json_encode($response);
  }
  public function listEvaPsychological($idSucursal) {
    $this->load->model('evaluation/PsychologicalModel');
    $page = $q = $this->input->get('page') ?? 1;
    $limit = $q = $this->input->get('limit') ?? 10;
    $offset = ($page - 1) * $limit;
    $data = $this->PsychologicalModel->getEvaluations($limit, $offset,$idSucursal);
    $total = $this->PsychologicalModel->getEvaluationsTotal($idSucursal);
    $response = [
      'data' => $data,
      'pagination' => [
        'total' => $total,
        'page' => (int) $page,
        'limit' => (int) $limit,
        'totalPages' => ceil($total / $limit)
      ]
    ];
    echo json_encode($response);
  }
  public function getDetailRent($idContrato) {
    if (!validate_http_method($this, ['GET'])) return; 
    $data = [];
    $productos = [];
    $response = [
      'data' => $data,
      'productos'=>$productos     
    ];
    echo json_encode($response);
  }
}
