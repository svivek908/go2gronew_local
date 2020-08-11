<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Order Confirmation</title>
    <link href="https://fonts.googleapis.com/css?family=Saira+Condensed" rel="stylesheet">
	<style type="text/css">
body {
  font-family: "Open Sans", sans-serif;
  background-color:#FBFCE4;
  font-size:13px;
  padding:0;
  margin:0;
}
h2{font-size:12px; font-weight:bold; text-align:center; color:#467f05; margin:0;
}
table {

  border-collapse: collapse;

  padding: 0;
  width: 100%;
  table-layout: fixed;
  width:700px;
  margin:0 auto;
  text-align:left;
}
table:first-child{ margin-top:10px;}
table p{margin:0; padding:5px 0;}
table caption {
  font-size: 12px;
}
strong{font-size:12px;}
p{font-size:12px;}
table tr.heading{
  background-color: #c3dc99;
  font-size:12px;
  }
table th {
  font-size: 12px;

  text-transform: uppercase;
}

.container{width:600px; margin:0 auto;}
 .logo {text-align:center; margin-top:10px;}
  .logo img{width:30%;}

b{margin-right:5px;}
.invoice-table{ margin-bottom:10px;}
.invoice-table tr td{ border: 1px solid #acc385; padding: 8px 4.5px;}


.invoice-table tr th{ border: 1px solid #acc385; padding: 8px 4.5px;}
.smal-font{ font-size:11px;}
.invoice-table span{margin-left:3px; font-size:12px;}
.foter{margin-bottom:10px;}
.foter h2{text-align:left;}
.welcomtxt{border-top: 1px solid;
border-bottom: 1px solid ;
padding: 5px 0;
margin: 10px 0 10px;}
 .mobl{display:none; }
@media screen and (max-width: 600px) {
  table {
    border: 0;
	width:99%;

  }
  table caption {
    font-size: 12px;
  }
  .logo img{width:70%;}
  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }
  table tr {

    display: block;

  }
  .invoice-table{ margin-bottom:20px;}

.invoice-table tr{margin-bottom:10px;}
.heading{margin-bottom:0 !important;}

  table td {

    display: block;
    font-size: 12px;
    text-align: right;
	display:inline-block;
	width:97%;
  }
 table tr:first-child th{border:none;}
  table td:before {  content: attr(data-label); float: left;  font-weight: bold; text-transform: uppercase; }
.invoice-table tr td strong{float:left;}
.invoice-table tr td label{float:right;}
  .resdis tr:first-child{display:none;}
  .foter{text-align:left; padding:0 5px;}
  .contentEditable{text-align:left; padding:0 5px;}
  .ondesk{display:none;}
 .mobl{float:left;  display:block; width: 30%; text-align: left;}
 .mobr{float:right; width: 65%; text-align: right;}
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
													<img src=' . Serverurl . 'mail_images/logo.png   />
												</div>

										</td>

									</tr>

								</table>
                                <div class="contentEditableContainer contentTextEditable">
												<div class="contentEditable" >


													<h2><p class="welcomtxt ">Your Go2Gro Order ('.$orderid.') requires attention!</p></h2>
                                                    <p> <strong> Subject: Your Go2Gro Order ('.$orderid.') requires attention! </strong></p>
													 <p>Date:<strong>' . $datetime . '</strong></p><br>
                                                    <p>' . $msglink . '</p>
                                                    <p><strong>Thank you for using Go2Gro!</strong></p>

                                                    <div class="foter">


                                                    <p class="smal-font ">All sales are final. Although, you may cancel your order within 10 minutes of ordering by contacting  +1 (833)346-2476 / +1 (833)3GO-2GRO. Go2Gro aims to present absolutely correct and accurate product information but cannot ensure the accuracy of any product information presented on the website. For absolute correct information, please refer to the manufacturer of the products. All offers are subject to terms and conditions of Go2Gro. Go2Gro is a subsidiary brand of SNPC Global LLC (5005 Cameron Forest Pkwy, Johns Creek, Georgia, 30022).</p>
    </div>
			</div></div>




<!-- END BODY -->
			</td>
		</tr>
	</table>
	<!-- End of wrapper table -->
</body>
</html>