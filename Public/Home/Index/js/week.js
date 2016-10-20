jQuery.fn.rowspan = function (colIdx) { //封装的一个JQuery小插件
    return this.each(function () {
        var that;
        var row;
        $('tr', this).each(function (row) {
            row = this;
            $('td:eq(' + colIdx + ')', this).filter(':visible').each(function (col) {
                var td_td = "";
                var td = "";
                var idx = parseInt(colIdx) - 1;
                if (idx >= 0) {
                    td_td = $(that).parent().find("td:eq(" + idx + ")").html();
                    td = $(this).parent().find("td:eq(" + idx + ")").html();
                }
                if (that != null && $(this).html() == $(that).html() && td == td_td) {
                    rowspan = $(that).attr("rowSpan");
                    if (rowspan == undefined) {
                        $(that).attr("rowSpan", 1);
                        rowspan = $(that).attr("rowSpan");
                    }
                    rowspan = Number(rowspan) + 1;
                    $(that).attr("rowSpan", rowspan);
                    $(this).hide();
                } else {
                    that = this;
                }
                $(this).css({"text-align": "center", "vertical-align": "middle"});
            });
        });
    });
}
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
        "bAutoWidth": false,
        "bPaginate": false,
        "bSort": false,
        "bFilter": false,
        "bInfo": false,
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
        "aoColumns": [
            {"mData": "u_time"},
            {"mData": "dict_cp"},
            {"mData": "dict_qy"},
            {"mData": "count"},
            {"mData": "sum"}
        ],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData = {
                "sj": $("#reservation").val()
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
                    $("#simpledatatable").rowspan(0);
                    $("#simpledatatable").rowspan(1);
                }
            });
        },
        "fnInitComplete": function () {
            $("input[type=search]").attr("placeholder", "筛选");
        }

    });

}

