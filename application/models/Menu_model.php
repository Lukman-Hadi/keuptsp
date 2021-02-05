<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getMenus()
    {
    	$query='SELECT * FROM tbl_menus WHERE id_main=1 AND status = 1 ORDER BY `ordinal` ASC';
    	return $this->db->query($query);
    }

    function getSubMenus($is_main)
    {
    	$this->db->from('tbl_menus');
    	$this->db->where('id_main',$is_main);
    	return $this->db->get();
    }
}