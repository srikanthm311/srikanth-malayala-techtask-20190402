<?php



class Sociallogin_model extends CI_Model {


	function __construct(){
		 parent::__construct();

	}

	function ifUserExists($data)
	{
		if($this->db->get_where("users",array("email"=>$data["email"]))->num_rows()>0)
		{
			if($this->db->get_where("users",array("email"=>$data["email"]))->num_rows()>0)
				return 1;
			else
				return -1;
		}
		else
			return 0;
	}



	function Login($data)
	{
		$user = $this->db->get_where("users",array("email"=>$data["email"]))->row_array();
		/*echo "<pre>";
		print_r ($user);
		echo "</pre>"; exit;*/
		if($user)
		{
			$this->session->set_userdata(array('user_id'=>$user['id'], 'email'=>$user['email']));
		}
		redirect('sociallogin','refresh');
	}

	public function facebook_insert($user='')
	{
		/*echo "<pre>";
		print_r ($user);
		echo "</pre>"; exit;*/

		$url = "https://graph.facebook.com/".$user['id']."/picture?type=large";
		$path="./profiles/";			
		$img = time().'.jpg';

   	    file_put_contents($path.$img, file_get_contents($url));			

		$this->db->insert('users', array('id'=>$user['id'], 'name'=>$user['name'], 'email'=>$user['email'], "pic"=>  "profiles/".$img,));

		$this->session->set_userdata(array('user_id'=>$user['id'], 'email'=>$user['email']));
		redirect('sociallogin','refresh');

	}

	public function getuserdata($id='')
	{
		$this->db->select('*');
		$this->db->where('email', $id);
		$result = $this->db->get('users')->row_array();
		//echo $this->db->last_query();
		return $result;
	}

}