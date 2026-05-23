$(document).ready(function(){

    
  $('#country_name').change(function() {
      var parentValue = $(this).val();

      $.ajax({
        url: '/get-state-list', // Replace with your route
        method: 'GET',
        data: { parent_name: parentValue },
        beforeSend: function() {
              $("#global-loader").css('display','block');
                $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
        },
        success: function(response) {
            console.log(response);
            var optionsHtml = '';
            optionsHtml += '<option value="">Select State </option>';
            $.each(response, function(key, value) {
                optionsHtml += '<option value="' + value.id + '">' + value.name + '</option>';
            });
            $('#state_name').html(optionsHtml);
        },
        error: function(xhr, status, error) {
          console.error(error);
        },
        complete: function() {
            // Hide the loader
            $("#global-loader").css('display','none');
          }
      });
    });

    
    //institute Logo Upload
    $('#instituteLogoUpload').click(function(){
        $('#institute_logo').click();
    });

      $("#institute_logo").on("change", function()
      {
          var files = !!this.files ? this.files : [];
          if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

          if (/^image/.test( files[0].type)){ // only image file
              var reader = new FileReader(); // instance of the FileReader
              reader.readAsDataURL(files[0]); // read the local file

              reader.onloadend = function(){ // set image data as background of div
                 
                  $('#institute_logo_background').css('background-image', 'url(' + this.result + ')');
                    $('#institute_logo_background').css('background-size', 'cover'); // Optional: Adjust as needed
                   // $('#institute_logo_background').css('background-position', 'center'); // Optional: Adjust as need
              }
          }
      });

      $("#cancel_institute_logo").click(function(){

        $('#institute_logo_background').css('background-image','none');
        $("#institute_logo").val('');
      });

      $('#instituteLogoUpload').click(function(){
        $('#institute_logo').click();
    });

    //Institute seal upload

      $("#institute_seal").on("change", function()
      {
          var files = !!this.files ? this.files : [];
          if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

          if (/^image/.test( files[0].type)){ // only image file
              var reader = new FileReader(); // instance of the FileReader
              reader.readAsDataURL(files[0]); // read the local file

              reader.onloadend = function(){ // set image data as background of div
                 
                  $('#institute_seal_background').css('background-image', 'url(' + this.result + ')');
                    $('#institute_seal_background').css('background-size', 'cover'); // Optional: Adjust as needed
                   // $('#institute_logo_background').css('background-position', 'center'); // Optional: Adjust as need
              }
          }
      });

      $("#cancel_institute_seal").click(function(){

        $('#institute_seal_background').css('background-image','none');
        $("#institute_seal").val('');
      })

    


    $('#create_new_institute').click(function(e) {
      
		var error = 0;
        $('#institute_name_error').text('');
		    $('#institute_name').css('border','1 px solid #e5e7eb');

        $('#institute_type_error').text('');
		    $('#institute_type.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});

		    $('#institute_email_error').text('');
        $('#institute_email').css('border','1 px solid #e5e7eb');
        $('#institute_phone_error').text('');
        $('#institute_phone').css('border','1 px solid #e5e7eb');

        $('#address_line_error').text('');
        $('#address_line').css('border','1 px solid #e5e7eb');

        $('#country_name_error').text('');
        $('#country_name.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});

        $('#state_name_error').text('');
        $('#state_name.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});

        $('#city_name_error').text('');
        $('#city_name').css('border','1 px solid #e5e7eb');

        $('#zip_code_error').text('');
        $('#zip_code').css('border','1 px solid #e5e7eb');

        $('#zip_code_error').text('');
        $('#zip_code').css('border','1 px solid #e5e7eb');

        $('#full_affiliated_course_error').text('');
        $('#full_affiliated_course').css('border','1 px solid #e5e7eb');

        $('#prov_course_error').text('');
        $('#prov_course').css('border','1 px solid #e5e7eb');

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

     if($("#institute_name").val() == null || $("#institute_name").val() == '' ){
        error++;			
        $('#institute_name_error').text('Institute name is required');
        $('#institute_name').addClass('invalid');     
        
		 }      
     

    if($("#institute_type").val() == null || $("#institute_type").val() == '' ){
			error++;			
      $('#institute_type_error').text('Institute type is required');
      $('#institute_type.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		}  

    if($("#institute_email").val() == null || $("#institute_email").val() == '' ){
        error++;			
        $('#institute_email_error').text('Institute email is required');
        $('#institute_email').addClass('invalid');     
        
		 }

     if($("#institute_phone").val() == null || $("#institute_phone").val() == '' ){
        error++;			
        $('#institute_phone_error').text('Institute 10 digit phone no is required');
        $('#institute_phone').addClass('invalid');     
        
		 }

     if($("#address_line").val() == null || $("#address_line").val() == '' ){
        error++;			
        $('#address_line_error').text('Institute address is required');
        $('#address_line').addClass('invalid');     
        
		 }

     if($("#country_name").val() == null || $("#country_name").val() == '' ){
			error++;			
      $('#country_name_error').text('Please select country name');
      $('#country_name.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		} 

    if($("#state_name").val() == null || $("#state_name").val() == '' ){
			error++;			
      $('#state_name_error').text('Please select state name');
      $('#state_name.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		} 

    if($("#city_name").val() == null || $("#city_name").val() == '' ){
        error++;			
        $('#city_name_error').text('City name  is required');
        $('#city_name').addClass('invalid');     
        
		 }

     if($("#zip_code").val() == null || $("#zip_code").val() == '' ){
        error++;			
        $('#zip_code_error').text('Zip code or Postal code is required');
        $('#city_name').addClass('invalid');     
        
		 }

     if(($("#full_affiliated_course").val() == null || $("#full_affiliated_course").val() == '') && ($("#prov_course").val() == null || $("#prov_course").val() == '') ){
			error++;			
      $('#full_affiliated_course_error').text('Please select at least one course name');
      $('#prov_course_error').text('Please select at least one course name');
      $('#full_affiliated_course.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});   
      $('#prov_course.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		} 


		
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

    



		

		if(error>0){
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}

        e.preventDefault();
               
        $.ajax({
            type: 'POST',
            url: $('#createInstituteForm').attr('action'),
            data: $('#createInstituteForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#createInstituteForm')[0].reset();            
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


    //Update Institute details 

    $('#update_institute').click(function(e) {
      
		    var error = 0;
        $('#institute_name_error').text('');
		    $('#institute_name').css('border','1 px solid #e5e7eb');

        $('#institute_type_error').text('');
		    $('#institute_type.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});

		    $('#institute_email_error').text('');
        $('#institute_email').css('border','1 px solid #e5e7eb');
        $('#institute_phone_error').text('');
        $('#institute_phone').css('border','1 px solid #e5e7eb');

        $('#address_line_error').text('');
        $('#address_line').css('border','1 px solid #e5e7eb');

        $('#country_name_error').text('');
        $('#country_name.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});

        $('#state_name_error').text('');
        $('#state_name.select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});

        $('#city_name_error').text('');
        $('#city_name').css('border','1 px solid #e5e7eb');

        $('#zip_code_error').text('');
        $('#zip_code').css('border','1 px solid #e5e7eb');

        $('#zip_code_error').text('');
        $('#zip_code').css('border','1 px solid #e5e7eb');

        $('#full_affiliated_course_error').text('');
        $('#full_affiliated_course').css('border','1 px solid #e5e7eb');

        $('#prov_course_error').text('');
        $('#prov_course').css('border','1 px solid #e5e7eb');

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

     if($("#institute_name").val() == null || $("#institute_name").val() == '' ){
        error++;			
        $('#institute_name_error').text('Institute name is required');
        $('#institute_name').addClass('invalid');     
        
		 }      
     

    if($("#institute_type").val() == null || $("#institute_type").val() == '' ){
			error++;			
      $('#institute_type_error').text('Institute type is required');
      $('#institute_type.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		}  

    if($("#institute_email").val() == null || $("#institute_email").val() == '' ){
        error++;			
        $('#institute_email_error').text('Institute email is required');
        $('#institute_email').addClass('invalid');     
        
		 }

     if($("#institute_phone").val() == null || $("#institute_phone").val() == '' ){
        error++;			
        $('#institute_phone_error').text('Institute 10 digit phone no is required');
        $('#institute_phone').addClass('invalid');     
        
		 }

     if($("#address_line").val() == null || $("#address_line").val() == '' ){
        error++;			
        $('#address_line_error').text('Institute address is required');
        $('#address_line').addClass('invalid');     
        
		 }

     if($("#status").val() == null || $("#status").val() == '' ){
        error++;			
        $('#status_error').text('Status is required');
        $('#status.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});   
      
        
		 }

     if($("#country_name").val() == null || $("#country_name").val() == '' ){
			error++;			
      $('#country_name_error').text('Please select country name');
      $('#country_name.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		} 

    if($("#state_name").val() == null || $("#state_name").val() == '' ){
			error++;			
      $('#state_name_error').text('Please select state name');
      $('#state_name.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		} 

    if($("#city_name").val() == null || $("#city_name").val() == '' ){
        error++;			
        $('#city_name_error').text('City name  is required');
        $('#city_name').addClass('invalid');     
        
		 }

     if($("#zip_code").val() == null || $("#zip_code").val() == '' ){
        error++;			
        $('#zip_code_error').text('Zip code or Postal code is required');
        $('#city_name').addClass('invalid');     
        
		 }

     if(($("#full_affiliated_course").val() == null || $("#full_affiliated_course").val() == '') && ($("#prov_course").val() == null || $("#prov_course").val() == '') ){
			error++;			
      $('#full_affiliated_course_error').text('Please select at least one course name');
      $('#prov_course_error').text('Please select at least one course name');
      $('#full_affiliated_course.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});   
      $('#prov_course.select2').css({'border':'1 px solid #F26522','border-radius':'5px'});     
			
		} 


		
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

    



		

		if(error>0){
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}

        e.preventDefault();
               
        $.ajax({
            type: 'PUT',
            url: $('#updateInstituteForm').attr('action'),
            data: $('#updateInstituteForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
                $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#updateInstituteForm')[0].reset();            
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