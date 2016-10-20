$('#form').submit(function () {
    var manager_name = $("#manager_name").find("option:selected").val();
    var object_aera = $("#object_aera").find("option:selected").val();
    var object_hangye = $("#object_hangye").find("option:selected").val();
    var object_sealman = $("#object_sealman").find("option:selected").val();
    var project_name = $('#project_name').val();
    var project_channel = $('#project_channel').val();
    var object_list = $('#object_list').find("option:selected").val();
    var object_budget = $('#object_budget').val();
    var get_date = $("input[name*='get_time']").val();
    var object_status = $('#object_status').find("option:selected").val();
    var grasp_degree = $('#grasp_degree1').find("option:selected").val();
    var current_progress = $('#current_progress').find("option:selected").val();
    var winning_channel = $('#winning_channel').val();
    var account_manager = $('#account_manager').val();
    var project_id = $('#project_id').val();
    var urgent = $('#urgent').val();
    var demo = $("#demo").val();
    var history_demo = $("#history_demo").val();
    if (project_name == '') {//项目名称不能为空
        $(".alert").fadeIn();
        $(".alert span").html(" 项目名不能为空");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
    } else if (object_budget == '') { //项目预算不能为空
        $(".alert").fadeIn();
        $(".alert span").html(" 项目预算不能为空");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
    } else {
        $.ajax({
            type: 'POST',
            url: $('#edit_project1').attr('url'),
            data: {
                'project_id': project_id,
                'manager_name': manager_name,
                'object_aera': object_aera,
                'object_hangye': object_hangye,
                'object_sealman': object_sealman,
                'project_name': project_name,
                'project_channel': project_channel,
                'object_list': object_list,
                'object_budget': object_budget,
                'get_date': get_date,
                'object_status': object_status,
                'grasp_degree': grasp_degree,
                'current_progress': current_progress,
                'winning_channel': winning_channel,
                'account_manager': account_manager,
                'demo': demo,
                'history_demo': history_demo,
                'urgent': urgent
            },
            success: function (data) {
                if (data == 0) {
                    $(".close").click();
                    bootMessage("success", "项目信息修改成功！");
                    GetData();
                } else if (data == 1) {
                    bootMessage("danger", "项目信息修改失败！");
                }
            }
        });
    }
    return false;
});


/*
 $('#form1').submit(function(){
 var object_status = $('#object_status1').find("option:selected").val();
 var grasp_degree = $('#grasp_degree11').find("option:selected").val();
 var current_progress = $('#current_progress11').find("option:selected").val();
 var project_id = $('#project_id1').val();
 var history_demo=$("#history_demo1").val();
 $.ajax({
 type:'POST',
 url:$('#add_to_history').attr('url'),
 data:{
 'project_id':project_id,
 'object_status':object_status,
 'grasp_degree':grasp_degree,
 'current_progress':current_progress,
 'history_demo':history_demo
 },
 success:function(data) {
 if (data == 0) {
 $(".close").click();
 $("#show_model").trigger("click");
 } else if (data == 1){
 $("#show_danger").trigger("click");
 }
 }
 });
 return false;
 });
 */
