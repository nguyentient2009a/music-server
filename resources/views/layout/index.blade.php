<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<meta name="description" content="">
	<meta name="author" content="">

	<style>
		.my-custom-scrollbar {
			position: relative;
			height: 1000px;
			overflow: auto;
		}

		.table-wrapper-scroll-y {
			display: block;
		}
	</style>
	<title>SPMusic</title>

	<!-- Custom fonts for this template-->
	<link href="{{ asset('admin-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="{{ asset('admin-assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/style-custom.css') }}" rel="stylesheet">

</head>

<body id="page-top">

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- Menu Sidebar -->

		@include('layout.menu-sidebar')

		<!-- End of Sidebar -->

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- Topbar -->
				@include('layout.topbar')
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">
					@yield('main-container')

				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

			<!-- Footer -->
			<footer class="sticky-footer bg-white">
				<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright &copy; 3Gs House 2020</span>
					</div>
				</div>
			</footer>
			<!-- End of Footer -->

		</div>
		<!-- End of Content Wrapper -->

	</div>
	<!-- End of Page Wrapper -->

	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>

	<!-- Bootstrap core JavaScript-->
	<script src="{{ asset('admin-assets/vendor/jquery/jquery.min.js')}}"></script>
	<script src="{{ asset('admin-assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

	<!-- Core plugin JavaScript-->
	<script src="{{ asset('admin-assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

	<!-- Custom scripts for all pages-->
	<script src="{{ asset('admin-assets/js/sb-admin-2.min.js')}}"></script>

	<!-- Page level plugins -->
	<script src="{{ asset('admin-assets/vendor/chart.js/Chart.min.js')}}"></script>

	<!-- Sweet Alert 2 plugin -->
	<script src="{{ asset('admin-assets/js/sweetalert2.js')}}"></script>

	<!-- CK Editor 5 plugin -->
	<script src="{{ asset('admin-assets/js/ckeditor5/ckeditor.js')}}"></script>

	<!-- Page level custom scripts -->
	<!-- <script src="{{ asset('admin-assets/js/demo/chart-area-demo.js')}}"></script>
	<script src="{{ asset('admin-assets/js/demo/chart-pie-demo.js')}}"></script> -->

	<!-- Script tự viết -->
	@yield('script')

	<script>
		// Kiểm tra kết quả xử lý
		@if(Session::has('success'))
        Swal.fire({
            title: 'Thành Công',
            text: "{{ Session::get('success') }}",
            icon: 'success',
            showConfirmButton: false,
            timer: 1300
        })
        @elseif(Session::has('fail'))
        Swal.fire({
            title: 'Thất Bại',
            text: "{{ Session::get('fail') }}",
            icon: 'error',
            showConfirmButton: true,
        })
        @endif
	</script>
</body>

</html>
