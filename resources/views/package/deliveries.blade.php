@extends('layout.dashboard')
@section('script_custom')
<script type="text/javascript">
$('#manage_deliveries').addClass('active');

$( document ).ready(function() {
    @if (!empty($_GET['keyword']))
        $('#keyword').val("{{$_GET['keyword']}}");
    @endif  
}); 


</script>
@endsection
@section('title','List Deliveries')

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
		<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              {{-- start --}}
                    <form method="GET">
                    
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Track ID / Nomor Resi</label>
                        <input type="text" name="keyword" class="form-control" id="keyword">
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
                <th>Track ID</th>
								<th>Status</th>
								<th>Current Position</th>
                <th>Courrier Name</th>
                <th>Delivered Time</th>
                
                {{-- <th>Action</th> --}}
							</tr>
						</thead>

						<tbody>
							
                @if(count($deliveries) == 0)
                    <tr><Td colspan="7"><i><center>No Data Found</center></i></td></tr>
                @else
                    @foreach($deliveries as $row)
                        <tr>
                            <td>{{$number}}</td>
                            <td>{{$row->resi_number}}</td>
                            <td>{{$row->track_id}}</td>
                            
                            <td>
                                @if ($row->status == 1) 
                                    <span class="label label-warning">Pending</span>
                                @elseif ($row->status == 2) 
                                    <span class="label label-primary">In-Progress</span>
                                @elseif ($row->status == 3) 
                                    <span class="label label-success">Completed</span>
                                @endif
                                
                            </td>
                            <td>{{$row->current_lat}}, {{$row->current_longi}}</td>
                            <td>
                                @if ($row->courrier_id == null) 
                                    -
                                @else
                                    {{$row->courrier_id}}
                                @endif
                            </td>
                            <td>
                                @if ($row->finished_at == null) 
                                    -
                                @else
                                    {{$row->finished_at}}
                                @endif
                            </td>
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
          {{ $deliveries->links() }}
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