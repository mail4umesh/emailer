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
		//print_r($input);

		$fname  = $input['fname'];
		$lname  = $input['lname'];
		$email  = $input['email'];
		$result = $this->save2mailchimp($fname,$lname,$email);
		return Response::json($result,200);
		


	}

	function save2mailchimp($fname,$lname,$email){

		if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        	// MailChimp API credentials
        	$apiKey = 'c837c605cc1a43b439762c09ac710061-us15';
        	$listID = 'b6f8006b0a';
        	
        	// MailChimp API URL
        	$memberID = md5(strtolower($email));
        	$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        	$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
        	
        	// member information
        	$json = json_encode([
        	    'email_address' => $email,
        	    'status'        => 'subscribed',
        	    'merge_fields'  => [
        	        'FNAME'     => $fname,
        	        'LNAME'     => $lname
        	    ]
        	]);
        	
        	// send a HTTP POST request with curl
        	$ch = curl_init($url);
        	curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        	$result = curl_exec($ch);
        	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        	curl_close($ch);
        	
        	// store the status message based on response code
        	if ($httpCode == 200) {
        		$success = true;
        	    $msg = '<strong>Success!</strong>You have successfully subscribed to Our Email World.</p>';
        	} else {
        	    switch ($httpCode) {
        	        case 214:
        	            $msg = '<strong>Fail!</strong>You are already subscribed.';
        	            $success = false;
        	            break;
        	        default:
        	            $msg = '<strong>Fail!</strong>Some problem occurred, please try again.';
        	            $success = false;
        	            break;
        	    }
        	}
    	}else{
    	    $msg = '<strong>Fail!</strong>Please enter valid email address.';
    	    $success = false;
    	}
    	$data['success'] 	=	$success;
    	$data['msg'] 		=	$msg;
    	return $data;
	}
	
}

?>