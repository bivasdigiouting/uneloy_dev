$(document).ready(function(){
	 $('#create_new_user_button').click(function(e) {
		var error = 0;
    $('#name_error').text('');
		$('#name').css('border','1 px solid #e5e7eb');
    $('#username_error').text('');
		$('#username').css('border','1 px solid #e5e7eb');
		$('#email_error').text('');
    $('#email').css('border','1 px solid #e5e7eb');
    $('#password_error').text('');
    $('#password').css('border','1 px solid #e5e7eb');
    $('#confirm-password_error').text('');
    $('#confirm-password').css('border','1 px solid #e5e7eb');
		$('.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});
		$('#roles_error').text('');
		if($("#name").val() == null || $("#name").val() == '' ){
			error++;			
      $('#name_error').text('Name is required');
      $('#name').addClass('invalid');
     
			
		}

    if($("#username").val() == null || $("#username").val() == '' ){
			error++;			
      $('#username_error').text('Username is required');
      $('#username').addClass('invalid');
			
		}

    if($("#email").val() == null || $("#email").val() == '' ){
			error++;			
      $('#email_error').text('Email is required');
      $('#email').addClass('invalid');
			
		}

    if($("#password").val() == null || $("#password").val() == '' ){
			error++;			
      $('#password_error').text('Password is required');
      $('#password').addClass('invalid');
     
			
		}

    if($("#confirm-password").val() == null || $("#confirm-password").val() == '' ){
			error++;			
      $('#confirm-password_error').text('Confirm Password is required');
      $('#confirm-password').addClass('invalid');
			
		}

    if($("#confirm-password").val() != $("#password").val()){
			error++;			
      $('#confirm-password_error').text('Password and confirm password must be same');
       
			
		}

    if($("#roles").val() == null || $("#roles").val() == '' ){
			error++;			
      $('#roles_error').text('Please select at least one role');
      $('.select2').addClass('select-invalid')
			
		}



		

		if(error>0){
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}

        e.preventDefault();
               
        $.ajax({
            type: 'POST',
            url: $('#createNewUserForm').attr('action'),
            data: $('#createNewUserForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
                $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#createNewUserForm')[0].reset();            
                showSuccessAlertBox(response.message,response.redirect_url)
              }else{

                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Something error occurred , Please contact support team or try again later"));
              }
                
            },
          error: function(jqXHR, exception) {
            // Handle errors
            console.log(jqXHR.responseText);
            arr = $.parseJSON(jqXHR.responseText); //convert to javascript array
            $.each(arr,function(key,value){
              if(key == 'message'){
                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."));
              }
              if(key =='errors'){
                $.each(value, function(key, value){
                  $('#' + key).css('border','1px solid #F26522');
                              $('#' + key + '_error').text(value[0]);
                          });

              }
              console.log("======================");
            });
          },
          complete: function() {
            // Hide the loader
            $("#global-loader").css('display','none');
          }
            
        });
    });

    $('#update_user_button').click(function(e) {
		var error = 0;
    $('#name_error').text('');
		$('#name').css('border','1 px solid #e5e7eb');
    $('#username_error').text('');
		$('#username').css('border','1 px solid #e5e7eb');
		$('#email_error').text('');
    $('#email').css('border','1 px solid #e5e7eb');
    $('#password_error').text('');
    $('#password').css('border','1 px solid #e5e7eb');
    $('#confirm-password_error').text('');
    $('#confirm-password').css('border','1 px solid #e5e7eb');
		$('.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});
		$('#roles_error').text('');
		if($("#name").val() == null || $("#name").val() == '' ){
			error++;			
      $('#name_error').text('Name is required');
      $('#name').addClass('invalid');
     
			
		}

    if($("#username").val() == null || $("#username").val() == '' ){
			error++;			
      $('#username_error').text('Username is required');
      $('#username').addClass('invalid');
			
		}

    if($("#email").val() == null || $("#email").val() == '' ){
			error++;			
      $('#email_error').text('Email is required');
      $('#email').addClass('invalid');
			
		}

    

    

    if($("#confirm-password").val() != $("#password").val()){
			error++;			
      $('#confirm-password_error').text('Password and confirm password must be same');
       
			
		}

    if($("#roles").val() == null || $("#roles").val() == '' ){
			error++;			
      $('#roles_error').text('Please select at least one role');
      $('.select2').addClass('select-invalid')
			
		}

    if($("#status").val() == null || $("#status").val() == '' ){
			error++;			
      $('#status_error').text('Please select at least one status');
      $('.select2').addClass('select-invalid')
			
		}



		

		if(error>0){
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}

    let itemId = $(this).data('id');

        e.preventDefault();
               
        $.ajax({
            type: 'PUT',
            url: $('#updateUserForm').attr('action'),
            data: $('#updateUserForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
                $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#updateUserForm')[0].reset();            
                showSuccessAlertBox(response.message,response.redirect_url)
              }else{

                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Something error occurred , Please contact support team or try again later"));
              }
                
            },
          error: function(jqXHR, exception) {
            // Handle errors
            console.log(jqXHR.responseText);
            arr = $.parseJSON(jqXHR.responseText); //convert to javascript array
            $.each(arr,function(key,value){
              if(key == 'message'){
                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."));
              }
              if(key =='errors'){
                $.each(value, function(key, value){
                  $('#' + key).css('border','1px solid #F26522');
                              $('#' + key + '_error').text(value[0]);
                          });

              }
              console.log("======================");
            });
          },
          complete: function() {
            // Hide the loader
            $("#global-loader").css('display','none');
          }
            
        });
    });

});