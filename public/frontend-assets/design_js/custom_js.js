$(document).ready(function() {
  /* project info ***************************************************************/ 						   
  
  /* copyright ***************************************************************/ 
  $(".copy-year").text( (new Date).getFullYear() );
   
  /* scroll header fix ***************************************************************/ 
	if($( window ).width() > 767){
		$(window).scroll(function() {
			var scrollTop = $('.top-bar').height() ; 
			if ($(this).scrollTop() > scrollTop){  				 
				$('.logo-bar').addClass("logo-fixed-bar"); 
			}
			else{
			   $('.logo-bar').removeClass("logo-fixed-bar"); 
			}
		}); 
	} 
  /*gotop & down button ******************************************************/ 
	$(window).scroll(function() {
        if($(this).scrollTop() > 50){
            $('#goTop').show();
			$('#goDown').hide();	
			
        }else{
			$('#goTop').hide();	
			$('#goDown').show(); 
		}
    });
	
	$('#goDown').click(function() {
        $("html, body").animate({ scrollTop: $(document).height() }, 1000); 
		return false; 
    });
	
	$('#goTop').click(function() {
        $("html, body").animate({ scrollTop: 0 }, 1000); 
		return false; 
    });
	/*desktop navigation ********************************************************/ 
	$('.nav-bar #level_1 > li').hover(function() {  				
		$(this).children('a:first').addClass("active");
	}, function() {  
		$(this).children('a:first').removeClass("active");
	});
	/*responsive menu  ********************************************************/
	var main = function() {
		$(document).on('click', '.my_res_btn_bar .btn_open', function() {
			$('.my_res_nav_col').animate({    
				left: "0px" 
			}, 200);  
			$(this).addClass('btn_close');
			$('span:nth-child(2)', this ).hide();
			$(this).removeClass('btn_open');
		
		});
	
		$(document).on('click', '.my_res_btn_bar .btn_close',function() {  
			$('.my_res_nav_col').animate({
				left: "-300px"
			}, 200);
			$(this).addClass('btn_open');
			$('span:nth-child(2)', this ).show();
			$(this).removeClass('btn_close');
		});

		$(document).on('click', '.my_res_nav_col li a.has-nav', function(){
			$(this).next('ul').slideDown(200);	
			$(this).addClass('less-nav').removeClass('has-nav');
		})
		
		$(document).on('click', '.my_res_nav_col li a.less-nav', function(){
			$(this).next('ul').slideUp(200);	
			$(this).addClass('has-nav').removeClass('less-nav');
		})
		  
	}; 
	$('.my_res_nav_col ul').html($('#level_1').html());
	$(document).ready(main);    
	/**/
});  