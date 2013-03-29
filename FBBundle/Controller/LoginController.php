<?php

namespace FormBuilder\FBBundle\Controller;
require_once('services/user.php');
require_once('services/schema.php');
require_once('services/notification.php');
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use FormBuilder\FBBundle\Controller\services\schema\SubmitField;
use FormBuilder\FBBundle\Controller\services\schema\Form;

class LoginController extends Controller
{
    public function loginAction(){
	$logger = $this->get('logger');
	$request = $this->get('request');
	$email = $request->request->get('email', '');
	$password = $request->request->get('password', '');
	
	$user = user_exist($email, $password, $this->get('database_connection'), $logger);
	if ($user != null){
	    $result = array('responseCode'=>200, "success"=>"true", 'redirect_url'=>$this->get('router')->generate('dashboard'), 'user_info'=>$user);
	    $result = json_encode($result);
	    $response  = new Response($result, 200);
	    $response->headers->set('Content-type', 'application/json');
	    $response->headers->setCookie(new Cookie('formbuilder_login', $user['id']));
	}else{
	    $result = array('responseCode'=>200, "error"=>"true");
	    $result = json_encode($result);
	    $response  = new Response($result, 200);
	    $response->headers->set('Content-type', 'application/json');
	}
	return $response;
    }
    
    public function indexAction(){
	$logger = $this->get('logger');
	$request = $this->get('request');
	$user_id = $request->cookies->get('formbuilder_login');
	if (is_null($user_id)){
	    return $this->render('FormBuilderFBBundle:Login:index.html.twig', array('page_title' => 'Login'));
	}
	return $this->redirect($this->get('router')->generate('dashboard'));
    }
    
    public function registerAction(){
	$logger = $this->get('logger');
	$request = $this->get('request');
	$user_id = $request->cookies->get('formbuilder_login');
	if (is_null($user_id)){
	    return $this->render('FormBuilderFBBundle:Login:register.html.twig', array('page_title' => 'SignUp', 'user_info' => null));
	}
	return $this->redirect($this->get('router')->generate('dashboard'));
    }

    public function create_new_userAction(){
	$logger = $this->get('logger');
	$request = $this->get('request');
	$conn = $this->get('database_connection');
	$email = $request->request->get('email', '');
	$password = $request->request->get('password', '');
	$name = $request->request->get('user_name', '');
	$subdomain_name = $request->request->get('subdomain_name', '');
	try{	
	    $user_id = check_duplicate($email, $conn, $logger);
	    $unique_domain = check_unique_subdomain_name($subdomain_name, $conn, $logger);
	    if (is_null($user_id) and is_null($unique_domain)){
		$query_result = create_new_user($email, $password, $name, $subdomain_name, $conn, $logger);
		if ($query_result){
		    #Sending Signup Email
		    $email_sucess = notification_on_signup(array('to'=>$email, 'name'=>$name), $logger);
		    
		    $result = array('responseCode'=>200, "success"=>"true", 'redirect_url'=>$this->get('router')->generate('dashboard'), 'user_info'=>$query_result);
		    $result = json_encode($result);
		    $response  = new Response($result, 200);
		    $response->headers->set('Content-type', 'application/json');
		    $response->headers->setCookie(new Cookie('formbuilder_login', $query_result['id']));
		    return $response;
		}else{
		    $result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Error while creating new user.Please try again after sometime.');
		}
	    }else{
		if ($user_id and $unique_domain){
		    $reason = 'User with this email id and subdomain name already exist.';
		}elseif ($unique_domain){
		    $reason = 'User with this subdomain name already exist.';
		}elseif($user_id){
		    $reason = 'User with this email id already exist.';
		}
		$result = array('responseCode'=>200, "error"=>"true", 'reason'=>$reason);
	    }
	}catch (Exception $e){
	    $logger.err('Error occured while creating new user ' . $e);
	    $result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Error while creating new user.Please try again after sometime.');
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
    }

    public function forgot_passwordAction(){
	$logger = $this->get('logger');
	$logger->info('Forgot Password request received');
	return $this->render('FormBuilderFBBundle:Login:forgot_password.html.twig', array('page_title' => 'Forgot Password', 'user_info'=>null));
    }
    
    public function send_password_emailAction(){
	$logger = $this->get('logger');
	$logger->info('send password email request received');
	try{
	    $conn = $this->get('database_connection');
	    
	    $request = $this->get('request');
	    $email = $request->request->get('email', '');

	    $user_id = check_user_exist_with_email($email, $conn, $logger);
	    
	    if ($user_id != null){
		$new_password = generate_password($logger);
		$logger->info('New passwors is' . $new_password);
		$result = change_password($user_id, md5($new_password), $conn, $logger);
		$email_success = notification_on_forgot_password($email, $new_password, $logger);
		if ($result and $email_success){
		    $result = array('responseCode'=>200, "success"=>"true");
		    $result = json_encode($result);
		    $response  = new Response($result, 200);
		    $response->headers->set('Content-type', 'application/json');
		    return $response;
		}else{
		    $result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Error while sending an email.');
		}
	    }else{
		$result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Email Id does not exist in the system.');
	    }
	}catch(Exception $e){
	    $logger->err("Exception in send_password_emailAction " . $e);
	    $result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Error while sending an email.');
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
    }
    public function logoutAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$request = $this->get('request');
	$user_id = $request->request->get('user_id', '');

	if ($user_id){
	    $result = array('responseCode'=>200, "success"=>"true", 'redirect_link'=>$this->get('router')->generate('login'));
	    $result = json_encode($result);
	    $response  = new Response($result, 200);
	    $response->headers->set('Content-type', 'application/json');
	    $response->headers->clearCookie('formbuilder_login');
	    return $response;
	}else{
	    $result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Error while logging out.');
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;

    }
    public function change_passwordAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$request = $this->get('request');
	$user_id = $request->request->get('user_id', '');
	$new_password = $request->request->get('new_password', '');

	$result = change_password($user_id, $new_password, $conn, $logger);
	if ($result){
	    $result = array('responseCode'=>200, "success"=>"true", 'redirect_link'=>$this->get('router')->generate('login'));
	}else{
	    $result = array('responseCode'=>200, "error"=>"true", 'reason'=>'Same password exist already.Please enter another one.');
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
    }
    function passwordAction(){
	try{
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');

	$request = $this->get('request');
	$user_id = $request->cookies->get('formbuilder_login');
	$user_info = null;

	if (!is_null($user_id)){
	    $user_info = get_user_with_id($user_id, $conn, $logger);
	    return $this->render('FormBuilderFBBundle:Login:change_password.html.twig', array('page_title' => 'Change Password', 'user_info'=>$user_info));
	}
	}catch(Exception $e){
	    $logger->err("Error in password action " . $e);
	}
	return $this->render('FormBuilderFBBundle:Form:error.html.twig', array('page_title'=>'Error', 'user_info'=>$user_info));

    }
    public function gethtmltestAction(){
	$submit_obj = new SubmitField('test', 'sarang');
	$form_obj = new Form('test');
	$form_obj->addElement($submit_obj);
	$result_html = $form_obj->generateHTML();
	return $this->render('FormBuilderFBBundle:Login:loggedin.html.twig', array('page_title' => 'LoggedIn', 'result_html' => $result_html));
    }
}
