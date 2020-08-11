				<div class="content-w">
				    <ul class="breadcrumb">
				        <li class="breadcrumb-item"><a href="<?php echo base_url();?>Admin">Home</a>
				        </li>
				        <li class="breadcrumb-item"><a href="index.html">View order</a>
				        </li>
				    </ul>
				    <!-------------------- END - Breadcrumbs -------------------->
				    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
				    </div>-->
				    <div class="content-i">
				        <div class="content-box">
							
							<div class="row">
				                <div class="col-sm-12">
				                    <div class="element-wrapper">
				                        <h6 class="element-header">Sales Analytics</h6>
				                        <div class="element-box" style="padding-top:2%;">
										
										
										<!-- 	<div class="row"> 
												<div class="col-md-6 element-actions">
													<form class="form-inline">
														<label> Show enteries</label>
														<select class="form-control form-control-sm" style="margin-left:15px;">
															<option value="Pending">10</option>
														</select>
													</form>
												</div>
												
												<div class="col-md-6 element-actions">
													<form class="form-inline justify-content-sm-end">
														<div class="input-search-w"><input class="form-control rounded light" placeholder="Search..." type="search"></div>
													</form>
												</div>
											</div> -->
											
				                         <!--progress bar start-->
										 <div class="row"> 
											<div class="col-md-9">
													<div class="element-wrapper">
														<h6 class="element-header">Sales Analytics Graph</h6>
														<div class="element-box">
															<div class="os-tabs-w">
																<div class="os-tabs-controls">
																	<ul class="nav nav-tabs smaller">
																		<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#deliverOrder" aria-expanded="true" onclick="salesAnalyticGraph(4)">Delivered Order</a>
																		</li>
																		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#processOrder" aria-expanded="true" onclick="salesAnalyticGraph(0)">Process Order</a>
																		</li>
																		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#rejectedOrder" aria-expanded="true" onclick="salesAnalyticGraph(5)">Rejected Order</a>
																		</li>
																		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cancelOrder" aria-expanded="true" onclick="salesAnalyticGraph(6)">Cancel Order</a>
																		</li>
																	</ul>
																	<ul class="nav nav-pills smaller">
																		<li class="nav-item" style="margin-right:15px;"> To -From</li>
																		<li class="nav-item"><a  href="#" >
																		<div class="form-group no-margin">
																				<!--<label for="">Date Range Picker</label>-->
																				<input id="reportrange" class="multi-daterange form-control" type="text" placeholder="Form - To" style="padding:3px;">
																			</div>	
																		</a>
																		</li>
																		<li class="nav-item" style="margin-left:20px;"><a class="nav-link active" data-toggle="tab" href="javascript:void(0);" onclick="getSaleStatistics()" aria-expanded="true">Submit</a>
																		</li>
																	</ul>
																</div>
																<div class="tab-content">
																	<div class="tab-pane graphClear" id="processOrder" aria-expanded="true">

																	</div>
																	
																	
																	
																	<div class="tab-pane active graphClear" id="deliverOrder" aria-expanded="true">

																	</div>
																	<div class="tab-pane graphClear" id="rejectedOrder"  aria-expanded="false">
																		
																	</div>
																	<div class="tab-pane graphClear" id="cancelOrder"  aria-expanded="false">

																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											<div class="col-md-3 no-pad"> 
													<div class="element-wrapper">
													<h6 class="element-header">Todays Sale Statistics</h6>
													<div class="element-box" >
														<div class="el-chart-w" id="donut">
														</div>
														<div class="el-legend">
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #5797fc;"></div>
																<div class="legend-value">Pending</div>
															</div>
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #7e6fff;"></div>
																<div class="legend-value">Prepared</div>
															</div>
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #FF00FF;"></div>
																<div class="legend-value">Packed</div>
															</div>
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #ffcc29;"></div>
																<div class="legend-value">Out for delivery</div>
															</div>
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #4ecc48;"></div>
																<div class="legend-value">Delivered</div>
															</div>
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #808080;"></div>
																<div class="legend-value">Reject</div>
															</div>
															<div class="legend-value-w">
																<div class="legend-pin" style="background-color: #f37070;"></div>
																<div class="legend-value">Cancel</div>
															</div>
														</div>
													</div>
												</div>
												</div>
										 </div>
										 <!--progress bar close-->
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
</div>

