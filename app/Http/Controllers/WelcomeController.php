<?php namespace App\Http\Controllers;

use Response;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the API resources and operations index.
	|
	*/

	/**
	 * Show the API resources list.
	 *
	 * @return Response
	 */
	public function indexList()
	{

        $message = 'Visit https://bitbucket.org/emgsilva/chatapp for details on how to use the API resources and its operations';

        //return $message;

        //$array = array('foo', 'bar');

        //this route should returns json response
        //return $array;

        return $message;

		//return view('welcome');
	}

}
