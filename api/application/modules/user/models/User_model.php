<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *	User Model
 */
class User_model extends CI_Model {

	/*
	 *	Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/*
	 * Login User
	 */
	public function login_user($identity, $password) {
		if ($this->ion_auth->login($identity, $password)) {
			// login is successful
			// create session_key and update in database
			return $this->generate_session($identity);
		} else {
			// login was un-successful
			return false;
		}
	}

	/*
	 * Generate Session key
	 */
	private function generate_session($username) {
		$key = md5(microtime().rand());
		if(!$this->checkUserExists($username)) {
			// echo mdate("%Y-%m-%d %H:%i:%s", now());
			$this->db->insert('rest_user_loggedin', array('username'=>$username, 'sess_key'=>$key, 'date_loggedin'=>mdate("%Y-%m-%d %H:%i:%s", now())));
		} else {
			$this->db->where('username', $username);
			$this->db->update('rest_user_loggedin', array('sess_key'=>$key, 'date_loggedin'=>mdate("%Y-%m-%d %H:%i:%s", now())));
		}
		return $key;
	}

	/*
	 * Check for user already logged in or not
	 */
	private function checkUserExists($username) {
		$count = $this->db->where('username', $username)
					->from('rest_user_loggedin')
					->count_all_results();

		return ($count > 0) ? true : false;
	}

} // end of Username Model