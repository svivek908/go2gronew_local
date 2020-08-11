<script>
    $(document).ready(function () {
        var dataTable = $('#myTable').DataTable( {
            "processing": true,
            "serverSide": true,
            //"pageLength": 50,
            "columnDefs": [
                { className: "nowrap sorting_1", "targets": [ 0 ] },
                { className: "word-wrap", "targets": [ 3,4 ] },
                { className: "text-right", "targets": [ 5 ] },
                { className: "text-center", "targets": [ 6 ] }
            ],
            "columns": [
                null,
                { "orderable": false },
                null,
                { "orderable": false },
                { "orderable": false },
                { "orderable": false },
                null,
                { "orderable": false }
            ],
            "ajax":{
                url :"<?php echo base_url('Admin/getalliteam');?>", // json datasource
                type: "post",  // method  , by default get
                data: {},
                error: function(){  // error handling
                    $(".myTable-error").html("");
                    $("#myTable").append('<tbody class="myTable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#myTable_processing").css("display","none");

                }

            }
        } );
    });

</script>
<div class="content-w">

    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/Admin">Home</a>
        </li>
        <li class="breadcrumb-item">Products
        </li>
    </ul>
    <!--------------------
END - Breadcrumbs
-------------------->
    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <?php $this->load->view('admin/include/inpage_store_select'); ?>
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">

                    <div class="element-wrapper">
                        <!-- <div class="element-actions">
                                <form class="form-inline justify-content-sm-end">
                                    <select class="form-control form-control-sm rounded">
                                        <option value="Pending">Today</option>
                                        <option value="Active">Last Week </option>
                                        <option value="Cancelled">Last 30 Days</option>
                                    </select>
                                </form>
                            </div> -->
                        <h6 class="element-header">Edit Product</h6>

                        <div class="element-box" style="padding-bottom: 100px; position:relative;">

                           <!-- <div class="loader" id="loader"></div>-->
                            <div class="table-responsive">
                                <table class="table table-lightborder" id="myTable">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Product Image</th>
                                        <th style="width:100px;">Products Name</th>
                                        <th>Short Details</th>
                                        <th>Full Details</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>

                                    </tr>
                                    </thead>
                                    <!--<tbody id="data-list">

                                    </tbody>-->
                                </table>
                            </div>
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
