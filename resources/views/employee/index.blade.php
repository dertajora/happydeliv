@extends('layout.dashboard')
@section('script_custom')
<script type="text/javascript">
$('#scheduling_monthly_report').addClass('active');
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
@section('title','Employees')

@if (session('status'))
@section('notification_message')
<script type="text/javascript">
swal("{{session('status')}}");
</script>
@endsection
@endif

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
          <a href="{{'manage_employees/add'}}" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> &nbsp&nbsp Add Employee</a>
          <br><br>
        </div>
    </div>  
</div>
<div class="row">
	<div class="col-md-12">
		<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              {{-- start --}}
                    <form method="GET">
                    
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="period" id="period">
                            <option value="">Choose Role</option>
                            <option value="2">Courrier</option>
                            <option value="3">Staff Office</option>

                        </select>
                      </div>
                    </div>
	                
                	
	                  <div class="col-md-2">
                      <label>&nbsp</label>
                      <div class="form-group">
                         <input type="submit" value="Search" class="btn btn-warning btn-flat">
                         </form>
                      </div>
                    </div>
                    
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer-->
          	</div>
          	
			<div class="box box-danger">
			<div class="box-body pad">
				<div class="table-responsive">
					<table id="tableFullFeatures" class="table table-border">

						<thead>
							<tr>
								<th width="5%">ID</th>
								<th>Name</th>
								<th>Phone Number</th>
								<th>Email</th>
                <th>Role</th>
                <th>Action</th>
							</tr>
						</thead>

						<tbody>
							
                @if(count($users) == 0)
                    <tr><Td colspan="5"><i><center>No Data Found</center></i></td></tr>
                @else
                    @foreach($users as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->phone}}</td>
                            <td>{{$row->email}}</td>
                            <td>{{$row->role}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif
							
							

						</tbody>

						

					</table> <!-- /table -->
				</div> <!-- /table-responsive -->
			</div> <!-- /box-body -->
      <div class="box-footer clearfix">  
        <ul class="pagination pagination-sm no-margin pull-right">
          {{-- to include parameter searching in pagination --}}
          
        </ul>
      </div>
			</div> <!-- /box -->
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