<script>
	var currentStatus=4;  //delivered order
	jQuery(document).ready(function(){
		var currentDate =  moment().format('MM/DD/YYYY');
		var startDate = moment().subtract('days', 180).format('MM/DD/YYYY');
		$('input.multi-daterange').daterangepicker({ "startDate": startDate, "endDate": currentDate });
		todaySales();
		salesAnalyticGraph(currentStatus);

		function todaySales(){
			var start = moment().startOf('day').unix();
			var end = moment().endOf('day').unix();
			//console.log(start,end);
			jQuery.ajax({
				type: 'GET',
				url: '<?php echo base_url('Admin_control/salesanalyticDatailDonut');?>',
				data: {"fromTime":start, "toTime":end},
				dataType:"json",
				success:function(res){
					var donutHtml = '';
					var donutDetail = res.detail;
					var donutObj={};
					var OrdersTotal = 0;
					for(var i=0; i<donutDetail.length; i++){
						var result = donutDetail[i];
						OrdersTotal = OrdersTotal+result.total;
						if(result.status == 0){
							donutObj.pending = result.total;
						}else if(result.status ==1){
							donutObj.prepare = result.total;
						}else if(result.status ==2){
							donutObj.packed = result.total;
						}else if(result.status ==3){
							donutObj.outfordelivery = result.total;
						}else if(result.status ==4){
							donutObj.delivered = result.total;
						}else if(result.status ==5){
							donutObj.reject = result.total;
						}else if(result.status ==6){
							donutObj.cancel = result.total;
						}
					}
					donutHtml +='<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
						<canvas height="100" id="donutChart" width="184" style="display: block; width: 100px; height: 100px;"></canvas>\
						<div class="inside-donut-chart-label"><strong>$'+OrdersTotal.toFixed(2)+'</strong><span>Total Sales</span>\
					</div>';

					jQuery("#donut").html(donutHtml);
					var pending,prepare,packed,outfordelivery,delivered,reject,cancel;
					if(typeof donutObj.pending === "undefined"){
						pending = 0;
					}else{
						pending = donutObj.pending.toFixed(2);
					}
					if(typeof donutObj.prepare === "undefined"){
						var prepare = 0;
					}else{
						prepare = donutObj.prepare.toFixed(2)
					}
					if(typeof donutObj.packed === "undefined"){
						packed = 0;
					}else{
						packed = donutObj.packed.toFixed(2);
					}
					if(typeof donutObj.outfordelivery === "undefined"){
						outfordelivery = 0;
					}else{
						outfordelivery = donutObj.outfordelivery.toFixed(2);
					}
					if(typeof donutObj.delivered === "undefined"){
						delivered = 0;
					}else {
						delivered = donutObj.delivered.toFixed(2);
					}

					if(typeof donutObj.reject === "undefined"){
						reject = 0;
					}else{
						reject = donutObj.reject.toFixed(2);
					}
					if(typeof donutObj.cancel === "undefined"){
						cancel = 0;
					}else {
						cancel = donutObj.cancel.toFixed(2);
					}

					if ($("#donutChart").length) {
						var donutChart = $("#donutChart");

						// donut chart data
						var data = {
							labels: ["Pending", "Prepare", "Packed", "Out delivery", "Deliverd", "Reject", "Cancel"],
							datasets: [{
								data: [pending, prepare, packed, outfordelivery, delivered, reject, cancel],
								backgroundColor: ["#5797fc", "#7e6fff", "#FF00FF", "#ffcc29", "#4ecc48", "#808080", "#f37070"],
								hoverBackgroundColor: ["#5797fc", "#7e6fff", "#FF00FF", "#ffcc29", "#4ecc48", "#808080", "#f37070"],
								borderWidth: 0
							}]
						};

						// -----------------
						// init donut chart
						// -----------------
						new Chart(donutChart, {
							type: 'doughnut',
							data: data,
							options: {
								legend: {
									display: false
								},
								animation: {
									animateScale: true
								},
								cutoutPercentage: 80
							}
						});
					}
				}
			});
		}



	});

	function salesAnalyticGraph(status){
		$('.graphClear').html('');
		currentStatus =status;
		var startDate = $('#reportrange').data('daterangepicker').startDate._d;
		var endDate = $('#reportrange').data('daterangepicker').endDate._d;
		var start = moment(startDate).startOf('day').unix();
		var end = moment(endDate).endOf('day').unix();
		console.log(start,end);
		jQuery.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>Admin/saleDatailByOrderStatus',
			data: {"fromTime":start, "toTime":end, status:status},
			dataType:"json",
			success:function(res){
				//console.log(res.detail);
				var monthNameArray = [];
				var monttTotalArray=[];
				var monthOrderCount =[];
				var totalSale=0;
				var totalOrder =0;
				if(!res.error){

					var monthOrderDetail = res.detail;
					for(var i=0; i<monthOrderDetail.length; i++){
						var result= monthOrderDetail[i];
						monthNameArray.push(result.month_name+'('+result.year_name+')');
						monttTotalArray.push(parseFloat(result.total).toFixed(2));
						monthOrderCount.push(result.order_count);
						totalSale=totalSale+result.total;
						totalOrder=totalOrder+result.order_count;
					}
					var html='';
					if(status == 0){
						html += '<div class="el-tablo" >\
							<div class="label">Total sale</div>\
						<div class="value" id="Total">$'+totalSale.toFixed(2)+'</div>\
							</div>\
							<div class="el-chart-w" style="margin-bottom: 50px;">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChart_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>\
							<div class="el-tablo">\
							<div class="label">Total Order Count</div>\
						<div class="value" id="Total">'+totalOrder+'</div>\
							</div>\
							<div class="el-chart-w">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChartcount_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>';
						$("#processOrder").html(html);
					}else if(status==4){
						html += '<div class="el-tablo" >\
							<div class="label">Total sale</div>\
						<div class="value" id="Total">$'+parseFloat(totalSale).toFixed(2)+'</div>\
							</div>\
							<div class="el-chart-w" style="margin-bottom: 50px;">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChart_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>\
							<div class="el-tablo">\
							<div class="label">Total Order Count</div>\
						<div class="value" id="Total">'+totalOrder+'</div>\
							</div>\
							<div class="el-chart-w">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChartcount_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>';
						$("#deliverOrder").html(html);
					}else if(status==5){
						html += '<div class="el-tablo">\
							<div class="label">Total sale</div>\
						<div class="value" id="Total">$'+totalSale.toFixed(2)+'</div>\
							</div>\
							<div class="el-chart-w" style="margin-bottom: 50px;">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChart_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>\
							<div class="el-tablo">\
							<div class="label">Total Order Count</div>\
						<div class="value" id="Total">'+totalOrder+'</div>\
							</div>\
							<div class="el-chart-w">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChartcount_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>';
						$("#rejectedOrder").html(html);
					}else if(status==6){
						html += '<div class="el-tablo">\
							<div class="label">Total sale</div>\
						<div class="value" id="Total">$'+totalSale.toFixed(2)+'</div>\
							</div>\
							<div class="el-chart-w" style="margin-bottom: 50px;">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChart_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>\
							<div class="el-tablo">\
							<div class="label">Total Order Count</div>\
						<div class="value" id="Total">'+totalOrder+'</div>\
							</div>\
							<div class="el-chart-w">\
							<iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>\
							<canvas height="185" id="barChartcount_'+status+'" width="740" style="display: block; width: 740px; height: 185px;"></canvas>\
							</div>';
						$("#cancelOrder").html(html);
					}

				}else{
					if(status == 0){
						$("#processOrder").html('No Record Found');
					}else if(status==4){
						$("#deliverOrder").html('No Record Found');
					}else if(status==5){
						$("#rejectedOrder").html('No Record Found');
					}else if(status==6){
						$("#cancelOrder").html('No Record Found');
					}
				}

				if ($("#barChart_"+status).length) {
					var barChart1 = $("#barChart_"+status);
					var barData1 = {
						labels: monthNameArray,
						datasets: [{
							label: "Total($)",
							backgroundColor: ["#5797FC", "#629FFF", "#6BA4FE", "#74AAFF", "#7AAEFF", '#85B4FF', "#7AAEFF", '#85B4FF'],
							borderColor: ['rgba(255,99,132,0)', 'rgba(54, 162, 235, 0)', 'rgba(255, 206, 86, 0)', 'rgba(75, 192, 192, 0)', 'rgba(153, 102, 255, 0)', 'rgba(255, 159, 64, 0)'],
							borderWidth: 1,
							data: monttTotalArray
						}]
					};


					// -----------------
					// init bar chart
					// -----------------
					new Chart(barChart1, {
						type: 'bar',
						data: barData1,
						options: {
							scales: {
								xAxes: [{
									display: true,
									ticks: {
										fontSize: '15',
										fontColor: '#969da5'
									},
									gridLines: {
										color: 'rgba(0,0,0,0.05)',
										zeroLineColor: 'rgba(0,0,0,0.05)'
									}
								}],
								yAxes: [{
									ticks: {
										beginAtZero: true
									},
									gridLines: {
										color: 'rgba(0,0,0,0.05)',
										zeroLineColor: '#6896f9'
									}
								}]
							},
							legend: {
								display: false
							},
							animation: {
								animateScale: true
							}
						}
					});
				}

				if ($("#barChartcount_"+status).length) {
					var barChart2 = $("#barChartcount_"+status);
					var barData2 = {
						labels: monthNameArray,
						datasets: [{
							label: "Order Count",
							backgroundColor: ["#5797FC", "#629FFF", "#6BA4FE", "#74AAFF", "#7AAEFF", '#85B4FF', "#7AAEFF", '#85B4FF'],
							borderColor: ['rgba(255,99,132,0)', 'rgba(54, 162, 235, 0)', 'rgba(255, 206, 86, 0)', 'rgba(75, 192, 192, 0)', 'rgba(153, 102, 255, 0)', 'rgba(255, 159, 64, 0)'],
							borderWidth: 1,
							data: monthOrderCount
						}]
					};


					// -----------------
					// init bar chart
					// -----------------
					new Chart(barChart2, {
						type: 'bar',
						data: barData2,
						options: {
							scales: {
								xAxes: [{
									display: true,
									ticks: {
										fontSize: '15',
										fontColor: '#969da5'
									},
									gridLines: {
										color: 'rgba(0,0,0,0.05)',
										zeroLineColor: 'rgba(0,0,0,0.05)'
									}
								}],
								yAxes: [{
									ticks: {
										beginAtZero: true
									},
									gridLines: {
										color: 'rgba(0,0,0,0.05)',
										zeroLineColor: '#6896f9'
									}
								}]
							},
							legend: {
								display: false
							},
							animation: {
								animateScale: true
							}
						}
					});
				}
			}
		});
	}

	function getSaleStatistics(){
		salesAnalyticGraph(currentStatus);
	}
</script>
