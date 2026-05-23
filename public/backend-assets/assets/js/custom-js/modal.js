createModalOpen = (ur) => {
  // alert(ur);return false;
  $("#appendAllModal").empty();
  $("#global-loader").css('display','block');
  $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
  
  $.ajax({
    url: ur,
    type: "GET",
    success: function (data) {  
          
      $("#global-loader").css('display','none');
      if (data == "fail") {
        
        
      } else { 
        $("#appendAllModal").append(data);
        $("#add_new_role").modal("show");
        $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
        
      }
    },
    error: function (err) {
      $("#global-loader").css('display','none');
      // if (err?.responseJSON?.message) {
      //   Toast.fire({
      //     iconColor: "white",
      //     icon: "error",
      //     title: err.responseJSON.message,
      //   });
      // }
    },
  });
};


//=================== Role Modal =============================
editRolesModalOpen = (ur) => {  
   //console.log(ur);return false;
  $("#appendAllModal").empty();
  $("#global-loader").css('display','block');
  $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
  
  $.ajax({
    url: ur,
    type: "GET",
    success: function (data) {  
         console.log(12); 
      $("#global-loader").css('display','none');
      if (data == "fail") {
        
        
      } else { 
        $("#appendAllModal").append(data);
        $("#updateNewRoleModal").modal("show");
        $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
        
      }
    },
    error: function (err) {
      $("#global-loader").css('display','none');
      console.log(11);
      // if (err?.responseJSON?.message) {
      //   Toast.fire({
      //     iconColor: "white",
      //     icon: "error",
      //     title: err.responseJSON.message,
      //   });
      // }
    },
  });
};
//System Modules Modal

closeModal = ()=>{

  $("#appendAllModal").empty();

}



deleteModalOpen = (ur,txt)=>{
  //alert('hello');exit;
  $("#appendAllModal").empty();
  $("#global-loader").css('display','block');
  $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
  $("#global-loader").css('display','none');
  $("#delete_modal_form").attr('action',ur);
  
  $("#delete_alert_message").html("Are you sure you want to delete this <strong>"+ txt +"</strong> ? This action can not be undone");
  $("#delete_submit_button").text('Yes I want to delete this');
  $("#delete_modal_alert").modal('show');



}

