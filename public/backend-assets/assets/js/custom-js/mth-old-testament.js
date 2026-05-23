$(document).ready(function() {

  
  // Get the count of checked checkboxes
  var totalSelectedSenatePaper = $('.senatePaper:checked').length;
  
  //Referred Paper 
  var totalSelectedSenatePaperReferred = $('.senateReferredPaper:checked').length;
  
  var totalSelectedSenatePaper = parseInt(totalSelectedSenatePaper) + parseInt(totalSelectedSenatePaperReferred);

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

$('.senatePaper').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.senateReferredPaper').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.collegePaper').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});

$('.collegeReferredPaper').on('change', function() {
    // If the checkbox was previously checked and is now attempting to be unchecked
    if (!$(this).is(':checked')) { 
        // Re-check the checkbox to prevent unchecking
        $(this).prop('checked', true);
    }
});


//get selected Paper Value 


//Add Subject Registration
$('#submit_mth_form').click(function(e) {
      $("#global-loader").css('display','block');
      $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
		var error = 0;
        $('#exam_center_error').text('');
		$('#exam_center').css('border','1 px solid #e5e7eb');

        $('#paid_full_fees_error').text('');
		$('#paid_full_fees').css('border','1 px solid #e5e7eb');
        
        //check for selected paper 

        var totalSelectedSenatePaper = $('.senatePaper:checked').length;
        var totalSelectedSenatePaperReferred = $('.senateReferredPaper:checked').length;
        var totalSelectedCollegePaper = $('.collegePaper:checked').length;
        var totalSelectedCollegePaperReferred = $('.collegeReferredPaper:checked').length;

        var collegeOtCreditCalculated = $('.collegePaperSelected:checked').length;
        var collegeOtReferredCreditCalculated = $('.collegeReferredPaperSelected:checked').length;

        var collegePaperOtherBranch = $('#collegePaperOtherBranch').length;

        console.log($('.collegePaperSelected:checked').length);
        console.log($('.collegeReferredPaperSelected:checked').length);
        console.log($('#OTBranchPendingCollegeOptionalCredit').val());

        var OTBranchPendingCollegeOptionalCredit = parseInt($('#OTBranchCompletedCollegeOptionalCredit').val());
        var OTOtherBranchPassPaper = $("#OTOtherBranchPassPaper").val();

        var totalCollegeOptionalTenCredit = OTBranchPendingCollegeOptionalCredit +  parseInt(collegeOtCreditCalculated) *2 + parseInt(collegeOtReferredCreditCalculated) * 2 ; 

        var totalOtOtherBranchPaper  = collegePaperOtherBranch + OTOtherBranchPassPaper ;
        
        var errorMsg = "";
            errorMsg +="<ol>";
        
        
        if(totalSelectedSenatePaper < 1 && 
            totalSelectedSenatePaperReferred < 1 && 
            totalSelectedCollegePaper < 1 && 
            totalSelectedCollegePaperReferred < 1 && 
            collegeOtCreditCalculated < 1 &&
            collegeOtReferredCreditCalculated < 1 &&
            collegePaperOtherBranch < 1

        ){
            

            error++;
            errorMsg +="<li>"+error+") Please select at least one paper(s)! No paper selected</li>";
            
        }

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
        

        if(parseInt($("#exYear").val())>1 && $("#is_thesis_completed").val()=='No' && ($("#thesis_title").val() == '' || ($("#thesis_title").val() == null))){            
            
            error++;
            errorMsg +="<li>"+error+") As you are in final year and You must enter Thesis Title!</li>";

          

        }

        if(parseInt($("#exYear").val())>1 && totalOtOtherBranchPaper<2 ){            
            
             error++;
            errorMsg +="<li>"+error+") As you are in final year and You must select at least two paper(s) from other branch! you had selected "+totalOtOtherBranchPaper+" paper(s)</li>";

          

        }

        if(parseInt($("#exYear").val())>1 && totalCollegeOptionalTenCredit<10 ){            
            
             error++;
            errorMsg +="<li>"+error+") As you are in final year and You must select at least 10 credit(s) from optional subject! you had selected "+totalCollegeOptionalTenCredit+" credit(s)</li>";

          

        }

        

        

        
		
        errorMsg +="<ol>";
        if(error>0){
                $("#global-loader").css('display','none');
                showErrorAlertModal(errorMsg);
          $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
          return false;
        }

        // Check if college optional subjects (10 credit) or other branch papers are not selected
        var needsConfirmation = false;
        var confirmationMessage = "";
        
        if(totalCollegeOptionalTenCredit < 1) {
            needsConfirmation = true;
            confirmationMessage += "College Optional Subject 10 credit is not selected. ";
        }

        console.log(totalCollegeOptionalTenCredit);
        
        if(totalOtOtherBranchPaper < 1) {
            needsConfirmation = true;
            confirmationMessage += "Other branch paper is not selected. ";
        }

        // Always show confirmation dialog for form submission
        var finalMessage = needsConfirmation ? confirmationMessage + "Do you want to continue with the form submission?" : "Are you sure you want to submit the MTH Old Testament registration form?";
         $("#global-loader").css('display','none');
        Swal.fire({
            title: needsConfirmation ? 'Confirmation Required' : 'Confirm Submission',
            text: finalMessage,
            icon: needsConfirmation ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with form submission
                submitMthForm();
            } else {
                // User cancelled, hide loader
                $("#global-loader").css('display','none');
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Hide loader in case of error
            $("#global-loader").css('display','none');
        });
        
        e.preventDefault();
        return false;
    });
    
    function submitMthForm() {
        $.ajax({
            type: 'POST',
            url: $('#MthSubjectRegistrationForm').attr('action'),
            data: $('#MthSubjectRegistrationForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#MthSubjectRegistrationForm')[0].reset();            
                showSuccessAlertBox(response.message,response.redirect_url)
              }else{

                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Something error occurred , Please contact support team or try again later"));
              }
                
            },
          error: function(jqXHR, exception) {
            // Handle errors
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

            });
          },
          complete: function() {
            // Hide the loader
            $("#global-loader").css('display','none');
          }
            
        });
    }

 // End of document ready function

  $('#update_mth_form').click(function(e) {
      $("#global-loader").css('display','block');
      $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
		var error = 0;
        $('#exam_center_error').text('');
		    $('#exam_center').css('border','1 px solid #e5e7eb');

        $('#paid_full_fees_error').text('');
		    $('#paid_full_fees').css('border','1 px solid #e5e7eb');
        
        //check for selected paper 
     var totalSelectedSenatePaper = $('.senatePaper:checked').length;
        var totalSelectedSenatePaperReferred = $('.senateReferredPaper:checked').length;
        var totalSelectedCollegePaper = $('.collegePaper:checked').length;
        var totalSelectedCollegePaperReferred = $('.collegeReferredPaper:checked').length;

        var collegeOtCreditCalculated = $('.collegePaperSelected:checked').length;
        var collegeOtReferredCreditCalculated = $('.collegeReferredPaperSelected:checked').length;

        var collegePaperOtherBranch = $('#collegePaperOtherBranch').length;

        console.log($('.collegePaperSelected:checked').length);
        console.log($('.collegeReferredPaperSelected:checked').length);
        console.log($('#OTBranchPendingCollegeOptionalCredit').val());

        var OTBranchPendingCollegeOptionalCredit = parseInt($('#OTBranchCompletedCollegeOptionalCredit').val());
        var OTOtherBranchPassPaper = $("#OTOtherBranchPassPaper").val();

        var totalCollegeOptionalTenCredit = OTBranchPendingCollegeOptionalCredit +  parseInt(collegeOtCreditCalculated) *2 + parseInt(collegeOtReferredCreditCalculated) * 2 ; 

        var totalOtOtherBranchPaper  = collegePaperOtherBranch + OTOtherBranchPassPaper ;
        
        var errorMsg = "";
            errorMsg +="<ol>";
        
        
        if(totalSelectedSenatePaper < 1 && 
            totalSelectedSenatePaperReferred < 1 && 
            totalSelectedCollegePaper < 1 && 
            totalSelectedCollegePaperReferred < 1 && 
            collegeOtCreditCalculated < 1 &&
            collegeOtReferredCreditCalculated < 1 &&
            collegePaperOtherBranch < 1

        ){
            

            error++;
            errorMsg +="<li>"+error+") Please select at least one paper(s)! No paper selected</li>";
            
        }

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
        

        if(parseInt($("#exYear").val())>1 && $("#is_thesis_completed").val()=='No' && ($("#thesis_title").val() == '' || ($("#thesis_title").val() == null))){            
            
            error++;
            errorMsg +="<li>"+error+") As you are in final year and You must enter Thesis Title!</li>";

          

        }

        if(parseInt($("#exYear").val())>1 && totalOtOtherBranchPaper<2 ){            
            
             error++;
            errorMsg +="<li>"+error+") As you are in final year and You must select at least two paper(s) from other branch! you had selected "+totalOtOtherBranchPaper+" paper(s)</li>";

          

        }

        if(parseInt($("#exYear").val())>1 && totalCollegeOptionalTenCredit<10 ){            
            
             error++;
            errorMsg +="<li>"+error+") As you are in final year and You must select at least 10 credit(s) from optional subject! you had selected "+totalCollegeOptionalTenCredit+" credit(s)</li>";

          

        }

        

        

        
		
        errorMsg +="<ol>";
        if(error>0){
                $("#global-loader").css('display','none');
                showErrorAlertModal(errorMsg);
          $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Following error has been occurred, Please solve the error before submit the form."))
          return false;
        }

        // Check if college optional subjects (10 credit) or other branch papers are not selected
        var needsConfirmation = false;
        var confirmationMessage = "";
        
        if(totalCollegeOptionalTenCredit < 1) {
            needsConfirmation = true;
            confirmationMessage += "College Optional Subject 10 credit is not selected. ";
        }

        console.log(totalCollegeOptionalTenCredit);
        
        if(totalOtOtherBranchPaper < 1) {
            needsConfirmation = true;
            confirmationMessage += "Other branch paper is not selected. ";
        }
      $("#global-loader").css('display','none');
        // Always show confirmation dialog for form update
        var finalMessage = needsConfirmation ? confirmationMessage + "Do you want to continue with the form update?" : "Are you sure you want to update the MTH Old Testament registration?";
        
        Swal.fire({
            title: needsConfirmation ? 'Confirmation Required' : 'Confirm Update',
            text: finalMessage,
            icon: needsConfirmation ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with form update
                updateMthForm();
            } else {
                // User cancelled, hide loader
                $("#global-loader").css('display','none');
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Hide loader in case of error
            $("#global-loader").css('display','none');
        });
        
        e.preventDefault();
        return false;
    });
    
    function updateMthForm() {
        $.ajax({
            type: 'POST',
            url: $('#UpdateMthSubjectRegistrationForm').attr('action'),
            data: $('#UpdateMthSubjectRegistrationForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#UpdateMthSubjectRegistrationForm')[0].reset();            
                showSuccessAlertBox(response.message,response.redirect_url)
              }else{

                $("#message_alert").html(ValidationErrorAlert("<strong>Ops! </strong> Something error occurred , Please contact support team or try again later"));
              }
                
            },
          error: function(jqXHR, exception) {
            // Handle errors
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

            });
          },
          complete: function() {
            // Hide the loader
            $("#global-loader").css('display','none');
          }
            
        });
    }

