<body class="auth-wrapper">
    <div class="all-wrapper menu-side with-pattern">

        <div class="auth-box-w">
            <div class="logo-w" style="padding: 15% 18%;">
                <a href="index.html"><img alt="" src="<?php echo base_url('admin_assets/img/logo.png');?>">
                </a>
            </div>
          <?php if($this->session->flashdata('error'))
          {
            ?>
           <div class="alert alert-danger alert-dismissable">
             <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
             <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>.
                </div>
           <?php
         }
         ?>
            <h4 class="auth-header">Login Form</h4>
            <?php echo form_open('Go2gro_adminlogin'); ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Username</label>
                    <input class="form-control"  id="email_id" name="username" placeholder="Enter your username" type="text" required="">
                    <div class="pre-icon os-icon os-icon-user-male-circle"></div>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input class="form-control" id="pass_word" name="password" placeholder="Enter your password" type="password" required="">
                    <div class="pre-icon os-icon os-icon-fingerprint"></div>
                </div>
                <div class="buttons-w">
                    <button class="btn btn-primary" id="login2">Log me in</button>
                    <div class="form-check-inline">
                        
                    </div>
                </div>

            </form>

            <?php echo form_close(); ?>
        </div>
    </div>
</body>
</html>


<script>
    jQuery(document).ready(function(){

        jQuery('#login2').click(function () {

           var email=jQuery('#email_id').val();
           var password=jQuery('#pass_word').val();
          if(email=='')
           {

               swal("email is required");

           }else if(password==''){

              swal("password is required");
            }
            else{
               jQuery.ajax({
                   type:'POST',
                   url:'<?php echo base_url();?>/index.php/Demo/login',
                   data:{'email':email, 'password':password},
                   success: function (res) {
                       if (res.error == true) {
                           sweetAlert("Oops...", res.message, "error");
                       } else {
 // swal("Good job!", res.message, "success");
                           console.log(res);
                 window.location.href = '<?php echo base_url();?>';

                       }
                   }
               });

            }
            return false;
        });