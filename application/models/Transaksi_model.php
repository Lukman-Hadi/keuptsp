<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }
    function getAll(){
        $offset = $this->input->get('offset')!=null ? intval($this->input->get('offset')) : 0;
        $limit = $this->input->get('limit')!=null ? intval($this->input->get('limit')) : 20;
        $sort = $this->input->get('sort')!=null ? strval($this->input->get('sort')) : 'tp._id';
        $order = $this->input->get('order')!=null ? strval($this->input->get('order')) : 'DESC';
        $search = $this->input->get('search')!=null ? strval($this->input->get('search')) : '';
        $this->db->select('tp.*,us.nama_user,bd.nama_bidang,nama_progress');
        $this->db->from('tbl_pengajuan tp');
        $this->db->join('tbl_users us', 'us._id = tp.id_user','LEFT');
        $this->db->join('tbl_bidang bd', 'bd._id = tp.id_bidang','LEFT');
        $this->db->join('tbl_alur al', 'al._id = tp.status','LEFT');
        $this->db->join('tbl_progress prg', 'prg._id = al.id_progress','LEFT');
        if($this->input->get('search')){
            $this->db->group_start();
            $this->db->like('tp.kode_pengajuan',$search,'both');
            $this->db->or_like('us.nama_user',$search,'both');
            $this->db->or_like('bd.nama_bidang',$search,'both');
            $this->db->group_end();
        }
        $result['total'] = $this->db->get()->num_rows();
        $this->db->select('tp.*,us.nama_user,bd.nama_bidang,nama_progress');
        $this->db->from('tbl_pengajuan tp');
        $this->db->join('tbl_users us', 'us._id = tp.id_user','LEFT');
        $this->db->join('tbl_bidang bd', 'bd._id = tp.id_bidang','LEFT');
        $this->db->join('tbl_alur al', 'al._id = tp.status','LEFT');
        $this->db->join('tbl_progress prg', 'prg._id = al.id_progress','LEFT');
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
    function getIsRekening($id){
        $this->db->select('*');
        $this->db->from('tbl_rekening_kegiatan');
        $this->db->where('id_sub',$id);
        $this->db->where('status','1');
        $query = $this->db->get();
        return $query;
    }
    function getDetail($nPermohonan){
        $this->db->select('nama_program, kode_rekening, kode_kegiatan, nama_kegiatan, nama_sub, nama_rekening, jumlah, pagu, (SELECT SUM(jumlah) from tbl_pengajuan_detail pds WHERE pds.id_rekening = pd.id_rekening) as total');
        $this->db->from('tbl_pengajuan_detail pd');
        $this->db->join('tbl_program p','p._id = pd.id_program');
        $this->db->join('tbl_kegiatan k','k._id = pd.id_kegiatan');
        $this->db->join('tbl_sub_kegiatan s','s._id = pd.id_sub');
        $this->db->join('tbl_rekening_kegiatan r','r._id = pd.id_rekening');
        $this->db->where('pd.kode_pengajuan',$nPermohonan);
        return $this->db->get();
    }
    function getPengajuan($nPermohonan){
        $this->db->select('tp.*,us.nama_user,bd.nama_bidang,nama_progress');
        $this->db->from('tbl_pengajuan tp');
        $this->db->join('tbl_users us', 'us._id = tp.id_user','LEFT');
        $this->db->join('tbl_bidang bd', 'bd._id = tp.id_bidang','LEFT');
        $this->db->join('tbl_alur al', 'al._id = tp.status','LEFT');
        $this->db->join('tbl_progress prg', 'prg._id = al.id_progress','LEFT');
        $this->db->where('tp.kode_pengajuan',$nPermohonan);
        return $this->db->get();
    }
    function getStatusProgress(){
        $this->db->select('*');
        $this->db->from('tbl_alur');
        $this->db->order_by('ordinal','ASC');
        $this->db->limit(1,0);
        return $this->db->get()->row();
    }
}