<?php
    require_once('user.php');
    
    function get_form_elements_details($form_id, $conn, $logger) {
	$field_dict = array();	
	try{
	    $field_dict = get_form_details($form_id, $conn, $logger);
	    $form_elements = get_form_elements($form_id, $conn, $logger);
	    foreach($form_elements as $element){
		$name = 'field' . $element['display_no'];
		$element_details = get_element_details($element, $conn, $logger);
		$element_details['type'] = $element['type'];
		if ($element['type'] == 'submit' || $element['type'] == 'reset' || $element['type'] == 'label' || $element['type'] == 'file'){
		    $field_dict[$name] = $element_details;
		}elseif($element['type'] == 'select'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $element_details['label'] = $label_details;
		    $select_elements = get_select_elements($form_id, $element_details['id'], $conn, $logger);
		    $option_dict = array();
		    foreach($select_elements as $option){
			$option_name = 'option' . $option['display_no'];
			$option_details = get_select_element_details($option, $conn, $logger);
			$option_dict[$option_name] = $option_details;
		    }
		    $element_details['options'] = $option_dict;
		    $field_dict[$name] = $element_details;
		}elseif($element['type'] == 'checkboxgroup'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $element_details['label'] = $label_details;
		    $group_elements = get_group_elements($form_id, $element_details['id'], $element['type'], $conn, $logger);
		    $checkboxes_dict = array();
		    foreach($group_elements as $group_element){
			$checkbox_name = 'checkbox' . $group_element['display_no'];
			$group_element_details = get_group_element_details($group_element, $element['type'], $conn, $logger);
			$group_label_details = get_label_details($group_element_details, $conn, $logger);
			$group_element_details['label'] = $group_label_details;
			$checkboxes_dict[$checkbox_name] = $group_element_details; 
		    }
		    $element_details['checkboxes'] = $checkboxes_dict;
		    $field_dict[$name] = $element_details;
		}elseif($element['type'] == 'radioboxgroup'){
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $element_details['label'] = $label_details;
		    $group_elements = get_group_elements($form_id, $element_details['id'], $element['type'], $conn, $logger);
		    $radioboxes_dict = array();
		    foreach($group_elements as $group_element){
			$radio_name = 'radiobox' . $group_element['display_no'];
			$group_element_details = get_group_element_details($group_element, $element['type'], $conn, $logger);
			$group_label_details = get_label_details($group_element_details, $conn, $logger);
			$group_element_details['label'] = $group_label_details;
			$radioboxes_dict[$radio_name] = $group_element_details; 
		    }
		    $element_details['radioboxes'] = $radioboxes_dict;
		    $field_dict[$name] = $element_details;
		}else{
		    $label_details = get_label_details($element_details, $conn, $logger);
		    $element_details['label'] = $label_details;
		    $field_dict[$name] = $element_details;
		}

	    }
	}catch(Exception $e){
	    $logger->err("Error while retrievinf form elements details " . $e);
	}
	return $field_dict;
    }
    function delete_form($form_id, $conn, $logger){
	try{
	    $form_result = $conn->update('form', array('deleted'=>1), array('id'=>$form_id));
	    $account_to_form_result = $conn->update('account_to_form', array('deleted'=>1), array('form_id'=>$form_id));
	    if ($form_result and $account_to_form_result){
		return true;    
	    }
	}catch(Exception $e){
	    $logger->err("Error while deleting the form " . $e);
	}
	return false;
    }
    function create_form($user_id, $form_title, $description, $form_alignment,$thankyou_message, $field_dict, $field_count, $conn, $logger){
	try{
	    $form_id = md5($user_id . date('Y-m-d H:i:s')); 
	    #Insert form row in form table
	    $result = $conn->insert('form', array('id'=>$form_id, 'title'=>$form_title, 'description'=>$description, 'action'=>'', 'thankyou_message'=>$thankyou_message, 'enctype' => '', 'method'=>'POST', 'form_alignment'=>$form_alignment));
	    if ($result){
		#Map userd_id to form_id
		$map_result = $conn->insert('account_to_form', array('account_id'=>$user_id, 'form_id'=>$form_id));
		#Access elements
		$i = 0;
		foreach($field_dict as $name => $field_data){
		    //$name = 'field' . $i;
		    //$field_data = $field_dict[$name];
		    $i++;

		    $type_of_element = $field_data['type'];
		    unset($field_data['type']);
		    $table_name = $type_of_element . '_field';
		    $field_data['form_id'] = $form_id;
		    if (array_key_exists('label', $field_data)){
			$label_data = $field_data['label'];
			unset($field_data['label']);
			$label_data['form_id'] = $form_id;
			$field_data['label_id'] = $label_data['id'];
			insert_field('label_field', $label_data, $conn, $logger);
		    }
		    if ($type_of_element == 'select'){
			$j = 0;
			$field_data_select = $field_data['options'];
			unset($field_data['options']);
			insert_field($table_name, $field_data, $conn, $logger);
			insert_form_to_element($i, $form_id, $field_data, $type_of_element, $conn, $logger);
			foreach($field_data_select as $option_name => $option_data){
			    $j++;
			    //$option_name = 'option' . $j;
			    //$option_data = $field_data[$option_name];
			    //unset($field_data[$option_name]);
			    $option_data['form_id'] = $form_id;
			    $option_data['select_id'] = $field_data['id'];
			    insert_field('option_field', $option_data, $conn, $logger);
			    insert_select_to_option($j, $form_id, $field_data, $option_data, 'option', $conn, $logger);
			}
		    }elseif ($type_of_element == 'checkboxgroup'){
			//$checkbox_count = $field_data['checkbox_count'];
			//unset($field_data['checkbox_count']);
			$j = 0;
			$field_data_checkboxes = $field_data['checkboxes'];
			unset($field_data['checkboxes']);
			insert_field($table_name, $field_data, $conn, $logger);
			insert_form_to_element($i, $form_id, $field_data, $type_of_element, $conn, $logger);
			foreach($field_data_checkboxes as $checkbox_name => $checkbox_data){
			    $j++;
			    //$checkbox_name = 'checkbox' . $j;
			    //$checkbox_data = $field_data[$checkbox_name];
			    //unset($field_data[$checkbox_name]);
			    $checkbox_data['form_id'] = $form_id;
			    $checkbox_data['group_id'] = $field_data['id'];
			    if (array_key_exists('label', $checkbox_data)){
				$cblabel_data = $checkbox_data['label'];
				unset($checkbox_data['label']);
				$cblabel_data['form_id'] = $form_id;
				$checkbox_data['label_id'] = $cblabel_data['id'];
				insert_field('label_field', $cblabel_data, $conn, $logger);
			    }
			    insert_field('checkbox_field', $checkbox_data, $conn, $logger);
			    insert_checkboxgroup_to_checkbox($j, $field_data, $checkbox_data, $conn, $logger);
			}
		    }elseif ($type_of_element == 'radioboxgroup'){
			//$radiobox_count = $field_data['radiobox_count'];
			//unset($field_data['radiobox_count']);
			$j = 0;
			$field_data_radioboxes = $field_data['radioboxes'];
			unset($field_data['radioboxes']);
			insert_field($table_name, $field_data, $conn, $logger);
			insert_form_to_element($i, $form_id, $field_data, $type_of_element, $conn, $logger);
			foreach($field_data_radioboxes as $radiobox_name => $radiobox_data){
			    $j++;
			    //$radiobox_name = 'radiobox' . $j;
			    //$radiobox_data = $field_data[$radiobox_name];
			    //unset($field_data[$radiobox_name]);
			    $radiobox_data['form_id'] = $form_id;
			    $radiobox_data['name'] = $field_data['name'];
			    $radiobox_data['group_id'] = $field_data['id'];
			    if (array_key_exists('label', $radiobox_data)){
				$rblabel_data = $radiobox_data['label'];
				unset($radiobox_data['label']);
				$rblabel_data['form_id'] = $form_id;
				$radiobox_data['label_id'] = $rblabel_data['id'];
				insert_field('label_field', $rblabel_data, $conn, $logger);
			    }
			    insert_field('radio_field', $radiobox_data, $conn, $logger);
			    insert_radioboxgroup_to_radiobox($j, $field_data, $radiobox_data, $conn, $logger);
			}
		    }else{
			insert_field($table_name, $field_data, $conn, $logger);
			insert_form_to_element($i, $form_id, $field_data, $type_of_element, $conn, $logger);
		    }
		}
		return $form_id;
	    }
	}catch (Exception $e){
	    $logger->err("Error while creating the form " . $e);
	}
	return null;
    }
    function insert_field($table_name, $field_data, $conn, $logger){
	#Insert field into the table
	$logger->info("Into insert_field");
	$conn->insert($table_name, $field_data);
    }
    function insert_form_to_element($display_no, $form_id, $field_data, $type_of_element, $conn, $logger){
	$conn->insert('form_to_element', array('form_id'=>$form_id, 'element_id'=>$field_data['id'], 'display_no'=>$display_no, 'type'=>$type_of_element));
    }
    function insert_select_to_option($display_no, $form_id, $field_data, $option_data, $type_of_element, $conn, $logger){
	$conn->insert('select_to_element', array('form_id'=>$form_id, 'select_id'=>$field_data['id'], 'element_id'=>$option_data['id'], 'display_no'=>$display_no, 'type'=>$type_of_element));
    }
    function insert_checkboxgroup_to_checkbox($display_no, $field_data, $checkbox_data, $conn, $logger){
	$conn->insert('checkboxgroup_to_checkbox', array('form_id'=>$field_data['form_id'], 'group_id'=>$field_data['id'], 'element_id'=>$checkbox_data['id'], 'display_no'=>$display_no));
    }
    function insert_radioboxgroup_to_radiobox($display_no, $field_data, $radiobox_data, $conn, $logger){
	$conn->insert('radioboxgroup_to_radiobox', array('form_id'=>$field_data['form_id'], 'group_id'=>$field_data['id'], 'element_id'=>$radiobox_data['id'], 'display_no'=>$display_no));
    }

    function get_form_details($form_id, $conn, $logger){
	try{
	    $form_details = $conn->fetchAssoc("SELECT * FROM form WHERE id = ?", array($form_id));
	    if ($form_details){
		return $form_details;
	    }
	}catch (Exception $e){
	    $logger->err("Error in retrieving form data " . $e);
	}
	return null;
    }

    function get_form_elements($form_id, $conn, $logger){
	$form_elements = null;
	try{
	    $form_elements = $conn->fetchAll("SELECT * FROM form_to_element WHERE form_id = ? ORDER BY display_no ASC", array($form_id));
	}catch (Exception $e){
	    $logger->err("Error in retrieving form data " . $e);
	}
	return $form_elements;
    }
    function get_type_of_form_element($form_id, $field_id, $conn, $logger){
	$form_elements = null;
	try{
	    $type_of_element = $conn->fetchColumn("SELECT type FROM form_to_element WHERE form_id = ? and element_id = ?", array($form_id, $field_id));
	    if ($type_of_element){
		return $type_of_element;
	    }

	}catch (Exception $e){
	    $logger->err("Error in retrieving type of element" . $e);
	}
	return null;
    }
    function get_element_details($element, $conn, $logger){
	$element_data = null;
	try{
	    $table_name = $element['type'] . '_field';
	    $element_data = $conn->fetchAssoc("SELECT * FROM " . $table_name . " WHERE form_id = ? and id = ?", array($element['form_id'], $element['element_id']));
	    if ($element_data){
		return $element_data;
	    }
	    
	}catch(Exception $e){
	    $logger->err("Error in retrieving  form element data " . $e);
	}
	return $element_data;
    }
    function get_label_details($element, $conn, $logger){
	$element_data = null;
	try{
	    $element_data = $conn->fetchAssoc("SELECT * FROM label_field WHERE form_id = ? and id = ?", array($element['form_id'], $element['label_id']));
	    if ($element_data){
		return $element_data;
	    }
	    
	}catch(Exception $e){
	    $logger->err("Error in retrieving label data " . $e);
	}
	return $element_data;
    }
    function get_select_elements($form_id, $select_id, $conn, $logger){
	$select_elements = null;
	try{
	    $select_elements = $conn->fetchAll("SELECT * FROM select_to_element WHERE form_id = ? and select_id = ? ORDER BY display_no ASC", array($form_id, $select_id));
	}catch (Exception $e){
	    $logger->err("Error in retrieving select data " . $e);
	}
	return $select_elements;
    }
    function get_select_element_details($element, $conn, $logger){
	$element_data = null;
	try{
	    $table_name = $element['type'] . '_field';
	    $element_data = $conn->fetchAssoc("SELECT * FROM " . $table_name . " WHERE form_id = ? and select_id = ? and id = ?", array($element['form_id'], $element['select_id'], $element['element_id']));
	    if ($element_data){
		return $element_data;
	    }
	    
	}catch(Exception $e){
	    $logger->err("Error in retrieving select element data " . $e);
	}
	return $element_data;
    }
    function get_group_elements($form_id, $group_id, $type, $conn, $logger){
	$select_elements = null;
	try{
	    if ($type == 'checkboxgroup'){
		$table_name = 'checkboxgroup_to_checkbox';
	    }else{
		$table_name = 'radioboxgroup_to_radiobox';
	    }
	    $group_elements = $conn->fetchAll("SELECT * FROM " . $table_name .  " WHERE form_id = ? and group_id = ? ORDER BY display_no ASC", array($form_id, $group_id));
	}catch (Exception $e){
	    $logger->err("Error in retrieving group data " . $e);
	}
	return $group_elements;
    }
    function get_group_element_details($element, $type, $conn, $logger){
	$element_data = null;
	try{
	    if ($type == 'checkboxgroup'){
		$table_name = 'checkbox_field';
	    }else{
		$table_name = 'radio_field';
	    }
	    $element_data = $conn->fetchAssoc("SELECT * FROM " . $table_name . " WHERE form_id = ? and group_id = ? and id = ?", array($element['form_id'], $element['group_id'], $element['element_id']));
	    if ($element_data){
		return $element_data;
	    }
	    
	}catch(Exception $e){
	    $logger->err("Error in retrieving group element data " . $e);
	}
	return $element_data;
    }
    function get_all_forms($user_id, $conn, $logger){
	$all_forms = array();
	try{
	    $forms_to_account = $conn->fetchAll("SELECT * FROM account_to_form WHERE account_id = ? and deleted = 0", array($user_id));
	    foreach($forms_to_account as $form){
		$all_forms[$form['form_id']] = get_form_details($form['form_id'], $conn, $logger);
	    }
	}catch(Exception $e){
	    $logger->err("Error in get_all_forms for an account " . $e);
	}
	return $all_forms;
    }
?>
