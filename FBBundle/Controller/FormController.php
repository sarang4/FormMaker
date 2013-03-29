<?php

namespace FormBuilder\FBBundle\Controller;
require_once('services/user.php');
require_once('services/form.php');
require_once('services/schema.php');
require_once('services/data.php');
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use FormBuilder\FBBundle\Controller\services\schema\SubmitField;
use FormBuilder\FBBundle\Controller\services\schema\TextField;
use FormBuilder\FBBundle\Controller\services\schema\TextAreaField;
use FormBuilder\FBBundle\Controller\services\schema\ResetField;
use FormBuilder\FBBundle\Controller\services\schema\PasswordField;
use FormBuilder\FBBundle\Controller\services\schema\NumberField;
use FormBuilder\FBBundle\Controller\services\schema\FileField;
use FormBuilder\FBBundle\Controller\services\schema\RadioField;
use FormBuilder\FBBundle\Controller\services\schema\CheckBoxField;
use FormBuilder\FBBundle\Controller\services\schema\LabelField;
use FormBuilder\FBBundle\Controller\services\schema\OptionField;
use FormBuilder\FBBundle\Controller\services\schema\SelectField;
use FormBuilder\FBBundle\Controller\services\schema\CheckboxGroup;
use FormBuilder\FBBundle\Controller\services\schema\RadioboxGroup;
use FormBuilder\FBBundle\Controller\services\schema\Form;

