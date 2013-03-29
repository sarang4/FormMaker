<?php
require_once('form.php');

function save_form_data($form_id, $data_dict, $conn, $logger){
    $logger->info("Saving data for {$form_id}");
    try{
	$unique_id = md5($form_id . date('Y-m-d H:i:s:u')); 
	$form_elemets = get_form_elements($form_id, $conn, $logger);
	foreach($form_elemets as $element){
	    if($element['type'] == 'checkboxgroup'){
		$table_name = $element['type'] . "_field_data";
		$checkboxes = get_group_elements($form_id, $element['element_id'], $element['type'], $conn, $logger);
		foreach($checkboxes as $checkbox){
		    if(array_key_exists($checkbox['element_id'], $data_dict)){
			insert_group_field_data($table_name, $form_id, $unique_id, $element['element_id'], $checkbox['element_id'], $conn, $logger);
			insert_group_element_field_data('checkbox_field_data', $form_id, $unique_id, $element['element_id'], $checkbox['element_id'], $data_dict[$checkbox['element_id']], $conn, $logger);
		    }
		}
	    }else{
		if(array_key_exists($element['element_id'], $data_dict)){
		    $table_name = $element['type'] . "_field_data";
		    insert_field_data($table_name, $form_id, $unique_id, $element['element_id'], $data_dict[$element['element_id']], $conn, $logger);
		}
	    }
	}
    }catch(Exception $e){
	$logger->err("Error while saving data {$e}");
    }
}
function insert_field_data($table_name, $form_id, $unique_id,$field_id, $field_value, $conn, $logger){
    #Insert field value into the table
    $logger->info("Into insert_field_data");
    $conn->insert($table_name, array('form_id'=>$form_id, 'field_id' => $field_id, 'id' => $unique_id, 'value' => $field_value));
}
function insert_group_field_data($table_name, $form_id, $unique_id, $group_id, $element_id, $conn, $logger){
    #Insert field value into the table
    $logger->info("Into insert_group_field_data");
    $conn->insert($table_name, array('form_id'=>$form_id, 'group_id' => $group_id, 'id' => $unique_id, 'element_id' => $element_id));
}
function insert_group_element_field_data($table_name, $form_id, $unique_id, $group_id, $field_id, $field_value, $conn, $logger){
    #Insert field value into the table
    $logger->info("Into insert_group_element_field_data");
    $conn->insert($table_name, array('form_id'=>$form_id, 'group_id' => $group_id, 'id' => $unique_id, 'field_id' => $field_id, 'value' => $field_value));
}
function retrieve_form_data($form_id, $conn, $logger){
    $logger->info("Retrieving data for {$form_id}");
    try{
	$form_data = array();
	$form_elemets = get_form_elements($form_id, $conn, $logger);
	foreach($form_elemets as $element){
	    if (is_valid_type($element['type'])){
		if($element['type'] == 'checkboxgroup'){
		    $checkbox_elements = get_group_data($element, $conn, $logger);
		    foreach($checkbox_elements as $checkbox_element){
			$element_data = get_group_element_data($checkbox_element, 'checkbox', $conn, $logger);
			if(array_key_exists($checkbox_element['id'], $form_data)){
			    if(array_key_exists($checkbox_element['group_id'], $form_data[$checkbox_element['id']])){
				$form_data[$checkbox_element['id']][$checkbox_element['group_id']][$element_data['field_id']] = $element_data['value'];
			    }else{
				$form_data[$checkbox_element['id']][$checkbox_element['group_id']] = array($element_data['field_id']=>$element_data['value']);
			    }
			}else{
			    $form_data[$checkbox_element['id']] = array($checkbox_element['group_id']=> array($element_data['field_id']=>$element_data['value']));
			}
		    }
		}else{
		    $element_data = get_form_element_data($element, $conn, $logger);
		    foreach($element_data as $row){
			if(array_key_exists($row['id'], $form_data)){
			    $logger->info('Form data is in exist' . $row['field_id'] . '  ' . $row['value']);
			    $form_data[$row['id']][$row['field_id']] = $row['value'];
			}else{
			    $logger->info('Form data is ' . $row['id'] . $row['field_id'] . '  ' . $row['value']);
			    $form_data[$row['id']] = array($row['field_id']=>$row['value']);
			}
		    }
		}
	    }
	}
    }catch(Exception $e){
	$logger->err("Error while retrieving data {$e}");
    }
    return $form_data;
}
function is_valid_type($type_of_element){
    $valid_types = array('text'=>1, 'textarea'=>2, 'radiobox'=>3, 'radioboxgroup'=>4, 'checkboxgroup'=>5, 'checkbox'=>6, 'select'=>7, 'password'=>8, 'number'=>9);
    if (array_key_exists($type_of_element, $valid_types)){
	return true;
    }
    return false;
}
function get_form_element_data($element, $conn, $logger){
    $element_data = null;
    try{
	$table_name = $element['type'] . '_field_data';
	$element_data = $conn->fetchAll("SELECT * FROM " . $table_name . " WHERE form_id = ? and field_id = ? ORDER BY id", array($element['form_id'], $element['element_id']));
	if ($element_data){
	    return $element_data;
	}
    }catch(Exception $e){
	$logger->err("Error in retrieving  form element data " . $e);
    }
    return $element_data;
}
function get_group_element_data($element, $type_of_element, $conn, $logger){
    $element_data = null;
    try{
	$table_name = $type_of_element . '_field_data';
	$element_data = $conn->fetchAssoc("SELECT * FROM " . $table_name . " WHERE form_id = ? and group_id = ? and field_id = ? and id = ?", array($element['form_id'], $element['group_id'], $element['element_id'], $element['id']));
	if ($element_data){
	    return $element_data;
	}
    }catch(Exception $e){
	$logger->err("Error in retrieving  group element data " . $e);
    }
    return $element_data;
}
function get_group_data($element, $conn, $logger){
    $element_data = null;
    try{
	$table_name = $element['type'] . '_field_data';
	$group_elements = $conn->fetchAll("SELECT * FROM " . $table_name . " WHERE form_id = ? and group_id = ? ORDER BY id", array($element['form_id'], $element['element_id']));
	if ($group_elements){
	    return $group_elements;
	}
    }catch(Exception $e){
	$logger->err("Error in retrieving group elements " . $e);
    }
    return $group_elements;
}
function get_form_data_elements($form_id, $conn, $logger){
    $form_data_elements = array();
    try{
	$form_elemets = get_form_elements($form_id, $conn, $logger);
	foreach($form_elemets as $element){
	    if(is_valid_type($element['type'])){
		$form_data_elements[] = $element;
	    }
	}
	return $form_data_elements;
    }catch(Exception $e){
	$logger->err("Error in retrieving form data elements  " . $e);
    }
    return $form_data_elements;

}
?>
