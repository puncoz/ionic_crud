<?php defined('BASEPATH') OR exit('No direct script access allowed');

		// Enable CORS origin resource sharing
		// header('Access-Control-Allow-Origin: *');
  //  		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
/*
 *	User API Controller
 */
class User extends REST_Controller {

	/*
	 *	Constructor
	 */
	public function __construct() {
		parent::__construct();

		// load User model
		$this->load->model('user_model');
	}

	public function get_users_post() {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    /*
     * API to check registered email
     */
    public function check_email_post() {
    	$email = $this->post('email');
    	$resp = $this->ion_auth->email_check($email);

    	$message = [
            'status' => 200,
            'msg' => ($resp) ? 'false' : 'true'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    /*
     * API to check registered username
     */
    public function check_username_post() {
    	$username = $this->post('username');
    	$resp = $this->ion_auth->username_check($username);

    	$message = [
            'status' => 200,
            'msg' => ($resp) ? 'false' : 'true'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    /*
     * API to register new user
     */
    public function register_user_post() {
    	$username = $this->post('username');
    	$password = $this->post('password');
    	$email  = $this->post('email');
    	$additional_data = array(
							'first_name' => $this->post('firstname'),
							'last_name' => $this->post('lastname'),
						);

    	$user = $this->ion_auth->register($username, $password, $email, $additional_data);

    	if($user) {
    		$user = $this->user_model->login_user($username, $password);
    	}

    	$message = [
            'status' => 200,
            'msg' => ($user) ? $user : 'false'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    /*
     * API to login existing user
     */
    public function login_user_post() {
    	$username = $this->post('username');
    	$password = $this->post('password');

    	$user = $this->user_model->login_user($username, $password);

    	$message = [
            'status' => 200,
            'msg' => ($user) ? $user : 'false'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

} // end of User Class Controller