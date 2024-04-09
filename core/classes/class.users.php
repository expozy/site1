<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class Users
 * ========================================================== */
class Users
{

	public $logged_in = null;
	public $uid = 0;
	public $sesid;
	public $email;
	public $names;
	public $userlevel = 0;
	public $token;

	const LEVEL_ADMIN = 99;

	function __construct()
	{
		$this->_start_session();
	}

	private function _start_session()
	{
		/* if (strlen(session_id()) < 1)
			@session_start(); */


		$this->logged_in = $this->login_check();

		if (!$this->logged_in) {
			$this->email = $_SESSION['email'] = "";
			$this->sesid = sha1(session_id());
			$this->userlevel = 0;
			$this->addresses ='';
		}
	}

	////////////////////////////////////////////////////////////////
	private function login_check()
	{

		if(isset($_SESSION['email']) && $_SESSION['email'] != "") {
			$row = $this->get_user_info($_SESSION['email']);

			$this->uid = $row['id'];
			$this->email = $row['email'];
			$this->names = $row['first_name'] . ' ' . $row['last_name'];
			$this->first_name = $row['first_name'];
			$this->last_name = $row['last_name'];
			$this->phone = $row['phone'];
			// $this->post_code = $row['address']['post_code'];
			$this->balance = $row['balance']??'';
			$this->userlevel = $row['userlevel'];
			$this->sesid = sha1(session_id());
			$this->token = $row['token'];

			$this->addresses = $row['addresses'];
			$this->company = $row['company'];
			return true;
		} else {
			if(isset($_COOKIE['email'])){
				$row = $this->get_user_info($_COOKIE['email']);
				$this->uid = $_SESSION['uid'] = $row['id'];
				$this->email = $_SESSION['email'] = $row['email'];
				$this->names = $_SESSION['names'] = $row['first_name'] . ' ' . $row['last_name'];
				$this->balance = $_SESSION['balance'] = $row['balance'];
				$this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
				$this->token = $_SESSION['token'] = $row['token'];
				$this->sesid = sha1(session_id());
				return true;
			} else {
				return false;
			}
		}
	}

	/** =========================================================
	 * Function : login()
	 *
	 * @param $email
	 * @param $password
	 *
	 * @return array
	========================================================== */
	public function login($email, $password)
	{
		global $core;

		$result = Api::data(['password'=> post('password'),
		    'email'=> post('email')])->post()->login();


		if(isset($result['status']) && $result['status']==1){

			$row = $result['user'];

			if(post('remember')){

				$this->uid = $_SESSION['uid'] = $row['id'];
				$this->email = $_SESSION['email'] = $row['email'];
				$this->names = $_SESSION['names'] = $row['first_name'] . ' ' . $row['last_name'];
				$this->balance = $_SESSION['balance'] = $row['balance'];
				$this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
				$this->lastlogin = date('Y-m-d H:i:s');

				$_SESSION['site_id'] = $core->site_id;
				$this->token = $_SESSION['token'] = $result['token'];
				setcookie('email', ($_SESSION['email']), time() + (86400 * 30));
			}else{

				$this->uid = $_SESSION['uid'] = $row['id'];
				$this->email = $_SESSION['email'] = $row['email'];
				$this->names = $_SESSION['names'] = $row['first_name'] . ' ' . $row['last_name'];
				$this->balance = $_SESSION['balance'] = $row['balance'];
				$this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
				$this->lastlogin = date('Y-m-d H:i:s');
				$_SESSION['site_id'] = $core->site_id;
				$this->token = $_SESSION['token'] = $result['token'];
			}

			if(post('is_mobile') && post('is_mobile') == 1){
				$return = array(
					'status' => 1,
					'msg' => '',
					'user' => $row,
				);
			} else {
				$return = array(
					'status' => 1,
					'msg' => '',
				);
			}

		} else {
			if(post('is_mobile') && post('is_mobile') == 1){
				$return = array(
					'status' => 0,
					'msg' => 'Грешно потребителско име или парола!'
				);
			} else {
				$return = array(
					'status' => 0,
					//'error_fields' => $result['error'],
				);
			}

		}

		return $return;
	}
	
	public function loginByToken(string $token)
	{
		global $core;

		$row = Api::get()->users();

		if(!$row) return;

		$this->logged_in = true;
		$this->uid = $_SESSION['uid'] = $row['id'];
		$this->email = $_SESSION['email'] = $row['email'];
		$this->names = $_SESSION['names'] = $row['first_name'] . ' ' . $row['last_name'];
		$this->balance = $_SESSION['balance'] = $row['balance'];
		$this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
		$this->lastlogin = date('Y-m-d H:i:s');
		$_SESSION['site_id'] = $core->site_id;
		$this->token = $_SESSION['token'] = $row['token'];

	}

	/** =========================================================
	 * Function : logout()
	 * ========================================================== */
	public function logout()
	{
		setcookie('email', $_SESSION['email'], time()-3600);

		unset($_SESSION['uid']);
		unset($_SESSION['email']);
		unset($_SESSION['names']);
		unset($_SESSION['balance']);
		unset($_SESSION['userlevel']);

		//session_regenerate_id();
		session_destroy();

		$this->logged_in = false;
		$this->email = "";
		$this->names = "";
		$this->balance = 0;
		$this->userlevel = 0;
	}

	/** =========================================================
	 * Function : get_user_info()
	 *
	 * @param $email
	 *
	 * @return array|bool|int|null
	========================================================== */
	public function get_user_info($email)
	{
		return Api::data(['email'=>$email])->get()->accounts();
	}


	public function is_admin() {
		return $this->userlevel >= self::LEVEL_ADMIN  ? true : false;
	}
	public function is_superAdmin() {
		return $this->userlevel > self::LEVEL_ADMIN  ? true : false;
	}

	public function hasAccess(){
	    global $core;

	    $ret = Api::data(['do'=>$core->do])->get()->admin_menu_access();
	    //var_dump($ret);
	    return filter_var($ret, FILTER_VALIDATE_BOOLEAN);
	}

}
?>
