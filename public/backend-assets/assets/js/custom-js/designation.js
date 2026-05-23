createDesignationModalOpen = (ur) => {
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
        $("#add_new_designation").modal("show");
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
editDesignationModalOpen = (ur) => {  
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
        $("#edit_designation").modal("show");
        $(".page-loader").css({'position':'relative','top':'50%','left':'50%'});
        
      }
    },
    error: function (err) {
      $("#global-loader").css('display','none');
      console.log(11);
      if (err?.responseJSON?.message) {
        Toast.fire({
          iconColor: "white",
          icon: "error",
          title: err.responseJSON.message,
        });
      }
    },
  });
};