<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PsychologicalModel extends CI_Model {
    protected $table = 'evaluacion_psicologica'; 
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation'); 
    }
    public function findIdentity($id) {
        return $this->db->get_where($this->table, ['id_evaluacion_psicologica' => $id])->row();
    }
    public function getId($cliente) {
        return $user->id_evaluacion_psicologica ?? null;
    }
    public function create($data,$idUsuario) {
      if (!$this->validate_pefil_data($data)) {
          return FALSE; 
      }
      $data['ap_materno'] = isset($data['ap_materno'])?$data['ap_materno']:'';
      $data['fecha_registro'] = date('Y-m-d H:i:s');
      $data['id_usuario_registra'] = $idUsuario;
      unset($data['text']);
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
    }
  public function update($id, $data,$idUsuario) {
    if (!$this->validate_pefil_data($data, $id)) {
        return FALSE;
    }
    unset($data['id_usuario_registra']);
    unset($data['fecha_registro']);
    unset($data['id_sucursal']);
    unset($data['text']);

    $data['ap_materno'] = isset($data['ap_materno'])?$data['ap_materno']:'';
    $data['fecha_modificacion'] = date('Y-m-d H:i:s');
    $data['id_usuario_modifica'] = $idUsuario;
    $this->db->where('id_evaluacion_psicologica', $id);
    return $this->db->update($this->table, $data);
  }
  public function search($q) {
    $url = getHttpHost();
    $this->db->select("id_evaluacion_psicologica,ap_paterno,ap_materno,nombre,edad,ci,lugar_nacimiento,fecha_nacimiento,profecion,fecha_evaluacion,
    domicilio,numero_domicilio,zona,telefono,historia_familiar,coordinacion_visomotora,personalidad,atencion_cognitiva,reaccion_estres_riego,
    observacion,id_estado_evaluacion,CONCAT('$url',foto)as foto,
     CONCAT(nombre, ' ', em.ap_paterno, ' ', em.ap_materno) AS nombre_completo, CONCAT(ci, ' - ', nombre, ' ', em.ap_paterno, ' ', em.ap_materno, ' - ', em.fecha_evaluacion) AS text");
    $this->db->from('evaluacion_psicologica em');
    $this->db->like('ci', $this->db->escape_like_str($q));
    $this->db->or_like("CONCAT(nombre, ' ', em.ap_paterno, ' ', em.ap_materno)", $this->db->escape_like_str($q));
    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query->result() : [];

  }
  public function updateFoto($url,$id){
    $this->db->where('id_evaluacion_psicologica', $id);
    return $this->db->update($this->table, ['foto'=>$url]);
  }
  private function validate_pefil_data($data, $id = 0) {
    $this->form_validation->set_data($data);
    $this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[100]');
    $this->form_validation->set_rules('ap_paterno', 'Apellido paterno', 'required|max_length[50]');
    $this->form_validation->set_rules('ci', 'ci', 'required');
    $this->form_validation->set_rules('edad', 'Edad', 'required');
    $this->form_validation->set_rules('fecha_nacimiento', 'Fecha Nacimiento', 'required');
    $this->form_validation->set_rules('lugar_nacimiento', 'Lugar Nacimiento', 'required');
    //$this->form_validation->set_rules('domicilio', 'Domicilio', 'required');
    $this->form_validation->set_rules('profecion', 'Profecion', 'required');
    $this->form_validation->set_rules('fecha_evaluacion', 'Eecha Evaluacion', 'required');
    return $this->form_validation->run();
  }
  public function getEvaluations($limit, $offset,$idSucursal){
    $this->db->select("id_evaluacion_psicologica,em.id_estado_evaluacion,fecha_evaluacion,ci,CONCAT(nombre, ' ', em.ap_paterno, ' ', em.ap_materno) AS nombre_completo");
    $this->db->from("evaluacion_psicologica em");
    $this->db->join("estado_evaluacion ee", "ee.id_estado_evaluacion = em.id_estado_evaluacion");
    //if($idSucursal>0) $this->db->where('id_sucursal',$idSucursal);
    $this->db->where_in('em.id_estado_evaluacion', [1,2]);
    $this->db->order_by('fecha_evaluacion', 'desc');
    $this->db->limit($limit, $offset);
    return $this->db->get()->result();
  }
  public function getEvaluationsTotal($idSucursal){
    //if($idSucursal>0)  $this->db->where('id_sucursal', $idSucursal);
    $this->db->where_in('id_estado_evaluacion', [1,2]);
    return $this->db->count_all_results('evaluacion_psicologica');
  }
}