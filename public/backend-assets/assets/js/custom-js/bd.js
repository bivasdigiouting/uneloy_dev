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

//get all selected senate optional paper value
$('.senateSemOneOptional').on('click', function(){
        let selectedValue = []; // Array to store values of all checked checkboxes
        let selectedValue2 = [];
        $('.senateSemTwoOptional:checked').each(function(){
            selectedValue2.push($(this).val());
        });

        if (jQuery.inArray($(this).val(), selectedValue2) !== -1) {

          var errorMsg = "You have already selected this paper("+$(this).val()+") in second semester optional subject, Please select different paper!";
          $(this).prop('checked', false);
          showErrorAlertModal(errorMsg);
          return false;

        }

        // Iterate over all checkboxes with the class 'myCheckbox'
        $('.senateSemOneOptional:checked').each(function(){
            selectedValue.push($(this).val());
        });

        // Display the selected values
        console.log(selectedValue);
});

//get all selected senate optional paper value
$('.senateSemTwoOptional').on('click', function(){
        let selectedValue = [];
        let selectedValue2 = [];
        $('.senateSemOneOptional:checked').each(function(){
            selectedValue.push($(this).val());
        });

        if (jQuery.inArray($(this).val(), selectedValue) !== -1) {

          var errorMsg = "You have already selected this paper("+$(this).val()+")  in first semester optional subject, Please select different paper!";
          $(this).prop('checked', false);
          showErrorAlertModal(errorMsg);
          return false;

        } // Array to store values of all checked checkboxes

        // Iterate over all checkboxes with the class 'myCheckbox'
        $('.senateSemTwoOptional:checked').each(function(){
            selectedValue.push($(this).val());
        });

        // Display the selected values
        console.log(selectedValue);
});


