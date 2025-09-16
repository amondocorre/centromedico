<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExplorationModel extends CI_Model {
    protected $table = 'ef_exploracion'; 
    public function __construct() {
        parent::__construct();
    }
  public function findIdentity($id) {
      return $this->db->get_where($this->table, ['id_ef_exploracion' => $id])->row();
  }
  public function getId($data) {
      return $producto->id_ef_exploracion ?? null;
  }
  public function create($data) {
    if($this->existExploration($data['descripcion'],$data['tipo'])) return true;
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }
  public function search($q, $tipo) {
    $this->db->like('descripcion',  $this->db->escape_like_str($q));
    $this->db->where('tipo', $tipo); 
    $query = $this->db->get($this->table);
    return ($query->num_rows() > 0) ? $query->result() : [];
  }

  function existExploration($descripcion,$tipo){
    $sql = "select * from ef_exploracion WHERE LTRIM(LOWER(descripcion))=LTRIM(LOWER('$descripcion')) and tipo = '$tipo'";
    $res = $this->db->query($sql)->result();
    return $res[0]??null;
  }
  private function validate_pet_data($data, $id_producto = 0) {
    $this->form_validation->set_data($data);
    $this->form_validation->set_rules('descripcion', 'Nombre', 'required|max_length[250]');
    return $this->form_validation->run();
  }
}
