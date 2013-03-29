<?php
use FormBuilder\FBBundle\Controller\services\mailer as Mailer;

function notification_on_signup($user_info, $logger)
{
    try{
	$new_mail = new Mailer;
	$new_mail->setToloc($user_info['to']);
	$new_mail->setFromloc('sarang.shravagi@gmail.com');
	$new_mail->setSubject('Form Builder Account');

	require_once('/var/www/Symfony/vendor/twig/lib/Twig/Autoloader.php');
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('/var/www/Symfony/src/FormBuilder/FBBundle/Resources/views/Email');
	$twig = new Twig_Environment($loader, array('debug' => true));
	$signup_templ = $twig->render('signup.html.twig', array('user_info'=>$user_info));
	
	$new_mail->setBody($signup_templ);
	$new_mail->setContent(true);
	$success = $new_mail->mail();
	return $success;
    }catch(Exception $e){
	$logger->err("Error in sending email " . $e);
    }
    return null;
}

function notification_on_forgot_password($email_to, $password, $logger){
    try{
	$new_mail = new Mailer;
	$new_mail->setToloc($email_to);
	$new_mail->setFromloc('sarang.shravagi@gmail.com');
	$new_mail->setSubject('New Password Request');

	require_once('/var/www/Symfony/vendor/twig/lib/Twig/Autoloader.php');
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('/var/www/Symfony/src/FormBuilder/FBBundle/Resources/views/Email');
	$twig = new Twig_Environment($loader, array('debug' => true));
	$forgot_password_templ = $twig->render('forgot_password.html.twig', array('password'=>$password));
	
	$new_mail->setBody($forgot_password_templ);
	$new_mail->setContent(true);
	$success = $new_mail->mail();
	return $success;
    }catch(Exception $e){
	$logger->err("Error in sending email " . $e);
    }
    return null;
}
