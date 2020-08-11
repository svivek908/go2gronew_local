$(document).ready(function(){
    $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
          width:200,
          height:200,
          type:'square' //circle
        },
        boundary:{
          width:300,
          height:300
        }
    });
});

$(document).on('change','#upload_image',function(){
//$('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

$('.crop_image').click(function(event){
    var upload_url = $("#uploadimageurl").val();
    $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function(response){
      $.ajax({
        url:upload_url,
        type: "POST",
        data:{"image": response},
        beforeSend: function ()
        {
          ajaxindicatorstart('Please Wait...');
        },
        success:function(data)
        {
            ajaxindicatorstop();
          $('#uploadimageModal').modal('hide');
          $('#uploaded_image').val(data);
        }
        });
    })
}); 
//------------Open Form---------------
function show_page(url,id,heading){
    $.ajax({
        cache: false,
        url:url,
        type: "POST",
        data: { id:id},
        dataType:"json",
        beforeSend: function ()
        {
          ajaxindicatorstart('Please Wait...');
        },
        success:function(result)
        {
            if(result.success== true)
            {
                $("#body_html").html('');
                $("#model_heading").text(heading);
                $("#body_html").html(result.page);
                $("#myModal").modal('show');
                ajaxindicatorstop();
            }
        }
    });
}
//------------Change Mom Stats---------
function change_status(url,id,changestatus){
    $.ajax({
        cache: false,
        url:url,
        type: "POST",
        data: { id:id,status:changestatus},
        dataType:"json",
        beforeSend: function ()
        {
          ajaxindicatorstart('Please Wait...');
        },
        success:function(result)
        {
            if(result.success== true)
            {
                //$("#row-"+rowid).html(result.data);
                ajaxindicatorstop();
                toastr.success('Record Updated');
                //location.reload();
                table.ajax.reload();
            }
        }
    });
}
//------------delete Record -----------
function record_delete(url,rowid,tableid){
    $.ajax({
        cache: false,
        url:url,
        type: "POST",
        data: { id:rowid},
        dataType:"json",
        beforeSend: function ()
        {
          ajaxindicatorstart('Please Wait...');
        },
        success:function(result)
        {
            if(result.success== true)
            {
                $("#"+tableid).dataTable().fnDestroy();
                ajaxindicatorstop();
                toastr.success('Record deleted');
                table.ajax.reload();
                /*toastr.warning('Warning')
                toastr.success('Success')
                toastr.error('Error',)*/
                //setTimeout(function(){ location.reload(); }, 2000);
            }
        }
    });
}

//-----------save form---------------
function savedata(formname){
    var url = $("#"+formname).attr('action');
    $.ajax({
        cache: false,
        url:url,
        type: "POST",
        data: $("#"+formname).serialize(),
        dataType:"json",
        beforeSend: function ()
        {
            ajaxindicatorstart('Please Wait...');
        },
        success:function(result)
        {
            //$('input[name="csrf_momssuper_name"]').val(result.hash);
            if(result.success== true)
            {
                ajaxindicatorstop();
                $("#myModal").modal('hide');
                toastr.success('Record Updated');
                if (table instanceof $.fn.dataTable.Api) {
                    table.ajax.reload();
                }else if(result.reload== true){
                    setTimeout(function(){
                        location.reload();
                    },2000);
                }
                else {
                    all_pagination();
                }
            }else{
                toastr.error('Error',result.invalid)
            }
        }
    });
}

//-----------save form with image-----
function savedataimage(formname){
    //
    var form = document.querySelector('form[name='+formname+']');
    var formdata = new FormData(form);
    var url = $("#"+formname).attr('action');
    $.ajax({
        cache: false,
        url:url,
        type: "POST",
        data: formdata,
        dataType:"json",
        contentType: false,
        processData:false,
        beforeSend: function ()
        {
            ajaxindicatorstart('Please Wait...');
        },
        success:function(result)
        {
            //$('input[name="csrf_momssuper_name"]').val(result.hash);
            if(result.success== true)
            {
                ajaxindicatorstop();
                $("#myModal").modal('hide');
                toastr.success('Record Updated');
                if (table instanceof $.fn.dataTable.Api) {
                    table.ajax.reload();
                }else if(result.reload== true){
                    setTimeout(function(){
                        location.reload();
                    },2000);
                }
                else {
                    all_pagination();
                }
            }else{
                toastr.error('Error',result.invalid)
            }
        }
    });
}

//-----------order activity Pagination-------
function  order_activity(page_num)
{
    var linkurl =  $('#page_url').val();
    var search = $('#search_input').val();
    var rowid = $('#rowid').val();
    var sortby = "";
    if ($('#sortby').length)
    {
        sortby = $('#sortby').val();
    }
    page_num = page_num?page_num:0;
    $.ajax({
        url: linkurl+'/'+page_num,
        type: "POST",
        data:{page:page_num,rowid:rowid,search:search,sortby:sortby},
        dataType:"json",
        beforeSend: function ()
        {
            ajaxindicatorstart('Please Wait...');
        },
        success:function(result){
            if(result.success==true){
                //$("#showrecord").html(result.page);
                $("#"+result.showid).html(result.page);
                ajaxindicatorstop();
            }
        }
    });
}

//---------Comman Pagination
function  all_pagination(page_num){
    var linkurl =  $('#page_url').val();
    var search = $('#search_input').val();
    var sortby = "";
    if ($('#sortby').length)
    {
        sortby = $('#sortby').val();
    }

    page_num = page_num?page_num:0;
    $.ajax({
        url: linkurl+'/'+page_num,
        type: "POST",
        data:{page:page_num,search:search,sortby:sortby},
        dataType:"json",
        beforeSend: function ()
        {
            ajaxindicatorstart('Please Wait...');
        },
        success:function(result){
            if(result.success==true){
                //$("#showrecord").html(result.page);
                $("#"+result.showid).html(result.page);
                ajaxindicatorstop();
            }
        }
    });
}
//-----------ajax indicator start------
function ajaxindicatorstart(text)
{
    if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
    jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="'+base_url_loader+'"><div>'+text+'</div></div><div class="bg"></div></div>');
    }
	jQuery('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });

	jQuery('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });

	jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'16px',
        'z-index':'10',
        'color':'#ffffff'

	});

    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}
//-----------ajax stop-----

function ajaxindicatorstop()
{
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}