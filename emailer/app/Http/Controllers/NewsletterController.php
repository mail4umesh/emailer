<?php 
namespace App\Http\Controllers;


use Config,
    View,
    Crypt,
    DateTime,
    Auth,
    Validator,
    Input,
    Response,
    Redirect,
    Helper,
    Hash,
    Request,
    File,
    URL,
    Session,
    Image,
    HTML,
    DB;


class NewsletterController extends Controller {

	protected $mailchimp;
	protected $listId = 'b6f8006b0a';        // Id of newsletter list

	/**
	 * Pull the Mailchimp-instance from the IoC-container.
	 */
	public function __construct(\Mailchimp $mailchimp)
	{
		$this->mailchimp = $mailchimp;
	}


	function index(){
		echo "I am newsletter controller";


	}


	function subscriberForm(){
		
		return View::make('subscriber-form');
	}



	function add2mailChimp(){

		$input = Input::All();
		print_r($input);

		$fname  = $input['fname'];
		$lname  = $input['lname'];
		$email  = $input['email'];
		$erro = false;

		try {
			$this->mailchimp
				->lists
				->subscribe(
					$this->listId,
					['email' => $email,'merge_fields'  => [
            		    'FNAME'     => $fname,
            		    'LNAME'     => $lname
            		]]

				);
        } catch (\Mailchimp_List_AlreadySubscribed $e) {
        	// do something
        	$erro = true;
        	$msg = "You are already in list";
        } catch (\Mailchimp_Error $e) {
        	// do something
        	$erro = true;
        	$msg = "Error";
        } 

        return Response::json(['error'=> $error,'msg'=>$msg ], 200);




	}



	
}

?>