<table class="table table-lightborder" id="">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Pin Code</th>
            <th>City</th>
            <th>State</th>
            <th>Country</th>
            <th>Date/Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($users) > 0){ 
            foreach ($users as $key => $value) { 
                $shipping_address = "";
                $shipping_info = json_decode($value['address']);

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
                }

                $ts = $value['unitime'];
                $date = new DateTime("@$ts");
        ?>
            <tr>
                <td><?php echo $value['first_name'].' '.$value['first_name'];?></td>
                <td><?php echo $value['email_id'];?></td>
                <td><?php echo $value['mobile'];?></td>
                <td class="text-center tooltip111"><?php echo substr($shipping_address,0, 15);?>...<span class="tooltiptext tooltip111-bottom"><?php echo  $shipping_address; ?></span></td>
                <td><?php echo $value['pincode'];?></td>
                <td><?php echo $value['city'];?></td>
                <td><?php echo $value['state'];?></td>
                <td><?php echo $value['country'];?></td>
                <td><?php echo $date->format('Y-m-d H:i:s');?></td>
            </tr>
        <?php } }?>
    </tbody>
</table>
<?php echo $this->ajax_pagination->create_links();?>