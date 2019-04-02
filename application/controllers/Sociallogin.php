<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sociallogin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
		$this->load->model("Sociallogin_model");
		ob_start();
	}

	public function index($value='')
	{
		$data = array();
		if ($this->session->userdata('email')) 
		{
			$data['userdata'] = $this->Sociallogin_model->getuserdata($this->session->userdata('email'));
			
		}
		$this->load->view('index', $data);
	}

	public function facebook()
	{
	  require_once APPPATH.'third_party/facebook-sdk-v5/autoload.php';
	  $fb = new Facebook\Facebook([  'app_id' => '2077631482356625',   'app_secret' => '9ea016df11e84e736f5d5c076a3c7e2a',  'default_graph_version' => 'v2.2',  ]);
	  $helper = $fb->getRedirectLoginHelper();
	  $permissions = ['email']; // Optional permissions
	  $loginUrl = $helper->getLoginUrl(base_url('sociallogin/handle_facebook_login'), $permissions);
	  redirect($loginUrl);
	}

	public function handle_facebook_login()
	{

		require_once APPPATH.'third_party/facebook-sdk-v5/autoload.php';
		$fb = new Facebook\Facebook([  'app_id' => '2077631482356625',   'app_secret' => '9ea016df11e84e736f5d5c076a3c7e2a',  'default_graph_version' => 'v2.2',  ]);
		$helper = $fb->getRedirectLoginHelper();
		try { $accessToken = $helper->getAccessToken();}
		catch(Facebook\Exceptions\FacebookResponseException $e) {	  echo 'Graph returned an error: ' . $e->getMessage();		  exit;		} 
		catch(Facebook\Exceptions\FacebookSDKException $e) {	  echo 'Facebook SDK returned an error: ' . $e->getMessage();		  exit;		}
		
		if (! isset($accessToken)) {
		  if ($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  } else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		  }
		  exit;
		}
		
		try {  $response = $fb->get('/me?fields=id,name,email', $accessToken->getValue());}
		catch(\Facebook\Exceptions\FacebookResponseException $e)  {		  echo 'Graph returned an error: ' . $e->getMessage();		  exit;		} 
		catch(\Facebook\Exceptions\FacebookSDKException $e) {		  echo 'Facebook SDK returned an error: ' . $e->getMessage();		  exit;		}

        $facebook_user = $response->getGraphUser();

		//echo '<pre>';print_r($facebook_user);exit;

		$access_token = $accessToken->getValue();
		//$this->Sociallogin_model->facebook_insert($facebook_user);

		

		/*echo "<pre>";
		print_r ($this->session->userdata());
		echo "</pre>"; exit;*/

		
		if($facebook_user)
		{
			switch($this->Sociallogin_model->ifUserExists($facebook_user))
			{
				case 0:
			     $userInfo=$this->Sociallogin_model->facebook_insert($facebook_user,"facebook");break;

				case -1:
						die("Disabled user");break;

				case 1:
						$this->Sociallogin_model->Login($facebook_user);break;

			}
			redirect('sociallogin','refresh');
		}

	}

    public function logout($value='')
    {
    	$this->session->unset_userdata('user_id');
    	$this->session->unset_userdata('email');

    	redirect('Sociallogin/index','refresh');
    }


}