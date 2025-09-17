<?php
class DashboardModel extends CI_Model {
  protected $ie = 'ingreso_salida'; 
  public function __construct() {
      parent::__construct();
  }
  public function fetch_arrivals_departures() {
    return $this->db->query("
      select sexo, count(sexo) as cantidad from cliente group by sexo")->result();
  }
  //se obtienen el total de clientes y cuantos varones y mujeres hay
  public function getTotalEvaMedical($id_sucursal) {
    $fecha = date('Y-m-d');
    $query = $this->db->query("
        SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN sexo = 'M' THEN 1 ELSE 0 END) AS masculino,
        SUM(CASE WHEN sexo = 'F' THEN 1 ELSE 0 END) AS femenino
        FROM evaluacion_medica where fecha_evaluacion='$fecha';");
    return $query->row(); 
  }
  public function getTotalEvaPsychological($id_sucursal) {
    $fecha = date('Y-m-d');
    $query = $this->db->query("
        SELECT 
        COUNT(*) AS total,
        '0' as masculino,
        '0' AS femenino
        FROM evaluacion_psicologica where fecha_evaluacion='$fecha';");
    return $query->row(); 
  }

  public function get_ingresos_diarios($id_sucursal){
    $this->db->select("
    DATE(fecha_movimiento) AS dia,
    SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) AS ingresos,
    SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END) AS egresos
    ");
    $this->db->from('movimientos_caja');
    $this->db->where('fecha_movimiento >=', date('Y-m-d', strtotime('-30 days')));
    $this->db->where('id_sucursal',$id_sucursal);
    $this->db->group_by('DATE(fecha_movimiento)');
    $this->db->order_by('DATE(fecha_movimiento)', 'ASC');

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result(); // Devuelve un array de objetos por día
    } else {
        return [];
    }
  }
}
