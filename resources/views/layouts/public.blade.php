
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head id="ctl00_Head1"><meta charset="utf-8" /><title>
	U Only
</title><meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico') }}">
<meta name="description" /><meta name="keywords" /><meta name="language" content="EN" /><meta name="audience" content="all" /><meta name="content-Language" content="English" /><meta name="distribution" content="global" /><meta name="country" content="India" /><meta name="robots" content="All" />
<!--css styles -->
<link rel="stylesheet" href="{{ asset('frontend-assets/design_css/custom_styles.css') }}" /><link rel="stylesheet" href="{{ asset('frontend-assets/design_css/responsive_styles.css') }}" /><link rel="stylesheet" href="{{ asset('frontend-assets/design_css/popup_display.css') }}" />
<link rel="stylesheet" href="{{ asset('frontend-assets/design_css/bootstrap.min.css') }}" /><link rel="stylesheet" href="{{ asset('frontend-assets/design_css/browser.css') }}" /><link rel="stylesheet" href="{{ asset('frontend-assets/design_css/font-awesome.min.css') }}" />
<!--nav styles-->
<link rel="stylesheet" href="{{ asset('frontend-assets/design_css/nav.css') }}" /><link rel="stylesheet" href="{{ asset('frontend-assets/design_css/resnav.css') }}" />
<!-- Scripts -->
<script src="{{ asset('frontend-assets/design_js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend-assets/design_js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend-assets/design_js/custom_js.js') }}"></script>
<script src="{{ asset('frontend-assets/design_js/disabled.js') }}"></script>
<!--Plugins-->
<script src="{{ asset('frontend-assets/design_plugins/counter/numscroller-1.0.js') }}" ></script>
<link rel="stylesheet" href="{{ asset('frontend-assets/design_plugins/nivo-slider/nivo.css') }}" /><link rel="stylesheet" href="{{ asset('frontend-assets/design_plugins/nivo-slider/nivo-custom.css') }}" />
<script src="{{ asset('frontend-assets/design_plugins/nivo-slider/nivo.js') }}"></script>
<!-- -->
<link rel="stylesheet" href="{{ asset('frontend-assets/design_plugins/animation/css/animate.css') }}" />
<script src="{{ asset('frontend-assets/design_plugins/animation/js/wow.min.js') }}"></script>
<script>
    wow = new WOW(
        {
            boxClass: 'wow',      // default
            animateClass: 'animated', // default
            offset: 0,          // default
            mobile: true,       // default
            live: true        // default
        }
    )
    wow.init();
</script>
<link href="{{asset('frontend-assets/design_plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend-assets/design_plugins/owl-carousel/owl.theme.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend-assets/design_plugins/owl-carousel/owl.custom.css') }}" rel="stylesheet" />
<script src="{{ asset('frontend-assets/design_plugins/owl-carousel/owl.carousel.js') }}"></script>

<!--Fonts-->
<link rel="preconnect" href="https://fonts.googleapis.com/" /><link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" /><link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&amp;display=swap" rel="stylesheet" /><link rel="preconnect" href="https://fonts.googleapis.com/" /><link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" /><link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&amp;family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&amp;display=swap" rel="stylesheet" />
</head>
<body>

   <!--Popup ends -->
   <div class="my_res_nav_col"><ul></ul></div>
<!--browser message -->
<div class="bg_overlay_browse"></div>
<div class="bg_content_browse">
  <div class="left"><img src="{{ asset('frontend-assets/design_img/error.png') }}" /></div>
  <div class="right">
    <p>It is recommended that you update your browser to the latest browser to view this page.</p>
    <p>Please update to continue or install another browser. </p>
    <a href="http://go.microsoft.com/fwlink/?LinkId=324628" class="btn-orange">Update</a> <a href="https://www.google.com/chrome/browser/desktop/index.html#" class="btn-blue">Google Chrome</a> </div>
