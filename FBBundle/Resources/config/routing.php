<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();
$collection->add('FormBuilderFBBundle_homepage', new Route('/hello/{name}', array(
    '_controller' => 'FormBuilderFBBundle:Default:index',
)));

$collection->add('login', new Route('/login', array(
    '_controller' => 'FormBuilderFBBundle:Login:index',
    ), array(
	'_method' => 'GET',
    )
));

$collection->add('login_post', new Route('/login', array(
    '_controller' => 'FormBuilderFBBundle:Login:login',
    ), array(
	'_method' => 'POST',
    )
));

$collection->add('register', new Route('/register', array(
    '_controller' => 'FormBuilderFBBundle:Login:register',
    ), array(
	'_method' => 'GET',
    )
));

$collection->add('create_new_user', new Route('/create_new_user', array(
    '_controller' => 'FormBuilderFBBundle:Login:create_new_user',
    ), array(
	'_method' => 'POST',
    )
));

$collection->add('forgot_password', new Route('/forgot_password', array(
    '_controller' => 'FormBuilderFBBundle:Login:forgot_password',
    ), array(
	'_method' => 'GET',
    )
));

$collection->add('send_password_email', new Route('/send_password_email', array(
    '_controller' => 'FormBuilderFBBundle:Login:send_password_email',
    ), array(
	'_method' => 'POST',
    )
));

$collection->add('change_password', new Route('/change_password', array(
    '_controller' => 'FormBuilderFBBundle:Login:change_password',
    ), array(
	'_method' => 'POST',
    )
));

$collection->add('logout', new Route('/logout', array(
    '_controller' => 'FormBuilderFBBundle:Login:logout',
    ), array(
	'_method' => 'POST',
    )
));

$collection->add('password_change', new Route('/password_change', array(
    '_controller' => 'FormBuilderFBBundle:Login:password',
    ), array(
	'_method' => 'GET',
    )
));

$collection->add('gethtmltest', new Route('/gethtmltest', array(
    '_controller' => 'FormBuilderFBBundle:Login:gethtmltest',
    ), array(
	'_method' => 'GET',
    )
));

$collection->add('form_create', new Route('/create_form', array(
    '_controller' => 'FormBuilderFBBundle:Form:create',
    ), array(
	'_method' => 'POST',
    )
));

$collection->add('FormCreation', new Route('/form/{type}', array(
    '_controller' => 'FormBuilderFBBundle:Form:index',
)));

$collection->add('form_show', new Route('/show_form/{form_id}', array(
    '_controller' => 'FormBuilderFBBundle:Form:show',
)));

$collection->add('save_form_data', new Route('/save_form_data', array(
    '_controller' => 'FormBuilderFBBundle:Form:save',
    ), array(
	'_method' => 'POST',
    )
));
$collection->add('retrieve_form_data', new Route('/reports/{form_id}', array(
    '_controller' => 'FormBuilderFBBundle:Form:retrieve',
    ), array(
	'_method' => 'GET',
    )
));
$collection->add('dashboard', new Route('/dashboard', array(
    '_controller' => 'FormBuilderFBBundle:Form:dashboard',
    ), array(
	'_method' => 'GET',
    )
));
$collection->add('edit_form', new Route('/edit_form', array(
    '_controller' => 'FormBuilderFBBundle:Form:edit',
    ), array(
	'_method' => 'POST',
    )
));
$collection->add('delete_form', new Route('/delete', array(
    '_controller' => 'FormBuilderFBBundle:Form:delete',
    ), array(
	'_method' => 'POST',
    )
));

return $collection;
