$(document).ready(function() {

  
  // Get the count of checked checkboxes
  var totalSelectedSenatePaperSemOne = $('.senateSemOne:checked').length;
  var totalSelectedSenatePaperSemTwo = $('.senateSemTwo:checked').length;

  //Referred Paper 
  var totalSelectedSenatePaperReferredSemOne = $('.senateReferredSemOne:checked').length;
  var totalSelectedSenatePaperReferredSemTwo = $('.senateReferredSemTwo:checked').length;

  var totalSelectedSenatePaper = parseInt(totalSelectedSenatePaperSemOne) + parseInt(totalSelectedSenatePaperSemTwo) + parseInt(totalSelectedSenatePaperReferredSemOne) + parseInt(totalSelectedSenatePaperReferredSemTwo);

  var senatePerPaperFees = parseFloat($("#paperFees").val());
  var totalSenatePerPaperFees = parseFloat($("#paperFees").val()) * totalSelectedSenatePaper ;
  var marksFees = parseFloat($("#marksFees").val());
  var councilFees = parseFloat($("#councilFees").val());
  var graduationFees = parseFloat($("#graduationFees").val());
  var lateFees = parseFloat($("#lateFees").val());


  var totalFees = totalSenatePerPaperFees + marksFees + councilFees + graduationFees + lateFees;
  $("#selectedSenatePaper").text("Total Senate Paper Selected: " + totalSelectedSenatePaper + " x " + senatePerPaperFees.toFixed(2)+"(per paper fees)"); 
  $("#senatePaperFees").text(totalSenatePerPaperFees.toFixed(2));
  $("#senateMarksFees").text(marksFees.toFixed(2)); 
  $("#senateCouncilFees").text(councilFees.toFixed(2)); 
  $("#senateGraduationFees").text(graduationFees.toFixed(2)); 
  $("#senateLateFees").text(lateFees.toFixed(2)); 
  $("#senateTotalFees").text(totalFees.toFixed(2)); 




});

$('.senateSemOne').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.collegeSemOne').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.senateSemTwo').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.collegeSemTwo').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});


$('.senateReferredSemOne').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.collegeReferredSemOne').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.senateReferredSemTwo').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.collegeReferredSemTwo').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});






