
@extends('layout.dashboard')
@section('script_custom')
<script type="text/javascript">
  $('#credential_management').addClass('active');

function generate_token_api(){
    client_id = $('#client_id').val();
    client_secret = $('#client_secret').val();
    
    $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "POST",
    url: "{{ url('/generate_token_api') }}",
    data: {client_id: client_id, client_secret : client_secret},
    success: function(msg) {
      // swal("Generate token success");
      setTimeout(location.reload.bind(location), 1000);
      
    },
    error: function(e) {
      console.log(e);
    }
    });
      
    
}
</script>
@endsection


@section('content')
     <div class="col-md-6">
          
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Credential API</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Client ID</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{$credentials->client_id}}" placeholder="Email" disabled>
                    <input type="hidden" id="client_id" value="{{$credentials->client_id}}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Client Secret</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{$credentials->client_secret}}" placeholder="Password" disabled>
                    <input type="hidden" id="client_secret" value="{{$credentials->client_secret}}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">&nbsp</label>
                  <div class="col-sm-10">
                  <a href="#" type="submit" class="btn btn-primary " onclick="generate_token_api()">Generate Token</a>
                  </div>
                </div>
                <?php 
                      if(!empty($credentials->token_api)){
                        $token_api = $credentials->token_api;
                        $expired_time = date('Y-m-d H:i:s ', strtotime($credentials->token_generated_time.' + 1 hours'));
                      }else{
                        $token_api = "Token HappyDeliv API";
                        $expired_time = "Token Expired Time";
                      }
                ?>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Token API</label>
                  
                  <div class="col-sm-10">
                    <input type="text" class="form-control"  value="{{$token_api}}" placeholder="Generated Token" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Expired Time</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{$expired_time}}" placeholder="Generated Token" disabled>
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
    </div>
@endsection
