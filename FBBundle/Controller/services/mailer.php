<?php

namespace FormBuilder\FBBundle\Controller\services;

class Mailer
{


	 protected $setTovar='';

	 protected $setFromvar='';

	 protected $setBodyvar='';

	 protected $setSubj='';

         protected $setCont='';

	 protected $model;

	  public function __construct()
	  {

	         $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                              ->setUsername('wpberrytest@gmail.com')
                              ->setPassword('WpBerryemail');

		$mailer = \Swift_Mailer::newInstance($transport);

		$this->model=$mailer;

 	  }

	 public function setToloc($to)
 	 {
		$this->setTovar=$to;
 	 }


	 public function setContent($con)
 	 {
		if($con =='true')
	  	        $this->setCont='text/html';
		else
	  		$this->setCont='text/plain';
 	 }


 	public function setFromloc($from)
	{
			$this->setFromvar=$from;
 	}


 	public function setBody($body)
 	{
			$this->setBodyvar=$body;
 	}

	 public function setSubject($subject)
 	 {
			$this->setSubj=$subject;
 	 }

	 public function mail()
 	 {
        		date_default_timezone_set('Asia/Kolkata');

        		$message = \Swift_Message::newInstance($this->setSubj);

		        $message->setFrom($this->setFromvar);

		        $message->setTo($this->setTovar);

			$message->setContentType($this->setCont);

		        $message->setBody($this->setBodyvar);

			$numSent = $this->model->send($message);

		       return $numSent;  
	  }
}

