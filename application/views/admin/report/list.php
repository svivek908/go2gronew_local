<table class="table table-lightborder" id="orderTable">
    <thead>
        <tr>
            <th>Order Id</th>
            <th>Store name</th>
            <th>Info </th>
            <th>Address</th>
            <th>Zip Code</th>
            <th>Sub Total</th>
            <th>Delivery Charge</th>
            <th>Sales Tax</th>
            <th>Processing Fee</th>
            <th>Tip</th>
            <th>Grand Total</th>
            <th>Date/Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($order) > 0){
            foreach ($order as $key => $value) { 
                $shipping_address = "";
                if ($value['refund_status'] == 1) {
                    $refundStatus = 'Refunded';
                } else {
                    $refundStatus = 'Not Refunded';
                }
                $shipping_info = json_decode($value['shipping_address']);

                if($shipping_info ){
                    $shipping_address = $shipping_info->street_address;
                    if($shipping_info->apt_no != ''){
                        $shipping_address .= ', ' . $shipping_info->apt_no;
                    }
                    if($shipping_info->complex_name != ''){
                        $shipping_address .= ', ' . $shipping_info->complex_name;
                    }
                }else{
                    $shipping_address = $value['shipping_address'];
                }?>
            <tr>
                <td><?php echo $value['order_id'];?></td>
                <td><?php echo $value['store_name'];?></td>
                <td><?php echo $value['name'];?></td>
                <td class="text-center tooltip111"><?php echo substr($shipping_address,0, 15);?>...<span class="tooltiptext tooltip111-bottom"><?php echo  $shipping_address; ?></span></td>
                <td><?php echo $value['ship_pincode'];?></td>
                <td>$<?php echo number_format((float)$value['total_price'], 2, '.', '');?></td>
                <td>$<?php echo number_format((float)$value['dlv_charge'], 2, '.', '');?></td>
                <td>$<?php echo number_format((float)$value['tax'], 2, '.', '');?></td>
                <td>$<?php echo number_format((float)$value['processingfee'], 2, '.', '');?></td>
                <td>$<?php echo number_format((float)$value['tip_amount'], 2, '.', '');?></td>
                <td>$<?php echo number_format((float)$value['finalprice'], 2, '.', '');?></td>
                <td><?php echo $value['datetime'];?></td>
                <td><?php echo $refundStatus;?></td>
            </tr>
        <?php    }
        }?>
    </tbody>
</table>
<?php echo $this->ajax_pagination->create_links();?>