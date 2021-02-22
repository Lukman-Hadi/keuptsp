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
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/extensions/group-by-v2/bootstrap-table-group-by.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/extensions/group-by-v2/bootstrap-table-group-by.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/select2/dist/js/select2.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/form/form.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/pdfobject/pdfobject.min.js';
        $this->template->load('template','transaksi/pengajuanbaru',$data);
    }
	function listPengajuan(){
        $data['title']  = 'DAFTAR PENGAJUAN';
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $this->template->load('template','transaksi/list',$data);
    }

    function addToCart(){
        if($this->session->has_userdata('cart')) {
            $c = array_values(unserialize($this->session->userdata('cart')));
        }
        $id             = uniqid();
        $idProgram      = $this->input->post('id_program')?$this->input->post('id_program'):$c[0]['id_program']; 
        $idKegiatan     = $this->input->post('id_kegiatan')?$this->input->post('id_kegiatan'):$c[0]['id_kegiatan'];
        $idSub          = $this->input->post('id_sub');
        $idRekening     = $this->input->post('id_rekening');
        $jumlah         = $this->input->post('jumlah');
        $nm_program     = $this->input->post('nm_program');
        $nm_kegiatan    = $this->input->post('nm_kegiatan');
        $nm_sub         = $this->input->post('nm_sub');
        $nm_rekening    = $this->input->post('nm_rekening');
        $ma_rekening    = $this->input->post('ma_rekening');
        $data = array();
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
    function addToCartNew(){
        if($this->session->has_userdata('cart')) {
            $c = array_values(unserialize($this->session->userdata('cart')));
        }
        $id             = uniqid();
        $idProgram      = $this->input->post('id_program')?$this->input->post('id_program'):$c[0]['id_program']; 
        $idKegiatan     = $this->input->post('id_kegiatan')?$this->input->post('id_kegiatan'):$c[0]['id_kegiatan'];
        $idSub          = $this->input->post('id_sub')?$this->input->post('id_sub'):$c[0]['id_sub'];
        $idRekening     = $this->input->post('id_rekening');
        $keterangan     = $this->input->post('keterangan');
        $satuan         = $this->input->post('satuan');
        $harga          = str_replace('.','',$this->input->post('harga'));
        $total          = str_replace('.','',$this->input->post('total'));
        $jumlah         = str_replace('.','',$this->input->post('jumlah'));
        $nm_program     = $this->input->post('nm_program');
        $nm_kegiatan    = $this->input->post('nm_kegiatan');
        $nm_sub         = $this->input->post('nm_sub');
        $nm_rekening    = $this->input->post('nm_rekening');
        $ma_rekening    = $this->input->post('ma_rekening');
        if(!$jumlah){
            $jumlah = intval($total)*intval($harga);
        }
        $data = array();
        $data = array(
            '_id'               =>$id,
            'id_program'        =>$idProgram,
            'id_kegiatan'       =>$idKegiatan,
            'id_sub'            =>$idSub,
            'id_rekening'       =>$idRekening,
            'jumlah'            =>$jumlah,
            'total'             =>$total,
            'harga'             =>$harga,
            'satuan'            =>$satuan,
            'keterangan'        =>$keterangan,
            'nm_program'        =>$nm_program,
            'nm_kegiatan'       =>$nm_kegiatan,
            'nm_sub'            =>$nm_sub,
            'nm_rekening'       =>$nm_rekening,
            'ma_rekening'       =>$ma_rekening,
        );
        if(!$this->session->has_userdata('cart')) {
            $cart = array($data);
            $this->session->set_userdata('cart', serialize($cart));
        } else {
            $cart = array_values(unserialize($this->session->userdata('cart')));
            array_push($cart, $data);
            $this->session->set_userdata('cart', serialize($cart));
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
        $this->output->set_content_type('application/json');
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
    function saveOld(){
        // var_dump($this->session->_id);
        $kodePengajuan = 'P-'. uniqid();
        $cart = unserialize($this->session->userdata('cart'));
        $total = 0;
        $data = array();
        $pengajuan = array();
        $dataProgress = array();
        $can = array();
        //optional
        $jaga = privilegeCheck();
        foreach($jaga as $j){
            $can[]= $j->_id;
        }
        $status = $this->tmodel->getStatusProgress();
        if(in_array($status->id_progress,$can)){
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
                'id_user'           => $this->session->_id,
                'status'            => $status->id_progress
            );
            $resDetail = $this->gmodel->insertbatch('tbl_pengajuan_detail',$data);
            if($resDetail){
                $res = $this->db->insert('tbl_pengajuan',$pengajuan);
                $resId = $this->db->insert_id();
                $this->session->unset_userdata('cart');
                $dataProgress = array(
                    'id_pengajuan'  =>$resId,
                    'ordinal'       =>$status->id_progress,
                    'id_user'       =>$this->session->_id,
                );
                if($res){
                    $this->gmodel->insert('tbl_progress_pengajuan',$dataProgress);  
                    echo json_encode(array('message'=>'Add Success'));
                }
                
            }else{
                echo json_encode(array('errorMsg'=>'Gagal'));
            }
        }else{
            $this->session->unset_userdata('cart');
            echo json_encode(array('errorMsg'=>'Anda Tidak Punya Akses'));
        }
    }
    private function rekeningIsExist($id,array $data)
    {
        for ($i = 0; $i < count($data); $i ++) {
            if ($data[$i]['id_rekening'] == $id) {
                return $i;
            }
        }
        return -1;
    }
    function save(){;
        $kodePengajuan = 'P-'. uniqid();
        $cart = unserialize($this->session->userdata('cart'));
        $total = 0;
        $data = array();
        $pengajuan = array();
        $dataProgress = array();
        $can = array();
        $rinci = array();
        $detail = array();
        //optional
        $jaga = privilegeCheck();
        foreach($jaga as $j){
            $can[]= $j->_id;
        }
        $status = $this->tmodel->getStatusProgress();
        if(in_array($status->id_progress,$can)){
            foreach($cart as $content){
                $total += $content['jumlah'];
                $index = $this->rekeningIsExist($content['id_rekening'],$data);
                $rinci = array(
                    'satuan'=>$content['satuan'],
                    'harga'=>$content['harga'],
                    'total'=>$content['total'],
                    'jumlah'=>$content['jumlah'],
                    'keterangan'=>$content['keterangan'],
                );
                if($index >=0){
                    $data[$index]['jumlah'] += $content['jumlah'];
                    $data[$index]['detail'][] = $rinci;
                }else{
                    $data[] = array(
                        'kode_pengajuan'    =>$kodePengajuan,
                        'id_program'        =>$content['id_program'],
                        'id_kegiatan'       =>$content['id_kegiatan'],
                        'id_sub'            =>$content['id_sub'],
                        'id_rekening'       =>$content['id_rekening'],
                        'jumlah'            =>$content['jumlah'],
                        'detail'            =>array($rinci),
                    );
                }
            }
            foreach($data as $d){
                $this->db->insert('tbl_pengajuan_detail',array(
                    'kode_pengajuan'=>$d['kode_pengajuan'],
                    'id_program'=>$d['id_program'],
                    'id_kegiatan'=>$d['id_kegiatan'],
                    'id_sub'=>$d['id_sub'],
                    'id_rekening'=>$d['id_rekening'],
                    'jumlah'=>$d['jumlah'],
                ));
                $inId = $this->db->insert_id();
                foreach($d['detail'] as $dd){
                    $detail[] = array(
                        'id_pengajuan_detail'=>$inId,
                        'keterangan'=>$dd['keterangan'],
                        'harga'=>$dd['harga'],
                        'total'=>$dd['total'],
                        'jumlah'=>$dd['jumlah'],
                    );
                }
            }
            $pengajuan = array(
                'kode_pengajuan'    => $kodePengajuan,
                'total'             => $total,
                'id_bidang'         => $this->session->id_bidang,
                'id_user'           => $this->session->_id,
                'status'            => $status->id_progress
            );
            $resDetail = $this->gmodel->insertbatch('tbl_pengajuan_rincian',$detail);
            if($resDetail){
                $res = $this->db->insert('tbl_pengajuan',$pengajuan);
                $resId = $this->db->insert_id();
                // $this->session->unset_userdata('cart');
                $dataProgress = array(
                    'id_pengajuan'  =>$resId,
                    'ordinal'       =>$status->id_progress,
                    'id_user'       =>$this->session->_id,
                );
                if($res){
                    $this->gmodel->insert('tbl_progress_pengajuan',$dataProgress);  
                    echo json_encode(array('message'=>'Add Success'));
                }      
            }else{
                echo json_encode(array('errorMsg'=>'Gagal'));
            }
        }else{
            $this->session->unset_userdata('cart');
            echo json_encode(array('errorMsg'=>'Anda Tidak Punya Akses'));
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
    function pencairan(){
        $data['title']  = 'DATA PENCAIRAN';     
        $data['subtitle']  = 'List Pengajuan yang Sudah Dicairkan';
        $data['description']  = 'Berikut Adalah Data Pengajuan yang Sudah Dicairkan';
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/select2/dist/css/select2.min.css';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/select2/dist/css/select2-bootstrap.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/select2/dist/js/select2.min.js';
        $this->template->load('template','transaksi/pencairan',$data);
    }

    function showPencairan(){

    }
    function test(){
        $can = array();
        //optional
        $jaga = privilegeCheck();
        foreach($jaga as $j){
            $can[]= $j->_id;
        }
        $status = $this->tmodel->getStatusProgress();
        if(in_array($status->id_progress,$can)){
            echo 'true';
        }else{
            echo 'false';
        }
    }
    //input terbaru//
    function saveBaru(){

    }
}