</div>
<!--browser message -->
<header>
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-5 col-lg-6 no-padding">
                    <div class="contact-sec">
                         <nav class="cont-nav">
                            <ul>
								 <li>
                                    <a href="tel:+912269645986">
                                        <div class="cont-icon"><img src="{{ asset('frontend-assets/design_img/header-icon-call.png') }}"></div>
                                        <div class="cont-text">+91 22 6964 5986</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:support@novabizglobal.in">
                                        <div class="cont-icon address"><img src="{{ asset('frontend-assets/design_img/header-icon-mail.png') }}"></div>
                                        <div class="cont-text">info@uonely.com</div>
                                    </a>
                                </li>
                               
                            </ul>
						 </nav>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-5  col-md-7 col-lg-6 no-padding pr">
                    <nav class="social-nav">
                        <ul>
                            <li>
                                <a href="https://www.facebook.com/" target="_blank">
                                    <i class="fa fa-facebook  fa-lg"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/login" target="_blank">
                                    <i class="fa fa-twitter  fa-lg"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/" target="_blank">
                                   <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">
                                    <i class="fa fa-instagram fa-lg"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- -->
	 <div class="logo-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-padding">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/logo.png')}}" alt="Uonely Solution Pvt. Ltd. - A Company that provide, infinite solutions.">
                    </a>
					<a href="{{url('/')}}" class="logo-sec">
                        <img src="{{ asset('frontend-assets/design_img/logo2.png') }}" alt="Uonely Solution Pvt. Ltd. - A Company that provide, infinite solutions.">
                    </a>
					 <!-- responsive nav btn start-->
                    <div class="my_res_btn_bar"> <a class="btn_open"><span></span><span></span><span></span></a> </div>
                    <!-- responsive nav btn end-->
                </div>
				<div class="col-xs-12 col-sm-4 col-md-5 col-lg-5 no-padding">
                    <div class="search-bar">
						<input type="text" placeholder=" Type your keyword" id="search-key">
						<a class="search-btn"></a>
						<div id="search-result"></div>
					</div>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4">
                   <nav class="user-nav">
						  <ul>
							<li><a class="login"><img src="{{ asset('frontend-assets/design_img/login-icon.png') }}" />Login</a>
							  <ul>
								<li><a href='{{ route('user.login') }}' target="_blank">Membership Login</a></li>
								<li><a href='{{ route('vendor.login') }}' target="_blank">E-Store Login</a></li>
								<li><a href='{{ route('ecard.login') }}' target="_blank">E-Card Seva Login</a></li>
							  </ul>

							</li>
							<li><a href="#" class="join "><img src="{{ asset('frontend-assets/design_img/join-us-icon.png') }}" />Registration </a>
							</li>
						  </ul>
					</nav>
                   
                </div>
                
            </div>
        </div>
    </div>
	<!-- -->
    <div class="menu-bar" > 	
