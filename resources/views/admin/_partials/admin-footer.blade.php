<script data-cfasync="false" src="{{ asset('backend-assets/js/email-decode.min.js') }}"></script>
    <script src="{{ asset('backend-assets/js/jquery-3.7.1.min.js') }}"></script>

	<!-- Bootstrap Core JS -->
	<script src="{{ asset('backend-assets/js/bootstrap.bundle.min.js') }}"></script>

	<!-- Feather Icon JS -->
	<script src="{{ asset('backend-assets/js/feather.min.js') }}"></script>

	<!-- Slimscroll JS -->
	<script src="{{ asset('backend-assets/js/jquery.slimscroll.min.js') }}"></script>

	<!-- Chart JS -->
	<script src="{{ asset('backend-assets/plugins/apexchart/apexcharts.min.js') }}"></script>
	<script src="{{ asset('backend-assets/plugins/apexchart/chart-data.js') }}"></script>

	<!-- Chart JS -->
	<script src="{{ asset('backend-assets/plugins/chartjs/chart.min.js') }}"></script>
	<script src="{{ asset('backend-assets/plugins/chartjs/chart-data.js') }}"></script>

	<!-- Datetimepicker JS -->
	<script src="{{ asset('backend-assets/js/moment.js') }}"></script>
	<script src="{{ asset('backend-assets/js/bootstrap-datetimepicker.min.js') }}"></script>

	<!-- Daterangepikcer JS -->
	<script src="{{ asset('backend-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

	<!-- Summernote JS -->
	<script src="{{ asset('backend-assets/plugins/summernote/summernote-lite.min.js') }}"></script>

	<!-- Bootstrap Tagsinput JS -->
	<script src="{{ asset('backend-assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>

	<!-- Select2 JS -->
	<script src="{{ asset('backend-assets/plugins/select2/js/select2.min.js') }}"></script>

	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

	<!-- Toastr JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	<!-- SweetAlert2 JS -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Color Picker JS -->
	<script src="{{ asset('backend-assets/plugins/%40simonwep/pickr/pickr.es5.min.js') }}"></script>

	<!-- Custom JS -->
	<script src="{{ asset('backend-assets/js/todo.js') }}"></script>
	<script src="{{ asset('backend-assets/js/theme-colorpicker.js') }}"></script>
	<script src="{{ asset('backend-assets/js/script.js') }}"></script>

	<script>
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>
