<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class My_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

   	function getActiveVerifiedUser(){
		$result=$this->db->query("SELECT * FROM users WHERE status=1 and isVerified=1");
        return $result->num_rows($result);
	}

    function getActiveVerifiedUser_ActiveProducts(){
        $result=$this->db->query("SELECT * FROM usersProducts WHERE userActVer=1 and productAct=1");
        return $result->num_rows($result);
    }

    function getActiveProducts(){
        $result=$this->db->query("SELECT * FROM products WHERE status=1");
        return $result->num_rows($result);
    }

    function getActiveProducts_noUser(){
        $result=$this->db->query("SELECT * FROM products WHERE status=1 and id not in (select distinct pid from usersProducts)");
        return $result->num_rows($result);
    }

    function getAmtActiveAttachedProducts(){
        $result=$this->db->query("SELECT sum(productCnt) as sCnt FROM usersProducts WHERE productAct=1");
        return $result->row()->sCnt;
    }

    function getSumAmtActiveProducts(){
        $result=$this->db->query("SELECT sum(productCnt*price) as sCnt FROM usersProducts WHERE productAct=1");
        return $result->row()->sCnt;
    }

    function getSumAmtAllActiveProductsUser(){
        $result=$this->db->query("select username,sCnt from users u inner join (SELECT uid,sum(productCnt*price) as sCnt FROM usersProducts WHERE productAct=1 group by uid) up on u.id=up.uid");
        return $result->result();
    }
}
?>