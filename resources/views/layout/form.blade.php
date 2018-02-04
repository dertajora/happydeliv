<!DOCTYPE html>
<html lang="en">
<head>
	<title>HappyDeliv - Empower Your Delivery Service</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	{{-- <link rel="icon" type="image/png" href="{{ URL::asset('form')}}/images/icons/favicon.ico"/> --}}
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/css/util.css">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('form')}}/css/main.css">
<!--===============================================================================================-->
</head>
<body style="background-color: #999999;">
	
	@yield('content')
	
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/vendor/bootstrap/js/popper.js"></script>
	<script src="{{ URL::asset('form')}}/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/vendor/daterangepicker/moment.min.js"></script>
	<script src="{{ URL::asset('form')}}/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="{{ URL::asset('form')}}/js/main.js"></script>

	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
	@yield('notification_message')

	
</body>
</html>