<div class="row no-margin order-cont">

    <div class="col-md-3 no-pl text-center" onclick="neworders()">
        <a href="javascript:void(0)" >
            <div class="order-inner-con"><p> New Order<span class="count" id="neworder">-</span>
                </p></div>
        </a>
    </div>

    <div class="col-md-3 no-pl text-center">
        <a href="javascript:void(0)" onclick="deleverorder()">
            <div class="order-inner-con"><p> Delivered Order<span class="count" id="deleverorder">-</span>
                </p></div>
        </a>
    </div>

    <div class="col-md-3 no-pl text-center">
        <a href="javascript:void(0)" onclick="rejectorder()">
            <div class="order-inner-con floatR"><p> Rejected Order<span class="count" id="rejectorder">-</span>
                </p></div>
        </a>
    </div>
    <div class="col-md-3 no-pl text-center">
        <a href="javascript:void(0)" onclick="cancelorder()">
            <div class="order-inner-con floatR"><p> Cancelled Order<span class="count" id="cancelorder">-</span>
                </p></div>
        </a>
    </div>
</div>

<script>

    $(document).ready(function(){
        //setInterval(function () {allordercount() }, 3000);
        allordercount();
    });

    function allordercount() {
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url('allordercount');?>',
            data: {},
            dataType:"json",
            success: function (res) {
                $("#neworder").html(res.newcount);
                $("#cancelOrder").html(res.canclecount);
                $("#deleverorder").html(res.delivercount);
                $("#rejectorder").html(res.rejectcount);
            }
        });
    }
</script>