//bbn.4 or bbn05 
$('input[name="BTNT1orBTNT2"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedGreekPassed = $("#is_prelim_greek_passed").val();
        
        var checkedBtPG1 = $('input.btPg1:checked').map(function() {
              return $(this).val();
          }).get(); 
      
        if(selectedSubject == 'BTNT2' && isAdvancedGreekPassed != 'Yes' && jQuery.inArray('BTPG1', checkedBtPG1) === -1  ){  

          var errorMsg = "You must select BTNT1 (Elementary Greek) subject as you have not passed Advanced Greek in previous examination! or you have not taken Advanced Greek in this examination!";
          $(this).prop('checked', false);
          $("input[name='BTNT1orBTNT2'][value='BTNT1']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
        
        
});

$('input[name="BTNT3orBTNT4"]').change(function() {    
        selectedSubject = $(this).val();
        var isAdvancedGreekPassed = $("#is_prelim_greek_passed").val();
        
        var checkedBtPG1 = $('input.btPg1:checked').map(function() {
              return $(this).val();
          }).get(); 
      
        if(selectedSubject == 'BTNT4' && isAdvancedGreekPassed != 'Yes' && jQuery.inArray('BTPG1', checkedBtPG1) === -1  ){  

          var errorMsg = "You must select BTNT3 (Elementary Greek) subject as you have not passed Advanced Greek in previous examination! or you have not taken Advanced Greek in this examination!";
          $(this).prop('checked', false);
          $("input[name='BTNT3orBTNT4'][value='BTNT3']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
        
});


$('input[name="BTOT2orBTOT3"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedHebrewPassed = $("#is_prelim_hebrew_passed").val();

        var checkedBtPh1 = $('input.btPh1:checked').map(function() {
              return $(this).val();
          }).get(); 

        console.log(selectedSubject);
        console.log(isAdvancedHebrewPassed);
        console.log(jQuery.inArray('BTPH1', checkedBtPh1));

        if($.trim(selectedSubject) == 'BTOT3' && isAdvancedHebrewPassed == 'No' && jQuery.inArray('BTPH1', checkedBtPh1) == -1  ){  
            console.log(1);
          var errorMsg = "You must select BTOT2 (Elementary Hebrew) subject as you have not passed Advanced Hebrew in previous examination! or you have not taken Advanced Hebrew in this examination!";
          $(this).prop('checked', false);
          $("input[name='BTOT2orBTOT3'][value='BTOT2']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }

});

$('input[name="BTOT4orBTOT5"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedHebrewPassed = $("#is_prelim_hebrew_passed").val();

        var checkedBtPh1 = $('input.btPh1:checked').map(function() {
              return $(this).val();
          }).get(); 

        if(selectedSubject == 'BTOT5' && isAdvancedHebrewPassed != 'Yes' && jQuery.inArray('BTPH1', checkedBtPh1) === -1  ){  

          var errorMsg = "You must select BTOT4 (Elementary Hebrew) subject as you have not passed Advanced Hebrew in previous examination! or you have not taken Advanced Hebrew in this examination!";
          $(this).prop('checked', false);
          $("input[name='BTOT4orBTOT5'][value='BT0T4']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }

});

//restrict senate not required paper 
$('.btPg1').on('change', function() {
  //alert('hello');

  var checkedBtPg1 = $('input.btPg1:checked').map(function() {
        return $(this).val();
    }).get();


    
  if($("input[name='BTNT1orBTNT2']:checked").val() == 'BTNT2' && jQuery.inArray('BTPG1', checkedBtPg1) === -1  ){
    
      var errorMsg = "You can not uncheck BTPG1 (Advanced Hebrew) as you have selected BTNT2 (Intermediate Hebrew) subject! Please uncheck BTNT2 first to uncheck BTPG1.";
      showErrorAlertModal(errorMsg);
      $(this).prop('checked', true);  
          return false;
  }
  if($("input[name='BTNT3orBTNT4']:checked").val() == 'BTNT4' && jQuery.inArray('BTPG1', checkedBtPg1) === -1  ){
    
      var errorMsg = "You can not uncheck BTPG1 (Advanced Hebrew) as you have selected BTNT4 (Intermediate Hebrew) subject! Please uncheck BTNT4 first to uncheck BTPG1.";
      showErrorAlertModal(errorMsg);
      $(this).prop('checked', true);  
          return false;
  }

  
}); 


//restrict senate not required paper 
$('.btPh1').on('change', function() {
  //alert('hello');

  var checkedBtPh1 = $('input.btPh1:checked').map(function() {
        return $(this).val();
    }).get();


    
  if($("input[name='BTOT2orBTOT3']:checked").val() == 'BTOT3' && jQuery.inArray('BTPH1', checkedBtPh1) === -1  ){
    
      var errorMsg = "You can not uncheck BTPH1 (Advanced Hebrew) as you have selected BTOT3 (Intermediate Hebrew) subject! Please uncheck BTOT3 first to uncheck BTPH1.";
      showErrorAlertModal(errorMsg);
      $(this).prop('checked', true);  
          return false;
  }
  if($("input[name='BTOT4orBTOT5']:checked").val() == 'BTOT5' && jQuery.inArray('BTPH1', checkedBtPh1) === -1  ){
    
      var errorMsg = "You can not uncheck BTPH1 (Advanced Hebrew) as you have selected BTOT5 (Intermediate Hebrew) subject! Please uncheck BTOT5 first to uncheck BTPH1.";
      showErrorAlertModal(errorMsg);
      $(this).prop('checked', true);  
          return false;
  }

  
}); 





//get selected Paper Value 


//Add Subject Registration
$('#submit_bth_form').click(function(e) {
      $("#global-loader").css('display','block');
      $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
		var error = 0;
        $('#exam_center_error').text('');
		$('#exam_center').css('border','1 px solid #e5e7eb');

        $('#paid_full_fees_error').text('');
		$('#paid_full_fees').css('border','1 px solid #e5e7eb');
        
        //check for selected paper 

        var totalSelectedSenatePaperSemOne = $('.senateSemOne:checked').length;
        var totalSelectedSenatePaperSemTwo = $('.senateSemTwo:checked').length;
        var totalSelectedCollegePaperSemOne = $('.collegeSemOne:checked').length;
        var totalSelectedCollegePaperSemTwo = $('.collegeSemTwo:checked').length;

        var totalSelectedSenatePaperReferredSemOne = $('.senateReferredSemOne:checked').length;
        var totalSelectedSenatePaperReferredSemTwo = $('.senateReferredSemTwo:checked').length;
        var totalSelectedCollegePaperReferredSemOne = $('.collegeReferredSemOne:checked').length;
        var totalSelectedCollegePaperReferredSemTwo = $('.collegeReferredSemTwo:checked').length;

        

        var errorMsg = "";
            errorMsg +="<ol>";
        var totalSelectedCollegePaperSemTwoOptional = $('.collegeOptionalSemTwo:checked').length;
        var totalSelectedCollegePaperReferredSemTwoOptional = $('.collegeOptionalReferredSemTwo:checked').length;
        var totalSelectedCollegePaperSemOneOptional = $('.collegeOptionalSemOne:checked').length;
        var totalSelectedCollegePaperReferredSemOneOptional = $('.collegeOptionalReferredSemOne:checked').length;

        if(totalSelectedSenatePaperSemOne<1 
          && totalSelectedSenatePaperSemTwo<1 
          && totalSelectedCollegePaperSemOne<1 
          && totalSelectedCollegePaperSemTwo<1 
          && totalSelectedSenatePaperReferredSemOne<1 
          && totalSelectedSenatePaperReferredSemTwo<1 
          && totalSelectedCollegePaperReferredSemOne<1 
          && totalSelectedCollegePaperReferredSemTwo<1 
          && totalSelectedCollegePaperSemOneOptional<1 
          && totalSelectedCollegePaperReferredSemOneOptional<1 
          && totalSelectedCollegePaperSemTwoOptional<1 
          && totalSelectedCollegePaperReferredSemTwoOptional<1 
          
          
        ){
            

            error++;
            errorMsg +="<li>"+error+") Please select at least one paper(s)! No paper selected</li>";
            
        }

         
        //check thesis and optional paper for final year student
        
        //console.log('Thesis Referred- '+thesisReferred);
        

        
        if($('#exam_center').val()=='' || $('#exam_center').val()==null){
            
           error++;
           errorMsg +="<li>"+error+") You must select Exam Center! It is missing in the form submission</li>"; 
           $('#exam_center_error').text('You must select Exam Center!');
        }

        if($('#paid_full_fees').val()=='' || $('#paid_full_fees').val()==null){
            
           error++;
           errorMsg +="<li>"+error+") You must select student has paid full fees yes or no ! It is missing in the form submission.</li>"; 
           $('#paid_full_fees_error').text('Please select fees payment status');
        }
        

        
		
        errorMsg +="<ol>";
		if(error>0){
            $("#global-loader").css('display','none');
            showErrorAlertModal(errorMsg);
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}
    

        e.preventDefault();
               
        $.ajax({
            type: 'POST',
            url: $('#BthSubjectRegistrationForm').attr('action'),
            data: $('#BthSubjectRegistrationForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#BthSubjectRegistrationForm')[0].reset();            
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
                var errorMsg = "There are some technical issue occurred, Please contact support team (Pankaj Patra +91-6296908733) with following message or try again later";
                  errorMsg += "<hr><div style='border:1px solid #F26522; padding:10px;width:100%;margin-top:10px;height:100px;overflow-y:scroll'><strong>Error Message: </strong>"+value+"</div>";
                showErrorAlertModal(errorMsg);
                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."));
              }
              if(key =='errors'){
                errorN = 0;
                var errorMsg = "";
                    errorMsg +="<ol>";
                $.each(value, function(key, value){
                  //$('#' + key).css('border','1px solid #F26522');
                  //$('#' + key + '_error').text(value[0]);
                   errorN++;
                   errorMsg +="<li>"+errorN+") "+value[0]+"</li>";

                });
                errorMsg +="</ol>";
                 showErrorAlertModal(errorMsg);
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

  $('#update_bth_form').click(function(e) {
      $("#global-loader").css('display','block');
      $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
		var error = 0;
        $('#exam_center_error').text('');
		    $('#exam_center').css('border','1 px solid #e5e7eb');

        $('#paid_full_fees_error').text('');
		    $('#paid_full_fees').css('border','1 px solid #e5e7eb');
        
        //check for selected paper 
      var totalSelectedSenatePaperSemOne = $('.senateSemOne:checked').length;
        var totalSelectedSenatePaperSemTwo = $('.senateSemTwo:checked').length;
        var totalSelectedCollegePaperSemOne = $('.collegeSemOne:checked').length;
        var totalSelectedCollegePaperSemTwo = $('.collegeSemTwo:checked').length;

        var totalSelectedSenatePaperReferredSemOne = $('.senateReferredSemOne:checked').length;
        var totalSelectedSenatePaperReferredSemTwo = $('.senateReferredSemTwo:checked').length;
        var totalSelectedCollegePaperReferredSemOne = $('.collegeReferredSemOne:checked').length;
        var totalSelectedCollegePaperReferredSemTwo = $('.collegeReferredSemTwo:checked').length;

        

        var errorMsg = "";
            errorMsg +="<ol>";
        var totalSelectedCollegePaperSemTwoOptional = $('.collegeOptionalSemTwo:checked').length;
        var totalSelectedCollegePaperReferredSemTwoOptional = $('.collegeOptionalReferredSemTwo:checked').length;
        var totalSelectedCollegePaperSemOneOptional = $('.collegeOptionalSemOne:checked').length;
        var totalSelectedCollegePaperReferredSemOneOptional = $('.collegeOptionalReferredSemOne:checked').length;

        if(totalSelectedSenatePaperSemOne<1 
          && totalSelectedSenatePaperSemTwo<1 
          && totalSelectedCollegePaperSemOne<1 
          && totalSelectedCollegePaperSemTwo<1 
          && totalSelectedSenatePaperReferredSemOne<1 
          && totalSelectedSenatePaperReferredSemTwo<1 
          && totalSelectedCollegePaperReferredSemOne<1 
          && totalSelectedCollegePaperReferredSemTwo<1 
          && totalSelectedCollegePaperSemOneOptional<1 
          && totalSelectedCollegePaperReferredSemOneOptional<1 
          && totalSelectedCollegePaperSemTwoOptional<1 
          && totalSelectedCollegePaperReferredSemTwoOptional<1 
          
          
        ){
            

            error++;
            errorMsg +="<li>"+error+") Please select at least one paper(s)! No paper selected</li>";
            
        }

         
        //check thesis and optional paper for final year student
        
        //console.log('Thesis Referred- '+thesisReferred);
        

        
        if($('#exam_center').val()=='' || $('#exam_center').val()==null){
            
           error++;
           errorMsg +="<li>"+error+") You must select Exam Center! It is missing in the form submission</li>"; 
           $('#exam_center_error').text('You must select Exam Center!');
        }

        if($('#paid_full_fees').val()=='' || $('#paid_full_fees').val()==null){
            
           error++;
           errorMsg +="<li>"+error+") You must select student has paid full fees yes or no ! It is missing in the form submission.</li>"; 
           $('#paid_full_fees_error').text('Please select fees payment status');
        }
        

        
		
        errorMsg +="<ol>";
		if(error>0){
            $("#global-loader").css('display','none');
            showErrorAlertModal(errorMsg);
			$("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
			return false;
		}
    
        e.preventDefault();
               
        $.ajax({
            type: 'POST',
            url: $('#BthSubjectRegistrationUpdateForm').attr('action'),
            data: $('#BthSubjectRegistrationUpdateForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#BthSubjectRegistrationUpdateForm')[0].reset();            
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
                var errorMsg = "There are some technical issue occurred, Please contact support team (Pankaj Patra +91-6296908733) with following message or try again later";
                  errorMsg += "<hr><div style='border:1px solid #F26522; padding:10px;width:100%;margin-top:10px;height:100px;overflow-y:scroll'><strong>Error Message: </strong>"+value+"</div>";
                showErrorAlertModal(errorMsg);
                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."));
              }
              if(key =='errors'){
                errorN = 0;
                var errorMsg = "";
                    errorMsg +="<ol>";
                $.each(value, function(key, value){
                  //$('#' + key).css('border','1px solid #F26522');
                  //$('#' + key + '_error').text(value[0]);
                   errorN++;
                   errorMsg +="<li>"+errorN+") "+value[0]+"</li>";

                });
                errorMsg +="</ol>";
                 showErrorAlertModal(errorMsg);
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
