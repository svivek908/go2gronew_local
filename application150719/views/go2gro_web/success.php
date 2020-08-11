<?php
include('header.php');
load_css(array('public/assets/stylesheet/pgstyle/success.css?'));
?>
<div class="container">
	<div class="row">
	    <div class="">
	        <div class="col-sm-12 col-xs-12 col-lg-12">
	            <div class="order-divman">
	               <div class="order-placedblock">
	                  <!--<input type="button"  onclick="orderplace();" value="ok">-->
	                  <p class="order-headertext">Your Order Has Been Placed.</p>
	                  <p class="tq-text">Thank You For Your Purchase.</span></p>
	                  <p class="receive-text">You Will Receive an Order Confirmation  email with details of your order.</p>
	               	</div>
	               	<div class="shop_list">
	                 	<div class="table-responsive">
	                     	<table class="data-table table-striped" id="my-orders-table">
		                        <colgroup>
		                           <col>
		                           <col>
		                           <col>
		                           <col width="1">
		                           <col width="1">
		                           <col width="20%">
		                        </colgroup>
		                        <thead>
		                           <tr class="first last">
		                              <th>Order Id </th>
		                              <th>Date</th>
		                              <th>Address</th>
		                              <th class="success-tablehead">Name</th>
		                              <th class="table-headfix"><span class="nobr">Grand Total</span></th>
		                              <th>Status</th>
		                              <th>Action</th>
		                           </tr>
		                        </thead>
	                       		<tbody class="order_detail">
	                        	</tbody>
	                    	</table>
	                  	</div>
	               	</div>
	           	</div>
	        </div>
	      </div>
	   </div>
	   <!--col-sm-12 col-xs-12 col-lg-8-->
	   <!--col-xs-12 col-lg-4-->
	</div>
<!--row-->
</div>
<?php
include('footer.php');?>
<script type="text/javascript">
	Orderconfirmation();
	function Orderconfirmation()
	{
		<?php if(empty($apikey) || !trim($apikey)) {?>
		var api_key='not defined';
		<?php }else{?>
		var api_key='<?php echo $apikey;?>';
		<?php }?>
		jQuery.ajax({
			type: 'GET',
			url: '<?php echo base_url(); ?>/OrderHistory',
			data: {
				'Authorization': api_key
			},
			success: function (res) {
				console.log(res);
				var html1 = '';

				if (res.error == true) {

				} else {
					var catList = res.order;

					var jsonLength = catList.length;

					var jsonLength1 = 1;
					console.log(jsonLength);
					var html1 = '';


					for (var i = 0; i < jsonLength; i++) {

						var result = catList[i];
						var time=unixtimestamp(result.datetime);
						var timeDiffrence = res.currentunitime-result.datetime;
						var orderid=result.order_id;
						var store_id = result.store_id;
                        var shipping_address = '';
                        try {
                            var shipping_addr_json = JSON.parse(result.shipping_address);
                            shipping_address = shipping_addr_json.street_address + ', ' + shipping_addr_json.apt_no + ', ' + shipping_addr_json.complex_name;
                        } catch (e) {
                            shipping_address = result.shipping_address;
                        }

						html1 += '<tr class="first odd">\
        <td><a href="<?php echo base_url();?>/accountDetail/'+orderid+'/'+store_id+'">'+result.order_id+'</a></td>\
       <td><span class="nobr">'+time+'</span></td>\
       <td class="tooltip111">' + shipping_address.substr(0, 15) + '...<span class="tooltiptext tooltip111-bottom">' + shipping_address +'</span></td>\
    <td>'+result.ship_name+'</td>\
    <td><span class="price">$'+roundoffvalue(result.finalprice)+'</span></td>\
       <td id="status_'+result.order_id+'"><em class="btn-em">'+orderStatus(result.status)+'</em>'+(result.status==6 && result.refund_status == 0? '<em class="red"> (Refund Inprogress)</em>': result.status==6 && result.refund_status == 1 ? '<em class="red"> (Refunded)</em>':'')+'</td>\
    <td class="a-center last"><span class="nobr"><a href="<?php echo base_url();?>/accountDetail/'+orderid+'/'+store_id+'">View Order</a> '+(result.status==0 && timeDiffrence<=300 ?'<a id="cancel_'+result.order_id+'" href="javascript:void(0)" class="block red" onclick=cancelOrder(\''+result.order_id+'\') >Cancel Order</a>':'')+'</span></td>\    </tr>';

					}
				}
				jQuery('.order_detail').append(html1);
			}
		});

	}

	function cancelOrder(orderId){
		var api_key='<?php echo $apikey;?>';
		swal({
				title: "Are you sure?",
				text: "You want to cancel this order",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
				closeOnCancel: true,
				showLoaderOnConfirm:true
			},
			function() {

				jQuery.ajax({
					type: 'post',
					url: '<?php echo base_url(); ?>/cancelorder',
					data: {
						'Authorization': api_key,
						'orderid':orderId
					},
					success: function (res) {
						if(res.error){
							swal('Error!', res.message, 'error');
						}else{
							swal('Success!', res.message, 'success');
							jQuery('#status_'+orderId).text(orderStatus(6));
							jQuery('#status_'+orderId).append('<em class="red"> (Refund Inprogress)</em>');
							jQuery('#cancel_'+orderId).remove();
						}
					}
				});

			});

	}
</script>