<div class="container">
    <div class="row">
        <div class="col-xs-12">
             <div class="nav-bar">
     <nav id="ctl00_WUCMenu1_menu" class="site-nav"><ul id="level_1"> <li><a href="{{url('/')}}" id="home">Home </a></li>  <li><a Class="has-nav"> About Us </a> <ul id="level_2"><li><a href="{{ route('about.organization-profile') }}"> Organization Profile</a></li><li><a href="{{ route('about.business-focus') }}"> Business Focus</a></li><li><a href="{{ route('about.excellence') }}"> Excellence</a></li><li><a href="{{ route('about.our-vision') }}"> Our Vision</a></li><li><a href="{{ route('about.our-team') }}"> Team</a></li><li><a href="{{ route('about.leadership-with-trust') }}"> Leadership With Trust</a></li><li><a href="{{ route('about.our-mission') }}"> Our Mission</a></li><li><a href="{{ route('about.legals') }}"> Legals</a></li><li><a href="{{ route('about.ecard-focus') }}"> e-Card focus</a></li><li><a href="{{ route('about.faqs') }}"> FAQ's</a></li></ul></li><li><a href="{{ route('frontend.government.index') }}"> Government</a> </li><li><a Class="has-nav"> Benefits </a> <ul id="level_2"><li><a href="{{ route('frontend.benefits.book-camp') }}"> Book Camp</a></li><li><a href="{{ route('frontend.benefits.blood-donate') }}"> Blood Donate</a></li></ul></li><li><a Class="has-nav"> Services </a> <ul id="level_2"><li><a href="{{ route('frontend.services.e-card') }}"> E-Card</a></li><li><a href="{{ route('frontend.services.on-demand-service') }}"> On Demand Service</a></li><li><a href="{{ route('frontend.services.marketplace') }}"> Market Place</a></li><li><a href="{{ route('frontend.services.city-development') }}"> City Development</a></li><li><a href="{{ route('frontend.services.education') }}"> Education</a></li><li><a href="{{ route('frontend.services.real-estate-business') }}"> Real Estate Business</a></li></ul></li><li><a Class="has-nav"> E-Store </a> <ul id="level_2"><li><a href="{{ route('frontend.e-store.hotels') }}"> Hotels</a></li><li><a href="{{ route('frontend.e-store.hospitals') }}"> Hospitals</a></li><li><a href="{{ route('frontend.e-store.shoppings') }}"> Shoppings</a></li></ul></li><li><a Class="has-nav"> UONLEY BY APPS </a> <ul id="level_2"><li><a href="{{ route('frontend.uonly-by-apps.education') }}"> Education</a></li><li><a href="{{ route('frontend.uonly-by-apps.u-mart') }}"> U-Mart</a></li><li><a href="{{ route('frontend.uonly-by-apps.u-admission') }}"> U-Admission</a></li></ul></li><li><a Class="has-nav"> Media </a> <ul id="level_2"><li><a href="{{ route('frontend.news.index') }}"> News</a></li></ul></li><li><a href="{{ route('help-support.index') }}"> Help &amp; Support </a></li><li Class=""> <a href ="{{ route('contact-us') }}"> Contact Us </a> </li></ul>  </nav>
 
                </div>
                <!-- -->
            </div>
        </div>
    </div>
</div>
</header>
    <main>
        @yield('content')
    </main>
