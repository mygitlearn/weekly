/*$('#delete').click(function () {
    //获取所有选中的value()值
    var strVal = [];
    $(":checked").each(function () {
        strVal.push($(this).val());
    });
    var get_data = $("#txtcheckbox").val(strVal);
    if (strVal.length == 0) {
        $("#show_error").css({display: ""}).animate({opacity: 1.0}, 2000).fadeOut("slow", function () {
            $(this).css({display: "none"});
        });
    } else {
        $.ajax({
            type: 'POST',
            url: $('#delete_url').attr('url'),
            data: {
                'arr': strVal
            },
            success: function (data) {
                if (data == 1) {
                    $("#show_success").css({display: ""}).animate({opacity: 1.0}, 2000).fadeOut("slow", function () {
                        $(this).css({display: "none"});
                        window.location.reload();//刷新当前页面.
                    });
                } else {
                    $("#show_error").css({display: ""}).animate({opacity: 1.0}, 2000).fadeOut("slow", function () {
                        $(this).css({display: "none"});
                    });
                }
            }

        });
    }
    // alert(strVal.length + " " + strVal);
});*/

$(function () {
    GetData();
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        $(".caret").remove();
    }
});
var resultDataTable = null;

function GetData() {
    var $searchResult = $("#simpledatatable");

    if (resultDataTable) {
        resultDataTable.fnClearTable();
        $searchResult.dataTable().fnDestroy();
        $("#simpledatatable tbody").empty();
        $('ul.toggle-table-columns').empty();
    } else {
        $searchResult.show();
    }
    resultDataTable = $searchResult.dataTable({
        "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
        "iDisplayLength": 10,
        "bAutoWidth": false,
        "sAjaxSource": $("#url").val(),
        "language": {
            "sProcessing": "正在加载数据...",
            "sZeroRecords": "没有您要搜索的内容",
            "sInfo": "从 _START_ 到 _END_ 条记录，总记录数为 _TOTAL_ 条",
            "sInfoEmpty": "总记录数为 0 条",
            "sInfoFiltered": "(全部记录数 _MAX_  条)",
            "sSearch": "",
            "sLengthMenu": "_MENU_",
            "oPaginate": {
                "sPrevious": "上一页",
                "sNext": "下一页"
            }
        },

        "columns": [
            /*{"mData": "Id"},*/
            {"data": "name"},
            {"data": "budget"},
            {"data": "project_status"},
            {"data": "power"},
            {"data": "project_rate"},
            {"data": "is_state"},
            {"data": "is_promise"},
            {"data": "is_change"},
            {"data": "is_alone"},
            {"data": "urgent"},
            {"data": "Id", "bSortable": false, "sWidth": "280px"}
        ],
        "aaSorting": [[0, 'desc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData = {
                "search_project_name": $("#search_project_name").val(),
                "buget_small": $("#budge_small_number").val(),
                "buget_big": $("#budge_big_number").val(),
                "get_object_status": $("#get_object_status").val(),
                "grasp_degree": $("#grasp_degree").val(),
                "get_current_progress": $("#get_current_progress").val()
            };
            $.ajax({
                "type": 'post',
                "url": sSource,
                "dataType": "json",
                "data": {
                    aoData: aoData
                    //aoData: JSON.stringify(aoData)
                },
                "success": function (resp) {
                    fnCallback(resp);
                }
            });
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).dblclick(function () {
                    $.ajax({
                        type: 'POST',
                        url: $('#edit_project').attr('url'),
                        data: {
                            'object_status_id': aData.Id
                        },
                        success: function (data) {
                            if (data == 1) {
                                bootMessage("danger","获取数据失败！")
                            } else {
                                $("#project_id").val(data['Id']);
                                $("#project_name").val(data['name']);
                                $("#urgent").val(data['urgent']);
                                $("#object_aera").val(data['dict_qy']);
                                $("#object_hangye").val(data['dict_hy']);
                                $("#object_sealman").val(data['dict_xs']);
                                $("#object_list").val(data['dict_cp']);
                                $("#object_status").val(data['project_status']);
                                $("#grasp_degree1").val(data['power']);
                                $("#current_progress").val(data['project_rate']);
                                $("#project_channel").val(data['channel']);
                                $("#id-date-picker-1").val(data['tender_time']);
                                $("#winning_channel").val(data['win_channel']);
                                $("#account_manager").val(data['vender']);
                                $("#object_budget").val(data['budget']);
                                $("#demo").val(data['demo']);
                                $("#history_demo").val(data['demo']);
                                $("#edit_show_content").trigger("click");
                            }
                        }

                    });
                });

                if (aData.status == 0) {
                    //$(nRow).find("td:last").html("<td id='t" + aData.Id + "'><a class='btn btn-info btn-xs' onclick=Doit('" + aData.Id + "')><i class='fa fa-check'></i> Do</a></td>");
                    $(nRow).find("td:last").html("<a class='btn btn-info btn-xs start' id='start_" + aData.Id + "' onclick=Start('" + aData.Id + "')  style='cursor:pointer;margin-right: 5px'> <i class='fa fa-play'></i>开始</a><a class='btn btn-info btn-xs start' id='edit_" + aData.Id + "' onclick=edit('" + aData.Id + "')  style='cursor:pointer;margin-right: 5px'> <i class='fa fa-edit'></i>编辑</a><a class='click_delete btn btn-danger btn-xs '  onclick=Delete('" + aData.Id + "') style='cursor:pointer;margin-right: 2px' id='del_" + aData.Id + "'> <i class='fa fa-trash-o'></i> 删除</a> <a class='btn btn-info btn-xs look' onclick=Look('" + aData.Id + "') style='cursor:pointer'><i class='fa fa-search'></i>历史</a> ");

                } else {
                    $(nRow).find("td:last").html("<a class='btn btn-info btn-xs start' id='edit_" + aData.Id + "' onclick=edit('" + aData.Id + "')  style='cursor:pointer;margin-right: 5px'> <i class='fa fa-edit'></i>编辑</a> <a class='btn btn-info btn-xs ' onclick=Look('" + aData.Id + "') style='cursor:pointer'> <i class='fa fa-search'></i>历史</a>");
                }
            }
            $(nRow).addClass("tr_" + aData.Id);
            return nRow;
        },

        "fnInitComplete": function () {
            $("input[type=search]").attr("placeholder", "筛选");
        }

    });

}

