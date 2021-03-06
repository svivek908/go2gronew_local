 <?php include 'header.php' ?>
    <div class="page-heading">
  </div>
  <!-- BEGIN Main Container col2-right -->
  <div class="main-container col2-right-layout">
    <div class="main container">
      <div class="row">
        <section class="col-main col-sm-9 wow bounceInUp animated animated" style="visibility: visible;">
          <div id="messages_product_view"></div>
          <form action="#" id="contactForm" method="post">
            <div class="static-contain">
              <fieldset class="group-select">
                <ul>
                  <li id="billing-new-address-form">
                    <fieldset class="">
                      <ul>
                        <li>
                          <div class="customer-name">
                            <div class="input-box name-firstname">
                              <label for="name">Name<em class="required">*</em></label>
                              <br>
                              <input title="Name" name="name" id="name" value="" class="input-text required-entry" type="text">
                            </div>
                            <div class="input-box name-firstname">
                              <label for="email">Email<em class="required">*</em></label>
                              <br>
                              <input title="Email" name="email" id="email" value="" class="input-text required-entry validate-email" type="text">
                            </div>
                          </div>
                        </li>
                        <li>
                          <label for="telephone">Telephone</label>
                          <br>
                          <input title="Telephone" name="phone" id="phone" value="" class="input-text" type="text">
                        </li>
                        <li>
                          <label for="comment">Comment<em class="required">*</em></label>
                          <br>
                          <textarea   title="Comment" name="comment" id="comment" class="required-entry input-text" cols="5" rows="3" style="resize: none;"></textarea>
                        </li>
                      </ul>
                    </fieldset>
                  </li>
<!--                  <p class="require"><em class="required">* </em>Required Fields</p>-->
                  <input type="text" name="hideit" id="hideit" value="" style="display:none !important;">
                  <div class="buttons-set">
                    <button type="button" title="Submit"  onclick="return contactUs();" class="button submit pull-right"><span><span>Submit</span></span></button>
                  </div>
                </ul>
              </fieldset>
            </div>
          </form>
          
        </section>
        <aside class="col-right sidebar col-sm-3 wow bounceInUp animated animated" style="visibility: visible;">
          <div class="block block-company">
            <div class="block-title">Company</div>
            <div class="block-content">
              <ol id="recently-viewed-items">
                <ul class="links">
                  <li class="item odd"><a title="Home" href="<?php echo base_url();?>">Home</a>
                  </li>
                  <li class="item even"><a title="About" href="<?php echo base_url();?>/about">About Us</a>
                  </li>
                  <li class="item odd"><a title="Privacy" href="<?php echo base_url();?>/privacy">Privacy Policy</a>
                  </li>


                  <li class="item  last"><a title="Return  Policy" href="<?php echo base_url();?>/return_policy">Return Policy</a>
                  </li>
                  <li class="item last"><a title="Terms & Condition" href="<?php echo base_url();?>/terms">Terms & Condition</a>
                  </li>

                </ul>

              </ol>
            </div>
          </div>
        </aside>
        <!--col-right sidebar--> 
      </div>
      <!--row--> 
    </div>
    <!--main-container-inner--> 
  </div>
  <!--main-container col2-left-layout--> 
  
 <?php include 'footer.php' ?>
 <script>
   function contactUs() {
     var name = jQuery("#name").val();
     var email = jQuery("#email").val();
     var mobile = jQuery("#mobile").val();
     var commentt = jQuery("#comment").val();
     if (name == '') {

       swal("Please Enter Your Full Name");

     } else if (email == '') {

       swal("Please Enter Your Email Address");
     }

     else if (commentt == '') {

       swal("Please Describe Something.");
     }
     else {
         if(mobile==''){
             mobile1='NA';
         }else{
             mobile1=mobile;
         }
       jQuery.ajax({
             type: 'POST',
             url: '<?php echo base_url();?>ContactUs',
             data: {'name': name, 'emailid': email, 'mobile': mobile1, 'comment': commentt
           },
           success: function (res) {
         if (res.error == true) {
           sweetAlert("", "Something went wrong! please try again", "error");
         } else {
           swal({
                 title: "Thank You!",
                 text: res.message,
                 type: "success",

               },
               function(){
                 window.location.href ='<?php echo base_url();?>contact';

               });

           // window.location.href = '<?php echo base_url();?>';

         }
       }
     });
     return false;
   }
   }
 </script>
</body>
</html>