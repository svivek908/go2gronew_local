 <table class="table table-lightborder">
    <thead>
    <tr>
        <th>Order Id</th>
        <th>Store</th>
        <th>Info</th>
        <th>Address</th>
        <th>Pincode</th>
        <th>price</th>
        <th>Date/Time</th>
        <th>Current status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody >
    <?php // echo "<pre>"; print_r($order);
        foreach ($order as $orders) {
            $shipping_address = "";
            $dateString =  gmdate("Y-m-d\TH:i:s\Z", $orders['datetime']);

            if($orders['is_order_edit'] == 2){
                $alternatOrderStatus = 'Edited';
            } else if($orders['is_order_edit'] ==0 && ($orders['status'] != 0 || ($orders['status'] != 1))){
                $alternatOrderStatus = 'Not edited';
            } else{
                $alternatOrderStatus = 'Pending for approval';
            }
            $status = getTextStatus($orders['status']);

            $shipping_info = json_decode($orders['shipping_address']);
            if($shipping_info ){
                $shipping_address = $shipping_info->street_address;
                if($shipping_info->apt_no != ''){
                    $shipping_address .= ', '. $shipping_info->apt_no;
                }
                if($shipping_info->complex_name != ''){
                    $shipping_address .= ', ' . $shipping_info->complex_name;
                }
            }else{
                $shipping_address = $orders['shipping_address'];
            }
    ?>

    <tr id="order_id_<?php echo $orders['order_id'];?>">
         <td class="nowrap"><a href="<?php echo base_url('Admin/checkstatus/'.$orders['order_id']);?>">
             <?php echo $orders['order_id'];?>
         </a></td>
        <td class="text-center" ><?php echo $orders['store_name'];?></td>

        <td class="word-wrap"><?php echo $orders['name'];?><p class="no-margin"><?php echo $orders['email_id'];?></p><?php echo $orders['ship_mobile'];?></td>

        <td class="text-center tooltip111"><?php echo substr($shipping_address,0, 15);?>...<span class="tooltiptext tooltip111-bottom">><?php echo  $shipping_address; ?></span></td>

        <td class="text-center" ><?php echo $orders['ship_pincode'];?></td>

        <td class="text-center">$<?php echo number_format((float)$orders['finalprice'], 2, '.', '');?></td>

        <td class="text-center"><span style="display: none;"><?php echo $datetime; ?></span> <?php echo $dateString;?></td>

        <td class="text-center"><span id="cstatus_<?php echo $orders['order_id'];?>"><?php echo $status;?></span>
        
        <?php if($orders['picked_by']){ ?>
            <select <?php if($orders['is_order_edit'] != '1'){ echo "disabled"; } ?> class="form-control form-control-sm" style="width: 102px;height: 28px;" id="selectstatus_<?php echo $orders['order_id'];?>" onchange="selectstatusmain('<?php echo  $status;?>', this.value, '<?php echo $orders['order_id'];?>');">
                <?php
                    $pending_s = $pending_dsbl = '';
                    $prepare = ''; $prepare_dsble = 'disabled';
                    $packed = ''; $packed_dsbl = 'disabled';
                    $out_4_dev=''; $out_4_dsbl ='disabled';
                    $delv='';  $delv_dsbl ='disabled';
                    $reject_s =''; $reject_dsbl ='';

                    if($orders['status'] >= 0){
                        $pending_dsbl = 'disabled';
                    }

                    if($orders['status'] >= 0){
                        $pending_s = 'selected';
                    }

                    if($orders['status'] == 0){
                        $prepare_dsble = '';
                    }

                    if($orders['status'] == 1){
                        $packed_dsbl = '';
                        $prepare = 'selected';
                    }

                    if($orders['status'] == 2){
                        $packed = 'selected';
                        $packed_dsbl ='';
                        $out_4_dsbl ='';
                    }

                    if($orders['status'] == 3){
                        $out_4_dev = 'selected';
                        $delv_dsbl = '';
                    }

                    if($orders['status'] == 4){
                        $delv = 'selected';
                        $reject_dsbl = 'disabled';
                    }

                    if($orders['status'] == 5){
                        $reject_s = 'selected';
                    }

                ?>

                <option value="0" <?php echo $pending_s .' '. $pending_dsbl; ?> selected>PENDING</option>
                <option value="1" <?php echo $prepare .' '. $prepare_dsble; ?> >PREPARE</option>
                <option value="2" <?php echo $packed .' '. $packed_dsbl; ?> >PACKED</option>
                <option value="3" <?php echo $out_4_dev .' '. $out_4_dsbl; ?> >OUT FOR DELIVERY</option>
                <option value="4" <?php echo $reject_s .' '. $reject_dsbl; ?> >REJECT</option>
            </select>
        <?php } ?></td>
        <td style="min-width: 80px;margin-left: 10px;">
            <?php if(!$orders['picked_by']) { 
                    if($orders['is_order_edit'] == 0 && ($orders['status'] == 0 ||  $orders['status'] == 1)){?>
                       <a href="<?php echo base_url('Admin/editOrderView/'.$orders['order_id']); ?>" class="edit-item-btn green-bg"> Edit Order </a>
                       <?php }else{  echo $alternatOrderStatus; }?>
                <?php } else { 
                        echo 'Picked by '.$orders['picked_by'];
                } ?>
        </td>
    </tr>
<?php } ?>
</tbody>
</table>
<?php echo $this->ajax_pagination->create_links();?>