<footer>
  <div class="container">
    <div class="footer-bar">
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"> 
			<a href="{{url('/')}}" class="logo"> <img
              src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('frontend-assets/design_img/footer-logo.png') }}" alt="Uonely Solutions Pvt. Ltd. - A Company that provide, infinite solutions"> </a>
			<a href="{{url('/')}}" class="logo2"> <img
              src="{{ asset('frontend-assets/design_img/footer-logo2.png') }}" alt="Uonely Solutions Pvt. Ltd. - A Company that provide, infinite solutions"> </a>  
			  
			  <p>
	<span style="font-size:16px"><span>{{ $settings->footer_text ?? 'To empower every eligible Indian citizen with realworld benefits through a single digital identity card that ensures cost savings, emergency assistance, health & life coverage, and futureready skills.' }}</span></span></p>
 
			
			
        </div>
        <!-- -->
        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
		
			  <h2>Quick Links</h2>
			  <nav class="nav-menu">
				<ul>
				  <li><a href="{{url('/')}}" id="home">Home </a></li>
				  <li><a href="{{ route('about.organization-profile') }}">Organization Profile</a></li>
				  <li><a href="{{ route('about.our-vision') }}">Our Vision</a></li>
				  <li><a href="{{ route('about.ecard-focus') }}">E-card Focus</a></li>
				  <li><a href="{{ route('about.legals') }}">Legals</a></li>
				  <li><a href="{{ route('about.faqs') }}"> FAQ's </a></li> 
				  <li><a href="{{ route('frontend.government.index') }}"> Government</a></li>
				  
				</ul>
			  </nav>
        </div>
        <!-- -->
        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
		  <h2>Quick Links</h2>
          <nav class="nav-menu">
            <ul>
              <li><a href="{{ route('frontend.news.index') }}">News </a></li>             
			  <li><a href="{{ route('frontend.gallery') }}">Gallery</a></li>
              <li><a href="{{ route('contact-us') }}">Contact us </a></li>
            </ul>
          </nav>
          <h2>Policies</h2>
          <nav class="nav-menu">
            <ul>
              <li><a href="{{ route('cms.page', 'privacy-policy') }}">Privacy Policy</a></li>
              <li><a href="{{ route('cms.page', 'terms-and-condition') }}">Terms and Conditions </a></li>
              <li><a href="{{ route('cms.page', 'refund-policy') }}">Refund Policy</a></li>
              <li><a href="{{ route('cms.page', 'shipping-policy') }}">Shipping Policy</a></li>
              <li><a href="{{ route('cms.page', 'return-policy') }}">Return Policy</a></li>
            </ul>
          </nav>
          <!-- -->
        

        </div>
        <!-- -->
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<h2>Policies</h2>
			  <nav class="site-nav">
				<ul>
				  <li style="margin-bottom:15px!important;"><i class="fa fa-map-marker"></i>Uonely Solutions Pvt. Ltd. <br />
					TD1/50B, Dhalipara, Teghoria,<br> Kolkata 700157, West Bengal 
				  </li>

				  <li><a href="tel:+912269645986" class="cont-call"><i class="fa fa-phone-square"></i>Contact Us
					  +91 22 6964 5986</a></li>
				  <li><a href="mailto:support@uonely.com"><i class="fa fa-envelope"
						aria-hidden="true"></i>support@uonely.com</a></li>
				  <!--<li>
					<i class="fa fa-clock-o" aria-hidden="true"></i> Mon - Sat 10:00 AM to 6:30 PM <br> Closed : Sunday &
					National Holidays
				  </li> -->

				</ul>
			  </nav>
			   <h2>Download App</h2>
				  <div class="app-outer">
					<div class="down-app1">
					  <a href="#" target="_blank">
						<img src="{{ asset('frontend-assets/design_img/icon-play-store.png') }}">
					  </a>

					</div>
					<div class="down-app2">
					  <a href="#" target="_blank">
						<img src="{{ asset('frontend-assets/design_img/app-store.png') }}">
					  </a>

					</div>
				  </div>
			  
			<!-- <h2 class="sm-gape">Follow us on</h2>
			  <nav class="social-nav ">
				<ul>
				  <li><a href="https://www.facebook.com/profile.php?id=100087455809886" target="_blank"><img
						src="{{ asset('frontend-assets/design_img/icon-facebook.png') }}" /></a></li>
				  <li><a href="https://mobile.twitter.com/novabizg" target="_blank"><img
						src="{{ asset('frontend-assets/design_img/icon-twitter.png') }}" /> </a></li>
				  <li><a href="https://www.instagram.com/novabiz11/" target="_blank"> <img
						src="{{ asset('frontend-assets/design_img/icon-instagram.png') }}" /> </a></li>
				  <li><a href="https://www.youtube.com/@NBG101" target="_blank"> <img src="{{ asset('frontend-assets/design_img/icon-youtube.png') }}" />
					</a></li>
				</ul>
			  </nav>  -->

        </div>
      </div>


    </div>
  </div>
  
  <div class="footer-bar-bottom">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <p class="copy">&copy; <span class="copy-year"></span> All Rights Reserved at <span class="co-name">Uonely Solutions Pvt. Ltd.</span> </p>
        </div>
        <div class="col-sm-4">
          <p class="designer"></p>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-social-icon">
    <div class="social-chat-icon">
      <nav class="cont-nav  ">
        <ul>
          <li><a target="_blank" href="https://web.whatsapp.com/send?phone=+910000000000" class="whats-desk"><img
                src="{{ asset('frontend-assets/design_img/whatsapp.png') }}" alt="whatsapp"></a></li>
          <li><a target="_blank" class="whats-mobile" href="whatsapp://send?phone=+910000000000"><img
                src="{{ asset('frontend-assets/design_img/whatsapp.png') }}" alt="whatsapp"></a> </li>
        </ul>
      </nav>
    </div>
  </div>

  <a class="go-top"><img src="{{ asset('frontend-assets/design_img/scroll-down.png') }}"></a>
</footer>
</body>
</html>
