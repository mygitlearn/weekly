$('#form').submit(function(){
    var urgent=$("#urgent").find("option:selected").val();
    var object_aera = $("#object_aera").find("option:selected").val();
    var object_hangye = $("#object_hangye").find("option:selected").val();
    var object_sealman = $("#object_sealman").find("option:selected").val();
    var project_name = $('#project_name').val();
    var project_channel = $('#project_channel').val();
    var object_list = $('#object_list').find("option:selected").val();
    var object_budget = $('#object_budget').val();
    var get_date = $("input[name*='get_time']") .val();
    var object_status = $('#object_status').find("option:selected").val();
    var grasp_degree = $('#grasp_degree').find("option:selected").val();
    var current_progress = $('#current_progress').find("option:selected").val();
    var winning_channel = $('#winning_channel').val();
    var account_manager = $('#account_manager').val();
    var demo = $('#demo').val();

    if(project_name == ''){ //项目名称不能为空
        $("#waring").html("<span id='tetx'><strong>Warning</strong> 项目名不能为空. </span>").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(object_budget ==''){ //项目预算不能为空
        $("#waring").html("<strong>Warning</strong> 项目预算不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(object_aera =='0'){
        $("#waring").html("<strong>Warning</strong> 项目区域不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(object_hangye =='0'){
        $("#waring").html("<strong>Warning</strong> 项目行业不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(object_sealman =='0'){
        $("#waring").html("<strong>Warning</strong> 项目销售人员不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(grasp_degree =='0'){
        $("#waring").html("<strong>Warning</strong> 项目把握度不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(current_progress =='0'){
        $("#waring").html("<strong>Warning</strong> 项目当前进展不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(object_status =='0'){
        $("#waring").html("<strong>Warning</strong> 项目状态不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else if(object_list =='0'){
        $("#waring").html("<strong>Warning</strong> 项目清单不能为空.").insertAfter($("#show_waring")).css({display:""}).animate({opacity: 1.0}, 2000).fadeOut("slow",function(){
            $(this).css({display:"none"});
        });
    }else{
        $.ajax({
            type:'POST',
            url:$('#form').attr('action'),
            data:{
                'urgent': urgent,
                'object_aera': object_aera,
                'object_hangye':object_hangye,
                'object_sealman': object_sealman,
                'project_name':project_name,
                'project_channel':project_channel,
                'object_list':object_list,
                'object_budget':object_budget,
                'get_date':get_date,
                'object_status':object_status,
                'grasp_degree':grasp_degree,
                'current_progress':current_progress,
                'winning_channel':winning_channel,
                'account_manager':account_manager,
                'demo':demo
            },
            success:function(data) {
                if (data == 0) {
                    $('#project_name').val("");
                    $('#object_budget').val("");
                    bootMessage("success","项目添加成功！");
                } else{
                    bootMessage("danger","项目添加失败！");
                }
            }
        });

    }

    return false;
});

function refush(){
    window.location.reload();
}