<?php $adminname = logged_admin_record(); ?>
<body>
<div class="loading-overlay" id="fullLoader">
    <div class="loader-data "></div>
</div>
<div class="all-wrapper menu-side with-side-panel">
    <div class="layout-w">

        <!--------------------
START - Mobile Menu
-------------------->
        <div class="menu-mobile menu-activated-on-click color-scheme-dark">
            <div class="mm-logo-buttons-w">
                <a class="mm-logo" href="<?php echo base_url('Admin_dashboard');?>"><img src="<?php echo base_url('admin_assets/img/logo.png');?>"><span>Go2grow</span>
                </a>
                <div class="mm-buttons">
                    <div class="content-panel-open">
                        <div class="os-icon os-icon-grid-circles"></div>
                    </div>
                    <div class="mobile-menu-trigger">
                        <div class="os-icon os-icon-hamburger-menu-1"></div>
                    </div>
                </div>
            </div>
            <div class="menu-and-user">
                <div class="logged-user-w">
                    <div class="avatar-w"><img alt="" src="<?php echo base_url('admin_assets/img/avatar1.png');?>">
                    </div>
                    <div class="logged-user-info-w">
                        <div class="logged-user-name"><?php echo $adminname['name'];?></div>
                        <div class="logged-user-role">Administrator</div>
                    </div>
                </div>
                <!--------------------
START - Mobile Menu List
-------------------->
                <ul class="main-menu">
<!--					<li class="has-sub-menu">-->
<!--                        <a href="#">-->
<!--                            <div class="icon-w">-->
<!--                                <div class="os-icon os-icon-pencil-12"></div>-->
<!--                            </div><span>Order</span>-->
<!--							<a href="#" style="background: #c00;color: #fff;text-align: center;display: block;padding: 5px;">  Change Store </a>-->
<!--                        </a>-->
<!--                    </li>-->
				
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url('Admin/allorders');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Order</span>
                        </a>
                    </li>
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url();?>Admin/addproduct">
                            <div class="icon-w">
                                <div class="os-icon os-icon-window-content"></div>
                            </div><span>Add Product</span>
                        </a>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.php">
                            <div class="icon-w">
                                <div class="os-icon os-icon-delivery-box-2"></div>
                            </div><span>Edit Product</span>
                        </a>
                    </li>
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url('Admin/viewproduct');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-hierarchy-structure-2"></div>
                            </div><span>Product Linking</span>
                        </a>
                    </li>
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url('Admin/createdepartment');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-newspaper"></div>
                            </div><span>Create Department</span>
                        </a>
                    </li>
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url('Admin/createcategory');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Create/Edit category</span>
                        </a>
                    </li>
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url('Admin/vieworder');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Sales  analytics</span>
                        </a>
                    </li>
                    
                    <li class="has-sub-menu">
                        <a href="<?php echo base_url('Admin/userDetails');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Users Details</span>
                        </a>
                    </li>

                </ul>
                <!--------------------
END - Mobile Menu List
-------------------->
                <div class="mobile-menu-magic">
                    <h4>Light Admin</h4>
                    <p>cfbxfgcvb</p>
                    <div class="btn-w"><a class="btn btn-white btn-rounded" href="https://themeforest.net/item/light-admin-clean-bootstrap-dashboard-html-template/19760124?ref=Osetin" target="_blank">Purchase Now</a>
                    </div>
                </div>
            </div>
        </div>
        <!--------------------
END - Mobile Menu
-------------------->
        <!--------------------
START - Menu side
-------------------->
        <div class="desktop-menu menu-side-w menu-activated-on-click">
            <div class="logo-w no-pad">
                <a class="logo" href="<?php echo base_url('Admin/index');?>"><img src="<?php echo base_url('admin_assets/img/logo.png');?>" style="width:200px;">
                </a>
            </div>
            <div class="menu-and-user">
                <div class="logged-user-w">
                    <div class="logged-user-i">
                        <div class="avatar-w"><img alt="" src="<?php echo base_url('admin_assets/img/avatar1.png');?>">
                        </div>
                        <div class="logged-user-info-w">
                            <div class="logged-user-name"><?php echo $adminname['name'];?></div>
                            <div class="logged-user-role">Administrator</div>
                        </div>
                        <div class="logged-user-menu">
                            <div class="logged-user-avatar-info">
                                <div class="avatar-w"><img alt="" src="<?php echo base_url('admin_assets/img/avatar1.png');?>">
                                </div>
                                <div class="logged-user-info-w">
                                    <div class="logged-user-name"><?php echo $adminname['name'];?></div>
                                    <div class="logged-user-role">Administrator</div>
                                </div>
                            </div>
                            <div class="bg-icon"><i class="os-icon os-icon-wallet-loaded"></i>
                            </div>
                            <ul>
                                <li class="text-center"><a href="<?php echo base_url('Go2gro_adminlogout');?>"><i class="os-icon os-icon-signs-11"></i><span>Logout</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="main-menu">

                    <li class="menu">
                        <a href="<?php echo base_url('Admin/allorders');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-wallet-loaded"></div>
                            </div><span>Order </span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin_dashboard');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-bar-chart-stats-up"></div>
                            </div><span>Sales analytics</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/report');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-hierarchy-structure-2"></div>
                            </div><span>Report</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/userDetails');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-user-male-circle"></div>
                            </div><span>Users Detail</span>
                        </a>
                    </li>
                    <!--<li class="menu">
                        <a href="<?php /*echo base_url('Admin/useranalytics');*/?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-robot-2"></div>
                            </div><span>User  analytics</span>
                        </a>
                    </li>-->
                    <li class="menu">
                        <a onclick ="select_store('<?php echo base_url('Admin/viewproduct');?>')" href="#">
                            <div class="icon-w">
                                <div class="os-icon os-icon-delivery-box-2"></div>
                            </div><span>Product Linking</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a onclick ="select_store('<?php echo base_url('Admin/activeproduct');?>')" href="#">
                            <div class="icon-w">
                                <div class="os-icon os-icon-delivery-box-2"></div>
                            </div><span>Best Seller</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a onclick ="select_store('<?php echo base_url('Admin/addproduct');?>')" href="#">
                            <div class="icon-w">
                                <div class="os-icon os-icon-window-content"></div>
                            </div><span>Add Product</span>

                    </li>
                    <li class="menu">
                        <a onclick ="select_store('<?php echo base_url('Admin/viewproduct');?>')" href="#">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Edit Product</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a onclick ="select_store('<?php echo base_url('Admin/createdepartment');?>')" href="#">
                            <div class="icon-w">
                                <div class="os-icon os-icon-newspaper"></div>
                            </div><span>Create Department</span>
                        </a>

                    </li>
                    <li class="menu">
                        <a onclick ="select_store('<?php echo base_url('Admin/createcategory');?>')" href="#">
                            <div class="icon-w">
                                <div class="os-icon os-icon-hierarchy-structure-2"></div>
                            </div><span>Create/Edit category</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/refund');?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Refund Payment</span>
                        </a>
                        </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/backup'); ?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Backup Download</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/pickerDeliveryUser'); ?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Picker Users</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/pickerOrders'); ?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Picker Orders</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/viewstores'); ?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Stores</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/notifications'); ?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-pencil-12"></div>
                            </div><span>Notifications</span>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="<?php echo base_url('Admin/viewpromocodes'); ?>">
                            <div class="icon-w">
                                <div class="os-icon os-icon-newspaper"></div>
                            </div><span>Promo Codes</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>