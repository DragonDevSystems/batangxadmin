@extends('layouts.master')
@section('addHead')
  <title>Dashboard</title>
@endsection

@section('content')
<div class="wrapper">
@include('includes.header')
  @include('includes.mainNav')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Access Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> User Access Management</a></li>
        <li class="active">User Access Level</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div-entry" class="box box-success"></div>
		<!-- user admin list -->
		<div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-header">
            	<h3 class="box-title">News List</h3>
            	<div class="box-tools pull-right">
                  <button id="editProduct" class="btn btn-info btn-sm " type="button" disabled>
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button id="deleteProduct" class="btn btn-danger btn-sm " type="button" disabled>
                    <i class="fa fa-trash"></i>
                    Delete
                  </button>
                </div>
            </div>
            <div class="box-body">
              <table id="newsList" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Title</th>
                  <th>Message</th>
                  <th>Date</th>
                </tr>
                </thead>
                <tbody id="tbUAList">
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('includes.footer')
  @include('includes.settingSidebar')
</div>
<script type="text/javascript">
	$(document).ready(function() {
		newsList();
		defaultDisplay();
	    //Initialize datatable Elements
	    var table = $('#newsList').DataTable();
	    $('#newsList tbody').on( 'click', 'tr', function () {
	        if ( $(this).hasClass('active') ) {
	            $(this).removeClass('active');
	            $('#editProduct').prop("disabled", true);
	            $('#deleteProduct').prop("disabled", true);
	        }
	        else {
	            table.$('tr.active').removeClass('active');
	            $(this).addClass('active');
	            $('#editProduct').prop("disabled", false);
	            $('#deleteProduct').prop("disabled", false);
	        }
	        if($('#editProduct').hasClass('continue_view'))
	        {
	        	var id = table.cell('.active', 0).data();
	        	setNewEntry(id);
	        }
	        $(window).scrollTop($('#div-entry').offset().top);
    	});
    	$('#editProduct').on( 'click', function () {
    		var id = table.cell('.active', 0).data();
    		$('#editProduct').addClass('continue_view');
    		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
    		setNewEntry(id);
    	});
    	$('#deleteProduct').on( 'click', function () {
    		var id = table.cell('.active', 0).data();
    		promptConfirmation('Are you sure?');
    		$("#btnYes").click(function(){
    			 var _token = "{{ csrf_token() }}";
    			$.post('{{URL::Route('deleteNews')}}',{ _token: _token ,id: id} , function(response)
				{
					if(response.status == "success"){
						promptMsg(response.status,response.message);
						newsList();
					}
				});
    		});
    	});
	});

	function newsList()
	{
		$.get('{{URL::Route('getNewsList')}}', function(response)
   		{
			$('#newsList').DataTable().clear();
			for (var i = 0; i < response.length; i++) 
        	{
				$('#newsList').DataTable().row.add([''+response[i].id+'', 
                                                    ''+response[i].title+'', 
                                                    ''+response[i].message+'',
                                                    ''+response[i].time+'',
                                                    ]).draw();
			}
		});
	}

	function setNewEntry(id)
	{
		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('accountAccessChecker',["view","product"])}}', function(data)
		{
			if(data.length != 0)
			{
				if(data.status == "success")
				{
					$.get('{{URL::Route('getNewsInfo')}}',{news : id}, function(data)
					{
						$('#div-entry').empty();
						$('#div-entry').append('<div class="box-header with-border">\
					          <h3 class="box-title">Add News</h3>\
					          <div class="box-tools pull-right">\
					            <button type="button" class="btn btn-danger btn-sm"  onClick="defaultDisplay()"><i class="fa fa-undo" aria-hidden="true"></i></button>\
					          </div>\
					        </div>');

						$('#div-entry').append('<div class="box-body formAddNews">\
													<form id="formAddNews" method="post" action="{{URL::Route('addNews')}}" role="form" enctype ="multipart/form-data">\
														<div class="row">\
															<div class="col-md-7">\
																<div class="form-group">\
																  <label for="title">Title :</label>\
																  <input type="hidden" class="form-control" id="news_id" name="news_id" value="'+data.id+'">\
																  <input type="text" class="form-control" id="title" name="title" value="'+data.title+'" placeholder="Enter news tile" required>\
																</div>\
																<div class="form-group">\
																  <label>Message :</label>\
																  <textarea style="resize: none;" class="form-control" rows="11"  placeholder="Enter news message..." name="message" id="message" required>'+data.message+'</textarea>\
																</div>\
																<input type="hidden" value="{{ csrf_token() }}" name="_token">\
															</div>\
															<div class="col-md-5">\
															<div class="form-group">\
																<label for="title">Browse here :</label>\
																<input type="file" id="file" name="file" '+data.required+'>\
															</div>\
															<div class="browse">\
															</div>\
															</div>\
														</div>\
														<div class="box-footer">\
															<button  type="submit" class="btn btn-primary addNews">'+data.btn_text+'</button>\
														</div>\
													</form>\
							           			</div>\
												');
						if(data.image.length !=0){
							var template = 
					        '<div>'+
						        '<div style="width:100%;height:280px;padding:10px;padding-bottom:0px;padding-top:0px;border:1px solid #e7e7e7;margin-bottom:10px;overflow:hidden;">'+
						          '<div class="pull-right">'+
						            '<button type="button" data-toggle="tooltip" title="Remove" class="btn btn-box-tool removeImage" data-id=""><i class="fa fa-trash-o"></i></button>'+
						          '</div>'+
						          '<img src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/'+data.image+'" style=" width:100%;height:230px;margin-bottom:5px;">'+
						        '</div>'+
					        '</div>';
					        
					        $('.browse').append(template);
						}
						$('.addImage').click( function () {
							$('#file').click();
						});
					});
					

					/*$('.addNews').click( function () {
						$('#formAddNews').find(':submit').click();
						var title = $('#title').val();
						var message = $('#message').val();
						$.post('{{URL::Route('addPrice')}}',{ _token: _token ,amount: amount, id : id} , function(response)
						{
						});
					});*/
					
					$(document).on("change","#file",function(e){
						$('.browse').empty();
						var fileCollection = new Array();
					    var  files = e.target.files;
					    $x = 0;
					    $.each(files, function(i, file)
					    {
					      fileCollection.push(file);
					      var reader = new FileReader();
					      reader.readAsDataURL(file);
					      reader.onload = function(e)
					      {
					        var template = 
					        '<div>'+
						        '<div style="width:100%;height:280px;padding:10px;padding-bottom:0px;padding-top:0px;border:1px solid #e7e7e7;margin-bottom:10px;overflow:hidden;">'+
						          '<div class="pull-right">'+
						            '<button type="button" data-toggle="tooltip" title="Remove" class="btn btn-box-tool removeImage" data-id="'+$x+'"><i class="fa fa-trash-o"></i></button>'+
						          '</div>'+
						          '<img src="'+e.target.result+'" style=" width:100%;height:230px;margin-bottom:5px;">'+
						        '</div>'+
					        '</div>';
					        
					        $('.browse').append(template);
					      };
				    	});
					});
				}
				else
				{
					promptMsg(data.status,data.message);
					$('.overlay').remove();
				}
			}
		});
    }
	function defaultDisplay()
	{
	   	$('#div-entry').append('<div class="overlay">\
			        	<i class="fa fa-spinner fa-spin"></i>\
			        </div>');
	   	$('#div-entry').empty();
		$('#div-entry').append(
				$('<div />' , { 'class' : 'box-header with-border'}).append(
					$('<h3 />' , { 'class' : 'box-title' , 'text' : 'News'}),
					$('<div />' , { 'class' : 'box-tools pull-right'}).append(
						'<button id="btn-new-user" class="btn btn-primary btn-sm" type="button" onClick="setNewEntry();">\
							<i class="fa fa-plus-circle"></i>\
							New\
						</button>\
						<button class="btn btn-box-tool" data-widget="collapse">\
							<i class="fa fa-minus"></i>\
						</button>')),
				$('<div />' , { 'class' : 'box-body' , 'style' : 'min-height:100px'}).append(
					$('<div />' , { 'class' : 'row' }).append(
						$('<div />' , {'class' : 'col-md-12 col-lg-12'}).append(
							$('<h1 />' , { 'class': 'text-center'}).append(
								$('<small />').append(
									$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'onClick':'setNewEntry();' , 'html' : '<i class="fa fa-plus-circle fa-3x"></i>Add News'}).append(
										''),
									$('<br />'),
									'Click on the below list for preview'))))),
				$('<div />' , { 'class' : 'box-footer'}));
	}

	
</script>
@endsection

