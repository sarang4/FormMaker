<?php

function user_exist($email, $password, $conn, $logger){
    try{
	$user = $conn->fetchAssoc("SELECT * FROM fb_user WHERE email = ? and password = ?", array($email, $password));
	if ($user){
	    return $user;
	}
    }catch(Exception $e){
	$logger->err("Error in chcking user_exist " . $e);
	throw $e;
    }
    return null;
}  
function check_unique_subdomain_name($subdomain_name, $conn, $logger){
    try{
	$user = $conn->fetchAssoc("SELECT * FROM fb_user WHERE subdomain_name = ?", array($subdomain_name));
	if ($user){
	    return $user['id'];
	}
    }catch(Exception $e){
	$logger->err("Error in getting checking unique sudomain name " . $e);
	throw $e;
    }
    return null;
}
function check_duplicate($email, $conn, $logger){
    return get_user_id($email, $conn, $logger);
}
function get_user_id($email, $conn, $logger){
    try{
	$user = $conn->fetchAssoc("SELECT * FROM fb_user WHERE email = ?", array($email));
	if ($user){
	    return $user['id'];
	}
    }catch(Exception $e){
	$logger->err("Error in getting user from email " . $e);
	throw $e;
    }
    return null;
}
function get_user_with_id($id, $conn, $logger){
    try{
	$user = $conn->fetchAssoc("SELECT * FROM fb_user WHERE id = ?", array($id));
	if ($user){
	    return $user;
	}
    }catch(Exception $e){
	$logger->err("Error in getting user if from email " . $e);
	throw $e;
    }
    return null;
}
function check_user_exist_with_email($email, $conn, $logger){
    return get_user_id($email, $conn, $logger);
}
function create_new_user($email, $password, $name, $subdomain_name, $conn, $logger){
    try{
	$result = $conn->insert('fb_user', array('email'=>$email, 'name'=>$name, 'password'=>$password, 'subdomain_name'=>$subdomain_name));
	if ($result != null){
	    return user_exist($email, $password, $conn, $logger);
	}
    }catch(Exception $e){
	$logger->err("Error in creating new user " . $e);
	throw $e;
    }
    return false;
}
function change_password($user_id, $password, $conn, $logger){
    try{
	$result = $conn->update('fb_user', array('password'=>$password), array('id'=>$user_id));
	if ($result){
	    return $result;    
	}
    }catch(Exception $e){
	$logger->err('Error occured in change_password ' . $e);
	throw $e;
    }  
    return null;
}
function generate_password(){
    try{
	$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$i = 0;
	$password = "";
	while ($i < 8) {
	    $password .= $chars{mt_rand(0,strlen($chars))};
	    $i++;
	}
	return $password;
    }catch(Exception $e){
	$logger->err("Exception in generate_password " . $e);
    }
}
function check_user_admin($user_id, $conn, $logger){
    try{
	$logger->info("User row " . $user_id);
	$user = get_user_with_id($user_id, $conn, $logger);
	if ($user['type_of_user'] == 0){
	    return true;
	}
    }catch (Exception $e){
	$logger->err("Error while checking user as admin or not " . $e);
	throw $e;
    }
    return false;
}
?>