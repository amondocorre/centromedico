<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OtorrinoModel extends CI_Model {
    protected $table = 'evaluacion_otorrino'; 
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation'); 
    }

    public function findIdentity($id) {
        return $this->db->get_where($this->table, ['id_evaluacion_otorrino' => $id])->row();
    }

    public function create($data, $idUsuario) {
      if (!$this->validate_data($data)) {
          return FALSE; 
      }
      $data['ap_materno'] = isset($data['ap_materno']) ? $data['ap_materno'] : '';
      $data['fecha_registro'] = date('Y-m-d H:i:s');
      $data['id_usuario_registra'] = $idUsuario;
      unset($data['text']);
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
    }

    public function update($id, $data, $idUsuario) {
      if (!$this->validate_data($data, $id)) {
          return FALSE;
      }
      unset($data['id_usuario_registra']);
      unset($data['fecha_registro']);
      unset($data['text']);

      $data['ap_materno'] = isset($data['ap_materno']) ? $data['ap_materno'] : '';
      $data['fecha_modificacion'] = date('Y-m-d H:i:s');
      $data['id_usuario_modifica'] = $idUsuario;
      $this->db->where('id_evaluacion_otorrino', $id);
      return $this->db->update($this->table, $data);
    }

    public function activate($id, $idUsuario) {
      $data = new stdClass();
      $data->fecha_modificacion = date('Y-m-d H:i:s');
      $data->id_usuario_modifica = $idUsuario;
      $data->id_estado_evaluacion = '1';
      $this->db->where('id_evaluacion_otorrino', $id);
      return $this->db->update($this->table, $data);
    }

    public function search($q) {
      $url = getHttpHost();
      $this->db->select("*, CONCAT('$url', foto) as foto,
       CONCAT(nombre, ' ', ot.ap_paterno, ' ', ot.ap_materno) AS nombre_completo, 
       CONCAT(ci, ' - ', nombre, ' ', ot.ap_paterno, ' ', ot.ap_materno, ' - ', ot.fecha_evaluacion) AS text");
      $this->db->from('evaluacion_otorrino ot');
      $this->db->like('ci', $this->db->escape_like_str($q));
      $this->db->or_like("CONCAT(nombre, ' ', ot.ap_paterno, ' ', ot.ap_materno)", $this->db->escape_like_str($q));
      $query = $this->db->get();
      return ($query->num_rows() > 0) ? $query->result() : [];
    }

    public function updateFoto($url, $id) {
      $this->db->where('id_evaluacion_otorrino', $id);
      return $this->db->update($this->table, ['foto' => $url]);
    }

    private function validate_data($data, $id = 0) {
      $this->form_validation->set_data($data);
      $this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[100]');
      $this->form_validation->set_rules('ap_paterno', 'Apellido paterno', 'required|max_length[100]');
      $this->form_validation->set_rules('ci', 'CI', 'required');
      $this->form_validation->set_rules('edad', 'Edad', 'required');
      $this->form_validation->set_rules('sexo', 'Sexo', 'required');
      $this->form_validation->set_rules('fecha_evaluacion', 'Fecha Evaluacion', 'required');
      return $this->form_validation->run();
    }
}
