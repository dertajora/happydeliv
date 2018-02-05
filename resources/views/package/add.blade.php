@extends('layout.dashboard')
@section('script_custom')
<script type="text/javascript">
$('#manage_packages').addClass('active');
//Date picker
$('#start_date').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true
})

$('#end_date').datepicker({
  format: 'yyyy-mm-dd',
  maxDate: 0,
  autoclose: true
})

$( document ).ready(function() {

	  

}); 


</script>
@endsection
@section('title','Add Package')

@section('content')
@if (session('status'))
<div class="alert alert-warning">
	{{ session('status') }}
</div> <!-- /alert -->
@endif

<div class="row">
	<div class="col-md-6">
		<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              {{-- start --}}
                    <form method="POST" action="{{URL::to('manage_packages/save')}}">
                    {{ csrf_field() }} 


                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>No Resi </label>
                        <input type="text" name="resi_number" class="form-control" id="resi_number">
                        
                      </div>
                    </div>
                    </div>


                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Recipient Name</label>
                        <input type="text" name="recipient_name" class="form-control" id="recipient_name">
                        
                      </div>
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Recipient Phone</label>
                        <input type="number" name="recipient_phone" class="form-control" id="recipient_phone">
                        
                      </div>
                    </div>
                    </div>


                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Recipient Address</label>
                        <textarea class="form-control" rows="3" name="recipient_address" id="recipient_address">

                        </textarea>
                        
                      </div>
                    </div>
                    </div>
                    
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="submit" value="Submit Package" class="btn btn-primary btn-flat">
                        </div>
                      </div>
                    </div>
                    
	                 
                    
                	
	                  
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer-->
          	</div>
          	
			
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->
@endsection

@section('script_custom')
<script type="text/javascript">
$(document).ready(function() {
    $('#tableFullFeatures').DataTable();
});
</script>
@endsection