function Look(id) {
    $.ajax({
        type: 'POST',
        url: $('#history_detail').attr('url'),
        data: {
            'id': id
        },
        success: function (data) {
            $("#history").trigger("click");
            $("#data_ualue").html($('#data_history_temp').tmpl(data));
        }
    });
}


function Start(id) {
    $.ajax({
        type: 'POST',
        url: $('#newproject_id').attr('url'),
        data: {
            'object_status_id': id
        },
        success: function (data) {
            if (data == 0) {
                $("#del_" + id).remove();
                $("#start_" + id).remove();
                bootMessage("success","项目开始成功！")
               /* $("#show_start_success").trigger("click");*/
            } else {
                bootMessage("danger","项目开始失败！")
                /*$("#show_start_danger").trigger("click");*/
            }
        }

    });
}


function Delete(id) {
    bootbox.confirm({
        buttons: {
            confirm: {
                label: '确定',
                className: 'btn-primary'
            },
            cancel: {
                label: '取消',
                className: 'btn-default'
            }
        },
        message: '确定要删除这个项目吗？',
        callback: function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: $('#delete_project').attr('url'),
                    data: {
                        'object_status_id': id
                    },
                    success: function (data) {
                        if (data == 1) {
                            $(".tr_" + id).remove();
                            bootMessage("success","项目信息删除成功！");

                        } else if (data == 2) {
                            bootMessage("danger","项目信息删除失败！");
                        } else {
                            bootMessage("danger","项目信息删除失败！");
                        }
                    }
                });
            } else {

            }
        },
        title: "确认信息"
    });
}

function edit(id) {
    $.ajax({
        type: 'POST',
        url: $('#edit_project').attr('url'),
        data: {
            'object_status_id': id
        },
        success: function (data) {
            if (data == 1) {
                bootMessage("danger","获取数据失败！");
            } else {
                $("#project_id").val(data['Id']);
                $("#project_name").val(data['name']);
                $("#urgent").val(data['urgent']);
                $("#object_aera").val(data['dict_qy']);
                $("#object_hangye").val(data['dict_hy']);
                $("#object_sealman").val(data['dict_xs']);
                $("#object_list").val(data['dict_cp']);
                $("#object_status").val(data['project_status']);
                $("#grasp_degree1").val(data['power']);
                $("#current_progress").val(data['project_rate']);
                $("#project_channel").val(data['channel']);
                $("#id-date-picker-1").val(data['tender_time']);
                $("#winning_channel").val(data['win_channel']);
                $("#account_manager").val(data['vender']);
                $("#object_budget").val(data['budget']);
                $("#demo").val(data['demo']);
                $("#history_demo").val(data['demo']);
                $("#edit_show_content").trigger("click");
            }
        }

    });
}