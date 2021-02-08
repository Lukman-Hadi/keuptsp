<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!is_login())redirect(site_url('login'));
        $this->load->model('Approve_model','amodel');
        $this->load->model('Global_model','gmodel');
    }

	function index(){
        $data['title']  = 'Pengajuan Dalam Proses';
        $data['subtitle']  = 'Daftar Pengajuan Dalam Proses';
        $data['description'] = 'Pengajuan yang menunggu untuk ditinjau oleh dirimu';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $this->template->load('template','approve/index',$data);
    }

    function getApprove(){
        //untuk id yang mengapprove
        $id         = $this->session->_id;
        //filter bidang
        $idBidang   = $this->session->id_bidang;
        //cek hak akses
        $idJabatan  = $this->session->id_jabatan;
        $can = privilegeCheck();
        //hak akses apa saja
        $userCan = array();
        foreach($can as $c){
            $userCan[] = $c->_id;
        }
        //cek bisa approve apa saja
        $canApprove = $this->amodel->canApproveCheck($userCan);
        $userCanApprove = array();
        foreach($canApprove as $approve){
            $userCanApprove[] = $approve->ordinal-1;
        }
        //ammbil data yang akan diapprove????

        // $data = $this->amodel->getApproval($id,$idBidang,$idJabatan);
        $this->output->set_content_type('application/json');
        echo json_encode($userCanApprove);
    }
    function test(){

    }
}
