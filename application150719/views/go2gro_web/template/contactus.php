<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Contact Details</title>
    <link href="https://fonts.googleapis.com/css?family=Saira+Condensed" rel="stylesheet">
	<style type="text/css">
body {  font-family: "Open Sans", sans-serif;  background-color:#FBFCE4;  font-size:13px;  padding:0;  margin:0;}
h2{font-size:16px; font-weight:bold; text-align:center; color:#467f05; margin:0;}
table {  border-collapse: collapse;  padding: 0;  width: 100%;  table-layout: fixed;  width:700px;  margin:0 auto;  text-align:left;}
table caption { font-size: 12px;}
table tr.heading{  background-color: #c3dc99;  }
table th { font-size: 12px; text-transform: uppercase;}
.logo {text-align:center; margin-top:10px;}
.logo img{width:30%;}
.invoice-table{ margin-bottom:10px;}
.invoice-table tr td{ border: 1px solid #acc385; padding:8px;}
.invoice-table tr th{ border: 1px solid #acc385; padding:8px;}
.welcomtxt{border-top: 1px solid;border-bottom: 1px solid ;padding: 5px 0;margin: 10px 0 10px;}
@media screen and (max-width: 600px) {
table { border: 0;	width:100%;}
.logo img{width:70%;}
table thead { border: none;  clip: rect(0 0 0 0);  height: 1px;  margin: -1px;  overflow: hidden;  padding: 0;  position: absolute;  width: 1px;}
table tr { display: block;}
.invoice-table{ margin-bottom:20px;}
table td {  display: block; font-size: 12px;  text-align: left;display:inline-block;width:95%; padding-left:10px;  }
table tr:first-child th{border:none;}
}
</style>
</head>
<body>

	<table cellpadding="0" width="100%" cellspacing="0" border="0" >
		<tr>
			<td>
								<table cellpadding="0" cellspacing="0" border="0" align="center" width="600">

									<tr>

										<td width="200" valign="top" align="center">

												<div class="logo" >
													<img src="<?php Serverurl ?>'mail_images/logo.png"  />
												</div>

										</td>

									</tr>

								</table>




												<div class="contentEditable" >
													<h2><p class="welcomtxt "><?php echo $username['username'];?> Want To Contact You</p></h2>
                                                    <p> <strong> This Is Message</strong></p>
                                                    <p><?php echo $username['comment'];?></p>
                                                    </div>


							<div class="tableresponsive">
                                                    <table class="invoice-table fontfamily" width="100%" >
                                                    	<tr class="heading">
                                                        	<th>Contact Details</th>

                                                        </tr>

                                                        	<tr>
                                                            	<td><strong>Name :  <?php echo $username['username'];?></strong></td>

                                                            </tr>
                                                            <tr >
                                                            	<td  class="tab1"><strong>Eamil ID: <?php echo $username['emailid'];?></strong></td>

                                                            </tr>
                                                             <tr >
                                                            	<td class="tab1"><strong>Contact No: <?php echo $username['mobile'];?></strong></td>

                                                            </tr>

                                                    </table>

                                                    </div>



<!-- END BODY -->
			</td>
		</tr>
	</table>
	<!-- End of wrapper table -->
</body>
</html>