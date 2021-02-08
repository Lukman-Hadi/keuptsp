<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!is_login())redirect(site_url('login'));
        $this->load->model('Global_model','gmodel');
        $this->load->model('Rekening_model','rmodel');
        $this->load->model('Transaksi_model','tmodel');
    }

	// function index(){
    //     $data['title']  = 'DATA PROGRAM';
    //     $data['collapsed'] = '';
    //     $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
    //     $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
    //     $this->template->load('template','master/program',$data);
    // }
	function index(){
        $data['title']  = 'ENTRY PENGAJUAN';
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/select2/dist/css/select2.min.css';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/select2/dist/css/select2-bootstrap.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/select2/dist/js/select2.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/extensions/editable/bootstrap-editable.min.js';
        $this->template->load('template','transaksi/pengajuan',$data);
    }
	function listPengajuan(){
        $data['title']  = 'DAFTAR PENGAJUAN';
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $this->template->load('template','transaksi/list',$data);
    }

    function addToCart(){
        $id             = uniqid();
        $idProgram      = $this->input->post('id_program'); 
        $idKegiatan     = $this->input->post('id_kegiatan');
        $idSub          = $this->input->post('id_sub');
        $idRekening     = $this->input->post('id_rekening');
        $jumlah         = $this->input->post('jumlah');
        $nm_program     = $this->input->post('nm_program');
        $nm_kegiatan    = $this->input->post('nm_kegiatan');
        $nm_sub         = $this->input->post('nm_sub');
        $nm_rekening    = $this->input->post('nm_rekening');
        $ma_rekening    = $this->input->post('ma_rekening');
        $data = array(
            '_id'               =>$id,
            'id_program'        =>$idProgram,
            'id_kegiatan'       =>$idKegiatan,
            'id_sub'            =>$idSub,
            'id_rekening'       =>$idRekening,
            'jumlah'            =>str_replace('.','',$jumlah),
            'nm_program'        =>$nm_program,
            'nm_kegiatan'       =>$nm_kegiatan,
            'nm_sub'            =>$nm_sub,
            'nm_rekening'       =>$nm_rekening,
            'ma_rekening'       =>$ma_rekening,
        );
        if(!$this->session->has_userdata('cart')) {
            $cart = array($data);
            $result = $this->session->set_userdata('cart', serialize($cart));
        } else {
            $index = $this->cartIsExist($idRekening);
            $cart = array_values(unserialize($this->session->userdata('cart')));
            if($index == -1) {
                array_push($cart, $data);
                $result = $this->session->set_userdata('cart', serialize($cart));
            } else {
                $cart[$index]['jumlah']+=str_replace('.','',$jumlah);
                $result = $this->session->set_userdata('cart', serialize($cart));
            }
        }
        echo json_encode(array('message'=>'Add Success'));
    }
    private function cartIsExist($id)
    {
        $cart = array_values(unserialize($this->session->userdata('cart')));
        for ($i = 0; $i < count($cart); $i ++) {
            if ($cart[$i]['id_rekening'] == $id) {
                return $i;
            }
        }
        return -1;
    }
    function remove(){
        $id = $this->input->post('id');
        $cart = array_values(unserialize($this->session->userdata('cart')));
        for ($i = 0; $i < count($cart); $i ++) {
            if ($cart[$i]['_id'] == $id) {
                unset($cart[$i]);
                $this->session->set_userdata('cart', serialize($cart));
                echo json_encode(array('message'=>'Add Success'));
                break;
            }
        }
    }
    function show(){
        $cart = array();
        if($this->session->userdata('cart')){
            $cart = array_values(unserialize($this->session->userdata('cart')));
            echo json_encode($cart);
        }else{
            echo json_encode($cart);
        }
    }
    function showAll(){
        $result =  $this->tmodel->getAll();
        echo json_encode($result);
    }
    function save(){
        // var_dump($this->session->_id);
        $kodePengajuan = 'P-'. uniqid();
        $cart = unserialize($this->session->userdata('cart'));
        $total = 0;
        foreach($cart as $content){
            $total += $content['jumlah'];
            $data[] = array(
                'kode_pengajuan'    =>$kodePengajuan,
                'id_program'        =>$content['id_program'],
                'id_kegiatan'       =>$content['id_kegiatan'],
                'id_sub'            =>$content['id_sub'],
                'id_rekening'       =>$content['id_rekening'],
                'jumlah'            =>$content['jumlah']
            );
        }
        $pengajuan = array(
            'kode_pengajuan'    => $kodePengajuan,
            'total'             => $total,
            'id_bidang'         => $this->session->id_bidang,
            'id_user'           => $this->session->_id
        );
        $resDetail = $this->gmodel->insertbatch('tbl_pengajuan_detail',$data);
        if($resDetail){
            $res = $this->db->insert('tbl_pengajuan',$pengajuan);
            $resId = $this->db->insert_id();
            $this->session->unset_userdata('cart');
            $dataProgress = array(
                'id_pengajuan'  =>$resId,
                'id_progress'   =>1,
                'id_user'       =>$this->session->_id,
            );
            if($res){
                $this->gmodel->insert('tbl_progress_pengajuan',$dataProgress);  
                echo json_encode(array('message'=>'Add Success'));
            }
            
        }else{
            echo json_encode(array('message'=>'Gagal'));;
        }
    }
    function detail(){
        $nPermohonan = $this->uri->segment(3);
        $data['permohonan']= $this->tmodel->getPengajuan($nPermohonan)->row();
        $data['detail']= $this->tmodel->getDetail($nPermohonan)->result();
        $data['title']  = 'PENGAJUAN NO'.$nPermohonan;
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $this->template->load('template','transaksi/detail',$data);
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
    function isRekening(){
        $id = $this->input->get('id');
        $this->output->set_content_type('application/json');
        $data = $this->rmodel->getIsRekening($id)->result();
        echo json_encode($data);
    }
}
