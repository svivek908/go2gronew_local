<style>
	.upload-item-img{
		float: left;
	}
	a#make1 {
    color: #7fcb28;
    cursor: pointer;
}
</style>
<?php
//get api and segment
	$itemid=$this->uri->segment(3); // 1stsegment
?>
<script>
	//edititem list
	var store_id = "<?= $store_id; ?>";
	$(document).ready(function()
	{
		var imagecount;
		jQuery.ajax({
			type: 'GET',
			url:'<?php echo base_url();?>Admin/edititem/'+'<?= $itemid;?>',
			data: {},
			success: function (res) {
				//console.log(res);
				var item=res.item[0];
				console.log(item.item_name);
				getMotherCategoryEdit(item.mother_cat_id);
				console.log(item.mother_cat_id);
                var fluid_ounce = (item.fluid_ounce === null) ? "" : item.fluid_ounce;
			    var edititemhtml="";
				edititemhtml+='<div class="form-group">\
									<label for=""> Item Name</label>\
									<input class="form-control" placeholder="Item Name" type="text"  onfocus="show()" id="name" value="'+item.item_name+'">\
									<span id="error1"></span>\
								</div>\
								<div class="form-group">\
									<label for=""> Item Size</label>\
									<input class="form-control" placeholder="Item Size" type="text"  onfocus="show()" id="item_size" value="'+item.item_size+'">\
									<span id="error8"></span>\
								</div>\
								<div class="form-group">\
									<label> Short Description</label>\
									<textarea class="form-control"  onfocus="show()"  rows="3" id="Description">'+item.item_sdesc+'</textarea>\
									<span id="error2"></span>\
								</div>\
								<div class="form-group">\
									<label> Full Description</label>\
									<textarea class="form-control"   onfocus="show()" rows="3" id="fulldescription">'+item.item_fdesc+'</textarea>\
									<span id="error3"></span>\
								</div>\
						<div class="row">\
							<div class="col-sm-6">\
								<div class="form-group">\
									<label for=""> Sales Tax</label>\
									<input class="form-control" placeholder="Sales Tax"  onfocus="show()"  id="tax" value="'+item.Sales_tax+'" type="text">\
									<span id="error4"></span>\
								</div>\
							</div>\
							<div class="col-sm-6">\
								<div class="form-group no-margin">\
									<label for="">Price</label>\
								</div>\
									<div class="input-group">\
									<div class="input-group-addon">$</div><input class="form-control" placeholder="Price" id="price"  onfocus="show()"  type="text" value="'+item.item_price+'"><span id="error5"></span></div></div></div>\
                <div class="row">\
					<div class="col-sm-6">\
					    <div class="form-group no-margin">\
					        <label for="">Mother\'s Category</label>\
				        </div>\
				        <div class="input-group"><select class="form-control" id="rack" required=""><option value="">Select Category</option></select>\
					        <span id="error7"></span>\
                        </div>\
				    </div>\
				    <div class="col-sm-6">\
					    <div class="form-group no-margin">\
					        <label for="">Fluid Ounce</label>\
				        </div>\
				        <div class="input-group">\
				            <input class="form-control" placeholder="Fluid Ounce" id="fluid_ounce" onfocus="show()"  type="text" value="'+ fluid_ounce +'">\
					        <span id="error9"></span>\
                        </div>\
				    </div>\
				</div>\
                <div class="form-buttons-w"></div>';
				$("#forminfo").prepend(edititemhtml);
				var itemlist=res.images;
				var jsonlength=itemlist.length;
				imagecount = jsonlength;
				var imageshtml = '';
				for (var i = 0; i < jsonlength; i++) {
					var result = itemlist[i];
					console.log(item.primary_imageid);


					imageshtml="";
					imageshtml+='<div class="col-md-2 no-pl upload-item-img" id="primary" id="old_\''+'+result.image_id+'+'\'"" >\
										<div class="dropzone no-pad" id="pk">\
											<div class="dz-message no-pad">\
												<div class="thumbnail no-margin">\
												   <img src="<?php echo base_url();?>'+result.imageurl+'" alt="Lights" id="'+result.image_id+'" style="width:100%; height:100%">\
							'+(result.image_id==item.primary_imageid ?"":'<a id="make1" onclick="make_primary(\''+result.image_id+'\')" >Make Primary</a>')+'\
												</div>\
											</div>\
											'+(result.image_id==item.primary_imageid?'':'<span class="img-close" id="oldremove'+result.image_id+'" onclick="oldremove(\''+result.image_id+'\')"> x </span>')+'\
												 '+(result.image_id==item.primary_imageid?'<span class="primary-img">p</span>':'')+'\
																													</div>';
					$("#old-images").append(imageshtml);
					//console.log(item.primary_imageid);
				}
			}
		});

		var selDiv = "";
		var storedFiles = [];
		$("#files").on("change", handleFileSelect);
		$("#product-image").on("click", ".selFile", removeFile);
		selDiv = $("#product-image");

		function handleFileSelect(e) {
			var files = e.target.files;
			var filesArr = Array.prototype.slice.call(files);
			filesArr.forEach(function(f) {

				if(!f.type.match("image.*")) {
					return;
				}
				storedFiles.push(f);

				var reader = new FileReader();
				reader.onload = function (e) {
					var html = '<div class="col-md-2 no-pl upload-item-img ">\
				<div class="dropzone no-pad">\
				<div class="dz-message no-pad">\
					<div class="thumbnail no-margin" >\
					   <img class="selFile" data-file="'+f.name+'" src="'+e.target.result+'" title="Click to remove" alt="Lights" style="width:100%">\
					</div>\
				</div>\
				<!--<span class="img-close" >x</span>-->\
				</div>\
			</div>';
					selDiv.append(html);

				}
				reader.readAsDataURL(f);
			});

		}

		function removeFile(e) {
			var file = $(this).data("file");
			for(var i=0;i<storedFiles.length;i++) {
				if(storedFiles[i].name === file) {
					storedFiles.splice(i,1);
					break;
				}
			}
			$(this).parent().parent().parent().parent().remove();
		}
	$("#click").click(function()
   	{
   		
   		var item_id				=		"<?php echo $itemid; ?>";
	  	var name 				=		$("#name").val();
	  	var Description  		=		$("#Description").val();
	  	var fulldescription		=		$("#fulldescription").val();
	  	var tax					=		$("#tax").val();
	  	var price				=	    $("#price").val();
		var rackno				=	    $("#rack").val();
	  	var product_image		=		$("#files").val();
		var item_size = $("#item_size").val();
        var fluid_ounce = -1;

        if($('#fluid_ounce').val() != ''){
            if (isNaN($('#fluid_ounce').val())){
                $("#error9").html("* Enter a valid numeric value");
                return false;
            } else {
                fluid_ounce = $('#fluid_ounce').val();
            }
        }

	  	//var image				=		array;
	  //	console.log(item_id);
	  	if(name=="")
   		{
   			$("#error1").html("*item name required");
   			return false;
   			
   		}
   		/*if(Description=="")
   		{
   			$("#error2").html("*Short Description required");
   			return false;
   		}
   		if(fulldescription==0)
   		{
   			$("#error3").html("Full Description required");
   			return false;
   		}*/

   		if(tax=="")
   		{
   			$("#error4").html("* Enter only Numbric value");
   			 return false;

   		}
   		if(isNaN(tax))
   		{

   			$("#error4").html("* Enter only Numbric value");
   			 return false;
   		}
   			if(price=="")
   		{
   			$("#error5").html("*price must be required");
   			return false;
   		}


   		if(isNaN(price))
   		{
   			$("#error5").html("* Enter only Numbric value");
   			return false;
   		}
		if(rackno=="")
		{
			$("#error7").html("*Please Select Category");
			return false;
		}


		var imageval = 10-imagecount;
		if(storedFiles.length > imageval){
			$("#error6").html("* Please upload only "+imageval+" images.");
			return false;
		}

        $("#fluid_ounce").focus(function () {
            $("#error9").html("");
        });

	
        var formdata = new FormData();
	   	formdata.append('item_id',item_id);
		formdata.append('item_size',item_size);
        formdata.append('item_name',name);
        formdata.append('item_sdesc',Description);
        formdata.append('item_fdesc',fulldescription);
        formdata.append('item_price',price);
        formdata.append('Sales_tax',tax);
		formdata.append('rackno',rackno);
        formdata.append('fluid_ounce', fluid_ounce);
        //formdata.append('image',array);
      //var x= $("#files")[0].files[0];
          jQuery.each(storedFiles, function(i, file) {
        formdata.append('image['+i+']', file);
      });
		//console.log(x);
          //console.log(formdata);
		jQuery("#fullLoader").show();
        $.ajax({
            method: "post",
			url:"<?php echo base_url('Admin/updateitem');?>",
            data:formdata,
            contentType:false,
            processData: false,
            success:function(data){
				jQuery("#fullLoader").hide();
				if(data.error){
					swal("Oops!", data.message, "error");
				}else{

					swal({
							title:"Success",
							type:'success',
							text: "Product updated successfully.",
							closeOnConfirm: false,
							animation: "slide-from-top"
						},
						function(){
							location.reload();
						});
				}
            
            }
       
		});

		
	});


});

