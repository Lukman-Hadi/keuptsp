<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!is_login())redirect(site_url('login'));
        $this->load->model('Approve_model','amodel');
        $this->load->model('Transaksi_model','tmodel');
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
        $idBidang   = $this->session->id_bidang;
        $userCanApprove = canApproveCheck();
        $who = superCheck();
        if($who){
            $result['total'] = $this->amodel->getApproveTotal($userCanApprove)->num_rows();
            $item = $this->amodel->getApproval($userCanApprove)->result();
            $result = array_merge($result, ['rows' => $item]);
        }else{
            $result['total'] = $this->amodel->getApproveTotalByBidang($idBidang,$userCanApprove)->num_rows();
            $item = $this->amodel->getApprovalByBidang($idBidang,$userCanApprove)->result();
            $result = array_merge($result, ['rows' => $item]);   
        }
        $this->output->set_content_type('application/json');
        echo json_encode($result);
    }
    function detail(){
        $nPermohonan = $this->uri->segment(3);
        $data['permohonan']= $this->tmodel->getPengajuan($nPermohonan)->row();
        $data['detail']= $this->tmodel->getDetail($nPermohonan)->result();
        $data['title']  = 'PENGAJUAN NO'.$nPermohonan;
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $this->template->load('template','approve/detail',$data);
    }
    //insert to tbl-progress, update tbl pengajuan,
    function test(){
        $idPengajuan    = $this->input->get('_id');
        $note           = $this->input->post('catatan');
        // $idPengajuan = 5;
        $idUser = $this->session->_id;
        $old = $this->db->get_where('tbl_pengajuan',array('_id'=>$idPengajuan))->row();
        $status = $old->status+1;
        $result = $this->gmodel->update('tbl_pengajuan',array('status'=>$status),array('_id'=>$idPengajuan));
        if($result){
            $result = $this->gmodel->insert('tbl_progress_pengajuan',array('id_pengajuan'=>$idPengajuan,'ordinal'=>$status,'id_user'=>$idUser,'catatan'=>$note));
            if($result){
                echo json_encode(array('message'=>'Add Success'));
            }
        }else{
            echo json_encode(array('errorMsg'=>'Gagal'));
        }
    }
}
