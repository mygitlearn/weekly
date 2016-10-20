$('#delete').click(function(){
    //获取所有选中的value()值
    var strVal = [];
    $(":checkbox").each(function(){
        if(this.checked){
            strVal.push($(this).val());
        }
    });
   var get_data= $("#txtcheckbox").val(strVal);
    if(strVal.length==0){
        $("#show_error").css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else {
        $.ajax({
            type: 'POST',
            url: $('#delete_url').attr('url'),
            data: {
                'arr': strVal
            },
            success: function (data) {
               if(data ==1){
                   $("#show_success").css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
                       $(this).css({display:"none"});
                       window.location.reload();//刷新当前页面.
                   });
               }else{
                   $("#show_error").css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
                       $(this).css({display:"none"});
                   });
               }
            }

        });
      }

      // alert(strVal.length + " " + strVal);
});