class FormController extends Controller
{
    protected function detect_user_session($request, $conn, $logger){
	$user_id = $request->cookies->get('formbuilder_login');
	if (!is_null($user_id)){
	    return $user_id;
	}
	return null;
    }
    public function dashboardAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$all_forms = array();
	$user_info = null;
	try{	
	    $request = $this->get('request');
	    $user_id = $this->detect_user_session($request, $conn, $logger);
	    if (!is_null($user_id) and check_user_admin($user_id, $conn, $logger)){
		$user_info = get_user_with_id($user_id, $conn, $logger);
		$all_forms = get_all_forms($user_id, $conn, $logger);
		return $this->render('FormBuilderFBBundle:Form:dashboard.html.twig', array('all_forms'=>$all_forms, 
					'length'=>count($all_forms), 'page_title' => 'Dashboard', 'user_info'=>$user_info));
	    }
	}catch(Exception $e){
	    $logger->err("Error while saving form data " . $e);
	}
	return $this->render('FormBuilderFBBundle:Form:error.html.twig', array('page_title'=>'Error', 'user_info'=>$user_info));
    }
    public function createAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');

	$request = $this->get('request');
	$form_title = $request->request->get('title', 'Untitled Form');
	$description = $request->request->get('description', '');
	$form_alignment = $request->request->get('form_alignment', '');
	$thankyou_message = $request->request->get('thankyou_message', '');
	$field_dict = $request->request->get('field_dict');
	$field_count = $request->request->get('count');
	
	$logger->info('Create Form Request Received');
	try{
	    $user_id = $this->detect_user_session($request, $conn, $logger);
	    if (is_null($user_id)){
		$result = array('responseCode'=>200, "success"=>"true", 'error'=>'Please login before creating a form', 'redirect_url'=>'/login');
	    }else{
		if (check_user_admin($user_id, $conn, $logger)){
		    $form_id = create_form($user_id, $form_title, $description, $form_alignment,$thankyou_message, $field_dict, $field_count, $conn, $logger);
		    if ($form_id){
			$result = array('responseCode'=>200, "success"=>"true", 'form_id'=>$form_id);
		    }else{
			$result = array('responseCode'=>200, "success"=>"true", 'error'=>'Form does not created.');
		    }
		}else{
		    $result = array('responseCode'=>200, "success"=>"true", 'error' => 'You do not have permission to create a Form');
		}
	    }
	}catch (Exception $e){
	    $logger->err ("Error in creating a form " . $e);
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
    }
    public function showAction($form_id){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');

	$logger->info('Show Form Request Received ' . $form_id);
	try{
	    $form_details = get_form_details($form_id, $conn, $logger);
	    $form_elements = get_form_elements($form_id, $conn, $logger);
	    $form_obj = new Form($form_details['action'], $form_details['enctype'], $form_details['method'], 
				    $form_details['title'], $form_details['target'], $form_details['id'], 
				    $form_details['form_alignment'], $form_details['description'], $form_details['thankyou_message']);
	    foreach($form_elements as $element){
		$label_obj = null;
		$element_details = get_element_details($element, $conn, $logger);
		if ($element['type'] == 'text'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $field_obj = new TextField($element_details['name'], $element_details['id'], $element_details['hidden']);
		    $field_obj->set($element_details['maxlength'], $element_details['size']);
		    
		}elseif ($element['type'] == 'textarea'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $field_obj = new TextAreaField($element_details['id'], $element_details['name'], 
						    $element_details['hidden'],$element_details['disabled'], 
						    $element_details['readonly'], $element_details['rows'], 
						    $element_details['cols']);
		}elseif ($element['type'] == 'submit'){
		    $field_obj = new SubmitField($element_details['name'], $element_details['id'], $element_details['hidden'], $element_details['value']);
		}elseif ($element['type'] == 'reset'){
		    $field_obj = new ResetField($element_details['name'], $element_details['id'], $element_details['hidden'], $element_details['value']);
		}elseif ($element['type'] == 'number'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $field_obj = new NumberField($element_details['name'], $element_details['id'], $element_details['hidden']);
		    $field_obj->set($element_details['maxlength'], $element_details['size']);
		}elseif ($element['type'] == 'file'){
		    $field_obj = new FileField($element_details['name'], $element_details['id'], $element_details['hidden']);
		    $field_obj->set($element_details['maxlength'], $element_details['size']);
		}elseif ($element['type'] == 'password'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $field_obj = new PasswordField($element_details['id'], $element_details['name'], $element_details['hidden']);
		    $field_obj->set($element_details['maxlength'], $element_details['size'], $element_details['disabled'], $element_details['readonly']);
		}elseif ($element['type'] == 'radio'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $field_obj = new RadioField($element_details['id'], $element_details['name'], $element_details['hidden'], $element_details['value'],
						    $element_details['disabled'], $element_details['readonly'], $element_details['checked']);
		}elseif ($element['type'] == 'checkbox'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $field_obj = new CheckBoxField($element_details['id'], $element_details['name'], $element_details['hidden'], $element_details['value'],
						    $element_details['disabled'], $element_details['readonly'], $element_details['checked']);
		}elseif ($element['type'] == 'label'){
		    $field_obj = new LabelField($element_details['id'], $element_details['name'], $element_details['hidden'], $element_details['value'],
						    $element_details['for_attr']);
		}elseif ($element['type'] == 'select'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $select_elements = get_select_elements($form_id, $element_details['id'], $conn, $logger);
		    $field_obj = new SelectField($element_details['id'], $element_details['name'], $element_details['hidden'], 
						    $element_details['value'], $element_details['multiple']);
		    foreach($select_elements as $option){
			$option_details = get_select_element_details($option, $conn, $logger);
			if ($option['type'] == 'option'){
			    $option_obj = new OptionField($option_details['id'], $option_details['name'], $option_details['hidden'],
							    $option_details['value'], $option_details['disabled'], $option_details['selected']);
			    $field_obj->addoptionField($option_obj);
			}elseif($option['type'] == 'optiongroup'){
			    #To do if required
			}
		    }
		}elseif ($element['type'] == 'checkboxgroup'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $group_elements = get_group_elements($form_id, $element_details['id'], $element['type'], $conn, $logger);
		    $field_obj = new CheckboxGroup($element_details['id'], $element_details['name'], $element_details['hidden'], $element_details['value']);
		    foreach($group_elements as $group_element){
			$group_element_details = get_group_element_details($group_element, $element['type'], $conn, $logger);
			$group_element_obj = new CheckBoxField($group_element_details['id'], $group_element_details['name'], 
								$group_element_details['hidden'], $group_element_details['value'], 
								$group_element_details['disabled'], $group_element_details['readonly'],
								$group_element_details['checked']);
			$group_label_details = get_label_details($group_element_details, $conn, $logger);
			$group_element_obj->label_value = $group_label_details['value'];
			$field_obj->addcheckboxField($group_element_obj);
		    }
		}elseif ($element['type'] == 'radioboxgroup'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $label_obj = new LabelField($label_details['id'], $label_details['name'], $label_details['hidden'], 
						    $label_details['value'], $label_details['for_attr']);
		    $group_elements = get_group_elements($form_id, $element_details['id'], $element['type'], $conn, $logger);
		    $field_obj = new RadioboxGroup($element_details['id'], $element_details['name'], $element_details['hidden'], $element_details['value']);
		    foreach($group_elements as $group_element){
			$group_element_details = get_group_element_details($group_element, $element['type'], $conn, $logger);
			$group_element_obj = new RadioField($group_element_details['id'], $group_element_details['name'], 
								$group_element_details['hidden'], $group_element_details['value'], 
								$group_element_details['disabled'], $group_element_details['readonly'],
								$group_element_details['checked']);
			$group_label_details = get_label_details($group_element_details, $conn, $logger);
			$group_element_obj->label_value = $group_label_details['value'];
			$field_obj->addradioboxField($group_element_obj);
		    }
		}
		if ($label_obj){
		    $form_obj->addElement($label_obj);
		}
		$form_obj->addElement($field_obj);
	    }
	    #Add Save Button
	    $form_obj->addElement(new SubmitField('submit_data', 'submit_form_data', '', 'Submit'));
	    $return_html = $form_obj->generateHTML();
	    return $this->render('FormBuilderFBBundle:Form:form.html.twig', array('page_title'=>'Form', 'return_html'=>$return_html, 'user_info'=>null));
	}catch (Exception $e){
	    $logger->err("Error in showing a form " . $e);
	}
	return $this->render('FormBuilderFBBundle:Form:error.html.twig', array('page_title'=>'Error', 'user_info'=>null));
    }
    public function indexAction($type)
    {
	#this is the form create function, the type field determines if it is a new form with log-in or not
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	if ($type == 'signup' || $type == 'auth' || $type == 'form_edit' ){
	    $page_title = 'Form Builder';
	    $request = $this->get('request');
	    $form_id = $request->query->get('form_id', '');
	    
	    $user_info = null;
	    $user_id = $this->detect_user_session($request, $conn, $logger);
	    if (!is_null($user_id) and check_user_admin($user_id, $conn, $logger)){
		$user_info = get_user_with_id($user_id, $conn, $logger);
	    }
	    
	    $return_array = array('type' => $type, 'page_title' => $page_title, 'form_id' => $form_id, 'user_info'=>$user_info);
	    return $this->render('FormBuilderFBBundle:Form:index.html.twig', $return_array);
	}
	else{
	    return $this->render('FormBuilderFBBundle:Form:error.html.twig', array('page_title'=>'Error', 'user_info'=>$user_info));
	}
    }
    public function saveAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$request = $this->get('request');
	$form_id = $request->request->get('form_id');
	$data_dict = $request->request->get('data_dict');
	$logger->info("Form Save request for form_id " . $form_id);
	try{
	    save_form_data($form_id, $data_dict, $conn, $logger);
	    $result = array('responseCode'=>200, "success"=>"true");
	}catch(Exception $e){
	    $logger->err("Error while saving form data " . $e);
	    $result = array('responseCode'=>200, "error"=>"true");
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
	
    }
    public function retrieveAction($form_id){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$request = $this->get('request');
	$user_info = null;
	try{
	    $user_id = $this->detect_user_session($request, $conn, $logger);
	    if (!is_null($user_id) and check_user_admin($user_id, $conn, $logger)){
		$user_info = get_user_with_id($user_id, $conn, $logger);
		$form_details = get_form_details($form_id, $conn, $logger);
		$form_elements = get_form_data_elements($form_id, $conn, $logger);
		$form_data = retrieve_form_data($form_id, $conn, $logger);
		foreach ($form_data as $key => $value){
		    $logger->info('Form data is ' . $key . '  ' . $value);
		}
		return $this->render('FormBuilderFBBundle:Form:form_data.html.twig', array('form_elements'=>$form_elements,'form_data' => $form_data, 'page_title' => 'Form Data', 'form_details'=>$form_details, 'user_info'=>$user_info));
	    }
	}catch(Exception $e){
	    $logger->err("Error while saving form data " . $e);
	}
	return $this->render('FormBuilderFBBundle:Form:error.html.twig', array('page_title'=>'Error', 'user_info'=>$user_info));
    }

    public function editAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$request = $this->get('request');
	$form_id = $request->request->get('form_id');
	$logger->info("Form Element request for edit " . $form_id);
	try{
	    $field_dict = get_form_elements_details($form_id, $conn, $logger);
	    $result = array('responseCode'=>200, "success"=>"true", "field_dict"=>$field_dict);
	}catch(Exception $e){
	    $logger->err("Error while saving form data " . $e);
	    $result = array('responseCode'=>200, "error"=>"true");
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
    }
    public function deleteAction(){
	$logger = $this->get('logger');
	$conn = $this->get('database_connection');
	$request = $this->get('request');
	$form_id = $request->request->get('form_id');
	$logger->info("Form Delete request received for form id " . $form_id);
	try{
	    $success = delete_form($form_id, $conn, $logger);
	    if ($success){
		$result = array('responseCode'=>200, "success"=>"true", 'redirect_url'=>$this->get('router')->generate('dashboard'));
	    }else{
		$result = array('responseCode'=>200, "error"=>"true", 'redirect_url'=>$this->get('router')->generate('dashboard'));
	    }
	}catch(Exception $e){
	    $logger->err("Error while saving form data " . $e);
	    $result = array('responseCode'=>200, "error"=>"true");
	}
	$result = json_encode($result);
	$response  = new Response($result, 200);
	$response->headers->set('Content-type', 'application/json');
	return $response;
    }
}
