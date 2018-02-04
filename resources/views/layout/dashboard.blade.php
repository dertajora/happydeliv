<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>HappyDeliv | Dashboard</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		   folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/css/skins/custom-martin-skin.css">
		<!-- Morris chart -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/morris.js/morris.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/jvectormap/jquery-jvectormap.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!-- Daterange picker -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="{{ URL::asset('dist')}}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
		<!-- DataTables v1.10.16 -->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

		<!-- Favicon -->
		{{-- <link rel="shortcut icon" href="favicon.png"> --}}

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
		@yield('style')
	</head>

	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<header class="main-header">
			    <!-- Logo -->
			    <a href="#" class="logo">
			      <!-- mini logo for sidebar mini 50x50 pixels -->
			      <!-- logo mini setelah tampilan berubah dari dinamis -->
			      <!-- <span class="logo-mini"><i class="fa fa-compress"></i></span> -->
			      <!-- logo for regular state and mobile devices -->
			      <span class="logo-lg"><b>HappyDeliv</b></span>
			    </a>
			    <!-- Header Navbar: style can be found in header.less -->
			    <nav class="navbar navbar-static-top">
			      <!-- Sidebar toggle button-->

			      <!-- untuk memungkinkan navigation yang dinamis -->
			      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			        <span class="sr-only">Toggle navigation</span>
			      </a>

			      <div class="navbar-custom-menu">
			        <ul class="nav navbar-nav">
			          
			          <!-- Notifications: style can be found in dropdown.less -->
			          <li class="dropdown notifications-menu">
			            {{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			              <i class="fa fa-bell-o"></i>
			              <span class="label label-warning">10</span>
			            </a> --}}
			            <ul class="dropdown-menu">
			              <li class="header">You have 10 notifications</li>
			              <li>
			                <!-- inner menu: contains the actual data -->
			                <ul class="menu">
			                  <li>
			                    <a href="#">
			                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
			                    </a>
			                  </li>
			                  <li>
			                    <a href="#">
			                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
			                      page and may cause design problems
			                    </a>
			                  </li>
			                  <li>
			                    <a href="#">
			                      <i class="fa fa-users text-red"></i> 5 new members joined
			                    </a>
			                  </li>
			                  <li>
			                    <a href="#">
			                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
			                    </a>
			                  </li>
			                  <li>
			                    <a href="#">
			                      <i class="fa fa-user text-red"></i> You changed your username
			                    </a>
			                  </li>
			                </ul>
			              </li>
			              <li class="footer"><a href="#">View all</a></li>
			            </ul>
			          </li>
			          <!-- Tasks: style can be found in dropdown.less -->
			          
			          <!-- User Account: style can be found in dropdown.less -->
			          <li class="dropdown user user-menu">
			            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			              <img src="{{URL::to('')}}/images/user-dummy.png" class="user-image" alt="User Image">
			              <span class="hidden-xs">{{Auth::user()->name}}</span>
			            </a>
			            <ul class="dropdown-menu">
			              <!-- User image -->
			              <li class="user-header">
			                <img src="{{URL::to('')}}/images/user-dummy.png" class="img-circle" alt="User Image">

			                <p>
			                  {{Auth::user()->name}}
			                  <small>{{session('role_name')}}</small>
			                </p>
			              </li>
			              <!-- Menu Body -->
			              <li class="user-body">
			                <div class="row">
			                  <center>Company : {{session('company_name')}}</center>
			                </div>
			                <!-- /.row -->
			              </li>
			              <!-- Menu Footer-->
			              <li class="user-footer">
			                
			                <div class="pull-right">
			                  <a href="{{URL::to('logout')}}" class="btn btn-default btn-flat">Logout</a>
			                </div>
			              </li>
			            </ul>
			          </li>
			          
			        </ul>
			      </div>
			    </nav>
			  </header>
			  <!-- Left side column. contains the logo and sidebar -->
			  <aside class="main-sidebar">
			    <!-- sidebar: style can be found in sidebar.less -->
			    <section class="sidebar">
			      
			      <!-- /.search form -->
			      <!-- sidebar menu: : style can be found in sidebar.less -->
			      <ul class="sidebar-menu" data-widget="tree">
			      	<li>&nbsp</li>
			      	<li>&nbsp</li>

					{{-- <div class="user-panel">
						<div class="pull-left image">
							<img src="{{URL::to('')}}/images/user-dummy.png" class="img-circle" alt="{{Auth::user()->name}}">
						</div> <!-- /image -->
						<div class="pull-left info">
							<p>{{Auth::user()->name}}</p>
							<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
						</div> <!-- /info -->
					</div> <!-- /user-panel --> --}}
			        
			          
			        @if(Auth::user()->role_id == 4)
			        	@include('layout.sidebar_staff')
			            @include('layout.sidebar_owner')
			        @elseif(Auth::user()->role_id == 3)
			            @include('layout.sidebar_staff')

			        @endif
			        
			      </ul>
			    </section>
			    <!-- /.sidebar -->
			  </aside>

			  <!-- Content Wrapper. Contains page content -->
			  <div class="content-wrapper">
			    <!-- Content Header (Page header) -->
			    <section class="content-header">
			      <h1>
			        @yield('title')
			        
			      </h1>
			      {{-- <ol class="breadcrumb">
			        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			        <li class="active">Dashboard</li>
			      </ol> --}}
			    </section>

			    <!-- Main content -->
			    <section class="content">
			      <!-- Small boxes (Stat box) -->
			     
			      @yield('content')
			      
			      <!-- /.row -->
			      <!-- Main row -->
			      {{-- <div class="row"> --}}
			        <!-- Left col -->
			        {{-- <section class="col-lg-7 connectedSortable"> --}}

			          <!-- <div class="box box-primary"> -->
			          <!-- <div class="nav-tabs-custom"> -->
			          <!-- <div class="box box-success"> -->
			          <!-- <div class="box box-info"> -->
			          <!-- derta content -->

			        {{-- </section> --}}
			        <!-- /.Left col -->
			        <!-- right col (We are only adding the ID to make the widgets sortable)-->
			        
			        <!-- right col -->
			      
			      <!-- /.row (main row) -->

			    </section>
			    <!-- /.content -->
			  </div>
			  <!-- /.content-wrapper -->
			  <footer class="main-footer">
			    <div class="pull-right hidden-xs">
			      <b>Version</b> 0.0.0
			    </div>
			    <strong>Copyright &copy; 2018 HappyDeliv Developer Team</a>.</strong> All rights
			    reserved.
			  </footer>

			  
			  <!-- Add the sidebar's background. This div must be placed
			       immediately after the control sidebar -->
			  <div class="control-sidebar-bg"></div>
		</div> <!-- /wrapper -->

		<!-- jQuery 3 -->
		<script src="{{ URL::asset('dist')}}/bower_components/jquery/dist/jquery.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="{{ URL::asset('dist')}}/bower_components/jquery-ui/jquery-ui.min.js"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<script>
		  $.widget.bridge('uibutton', $.ui.button);
		</script>
		<!-- Bootstrap 3.3.7 -->
		<script src="{{ URL::asset('dist')}}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Morris.js charts -->
		<!-- <script src="{{ URL::asset('dist')}}/bower_components/raphael/raphael.min.js"></script> -->
		<!-- <script src="{{ URL::asset('dist')}}/bower_components/morris.js/morris.min.js"></script> -->
		<!-- Sparkline -->
		<script src="{{ URL::asset('dist')}}/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
		<!-- jvectormap -->
		<script src="{{ URL::asset('dist')}}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="{{ URL::asset('dist')}}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
		<!-- jQuery Knob Chart -->
		<script src="{{ URL::asset('dist')}}/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
		<!-- daterangepicker -->
		<script src="{{ URL::asset('dist')}}/bower_components/moment/min/moment.min.js"></script>
		<script src="{{ URL::asset('dist')}}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- datepicker -->
		<script src="{{ URL::asset('dist')}}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!-- Bootstrap WYSIHTML5 -->
		<script src="{{ URL::asset('dist')}}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
		<!-- Slimscroll -->
		<script src="{{ URL::asset('dist')}}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="{{ URL::asset('dist')}}/bower_components/fastclick/lib/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="{{ URL::asset('dist')}}/js/adminlte.min.js"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<!-- <script src="{{ URL::asset('dist')}}/js/pages/dashboard.js"></script> -->
		<!-- AdminLTE for demo purposes -->
		<script src="{{ URL::asset('dist')}}/js/demo.js"></script>

		

		<!-- DataTables v1.10.16 -->
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		@yield('script_custom')
	</body>
</html>