//bbn.4 or bbn05 
$('input[name="BBN04orBBN05"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedGreekPassed = $("#is_advanced_greek_passed").val();
        
        var checkedBBN02 = $('input.senateSemOneNotRequired:checked').map(function() {
              return $(this).val();
          }).get(); 
      console.log(jQuery.inArray('BBN02', checkedBBN02));
      console.log(checkedBBN02); 
      console.log(selectedSubject);
      console.log(isAdvancedGreekPassed); 
        if(selectedSubject == 'BBN05' && isAdvancedGreekPassed != 'Yes' && jQuery.inArray('BBN02', checkedBBN02) === -1  ){  

          var errorMsg = "You must select BBN04 (Elementary Greek) subject as you have not passed Advanced Greek in previous examination! or you have not taken Advanced Greek in this examination!";
          $(this).prop('checked', false);
          $("input[name='BBN04orBBN05'][value='BBN04']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
        
        
});

$('input[name="BBN06orBBN07"]').change(function() {    
        selectedSubject = $(this).val();
        var isAdvancedGreekPassed = $("#is_advanced_greek_passed").val();

        var checkedBBN02 = $('input.senateSemOneNotRequired:checked').map(function() {
              return $(this).val();
          }).get(); 

        if(selectedSubject == 'BBN07' && isAdvancedGreekPassed != 'Yes' && jQuery.inArray('BBN02', checkedBBN02) === -1  ){  

          var errorMsg = "You must select BBN06 (Elementary Greek) subject as you have not passed Advanced Greek in previous examination! or you have not taken Advanced Greek in this examination!";
          $(this).prop('checked', false);
          $("input[name='BBN06orBBN07'][value='BBN06']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
        
});

$('input[name="BBN08orBBN09"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedGreekPassed = $("#is_advanced_greek_passed").val();

        var checkedBBN02 = $('input.senateSemOneNotRequired:checked').map(function() {
              return $(this).val();
          }).get(); 

        if(selectedSubject == 'BBN09' && isAdvancedGreekPassed != 'Yes' && jQuery.inArray('BBN02', checkedBBN02) === -1  ){  

          var errorMsg = "You must select BBN08 (Elementary Greek) subject as you have not passed Advanced Greek in previous examination! or you have not taken Advanced Greek in this examination!";
          $(this).prop('checked', false);
          $("input[name='BBN08orBBN09'][value='BBN08']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
          
});

$('input[name="BBO14orBBO15"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedHebrewPassed = $("#is_advanced_hebrew_passed").val();

        var checkedBBO12 = $('input.senateSemOneNotRequired:checked').map(function() {
              return $(this).val();
          }).get(); 

        if(selectedSubject == 'BBO15' && isAdvancedHebrewPassed != 'Yes' && jQuery.inArray('BBO12', checkedBBO12) === -1 ){  

          var errorMsg = "You must select BBO14 (Elementary Hebrew) subject as you have not passed Advanced Hebrew in previous examination! or you have not taken Advanced Hebrew in this examination!";
          $(this).prop('checked', false);
          $("input[name='BBO14orBBO15'][value='BBO14']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }

});

$('input[name="BBO16orBBO17"]').change(function() {
       selectedSubject = $(this).val();
        var isAdvancedHebrewPassed = $("#is_advanced_hebrew_passed").val();

        var checkedBBO12 = $('input.senateSemOneNotRequired:checked').map(function() {
              return $(this).val();
          }).get(); 

        if(selectedSubject == 'BBO17' && isAdvancedHebrewPassed != 'Yes' && jQuery.inArray('BBO12', checkedBBO12) === -1 ){  

          var errorMsg = "You must select BBO16 (Elementary Hebrew) subject as you have not passed Advanced Hebrew in previous examination! or you have not taken Advanced Hebrew in this examination!";
          $(this).prop('checked', false);
          $("input[name='BBO16orBBO17'][value='BBO16']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
});

$('input[name="BBO18orBBO19"]').change(function() {
        selectedSubject = $(this).val();
        var isAdvancedHebrewPassed = $("#is_advanced_hebrew_passed").val();

        var checkedBBO12 = $('input.senateSemOneNotRequired:checked').map(function() {
              return $(this).val();
          }).get(); 

        if(selectedSubject == 'BBO19' && isAdvancedHebrewPassed != 'Yes' && jQuery.inArray('BBO12', checkedBBO12) === -1 ){  

          var errorMsg = "You must select BBO18 (Elementary Hebrew) subject as you have not passed Advanced Hebrew in previous examination! or you have not taken Advanced Hebrew in this examination!";
          $(this).prop('checked', false);         
          $("input[name='BBO18orBBO19'][value='BBO18']").prop('checked', true);
          showErrorAlertModal(errorMsg);
          return false;
        }
        
});

//restrict senate not required paper 
$('.senateSemOneNotRequired').on('change', function() {
  //alert('hello');

  var checkedBBO12 = $('input.senateSemOneNotRequired:checked').map(function() {
        return $(this).val();
    }).get();

  var checkedBBN02 = $('input.senateSemOneNotRequired:checked').map(function() {
        return $(this).val();
    }).get();
    
  if($("input[name='BBO18orBBO19']:checked").val() == 'BBO19' && jQuery.inArray('BBO12', checkedBBO12) === -1  ){
    
      var errorMsg = "You can not uncheck BBO12 (Advanced Hebrew) as you have selected BBO19 (Intermediate Hebrew) subject! Please uncheck BBO19 first to uncheck BBO12.";
      showErrorAlertModal(errorMsg);
      $(this).prop('checked', true);  
          return false;
  }

  if($("input[name='BBO16orBBO17']:checked").val() == 'BBO17' && jQuery.inArray('BBO12', checkedBBO12) === -1  ){
      var errorMsg = "You can not uncheck BBO12 (Advanced Hebrew) as you have selected BBO17 (Intermediate Hebrew) subject! Please uncheck BBO17 first to uncheck BBO12.";
      $(this).prop('checked', true); 
      showErrorAlertModal(errorMsg);
      
          return false;
  }

  if($("input[name='BBO14orBBO15']:checked").val() == 'BBO15' && jQuery.inArray('BBO12', checkedBBO12) === -1  ){
      var errorMsg = "You can not uncheck BBO12 (Advanced Hebrew) as you have selected BBO15 (Intermediate Hebrew) subject! Please uncheck BBO15 first to uncheck BBO12.";
      $(this).prop('checked', true); 
      showErrorAlertModal(errorMsg);
          return false;
  }

  if($("input[name='BBN04orBBN05']:checked").val() == 'BBN05' && jQuery.inArray('BBN02', checkedBBN02) === -1  ){
      var errorMsg = "You can not uncheck BBN02 (Advanced Greek) as you have selected BBN05 (Intermediate Greek) subject! Please uncheck BBN05 first to uncheck BBN02.";
      $(this).prop('checked', true); 
      showErrorAlertModal(errorMsg);
          return false;
  }

  if($("input[name='BBN06orBBN07']:checked").val() == 'BBN07' && jQuery.inArray('BBN02', checkedBBN02) === -1  ){
      var errorMsg = "You can not uncheck BBN02 (Advanced Greek) as you have selected BBN07 (Intermediate Greek) subject! Please uncheck BBN07 first to uncheck BBN02.";
      $(this).prop('checked', true); 
      showErrorAlertModal(errorMsg);
          return false;
  }

  if($("input[name='BBN08orBBN09']:checked").val() == 'BBN09' && jQuery.inArray('BBN02', checkedBBN02) === -1  ){
      var errorMsg = "You can not uncheck BBN02 (Advanced Greek) as you have selected BBN09 (Intermediate Greek) subject! Please uncheck BBN09 first to uncheck BBN02.";
      $(this).prop('checked', true); 
      showErrorAlertModal(errorMsg);
          return false;
  }

  
}); 




$('.collegeSemOneOptional').on('click', function(){
        let selectedValue = []; // Array to store values of all checked checkboxes
        let selectedValue2 = [];
        let totalSelectedCredit = [];
        $('.collegeSemTwoOptional:checked').each(function(){
            var expodeData = $(this).val().split('-');
            selectedValue2.push(expodeData[0]);
        });
        var explodeTw = $(this).val().split('-');
        if (jQuery.inArray(explodeTw[0], selectedValue2) !== -1) {

          var errorMsg = "You have already selected this paper("+explodeTw[0]+") in second semester college optional subject, Please select different paper!";
          $(this).prop('checked', false);
          showErrorAlertModal(errorMsg);
          return false;

        }

        // Iterate over all checkboxes with the class 'myCheckbox'
        $('.collegeSemOneOptional:checked').each(function(){
          var rtt = $(this).val().split('-');
            totalSelectedCredit.push(rtt[1]);
            selectedValue.push(rtt[0]);
        });

        var firstSemSumCredit = totalSelectedCredit.reduce(function(accumulator, currentValue) {
          return parseInt(accumulator) + parseInt(currentValue);
        }, 0);

        $("#first_sem_college_optional_subject_credit").val(firstSemSumCredit);
        $("#first_sem_college_optional_subject").val(selectedValue.toString());

        // Display the selected values
        console.log(selectedValue);
});

//get all selected senate optional paper value
$('.collegeSemTwoOptional').on('click', function(){
        let selectedValue = [];
        let selectedValue2 = [];
        let totalSelectedCredit = [];
        $('.collegeSemOneOptional:checked').each(function(){
          var expodeData = $(this).val().split('-');
            selectedValue2.push(expodeData[0]);
        });
        var explodeTw = $(this).val().split('-');
        if (jQuery.inArray(explodeTw[0], selectedValue2) !== -1) {

          var errorMsg = "You have already selected this paper("+explodeTw[0]+")  in first semester college optional subject, Please select different paper!";
          $(this).prop('checked', false);
          showErrorAlertModal(errorMsg);
          return false;

        } // Array to store values of all checked checkboxes

        // Iterate over all checkboxes with the class 'myCheckbox'
        $('.collegeSemTwoOptional:checked').each(function(){
          var rtt = $(this).val().split('-');
            totalSelectedCredit.push(rtt[1]);
            selectedValue.push(rtt[0]);
        });

        var secondSemSumCredit = totalSelectedCredit.reduce(function(accumulator, currentValue) {
          return parseInt(accumulator) + parseInt(currentValue);
        }, 0);

        $("#second_sem_college_optional_subject_credit").val(secondSemSumCredit);
        $("#second_sem_college_optional_subject").val(selectedValue.toString());

        // Display the selected values
        console.log(selectedValue);
});


//get selected Paper Value 


//Add Subject Registration
$('#submit_bd_form').click(function(e) {
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

        if(totalSelectedSenatePaperSemOne<1 
          && totalSelectedSenatePaperSemTwo<1 
          && totalSelectedCollegePaperSemOne<1 
          && totalSelectedCollegePaperSemTwo<1 
          && totalSelectedSenatePaperReferredSemOne<1 
          && totalSelectedSenatePaperReferredSemTwo<1 
          && totalSelectedCollegePaperReferredSemOne<1 
          && totalSelectedCollegePaperReferredSemTwo<1 
          
          
        ){
            

            error++;
            errorMsg +="<li>"+error+") Please select at least one paper(s)! No paper selected</li>";
            
        }

        var first_sem_college_optional_subject_credit = parseInt($('#first_sem_college_optional_subject_credit').val());
        var second_sem_college_optional_subject_credit = parseInt($('#second_sem_college_optional_subject_credit').val());
        var total_optional_credit_completed = parseInt($('#total_optional_credit_completed').val());
        var total_college_optional_subject_credit = first_sem_college_optional_subject_credit + second_sem_college_optional_subject_credit + total_optional_credit_completed;
        
        //check thesis and optional paper for final year student
        
        //console.log('Thesis Referred- '+thesisReferred);
        console.log('Credit Completed- '+total_college_optional_subject_credit);
        if(parseInt($("#exYear").val())>3 && ($("#thesis").prop('checked') == false) && total_college_optional_subject_credit<8){            
            
            error++;
            errorMsg +="<li>"+error+") As you are in final year and not selected thesis, You must select college optional subject of minimum "+(8-total_college_optional_subject_credit)+" credit!</li>";

          

        }

        if(parseInt($("#exYear").val())>3 && ($("#thesis").prop('checked') == true) && $("#thesis_title").val()==''){
            error++;
            errorMsg +="<li>"+error+") As you are in final year and  selected thesis, You must enter Thesis Title !</li>";

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
            url: $('#BdSubjectRegistrationForm').attr('action'),
            data: $('#BdSubjectRegistrationForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#BdSubjectRegistrationForm')[0].reset();            
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

  $('#update_bd_form').click(function(e) {
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

         var totalSelectedCollegePaperSemTwoOptional = $('.collegeOptionalSemTwo:checked').length;
        var totalSelectedCollegePaperReferredSemTwoOptional = $('.collegeOptionalReferredSemTwo:checked').length;

        if(totalSelectedSenatePaperSemOne<1 
          && totalSelectedSenatePaperSemTwo<1 
          && totalSelectedCollegePaperSemOne<1 
          && totalSelectedCollegePaperSemTwo<1 
          && totalSelectedSenatePaperReferredSemOne<1 
          && totalSelectedSenatePaperReferredSemTwo<1 
          && totalSelectedCollegePaperReferredSemOne<1 
          && totalSelectedCollegePaperReferredSemTwo<1 
          
          
        ){
            

            error++;
            errorMsg +="<li>"+error+") Please select at least one paper(s)! No paper selected</li>";
            
        }

        var first_sem_college_optional_subject_credit = parseInt($('#first_sem_college_optional_subject_credit').val());
        var second_sem_college_optional_subject_credit = parseInt($('#second_sem_college_optional_subject_credit').val());
        var total_optional_credit_completed = parseInt($('#total_optional_credit_completed').val());
        var total_college_optional_subject_credit = first_sem_college_optional_subject_credit + second_sem_college_optional_subject_credit + total_optional_credit_completed;
        
        //check thesis and optional paper for final year student
        
        //console.log('Thesis Referred- '+thesisReferred);
        console.log('Credit Completed- '+total_college_optional_subject_credit);
        if(parseInt($("#exYear").val())>3 && ($("#thesis").prop('checked') == false) && total_college_optional_subject_credit<8){            
            
            error++;
            errorMsg +="<li>"+error+") As you are in final year and not selected thesis, You must select college optional subject of minimum "+(8-total_college_optional_subject_credit)+" credit!</li>";

          

        }

        if(parseInt($("#exYear").val())>3 && ($("#thesis").prop('checked') == true) && $("#thesis_title").val()==''){
            error++;
            errorMsg +="<li>"+error+") As you are in final year and  selected thesis, You must enter Thesis Title !</li>";

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

        // var pendingOptionalSubject = parseInt($('#pendingOptionalSubject').val());
        // if(totalSelectedCollegePaperSemTwoOptional<pendingOptionalSubject){            

        //     error++;
        //     errorMsg +="<li>"+error+") Please select at least two paper(s) from college optional subject in second semester! You have selected <strong>"+totalSelectedCollegePaperSemTwoOptional+" paper </strong></li>";
            
        // }

        
		
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
            url: $('#BdSubjectRegistrationUpdateForm').attr('action'),
            data: $('#BdSubjectRegistrationUpdateForm').serialize(),
            beforeSend: function() {
              $("#global-loader").css('display','block');
               $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
            },
            success: function(response) {
                

              if(response.success){
                $("#message_alert").html(ValidationErrorAlert("<strong>Success! </strong> "+response.message))
                $('#BdSubjectRegistrationUpdateForm')[0].reset();            
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
