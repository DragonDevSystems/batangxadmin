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
        Product List
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::Route('getUAL') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div id="div-entry" class="box box-success">
		</div>

		<!-- product list -->
		<div class="box box-primary">
            <div class="box-header">
            	<h3 class="box-title">Product List</h3>
            	<div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" data-placeid="" onClick="setNewEntry();">
                    <i class="fa fa-plus"></i>
                    Add Product
                  </button>
                  <button id="editProduct" class="btn btn-info btn-sm " type="button" disabled>
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="product_list" class="table table-bordered table-hover">
                <thead>
                <?php 
                    $headers = Schema::getColumnListing('prod_information');
                    $include = ["id","name","description"]; 
                ?>
                <tr>
                  @foreach($headers as $header)
                    @if(in_array($header, $include))
                       <th>{{$header}}</th>
                    @endif
                  @endforeach
                </tr>
                </thead>
                <tbody>
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
		productList();
	    //Initialize datatable Elements
	    var table = $('#product_list').DataTable();
	    $('#product_list tbody').on( 'click', 'tr', function () {
	        if ( $(this).hasClass('active') ) {
	            $(this).removeClass('active');
	            $('#editProduct').prop("disabled", true);
	        }
	        else {
	            table.$('tr.active').removeClass('active');
	            $(this).addClass('active');
	            $('#editProduct').prop("disabled", false);
	        }
	        if($('#editProduct').hasClass('continue_view'))
	        {
	        	var id = table.cell('.active', 0).data();
	        	setNewEntry(id);
	        }
    	});
    	$('#editProduct').on( 'click', function () {
    		var id = table.cell('.active', 0).data();
    		$('#editProduct').addClass('continue_view');
    		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
    		setNewEntry(id);
    	});
	    
	});
	defaultDisplay();
	
	function productList()
	{
		 $.get('{{URL::Route('getProductList')}}', function(response)
   		 {
			$('#product_list').DataTable().clear();
			for (var i = 0; i < response.length; i++) 
        	{
				$('#product_list').DataTable().row.add([''+response[i].id+'', 
                                                    ''+response[i].name+'', 
                                                    ''+response[i].description+'',
                                                    ]).draw();
			}
			
		});
	}

	function defaultDisplay()
	{
		$('#editProduct').removeClass('continue_view');
	   	$('#div-entry').append('<div class="overlay">\
			        	<i class="fa fa-spinner fa-spin"></i>\
			        </div>');
	   	$('#div-entry').empty();
		$('#div-entry').append(
				$('<div />' , { 'class' : 'box-header with-border'}).append(
					$('<h3 />' , { 'class' : 'box-title' , 'text' : 'Add Product'}),
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
									$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'onClick':'setNewEntry();' , 'html' : '<i class="fa fa-plus-circle fa-3x"></i>Add Product'}).append(
										''),
									$('<br />'),
									'Click on the below list for preview'))))),
				$('<div />' , { 'class' : 'box-footer'}));
	}

	function setNewEntry(id)
	{
		$('#div-entry').append('<div class="overlay">\
		        	<i class="fa fa-spinner fa-spin"></i>\
		        </div>');
		$.get('{{URL::Route('accountAccessChecker',0)}}',{ event: "add"}, function(data)
		{
			if(data.length != 0)
			{
				if(data.status == "success")
				{
					$.get('{{URL::Route('getProductInfo')}}',{ product: id}, function(data)
					{
						console.log(data);
						if(data.length != 0)
						{
							$('#div-entry').empty();
							$('#div-entry').append('<div class="box-header with-border">\
						          <h3 class="box-title">Add Product</h3>\
						          <div class="box-tools pull-right">\
						            <button type="button" class="btn btn-danger btn-sm"  onClick="defaultDisplay()"><i class="fa fa-undo" aria-hidden="true"></i></button>\
						          </div>\
						        </div>');

							$('#div-entry').append('<div class="box-body formAddProduct">\
															<div class="row">\
																<div class="col-md-4">\
																	<form id="formAddProduct" method="post" role="form">\
																		<div class="form-group">\
																		  <label for="name">Product Name :</label>\
																		  <input type="text" class="form-control" id="name" name="name" value="'+data.name+'" placeholder="Enter product name" required>\
																		</div>\
																		<div class="form-group">\
															                <label for="category">Category :</label>\
															                <select id="category" name="category" class="form-control select2" style="width: 100%;" required>\
															                	@foreach($category as $categoryi)\
															                  	<option  value="{{$categoryi['id']}}">{{$categoryi['name']}}</option>\
															                  	@endforeach\
															                </select>\
															            </div>\
																		<div class="form-group">\
																		  <label for="description">Description :</label>\
																		  <textarea style="resize: none;" class="form-control" rows="3"  placeholder="Enter product description..." name="description" id="description" required>'+data.description+'</textarea>\
																		</div>\
																		<div class="specs_list">\
																			<label for="specs">Specs :</label>\
																		</div>\
																		<button type="submit" style="display:none"></button>\
																	</form>\
																	<form id="uploadProductImage" role="form" enctype ="multipart/form-data" method="post">\
																		<input type="file" id="file" name="file[]" style="display:none" multiple>\
																		<input type="hidden" id="processType" value="old" name="processType">\
																		<input type="hidden" value="{{ csrf_token() }}" name="_token">\
																		<button type="submit" class="btn btn-primary submitImage" style="display:none"></button>\
																	</form>\
																</div>\
																<div class="col-md-8 browse">\
																</div>\
															</div>\
															<div class="box-footer">\
																<button data-id='+data.id+' type="button" class="btn btn-primary addProduct">'+data.status+'</button>\
															</div>\
								           				</div>\
													');
							
							$('.browse').append(
								$('<div />' , { 'class' : 'box-body' , 'style' : 'min-height:100px'}).append(
									$('<div />' , { 'class' : 'row' }).append(
										$('<div />' , {'class' : 'col-md-12 col-lg-12'}).append(
											$('<h1 />' , { 'class': 'text-center'}).append(
												$('<small />').append(
													$('<button />' , { 'id':'btn-new-user-icn' , 'class':'btn btn-app addImage' , 'data-placement':'top' , 'data-toggle':'tooltip' , 'type':'button' , 'html' : '<i class="fa fa-plus-circle fa-3x"></i>Add Image'}).append(
														''),
													$('<br />'),
													'Click on here'))))));
       						$('#category')
			                  .val(data.pro_cat_id) //select option of select2
			                  .trigger("change"); //apply to select2
			                if(data.images.length != 0){
			                	for (var i = 0; i < data.images.length; i++) 
								{
									var template = 
							        '<div class="col-lg-3 col-md-3 col-xs-6 image_wrapper">'+
								        '<div  style="width:100%;padding:10px;padding-bottom:0px;padding-top:0px;border:1px solid #e7e7e7;margin-bottom:10px;">'+
								          '<div class="pull-right">'+
								            '<button type="button" data-toggle="tooltip" title="Remove" class="btn btn-box-tool removeImage" data-id="'+data.images[i].id+'"><i class="fa fa-trash-o"></i></button>'+
								          '</div>'+
								          '<img src="{{env('FILE_PATH_CUSTOM')}}productThumbnail/'+data.images[i].thumbnail_img+'" style="width:100%;height:auto;margin-bottom:5px;">'+
								        '</div>'+
							        '</div>';
							        
							        $('.browse').append(template);
								}
			                }
			                if(data.specs.length != 0){
				                for (var i = 0; i < data.specs.length; i++) 
								{
									$('.specs_list').append('<div class="form-group ">\
														  	<div class="input-group">\
															    <input type="text" placeholder="add specs" value="'+data.specs[i].specs+'" class="form-control" aria-label="...">\
																<div class="input-group-btn">\
																<button data-id="'+data.specs[i].id+'" type="button" class="btn btn-default minus">\
																	<i class="fa fa-minus" aria-hidden="true"></i>\
																</button>\
																</div>\
															</div>\
														</div>');

									if(i == (data.specs.length -1)){
										$('.specs_list').find('.input-group-btn').last().append('<button type="button" data-id="'+data.specs[i].id+'" class="btn btn-default plus">\
																	<i class="fa fa-plus" aria-hidden="true"></i>\
																</button>');
									}
								}
							}
							else{
								$('.specs_list').append('<div class="form-group ">\
														  	<div class="input-group">\
															    <input type="text" name="specs" placeholder="add specs" class="form-control" aria-label="...">\
																<div class="input-group-btn">\
																<button type="button" class="btn btn-default plus">\
																	<i class="fa fa-plus" aria-hidden="true"></i>\
																</button>\
																</div>\
															</div>\
														</div>');
							}
							
							$('#category').trigger('change');
							var form = document.getElementById('uploadProductImage');
							var request = new XMLHttpRequest();
							form.addEventListener('submit',function(e){
								e.preventDefault();
								var formdata = new FormData(form);
								request.open('post','{{ URL::Route('uploadProductImage')}}');
								
								request.onreadystatechange = function() {
								    if (request.readyState == 4 && request.status == 200) {
								      arr = JSON.parse(request.responseText);
								      if(arr.length != 0)
								      {   
								        for (var i = 0; i < arr.length; i++) 
								        { 
								          $('.removeImage[data-id="'+arr[i].count+'"]').attr("data-id",arr[i].image_id);
								        }
								      }
								    }
								};
								request.upload.addEventListener("progress", uploadProgressFile, false);
								request.addEventListener('load',transferCompleteFile);
								request.send(formdata);
							});
							function uploadProgressFile(evt) {
						        if (evt.lengthComputable) {
						            var percentComplete = Math.round(evt.loaded * 100 / evt.total);
						            $(".progress").find('.progress-bar').css("width",percentComplete+"%").html(percentComplete+"% Complete (upload)");
						        $('.addProduct').attr("disabled",true);
						        }
						        else {
						          alert('cant upload.')
						        }
						      }
							function transferCompleteFile(evt) {
						        console.log("The transfer file  is complete.");
						        $("#processType").val("new");
						        $('.addProduct').attr("disabled",false);
						        $(".progress").fadeOut("slow",function(){ 
						                                                  $(this).remove(); 
						                                               });
						    }
						    $('.addProduct').click( function () {
								$('#formAddProduct').find(':submit').click();
								var name = $('.formAddProduct').find('#name').val();
								var category = $('.formAddProduct').find('#category').val();
								var description = $('.formAddProduct').find('#description').val();
								var id = $(this).data('id');
								$specs =[];
								$x = 0;
								$("input[name='specs']").each(function(){
									$specs[$x]= $(this).val();
									$x++;
								});
								var check = $('.formAddProduct').find('.browse').find('.image_wrapper').length;
								$this = $(this);
								if(!$.trim(name) == '' && !$.trim(category) == '' && !$.trim(description) == ''){
									if(check != 0){
										var _token = "{{ csrf_token() }}";
										var message = (id == "new") ? "add" : "update";
										var status = confirm("Do you want to "+message+" this product?");
										if(status == true){
											$.post('{{URL::Route('addProduct')}}',{ _token: _token ,name: name, 
											category : category, description : description, specs : $specs, id : id} , function(response)
						    				{
						    					console.log(response);
						    					if(response.status = "success"){
						    						$this.closest(".formAddProduct").fadeOut("slow",function(){ 
					                                                  $(this).remove(); 
					                                                });
						    						defaultDisplay();
						    						productList();
						    						$(window).scrollTop($('#product_list').offset().top);
						    					}
						    				});
										}
									}
									else{
										alert('Please select image of product.');
									}
								}
							});
							$(document).on("submit", "#formAddProduct", function(e){
							    e.preventDefault();
							    return  false;
							});
							$('.addImage').click( function () {
								$('#file').click();
							});
							$(".select2").select2();
						    //end
						}
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
    $(document).on("click",".plus",function(){
    	
    	var checkValue= $(this).closest(".input-group").find("input").val().length;
    	if(checkValue != 0){
    		$(this).closest('.input-group').find('.minus').remove();
    		$(this).html('<i class="fa fa-minus" aria-hidden="true"></i>').addClass('minus').removeClass('plus');
	    	$('.specs_list').append('<div class="form-group ">\
									  	<div class="input-group">\
										    <input type="text" name="specs" placeholder="add specs" class="form-control">\
											<div class="input-group-btn">\
											<button type="button" class="btn btn-default plus">\
												<i class="fa fa-plus" aria-hidden="true"></i>\
											</button>\
											</div>\
										</div>\
									</div>');
    	}
    	else{
    		$(this).closest(".input-group").find("input[name='specs']").focus();
    	}
    	
    });
    $(document).on("click",".minus",function(){
    	var id = $(this).data('id');
    	var _token = "{{ csrf_token() }}";
    	$this = $(this);
    	if(typeof(id)  !== "undefined"){
    		var status = confirm("Do you want to remove this previous specs?");
    		if(status == true){
    			$.post('{{URL::Route('deleteSpecs')}}',{ _token: _token ,specs: id} , function(response)
				{
					if(response.status == "success"){
						$this.closest('.form-group').fadeOut("slow",function(){ 
				                                                  $(this).remove(); 
				                                               });
						promptMsg(response.status,response.message);
					}
				});
    		}
    		
    	}
    	else{
    		$(this).closest('.form-group').fadeOut("slow",function(){ 
				                                                  $(this).remove(); 
				                                               });
    	}
    	

    });
    $(document).on("click",".removeImage",function(){
    	var id = $(this).data('id');
    	var status = confirm('Do you want to remove this image?');
    	var _token = "{{ csrf_token() }}";
	    $this = $(this);
	    if(status  == true){
	    	$.post('{{URL::Route('deleteImage')}}',{ _token: _token ,image: id} , function(response)
			{
			if(response.status == "success"){
				$this.closest(".image_wrapper").fadeOut("slow",function(){ 
			                                          $(this).remove(); 
			                                        });
			}
			});
	    }
  	});
    $(document).on("change","#file",function(e){
    	$(this).closest(".box-body").append('<div class="progress">\
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"\
                aria-valuemin="0" aria-valuemax="100" style="width:0%">\
                  0% Complete (success)\
                </div>\
              </div>')
    	$('.submitImage').click();
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
	        '<div class="col-lg-3 col-md-3 col-xs-6 image_wrapper">'+
		        '<div  style="width:100%;padding:10px;padding-bottom:0px;padding-top:0px;border:1px solid #e7e7e7;margin-bottom:10px;">'+
		          '<div class="pull-right">'+
		            '<button type="button" data-toggle="tooltip" title="Remove" class="btn btn-box-tool removeImage" data-id="'+$x+'"><i class="fa fa-trash-o"></i></button>'+
		          '</div>'+
		          '<img src="'+e.target.result+'" style="width:100%;height:auto;margin-bottom:5px;">'+
		        '</div>'+
	        '</div>';
	        
	        $('.browse').append(template);
	        $x++;
	      };
      
    	});
    });

    function saveNewEntry()
    {
    	promptConfirmation("Are you sure you want to add this new category?");
    	/*$('#btnYes').click(function() {
	    	$_token = "{{ csrf_token() }}";
	    	$name = $('#name').val();
	    	$description = $('#description').val();
	    	$('#div-entry').append('<div class="overlay">\
				        	<i class="fa fa-spinner fa-spin"></i>\
				        </div>');
	    	$.post('{{URL::Route('addCategory')}}',{ _token: $_token, name: $name, description: $description}, function(data)
			{
				if(data.length != 0)
				{
					if(data.status == "success")
					{
						productList();
						defaultDisplay();
					}
					else
					{
						$('.overlay').remove();
					}
					promptMsg(data.status,data.message);
				}
			});
    	});*/
    	return false;
    }
</script>
@endsection

