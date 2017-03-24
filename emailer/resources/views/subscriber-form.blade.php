<html>
	<head>
		<title>Subscriber Form</title>
		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css">
		
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
	</head>
<body>
	<div class="container">
		<h2>Subscriber Form</h2>
		<div class="alert alert-success" style="display:none">
  			<a href="javascript:void(0)" class="alert-close pull-right" >&times;</a>
  			<div id="success-message"></div>
  			
		</div>
		<div class="alert alert-danger" style="display:none">
  			<a href="javascript:void(0)" class="alert-close pull-right" >&times;</a>
  			<div id="fail-message"></div>
		</div>
		<input type="text" id="fname" class="form-control" name="fname" placeholder="Enter First Name">
		<input type="text" id="lname" class="form-control" name="lname" placeholder="Enter Last Name">
		<input type="text" id="email" class="form-control" name="email" placeholder="Enter Email">
		<input type="hidden" id="_token" value="{{ csrf_token()}}" name="_token" value="{{ csrf_token()}}"/>
		<a href="javascript:void(0)" id="submit" class="btn btn-default">Submit</a>
		
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click",".alert-close",  function(){
			$(".alert").hide();
		});
		$(document).on("click","#submit",  function(){
			

			var fname = $("#fname").val();
            var lname = $("#lname").val();
            var email = $("#email").val();
            var _token = $("#_token").val();
            
            
            //alert(fname+" "+lname+" "+email+" " );

			$.ajax({
            type: "POST",
                    url : "{{ URL::to('/add2mailchimp') }}",
                    dataType:'json',
                    data : {fname:fname, lname:lname, email:email, _token:_token },
                    beforeSend:function(){
                    	$(".alert-danger").hide();
                    	$(".alert-success").hide();
                    	$("#submit").html('<i class="fa fa-spinner"></i>Saving...');
                    },
                    success : function(data){
                    //alert(data);
                    if(data.success){
                    	$("#success-message").html(data.msg);
                    	$(".alert-success").show();
                    	
                    } else {
                    	$("#fail-message").html(data.msg);
                    	$(".alert-danger").show();
                    	
                    }
                    $("#submit").html('Submit');
                }

            });

    

		});
	});
</script>	
</body>
</html>
