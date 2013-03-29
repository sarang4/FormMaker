<?php
namespace Ens\NewBundle\Controller;

require_once('Services/Notification.php');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
	{
            $x=array('to'=>'ucerturohit@gmail.com','name'=>'Rohitashv singhal');

            $z=notification_on_signup($x);

            if($z)
	    	   $name='success';
            else
		   $name='failed';

    return $this->render('FormBuilderFBBundle:Email:ind.html.twig',array('name'=>$name));
    }
}

