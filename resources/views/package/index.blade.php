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
@section('title','Packages')

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
          <a href="{{'manage_packages/add'}}" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> &nbsp&nbsp Add Package</a>
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
                        <label>Created By</label>
                        <select class="form-control" name="period" id="period">
                            <option value="">Choose Employee</option>
                            @foreach($list_employees as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach

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
								<th width="5%">No</th>
								<th>No Resi</th>
                <th>Recipient Name</th>
								<th>Recipient Phone Number</th>
								<th>Recipient Address</th>
                <th>Created By</th>
                <th>Created Time</th>
                
                {{-- <th>Action</th> --}}
							</tr>
						</thead>

						<tbody>
							
                @if(count($packages) == 0)
                    <tr><Td colspan="7"><i><center>No Data Found</center></i></td></tr>
                @else
                    @foreach($packages as $row)
                        <tr>
                            <td>{{$number}}</td>
                            <td>{{$row->resi_number}}</td>
                            <td>{{$row->recipient_name}}</td>
                            <td>{{$row->recipient_phone}}</td>
                            <td>{{$row->recipient_address}}</td>
                            <td>{{$row->created_by}}</td>
                            <td>{{$row->created_at}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    <?php $number+= 1 ;?>
                    @endforeach
                @endif
							
							

						</tbody>

						

					</table> <!-- /table -->
				</div> <!-- /table-responsive -->
			</div> <!-- /box-body -->
      <div class="box-footer clearfix">  
        <ul class="pagination pagination-sm no-margin pull-right">
          {{-- to include parameter searching in pagination --}}
          {{ $packages->links() }}
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