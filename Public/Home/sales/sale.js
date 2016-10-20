$(function () {
    GetData();
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        $(".caret").remove();
    }
});
var resultDataTable = null;
var Id = "";
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
            {"data": "name"},
            {"data": "channel"},
            {"data": "budget"},
            {"data": "project_status"},
            {"data": "power"},
            {"data": "project_rate"},
            {"data": "is_state"},
            {"data": "is_promise"},
            {"data": "is_alone"},
            {"data": "is_change"},
            {"data": "Id", "bSortable": false}
        ],
        "aaSorting": [[0, 'desc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData = {
                "name": $("#name").val(),
                "channel": $("#get_news_way").val(),
                "budget": $("#get_budget").val(),
                "budget2": $("#get_budget_2").val(),
                "status": $("#pss_select").val(),
                "rate": $("#pr_select").val(),
                "power": $("#ps_select").val()
            };
            $.ajax({
                "type": 'post',
                "url": sSource,
                "dataType": "json",
                "data": {
                    aoData: aoData
                },
                "success": function (resp) {
                    fnCallback(resp);
                }
            });

        },
        "fnInitComplete": function () {
            $("input[type=search]").attr("placeholder", "筛选");
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).find("td:eq(0)").attr("data-id", aData.Id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("<a class='btn btn-info btn-xs' id='infoedit' about='" + aData.Id + "' onclick='projectEdit(this)'><i class='fa fa-edit'></i> 编辑 </a>");
            }

            $(nRow).find("td:eq(0)").attr("data-id", aData.Id);
            $(nRow).dblclick(function () {
                bootbox.dialog({
                    message: $("#editMessage").html(),
                    title: "编辑项目信息",
                    className: ""
                });
                $(".bootbox .modal-dialog").css("width", "800px");
                $.post($("#item").val(), {id: aData.Id}, function (d, s) {
                    $(".bootbox").attr("data-id", aData.Id);
                    $(".bootbox #create_id").text(d.create_id);
                    $(".bootbox #dict_hy").text(d.dict_hy);
                    $(".bootbox #dict_qy").text(d.dict_qy);
                    $(".bootbox #budget").text(d.budget + "万");
                    $(".bootbox #dict_cp").text(d.dict_cp);
                    $(".bootbox #tender_time").text(d.tender_time);
                    $(".bootbox #project_status").text(d.project_status);
                    $(".bootbox #power").text(d.power);
                    $(".bootbox #create_time").text(d.create_time);
                    $(".bootbox #project_rate").text(d.project_rate);
                    $(".bootbox #demo").text(d.demo);
                    $(".bootbox #win_channel").val(d.win_channel);
                    $(".bootbox #vender").val(d.vender);
                    $(".bootbox #channel").val(d.channel);
                    $(".bootbox #title").val(d.name);
                    $(".bootbox #choice_state").val(d.is_state);
                    $(".bootbox #choice_alone").val(d.is_alone);
                    $(".bootbox #choice_promise").val(d.is_promise);
                    $(".bootbox #choice_change").val(d.is_change);

                }, 'json');
            });
            return nRow;
        }
    });
}
function btnSave() {
    $("#all_wrong_info span").html("");
    var state = $.trim($(".bootbox #choice_state").val());
    var alone = $.trim($(".bootbox #choice_alone").val());
    var promise = $.trim($(".bootbox #choice_promise").val());
    var change = $.trim($(".bootbox #choice_change").val());
    var channel = $.trim($(".bootbox #channel").val());
    var win_channel = $.trim($(".bootbox #win_channel").val());
    var vender = $.trim($(".bootbox #vender").val());
    var title = $.trim($(".bootbox #title").val());
    if (title == "") {
        $(".alert").fadeIn();
        $("#all_wrong_info span").html("项目名称不能为空");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000)
        return;
    }
    var address = $("#updateaddr").attr("value");
    $.ajax({
        type: "POST",
        url: address,
        data: {
            id: $(".bootbox").attr("data-id"),
            state: state,
            alone: alone,
            promise: promise,
            change: change,
            channel: channel,
            win_channel: win_channel,
            vender: vender,
            title: title
        },
        success: function (data) {
            $(".msg").html("");
            if (data) {
                $(".bootbox-close-button").click();
                bootMessage("success", "保存成功");
                GetData();
            } else {
                bootMessage("warning", "保存失败");
            }
        }
    });
}


//编辑按钮事件
function projectEdit(e) {

    bootbox.dialog({
        message: $("#editMessage").html(),
        title: "编辑项目信息",
        className: ""
    });
    $(".bootbox .modal-dialog").css("width", "800px");
    var thisId = $(e).attr("about");
    $.post($("#item").val(), {id: thisId}, function (d, s) {
        $(".bootbox").attr("data-id", thisId);
        $(".bootbox #create_id").text(d.create_id);
        $(".bootbox #dict_hy").text(d.dict_hy);
        $(".bootbox #dict_qy").text(d.dict_qy);
        $(".bootbox #budget").text(d.budget + "万");
        $(".bootbox #dict_cp").text(d.dict_cp);
        $(".bootbox #tender_time").text(d.tender_time);
        $(".bootbox #project_status").text(d.project_status);
        $(".bootbox #power").text(d.power);
        $(".bootbox #create_time").text(d.create_time);
        $(".bootbox #project_rate").text(d.project_rate);
        $(".bootbox #demo").text(d.demo);
        $(".bootbox #win_channel").val(d.win_channel);
        $(".bootbox #vender").val(d.vender);
        $(".bootbox #channel").val(d.channel);
        $(".bootbox #title").val(d.name);
        $(".bootbox #choice_state").val(d.is_state);
        $(".bootbox #choice_alone").val(d.is_alone);
        $(".bootbox #choice_promise").val(d.is_promise);
        $(".bootbox #choice_change").val(d.is_change);
    });

}


