<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!is_login())redirect(site_url('login'));
        $this->load->model('Proses_model','pmodel');
        $this->load->model('Global_model','gmodel');
    }

	function index(){
        $data['title']  = 'DATA PROSES';
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        // $data['css_files'][] = base_url() . 'assets/admin/easyui/themes/icon.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        // $data['js_files'][] = base_url() . 'assets/admin/easyui/datagrid-groupview.js';
        // $data['js_files'][] = base_url() . 'assets/admin/easyui/plugins/datagrid-scrollview.js';
        // $data['js_files'][] = base_url() . 'assets/admin/js/menu.js';
        $this->template->load('template','master/proses',$data);
    }

    function getData(){
        $result =  $this->pmodel->getAll();
        echo json_encode($result);
    }

    function save(){
        $kode = $this->input->post('kode_program', TRUE);
        $nama = $this->input->post('nama_program', TRUE);
        $data = array();
        $data = array(
            'kode_program' => $kode,
            'nama_program' => $nama
        );
        $result = $this->gmodel->insert('tbl_program',$data);
        if ($result){
            echo json_encode(array('message'=>'Update Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }

    function update(){
        $kode = $this->input->post('kode_program', TRUE);
        $nama = $this->input->post('nama_program', TRUE);
        $data = array();
        $data = array(
            'kode_program' => $kode,
            'nama_program' => $nama
        );
        $where = array('_id'=>$this->input->get('id'));
        $result = $this->gmodel->update('tbl_program',$data,$where);
        if ($result){
            echo json_encode(array('message'=>'Save Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }
    function delete(){
        $data = $this->input->post('id',TRUE);
        $result = $this->gmodel->deleteBatch('tbl_program',$data);
        if ($result){
            echo json_encode(array('message'=>'Delete Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }
    function aktif()
    {
        $id = $this->input->post('id',TRUE);
        $rows = $this->db->get_where('tbl_program', array('_id'=>$id))->row_array();
        if ($rows['status'] == 0){
            $aktif = '1';
        }else{
            $aktif = '0';
        }
        $result = $this->gmodel->update('tbl_program',array('status'=>$aktif), array('_id'=>$id));
        if ($result){
            echo json_encode(array('message'=> 'User  Aktif or Non Aktif Success'));
        } else {
            echo json_encode(array('errorMsg'=>'Some errors occured.'));
        }
    }
}
