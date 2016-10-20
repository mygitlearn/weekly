$(function () {

    $('#reservation').daterangepicker({
        format: 'YYYY/MM/DD',
        startDate: moment().subtract('days', 7),
        endDate: moment()
    });
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
            {"data": "create_time", "sWidth": "90px"},
            {"data": "name"},
            {"data": "dict_qy", "sWidth": "65px"},
            {"data": "dict_hy", "sWidth": "100px"},
            {"data": "create_id", "sWidth": "90px"},
            {"data": "dict_xs", "sWidth": "90px"},
            {"data": "channel", "sWidth": "120px"},
            {"data": "dict_cp", "sWidth": "90px"},
            {"data": "budget", "sWidth": "85px"},
            {"data": "tender_time", "sWidth": "90px"},
            {"data": "project_status", "sWidth": "90px"},
            {"data": "power", "sWidth": "75px"},
            {"data": "project_rate", "sWidth": "120px"},
            {"data": "is_state", "sWidth": "90px"},
            {"data": "is_promise", "sWidth": "90px"},
            {"data": "is_change", "sWidth": "60px"},
            {"data": "is_alone", "sWidth": "60px"},
            {"data": "win_channel", "sWidth": "100px"},
            {"data": "vender", "sWidth": "100px"},
            {"data": "demo"},
            /*{"data": "Id","bSortable": false}*/
        ],
        "aaSorting": [[0, 'desc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData = {
                "sj": $("#reservation").val(),
                "qy": $("#qy").val(),
                "hy": $("#hy").val(),
                "jl": $("#jl").val(),
                "xs": $("#xs").val(),
                "cp": $("#cp").val(),
                "ys": $("#ys").val(),
                "power": $("#power").val()
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
            /*if($(nRow).find(".btn-info").length==0) {
             $(nRow).find("td:last").html("<td id='t" + aData.Id + "'><a class='btn btn-info btn-xs' onclick=Doit('" + aData.Id + "')><i class='fa fa-check'></i> Do</a></td>");
             }
             console.log(aData.Id);*/
            return nRow;
        },
        "fnInitComplete": function () {
            $("input[type=search]").attr("placeholder", "筛选");
        }
    });

    //隐藏/显示列
    var targetTable = $('ul.toggle-table-columns');
    if (targetTable.find("li").length == 0) {
        $('#simpledatatable>thead th').each(function (i) {
            var text = $.trim($(this).html());
            if (text == "操作") {
                return
            }
            var hide = [2, 3, 4, 5, 7, 9, 17, 18, 19];
            if ($.inArray(i, hide) > -1) {
                targetTable.append('<li class="dropdown"><a href="javascript:void(0);"><form><label><input type="checkbox" data-id="' + i + '"><span class="text">' + text + '</span> </label></form>  </a></li>');
                $('#simpledatatable').DataTable().column(i).visible(false);
            } else {
                targetTable.append('<li class="dropdown"><a href="javascript:void(0);"><form><label><input type="checkbox" data-id="' + i + '" checked="true"><span class="text">' + text + '</span> </label></form>  </a></li>');
                $('#simpledatatable').DataTable().column(i).visible(true);
            }

        });
    }
    targetTable.find('input[type=checkbox]').change(function (i) {
        $('#simpledatatable').DataTable().column($(this).attr('data-id')).visible($(this).is(":checked"));
    });

}

function Doit(id) {
    $.post($("#todo").val(), {id: id}, function (d, s) {
        if (d.code == "0") {
            $("#my_message>.btn-success").click();
            $("#t" + id).parent().remove();
        } else {
            $("#my_message>.btn-warning").click();
        }
    }, "json");
}