function getMotherCategoryEdit(cat_id) {
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url();?>Admin/getMothersCategory',
		data: {'cat_id':cat_id,'':store_id},
		success: function (res) {
			$("#rack").html(res);
		}
	});
}

function oldremove(image_id)
{
	$("#oldremove"+image_id).parent().remove();
	var image_id=image_id;
   	var item_id     =   "<?php echo $itemid; ?>";
   		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url();?>Admin/delete_image',
			data: {'item_id': item_id, 'image_id':image_id},
			success: function (res) {
				console.log(res);
				if(res.error)
				{
					swal('Oops...','Image not deleted','error');
				}
				else
				{
					swal('Success...','Image Successfully Deleted','success');
				}
			}
	});

}

function show()
{
	$("#getitem span").html("");
}
function make_primary(image_id)
{
	var item_id		=	"<?php echo $itemid;?>";
	var image_id	=	image_id;
	jQuery.ajax({
		type: 'POST',
		url: '<?php echo base_url("Admin/make_primary");?>',
		data: { 'item_id': item_id, 'image_id':image_id},
		success: function (res) {
		
			if(res.error)
			{
				swal('Oops','Something went wrong!','error');
			}
			else
			{
				swal({
					title:"Success!",
					text: "Image priority updated successfully",
					type:'success',
					closeOnConfirm: true,
				},
				function(){
					location.reload();
				});
			}
		}
	});
} 
</script>
<div class="content-w">
	<!--------------------
START - Breadcrumbs
-------------------->
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.html">Home</a>
		</li>
		<li class="breadcrumb-item"><span>Edit product</span>
		</li>
	</ul>
	<!--------------------
END - Breadcrumbs
-------------------->
	<!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
	</div>-->
	<div class="content-i">
		<div class="content-box">
			<div class="row">
				<div class="col-sm-12">
					<div class="element-wrapper">
						<h6 class="element-header">Edit Product</h6>
						<div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
							<form id="getitem" action="" method="post">
								<div id="forminfo">
								</div>
								<div class="row no-margin" id="product-image">
									<div class="col-md-2 no-pl upload-item-img">
										<div class="dropzone no-pad">
											<div class="dz-message no-pad">
												<div class="thumbnail no-margin">
													<label class="btn-bs-file btn btn-primary upload-btn">
														Add image
														<input type="file" name="files[]" id="files" multiple="" />
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<span id="error6"></span>
								<div class="form-buttons-w">
									<button class="btn btn-primary" type="button" id="click"> Submit</button>
								</div>
							</form>
						</div>
						<div id="old-images">
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="display-type"></div>
</div>

</div>