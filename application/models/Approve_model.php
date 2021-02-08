<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approve_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function canApproveCheck($idProgress){
        $this->db->select('ordinal');
        $this->db->from('tbl_alur');
        $this->db->where_in('id_progress',$idProgress);
        return $this->db->get()->result();
    }
    function getApproval($id,$bidang,$jabatan){
        $offset = $this->input->get('offset')!=null ? intval($this->input->get('offset')) : 0;
        $limit = $this->input->get('limit')!=null ? intval($this->input->get('limit')) : 10;
        $sort = $this->input->get('sort')!=null ? strval($this->input->get('sort')) : 'k._id';
        $order = $this->input->get('order')!=null ? strval($this->input->get('order')) : 'DESC';
        $search = $this->input->get('search')!=null ? strval($this->input->get('search')) : '';

        $this->db->select('tp.*,us.nama_user, bd.nama_bidang,(SELECT prg.nama_progress FROM tbl_progress_pengajuan AS tpp JOIN tbl_progress AS prg ON prg._id = tpp.id_progress WHERE tpp.id_pengajuan = tp._id ORDER BY tpp._id DESC LIMIT 1) AS status');
        $this->db->from('tbl_pengajuan tp');
        $this->db->join('tbl_users us', 'us._id = tp.id_user','LEFT');
        $this->db->join('tbl_bidang bd', 'bd._id = tp.id_bidang','LEFT');
        if($this->input->get('search')){
            $this->db->group_start();
            $this->db->like('tp.kode_pengajuan',$search,'both');
            $this->db->or_like('us.nama_user',$search,'both');
            $this->db->or_like('bd.nama_bidang',$search,'both');
            $this->db->group_end();
        }
        $result['total'] = $this->db->get()->num_rows();

        $this->db->select('tp.*,us.nama_user, bd.nama_bidang,(SELECT prg.nama_progress FROM tbl_progress_pengajuan AS tpp JOIN tbl_progress AS prg ON prg._id = tpp.id_progress WHERE tpp.id_pengajuan = tp._id ORDER BY tpp._id DESC LIMIT 1) AS status');
        $this->db->from('tbl_pengajuan tp');
        $this->db->join('tbl_users us', 'us._id = tp.id_user','LEFT');
        $this->db->join('tbl_bidang bd', 'bd._id = tp.id_bidang','LEFT');
        if($this->input->get('search')){
            $this->db->group_start();
            $this->db->like('tp.kode_pengajuan',$search,'both');
            $this->db->or_like('us.nama_user',$search,'both');
            $this->db->or_like('bd.nama_bidang',$search,'both');
            $this->db->group_end();
        }
        $this->db->order_by($sort,$order);
        $this->db->limit($limit,$offset);
        $query=$this->db->get();
        $item = $query->result_array();    
        $result = array_merge($result, ['rows' => $item]);
        return $result;
    }
}