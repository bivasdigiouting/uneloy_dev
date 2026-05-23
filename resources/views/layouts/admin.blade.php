<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title') | {{env('APP_NAME')}}</title>
	
	<meta name="description" content="{{env('APP_NAME')}}">
	<meta name="keywords" content="{{env('APP_NAME')}}">
	<meta name="author" content="Digiouting LLP">
	<meta name="robots" content="index, follow">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend-assets/img/apple-touch-icon.png') }}">

	<!-- Favicon -->
	<link rel="icon" href="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('backend-assets/img/favicon.png') }}" type="image/x-icon">
	<link rel="shortcut icon" href="{{ $settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('backend-assets/img/favicon.png') }}" type="image/x-icon">

	<!-- Theme Script js -->
	<script src="{{ asset('backend-assets/js/theme-script.js') }}"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/css/bootstrap.min.css') }}">

	<!-- Feather CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/icons/feather/feather.css') }}">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/tabler-icons/tabler-icons.css') }}">

	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/select2/css/select2.min.css') }}">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/fontawesome/css/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/fontawesome/css/all.min.css') }}">

	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/css/bootstrap-datetimepicker.min.css') }}">

	<!-- Bootstrap Tagsinput CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

	<!-- Summernote CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/summernote/summernote-lite.min.css') }}">

	<!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/daterangepicker/daterangepicker.css') }}">

	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

	<!-- Toastr CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<!-- Color Picker Css -->
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/flatpickr/flatpickr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend-assets/plugins/%40simonwep/pickr/themes/nano.min.css') }}">

	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/css/style.css') }}">

	<!-- Dashboard Custom CSS -->
	<link rel="stylesheet" href="{{ asset('backend-assets/css/dashboard-custom.css') }}">

    @stack('styles')
</head>

<body>

	<div id="global-loader" style="display: none;">
		<div class="page-loader"></div>
	</div>

	<!-- Main Wrapper -->
	<div class="main-wrapper">

		<!-- Header -->
		@include('admin._partials.admin-header')
		<!-- /Header -->

		<!-- Sidebar -->
		@include('admin._partials.admin-side-bar')
		<!-- /Sidebar -->

		
		<!-- /Stacked Sidebar -->

		<!-- Page Wrapper -->
		<div class="page-wrapper">
			@yield('content')

			<div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
				<p class="mb-0">2025-2026 &copy; Uonly Solutions.</p>
				<p>Designed &amp; Developed By <a href="javascript:void(0);" class="text-primary">Digiouting LLP</a></p>
			</div>

		</div>
		<!-- /Page Wrapper -->

		@include('admin._partials.admin-footer')
	</div>
	<!-- /Main Wrapper -->

	<!-- Page-specific scripts -->
	@stack('scripts')
	
</body>
</html>