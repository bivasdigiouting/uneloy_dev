$(document).ready(function(){
    $('.min_max_div').css('display','none');
    $('#course_has_spec_cate').change(function(){
        $('.min_max_div').css('display','none');
        if($(this).val() == 3){
            $('.min_max_div').css('display','block');
        }
    });


    $('#create_new_course_button').click(function(e) {
		var error = 0;
        $('#name_error').text('');
		$('#name').css('border','1 px solid #e5e7eb');

        $('#min_duration_error').text('');
		$('#min_duration').css('border','1 px solid #e5e7eb');
        
        $('#max_duration_error').text('');
		$('#max_duration').css('border','1 px solid #e5e7eb');

        $('#course_code_error').text('');
		$('#course_code').css('border','1 px solid #e5e7eb');
		$('#course_has_spec_cate .select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});
		$('#course_has_spec_cate_error').text('');
		if($("#name").val() == null || $("#name").val() == '' ){
			error++;			
            $('#name_error').text('Course name is required');
            $('#name').addClass('invalid');
            
			
		}

        if($("#course_code").val() == null || $("#name").val() == '' ){
			error++;			
            $('#course_code_error').text('Course code is required');
            $('#course_code').addClass('invalid');
            
			
		}

        
                
        

        if($("#course_has_spec_cate").val() == null || $("#course_has_spec_cate").val() == '' ){
            error++;			
            $('#course_has_spec_cate_error').text('Please select specialization or category or select does not have any specialization or category ');
            //let cardElement = $("#course_has_spec_cate").closest('.select2');
            $('.select2').addClass('select-invalid');
                
        }

        if($("#course_has_spec_cate").val() == 3){
            
            if($("#min_duration").val() == null || $("#min_duration").val() == '' ){
                error++;			
                $('#min_duration_error').text('Min duration is required');
                $('#min_duration').addClass('select-invalid');
                
                
            }

            if($("#max_duration").val() == null || $("#max_duration").val() == '' ){
                error++;			
                $('#max_duration_error').text('Max duration is required');
                $('#max_duration').addClass('select-invalid');
                
                
            }
                
        }



		

		if(error>0){
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}

        e.preventDefault();
               
        $.ajax({
            type: 'POST',
            url: $('#createNewCourseForm').attr('action'),
            data: $('#createNewCourseForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
                $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#createNewCourseForm')[0].reset();            
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


    $('#update_course_button').click(function(e) {
		var error = 0;
        $('#name_error').text('');
		    $('#name').css('border','1 px solid #e5e7eb');

        $('#min_duration_error').text('');
		    $('#min_duration').css('border','1 px solid #e5e7eb');
        
        $('#max_duration_error').text('');
		    $('#max_duration').css('border','1 px solid #e5e7eb');

        $('#course_code_error').text('');
		    $('#course_code').css('border','1 px solid #e5e7eb');
		    $('#course_has_spec_cate .select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});
		    $('#course_has_spec_cate_error').text('');

        $('#status .select2').css({'border':'1 px solid #e5e7eb','border-radius':'5px'});
		    $('#status_error').text('');
		if($("#name").val() == null || $("#name").val() == '' ){
			error++;			
            $('#name_error').text('Course name is required');
            $('#name').addClass('invalid');
            
			
		}

        if($("#course_code").val() == null || $("#name").val() == '' ){
			    error++;			
            $('#course_code_error').text('Course code is required');
            $('#course_code').addClass('invalid');
            
			
		    }

        
                
        

        if($("#course_has_spec_cate").val() == null || $("#course_has_spec_cate").val() == '' ){
            error++;			
            $('#course_has_spec_cate_error').text('Please select specialization or category or select does not have any specialization or category ');
            //let cardElement = $("#course_has_spec_cate").closest('.select2');
            $('.select2').addClass('select-invalid');
                
        }

        if($("#status").val() == null || $("#status").val() == '' ){
            error++;			
            $('#status_error').text('Please select status ');
            //let cardElement = $("#course_has_spec_cate").closest('.select2');
            $('.select2').addClass('select-invalid');
                
        }

        if($("#course_has_spec_cate").val() == 3){
            
            if($("#min_duration").val() == null || $("#min_duration").val() == '' ){
                error++;			
                $('#min_duration_error').text('Min duration is required');
                $('#min_duration').addClass('select-invalid');
                
                
            }

            if($("#max_duration").val() == null || $("#max_duration").val() == '' ){
                error++;			
                $('#max_duration_error').text('Max duration is required');
                $('#max_duration').addClass('select-invalid');
                
                
            }
                
        }



		

		if(error>0){
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}

        e.preventDefault();
               
        $.ajax({
            type: 'PUT',
            url: $('#updateCourseForm').attr('action'),
            data: $('#updateCourseForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
                $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#updateCourseForm')[0].reset();            
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