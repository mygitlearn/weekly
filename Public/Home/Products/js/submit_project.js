$('#edit').click(function(){
    //获取所有选中的value()值
    var strVal = [];
    $(":checkbox").each(function(){
        if(this.checked){
            strVal.push($(this).val());
        }
    });
    if(strVal.length==0){
        $("#show_error").css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else {
        $.ajax({
            type: 'POST',
            url: $('#edit_project').attr('url'),
            data: {
                'arr': strVal
            },
            success: function (data) {
                if(data){
                    window.location.href = $('#show_edit').attr('url')+'?Id='+data;
                }else{
                    $("#show_error").css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
                        $(this).css({display:"none"});
                    });
                }
            }

        });
    }
});

$('#search_for_order').click(function(){
    $("#show_wall").css({display:""})
});


$('#ser_f_order').click(function(){
    var object_status_id = $("#object_name").find("option:selected").val(); //项目状态
    var grasp_jindu_id = $("#grasp_degree").find("option:selected").val(); //项目把握度
    var object_programm_id = $("#current_progress").find("option:selected").val();//当前进展
    var project_now_status = $("#project_now_status").find("option:selected").val();//当前进展
    $.ajax({
            type: 'POST',
            url: $('#search_form').attr('action'),
            data: {
                'object_status_id': object_status_id,
                'grasp_jindu_id':grasp_jindu_id,
                'object_programm_id':object_programm_id,
                'project_now_status':project_now_status
            },
            success: function (data) {
                /*if(data){
                 window.location.href = $('#show_edit').attr('url')+'?Id='+data;
                 }else{
                 $("#show_error").css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
                 $(this).css({display:"none"});
                 });
                 }*/
            }

    });

});