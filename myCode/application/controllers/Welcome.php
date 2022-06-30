<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	public function __construct() {
        parent::__construct();
        $this->load->model('my_model');
        //$this->load->model('subscribe_model');
        $this->load->library('session');

    }
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function showCounts(){
		//comments : 
		//Active user : status is 1 in table users
		//Verified user : isVerified is 1 in table users
		//Product Active : status is 1 in table products
		//User Active and Verified : userActVer is 1 in table usersProducts
		//Product Active : productAct is 1 in table usersProducts 
		$cntAVU = $this->my_model->getActiveVerifiedUser();
		$cntAVUAP = $this->my_model->getActiveVerifiedUser_ActiveProducts();
		$cntAP = $this->my_model->getActiveProducts();
		$cntAPNU = $this->my_model->getActiveProducts_noUser();
		$amtAAP = $this->my_model->getAmtActiveAttachedProducts();
		$sumAmtAAP = $this->my_model->getSumAmtActiveProducts();
		$sumAmtAAPU = $this->my_model->getSumAmtAllActiveProductsUser();

		$data['cntAVU'] = $cntAVU;
		$data['cntAVUAP'] = $cntAVUAP;
		$data['cntAP'] = $cntAP;
		$data['cntAPNU'] = $cntAPNU;
		$data['amtAAP'] = $amtAAP;
		$data['sumAmtAAP'] = $sumAmtAAP;
		$data['sumAmtAAPU'] = $sumAmtAAPU;


		$rateArr = array();
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to=RON&from=EUR&amount=1",
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: text/plain",
		    "apikey: oJohuqJguMTrs4WE8KgzRz3CK2EC8DsP"
		  ),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET"
		));

		$response_ron = curl_exec($curl);

		$jd_ron = json_decode($response_ron);
		if($jd_ron->success==true){
			$rateArr['ron'] = $jd_ron->result;
		}



		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to=USD&from=EUR&amount=1",
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: text/plain",
		    "apikey: oJohuqJguMTrs4WE8KgzRz3CK2EC8DsP"
		  ),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET"
		));

		$response_usd = curl_exec($curl);

		curl_close($curl);
		
		$jd_usd = json_decode($response_usd);
		if($jd_usd->success==true){
			$rateArr['usd'] = $jd_usd->result;
		}

		$data['rateArr'] = $rateArr;

		$this->load->view('appDataCnt',$data);	
	}
}
