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
            h2 {
              font-size:12px;
              font-weight:bold;
              text-align:center;
              color:#467f05; margin:0;
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
            table:first-child {
              margin-top:10px;
            }
            table p {
              margin:0;
              padding:5px 0;
            }
            table caption {
              font-size: 12px;
            }
            strong {
              font-size:12px;
            }
            p {
              font-size:12px;
            }
            table tr.heading {
              background-color: #c3dc99;
              font-size:12px;
            }
            table th {
              font-size: 12px;
              text-transform: uppercase;
            }
            .container {
              width:600px;
              margin:0 auto;
            }
            .logo {
              text-align:center;
              margin-top:10px;
            }
            .logo img {
              width:30%;
            }
            b {
              margin-right:5px;
            }
            .invoice-table {
              margin-bottom:10px;
            }
            .invoice-table tr td {
              border: 1px solid #acc385;
              padding: 8px 4.5px;
            }
            .invoice-table tr th {
              border: 1px solid #acc385;
              padding: 8px 4.5px;
            }
            .smal-font {
              font-size:11px;
            }
            .invoice-table span {
              margin-left:3px;
              font-size:12px;
            }
            .foter {
              margin-bottom:10px;
            }
            .foter h2 {
              text-align:left;
            }
            .welcomtxt {
              border-top: 1px solid;
              border-bottom: 1px solid ;
              padding: 5px 0;
              margin: 10px 0 10px;
            }
            .mobl {
              display:none;
            }
            table td {
              font-size: 12px;
            }
            .pad5 {
              padding:5px;
            }
            .border-green {
              border:solid 1px #acc385;
            }
            .pad_border {
              padding:5px;
              border-right:solid 1px #acc385;
            }

            /* CSS Specifically for Mobile Devices */
            @media screen and (max-width: 600px) {
              table {
                border: 0;
                width:99%;
              }
              table caption {
                font-size: 12px;
              }
              .logo img {
                width:70%;
              }
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
              /*
              table tr {
                display: block;
              }
              .invoice-table {
                margin-bottom:20px;
              }
              .invoice-table tr {
                margin-bottom:10px;
              }
              .heading {
                margin-bottom:0 !important;
              }
              table td {
                display: block;
                font-size: 12px;
                text-align: right;
                display:inline-block;
                width:97%;
              }
              */
              table tr:first-child th {
               border:none;
              }
              table td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
              }
              .invoice-table tr td strong {
                float:left;
              }
              .invoice-table tr td label {
                float:right;
              }
              .resdis tr:first-child {
                display:none;
              }
              .foter {
                text-align:left;
                padding:0 5px;
              }
              .contentEditable {
                text-align:left;
                padding:0 5px;
              }
              .ondesk {
                display:none;
              }
              .mobl {
                float:left;
                display:block;
                width: 30%;
                text-align: left;
              }
              .mobr {
                float:right;
                width: 65%;
                text-align: right;
              }
            }
            /* CSS Specifically for Mobile Devices Ends */
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
									<img src="<?php Serverurl ?>mail_images/logo.png"   />
								</div>
						    </td>
					    </tr>
				    </table>
                    <div class="contentEditableContainer contentTextEditable">
					    <div class="contentEditable" >
						    <h2><p class="welcomtxt ">Your Go2Gro order <?php echo $orderid;?> has been placed !</p></h2>
                            <p> <strong> Subject: Your Go2Gro order <?php echo $orderid;?> has been placed !</strong></p>
                            <p>Date:<strong><?php echo $datetime; ?></strong></p><br>
                            <p>Dear <?php echo $fullname;?>,</p>
                            <p>Your transaction id is <strong> #<?php echo $txnid;?></strong></p>
                            <p>We are here to save your run to the grocery store, ensuring fresh groceries in your kitchen! Our dedicated team prepares your order as soon as you hit checkout, and bring it to your doorstep with a smile on our face.</p>
                            <p>Your order will be with you shortly. We will contact you, if there are any changes in your order.</p>
                            <p><strong>Thank you for using Go2Gro!</strong></p>
                            <p><strong>Order Details :</strong></p>';
                            <?php
                                if ($tip_amount != 0) { ?>
                                <p><strong>Your Tip Amount : $ <?php echo $tip_amount;?></strong></p> 
                            <?php    } ?>
                        </div>
                        <div class="tableresponsive">
                            <table class="invoice-table fontfamily tab1" width="100%" >
                                <tr class="heading"> <th colspan="4" >Shipping information</th> </tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Name : </strong></td><td  class="pad5" colspan="3"><?php echo $fullname;?></td> </tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Contact No. :</strong></td> <td class="pad5" colspan="3"><?php echo $bi_contact;?></span></td></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Email :</td> <td colspan="3" class="pad5"><?php echo $bi_email;?></span></td></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Address :</strong> </td> <td colspan="3" class="pad5"><?php echo $bi_address;?></td></tr>
                            </table>
                            <table class="invoice-table fontfamily tab1" width="100%" >
                                <tr class="heading"><th colspan="4" >Order Details</th></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Order Id: </strong></td><td  class="pad5" colspan="3">#<?php echo $orderid;?></td> </tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Storename: </strong></td><td  class="pad5" colspan="3"><?php echo $storename;?></td> </tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Sub Total:</strong></td> <td class="pad5" colspan="3">$<?php echo number_format((float)$subtotal, 2, '.', '');?></span></td></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Sales Tax:</td> <td colspan="3" class="pad5">$<?php echo number_format((float)$totaltax, 2, '.', '');?></span></td></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Processing Fee:</strong> </td> <td colspan="3" class="pad5">$<?php echo number_format((float)$processingfees, 2, '.', '');?></td></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Delivery Charge:</td> <td colspan="3" class="pad5">$<?php echo number_format((float)$deliverycharge, 2, '.', ''); ?></span></td></tr>
                                <tr class="border-green"> <td class="pad_border" bgcolor="#e9f9ce"> <strong>Grand Total:</strong> </td> <td colspan="3" class="pad5">$<?php echo number_format((float)$finalprice, 2, '.', '') ;?></td></tr>
                            </table>
                            <table class="invoice-table fontfamily tab1" width="100%" >
                                <tr bgcolor="#e9f9ce">
                                    <th width="50%"><strong>Product</strong></th>
                                    <th><strong>Price</strong></th>
                                    <th><strong>Qty</strong></th>
                                    <th><strong>Total</strong></th>
                                </tr>
                                <?php $result = $this->Model->getcartitemformail($orderid,$storeid);
                                    if (count($result) > 0) {
                                       foreach ($result as $task) {
                                            $total = $task['price'] * $task['item_quty'];
                                            $totalround = number_format((float)$total, 2, '.', '');
                                            ?>                    '
                                        <tr>
                                            <td><span><?php echo $task['item_name']; ?></span></td>
                                            <td><span>$<?php echo $task['price']; ?></span></td>
                                            <td><span><?php echo $task['item_quty']; ?></span></td>
                                            <td><span>$<?php echo $totalround; ?></span></td>
                                        </tr>
                                <?php   }
                                } ?>
                            </table>
                        </div>
                        <div class="foter">
                            <p class="smal-font"><strong>* Your order will be delivered on <?php echo $develydate ." ". $slottime;?></strong></p>
                            <h2 class="smal-font"><strong>Fine print</strong></h2>
                            <p class="smal-font ">All sales are final. Although, you may cancel your order within 10 minutes of ordering by contacting  +1 (833)346-2476 / +1 (833)3GO-2GRO. Go2Gro aims to present absolutely correct and accurate product information but cannot ensure the accuracy of any product information presented on the website. For absolute correct information, please refer to the manufacturer of the products. All offers are subject to terms and conditions of Go2Gro. Go2Gro is a subsidiary brand of SNPC Global LLC (5005 Cameron Forest Pkwy, Johns Creek, Georgia, 30022).</p>
                        </div>
                    </div>
    	        </td>
            </tr>
        </table>
    </body>
</html>