//-----------------Check Name---------------------------------
function check_valid_name(input_id,responce_id) {
	var name = $("#"+input_id).val();
	var patt = /^[a-zA-Z ]+$/i;
	if (name == ""){
		$("#"+responce_id).html("Please Enter Name");
		$("#"+input_id).css("border-color", "red");
		return false;
	}
	else if (!name.match(patt)){
		$("#"+responce_id).html("Invalid Name");
		$("#"+input_id).css("border-color", "red");
		return false;
	}else{
		$("#"+input_id).css("border-color", "green");
		$("#"+responce_id).html("");
		return true;
	}
}

//-----------------Check email---------------------------------
function check_valid_email(input_id,responce_id) {
	var email = $("#"+input_id).val();
	var filter = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if (email == ""){
		$("#"+responce_id).html("Please Enter Email");
		$("#"+input_id).css("border-color", "red");
		return false;
	}
	else if (!filter.test(String(email).toLowerCase())){
		$("#"+responce_id).html("Invalid Email");
		$("#"+input_id).css("border-color", "red");
		return false;
	}else{
		$("#"+responce_id).html("");
		$("#"+input_id).css("border-color", "green");
		return true;
	}
}
//-----------------Check mobile---------------------------------
function check_valid_mobile(input_id,responce_id) {
	var mobile = $("#"+input_id).val();
	var patt1 = /[0-9]/g;
	if (mobile == ""){
		$("#"+responce_id).html("Please Enter Mobile");
		$("#"+input_id).css("border-color", "red");
		return false;
	}
	else if (!mobile.match(patt1)){
		$("#"+responce_id).html("Only Number are allowed");
		$("#"+input_id).css("border-color", "red");
		return false;
	}

	/*else if (!(mobile.charAt(0)==9 || mobile.charAt(0)==8 || mobile.charAt(0)==7 || mobile.charAt(0)==6)){
		$("#"+responce_id).html("Mobile should start at 9,8,7,6 digit");
		$("#"+input_id).css("border-color", "red");
		return false;
	}*/
	//else if (!(mobile.length == 10 || mobile.length == 13 )){
	else if (!(mobile.length == 10)){
		$("#"+responce_id).html("Enter 10 digit mobile no.");
		$("#"+input_id).css("border-color", "red");
		return false;
	}
	else{
		$("#"+responce_id).html("");
		$("#"+input_id).css("border-color", "green");
		return true;
	}
}
//-----------------Check File---------------------------------
function check_valid_file(check,input_id,responce_id,extension) {
	var allowed =true;
	var user_file = $('#'+input_id)[0].files.length;
	var ext = $('#'+input_id).val().split('.').pop().toLowerCase();
	$("#"+responce_id).html("");
	if(check=='yes'){
		if(user_file === 0){
			$("#"+responce_id).html("Please Choose file!");
			allowed = false;
		}
		else if($.inArray(ext, extension) == -1) {
			$("#"+responce_id).html("Invalid extension!");
			allowed = false;
		}
	}else{
		if($.inArray(ext, extension) == -1) {
			$("#"+responce_id).html("Invalid extension!");
			allowed = false;
		}
	}
	return allowed;
}
//--------------Checkbox------------------------
function  valid_checkbox(input_id,responce_id){
		//$("input[type='checkbox'][name='check']").change(function() {
			if ($("input[type='checkbox'][name= '"+input_id+"[]']:checked").length){
		    	$("#"+responce_id).html("");
				return true;
		    }else{
		    	$("#"+responce_id).html("Please Select Any one checkbox");
		    	return false;
		    }
		//})
	}