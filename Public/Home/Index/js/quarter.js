jQuery.fn.rowspan = function (colIdx) { //封装的一个JQuery小插件
    return this.each(function () {
        var that;
        $('tr', this).each(function (row) {
            $('td:eq(' + colIdx + ')', this).filter(':visible').each(function (col) {
                if (that != null && $(this).html() == $(that).html()) {
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
            {"mData": "dict_cp"},
            {"mData": "dict_qy"},
            {"mData": "count"},
            {"mData": "alone_count"},
            {"mData": "alone_count_per"},
            {"mData": "sum"},
            {"mData": "alone_sum"},
            {"mData": "alone_sum_per"}
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
                }
            });
        },
        "fnInitComplete": function () {
            $("input[type=search]").attr("placeholder", "筛选");
        }

    });

}

