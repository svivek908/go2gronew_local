<?php include 'header.php'; ?>
<div class="slideshow-container">

	<div class="mySlides fade1">
		<div class="numbertext">1 / 3</div>
		<img src="images/slide-img2.png" style="width:100%">
	</div>

	<div class="mySlides fade1">
		<div class="numbertext">2 / 3</div>
		<img src="images/slide-img1.png" style="width:100%">
	</div>

	<div class="mySlides fade1">
		<div class="numbertext">3 / 3</div>
		<img src="images/slide-img1.png" style="width:100%">
	</div>

	<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
	<a class="next" onclick="plusSlides(1)">&#10095;</a>
	<br>

	<div style="text-align:center">
		<span class="dot" onclick="currentSlide(1)"></span>
		<span class="dot" onclick="currentSlide(2)"></span>
		<span class="dot" onclick="currentSlide(3)"></span>
	</div>

</div>
<!--breadcrumbs-->

<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated">
	<!-- For version 1, 2, 3, 8 -->
	<!-- For version 1, 2, 3 -->
	<div class="container">
		<div class="row">
			<div class="col-main col-sm-9 product-grid">
				<div class="pro-coloumn">
					<div class="block">
						<div class="block-title"> Department </div>
					</div>
					<article class="col-main">
						<div class="category-products" id="cat_products">
							<ul class="products-grid" id="cat_product_grid">
							</ul>
						</div>
					</article>
				</div>
				<!--	///*///======    End article  ========= //*/// -->
			</div>
			<?php include('right_sidebar.php');?>
			<!--col-right sidebar-->
		</div>
		<!--row-->
	</div>
	<!--container-->
</section>
<!--main-container col2-left-layout-->
<section class=" wow bounceInUp animated">
	<div class="best-pro slider-items-products container">
		<div class="new_title">
			<h2>Best Seller</h2>
		</div>
		<div id="best-seller" class="product-flexslider hidden-buttons" >
		</div>
	</div>
</section>

<?php include 'footer.php' ?>

<!-- For version 1,2,3,4,6 -->
<script type="text/javascript">
	jQuery('#abc').modal({
		backdrop: 'static',
		keyboard: false
	});
	<?php if ($isLogin==true) { ?>
	jQuery("#abc").modal('hide');
	<?php }else {?>
	jQuery('.zipcode2').hide();
	jQuery("#abc").modal('show');
	<?php }?>

	jQuery(document).ready(function(){
		<?php if ($isLogin==true) { ?>
		<?php  if($this->session->userdata('pincode')) {?>
		pincodevalidate('<?php echo $pin_code;?>');
		function pincodevalidate(id){
			jQuery.ajax({
				type: 'GET',
				url: '<?php echo base_url("index.php/main/checkPincode"); ?>',
				data: {'id': id},
				success: function (res) {
					console.log(res);
					if (res.error == true) {
						jQuery('#abc1').modal({
							backdrop: 'static',
							keyboard: false
						});
						jQuery("#abc1").modal('show');
						jQuery("#pincode_value1").val(id);
						jQuery('.zipcode21').hide();
						jQuery('.firststep1').click(function () {
							var id = jQuery('#pincode_value1').val();
							jQuery.ajax({
								type: 'GET',
								url: '<?php echo base_url("index.php/main/checkPincode"); ?>',
								data: {'id': id},
								success: function (res) {
									console.log(res);
									if (res.error == true) {
										// sweetAlert("Oops...", res.message, "error");
										jQuery('.zipcode21').show();
										jQuery('.zipcode11').hide();
									}
									else {

//                            swal(res.message)
										//swal("Good job!", res.message, "success");
										jQuery("#abc1").modal('hide');




//                            window.location.href = '<?php //echo base_url();?>//index.php/main/login';
									}
								}
							});
							return false;
						});

					}
					else {
						jQuery("#abc1").modal('hide');

					}

				}

			});
		}

		<?php } else {?>

		<?php }} else{ ?>

		<!--        --><?php // if($this->session->userdata('pincode')) {?>
//        jQuery("#abc").modal('hide');
//        <?php //} else{?>
//        jQuery("#abc").modal('show');
//        <?php //}?>
		jQuery('.zipcode2').hide();

		jQuery('.firststep').click(function () {

//            jQuery('.zipcode2').show();

			var id = jQuery('#pincode_value').val();

			if(id=='')
			{
				swal("please enter your pincode");

			}else {
				jQuery.ajax({
					type: 'GET',
					url: '<?php echo base_url("index.php/main/checkPincode"); ?>',
					data: {'id': id},
					success: function (res) {
						console.log(res);
						if (res.error == true) {

							// sweetAlert("Oops...", res.message, "error");

							jQuery('.zipcode2').show();
							jQuery('.zipcode').hide();
						}
						else {

//                            swal(res.message)
							//swal("Good job!", res.message, "success");
							jQuery("#abc").modal('hide');




//                            window.location.href = '<?php //echo base_url();?>//index.php/main/login';
						}
					}
				});
			}
			return false;

		});
		<?php } ?>

		getcategory()
		getbestSeller();
	});

</script>
</body>
</html>