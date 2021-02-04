<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!is_login())redirect(site_url('login'));
    }

	function index(){
        $data['title']  = 'Cka Pot';
        $data['collapsed'] = '';
        $data['css_files'][] = base_url() . '';
        // $data['css_files'][] = base_url() . 'assets/admin/easyui/themes/icon.css';
        $data['js_files'][] = base_url() . '';
        // $data['js_files'][] = base_url() . 'assets/admin/easyui/datagrid-groupview.js';
        // $data['js_files'][] = base_url() . 'assets/admin/easyui/plugins/datagrid-scrollview.js';
        // $data['js_files'][] = base_url() . 'assets/admin/js/menu.js';
        $this->template->load('template','dashboard',$data);
    }
    function logout(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login','Anda sudah berhasil keluar dari aplikasi');
        $this->load->view('auth/login', 'refresh');
    }
}
