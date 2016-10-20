/**
 * Created by loner on 2015/11/11.
 */
$(function () {
    GetUserData();
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        $(".caret").remove();
    }
});

var resultDataTable = null;
function GetUserData() {

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
        "sAjaxSource": $("#address").val(),
        "language": {
            "sProcessing": "正在加载数据...",
            "sZeroRecords": "没有您要搜索的内容",
            "sInfo": "从_START_ 到 _END_ 条记录　总记录数为 _TOTAL_ 条",
            "sInfoEmpty": "记录数为0",
            "sInfoFiltered": "(全部记录数 _MAX_  条)",
            "sInfoPostFix": "",
            "search": "",
            "sLengthMenu": "_MENU_",
            "oPaginate": {
                "sPrevious": "上一条",
                "sNext": "下一条"
            }
        },

        "columns": [
            {"data": "account"},
            {"data": "name"},
            {"data": "tel"},
            {"data": "job"},
            {"data": "email"},
            {"data": "Id", "bSortable": false}
            //{"data": ""}
        ],
        "aaSorting": [[4, 'asc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData = {
                "condition": $("#condition_search").val()
            };
            $.ajax({
                "type": 'post',
                "url": sSource,
                "dataType": "json",
                "data": "",
                "success": function (resp) {
                    fnCallback(resp);
                    //console.log(resp);
                }
            });
        },
        "fnInitComplete": function () {
            $("input[type=search]").attr("placeholder", "筛选");
            bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).find("td:eq(0)").attr("data-id", aData.Id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("<a class='btn btn-info btn-xs edit click_edit' about='" + aData.Id + "'><i class='fa fa-edit'></i> 密码重置</a>&nbsp;<a class='btn btn-info btn-xs' id='infoedit' about='" + aData.Id + "'><i class='fa fa-edit'></i> 编辑 </a>&nbsp;<a class='click_delete btn btn-danger btn-xs delete' about='" + aData.Id + "'><i class='fa fa-trash-o'></i> 删除 </a>");
            }
            return nRow;
            //添加双击事件
            //$(nRow).dblclick(function(){
            //    "use strict";
            //    alert("ddddd");
            //})

        }
    });
}
var set;
function bind() {
    $("#simpledatatable tbody").on("click", "tr #infoedit", function () {
        var thisinfo = $(this).parent().parent().find("td");
        var account = thisinfo.eq(0).text();
        var username = thisinfo.eq(1).text();
        var phone = thisinfo.eq(2).text();
        var job = thisinfo.eq(3).text();
        var email = thisinfo.eq(4).text();
        set = $(this).attr("about");
        bootbox.dialog({
            message: $("#myUserModal").html(),
            title: "更新信息用户&emsp;",
            className: ""
        });

        $(".bootbox #uname").attr("value", username);
        $(".bootbox #iphone").attr("value", phone);
        $(".bootbox #email").attr("value", email);
        $(".bootbox #account").attr("value", account);
        if (job == "大区经理") {
            $(".bootbox #choice_job").find("option[value='1']").attr("selected", "selected");
        }
        if (job == "产品经理") {
            $(".bootbox #choice_job").find("option[value='2']").attr("selected", "selected");
        }
        if (job == "销售") {
            $(".bootbox #choice_job").find("option[value='3']").attr("selected", "selected");
        }
    });


//双击修改职员用户的信息
    $('#simpledatatable tbody').on('dblclick', 'tr', function () {

        var account = $(this).find("td").eq(0).text();
        var username = $(this).find("td").eq(1).text();
        var phone = $(this).find("td").eq(2).text();
        var job = $(this).find("td").eq(3).text();
        var email = $(this).find("td").eq(4).text();
        set = $(this).find("td").eq(5).find("a").attr("about");

        bootbox.dialog({
            message: $("#myUserModal").html(),
            title: "更新信息用户&emsp;",
            className: ""
        });
        $(".modal-dialog").width(460);
        $(".bootbox #uname").attr("value", username);
        $(".bootbox #iphone").attr("value", phone);
        $(".bootbox #email").attr("value", email);
        $(".bootbox #account").attr("value", account);
        if (job == "大区经理") {
            $(".bootbox #choice_job").find("option[value='1']").attr("selected", "selected");
        }
        if (job == "产品经理") {
            $(".bootbox #choice_job").find("option[value='2']").attr("selected", "selected");
        }
        if (job == "销售") {
            $(".bootbox #choice_job").find("option[value='3']").attr("selected", "selected");
        }

    });
//密码重置
    $("#simpledatatable tbody").on("click", "tr .click_edit", function () {
        var setid = $(this).attr("about");
        var url = $("#reset").attr("value");
        $.ajax({
            type: "post",
            url: url,
            data: {set: setid},
            success: function (data) {
                if (data) {
                    bootMessage("success", data)
                } else {
                    bootMessage("warning", "出现未知错误，密码重置失败")
                }
            }
        });
    });
//删除职员
    $("#simpledatatable tbody").on("click", "tr .delete", function () {
        var del_btn = $(this);
        var set = del_btn.attr("about");
        var url = $("#deladdr").attr("value");
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
            message: '确定要删除这个用户吗？',
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {set: set},
                        success: function (data) {
                            if (data) {

                                bootMessage("success", "删除成功")
                                del_btn.parent().parent().remove();
                            } else {
                                bootMessage("warning", "删除失败")
                            }
                        }
                    });
                } else {

                }
            },
            title: "确认信息"
        });
    });
}
function btnSave() {
    var uname = $(".bootbox #uname").val();
    var phone = $(".bootbox #iphone").val();
    var jobtype = $(".bootbox #choice_job").val();
    var email = $(".bootbox #email").val();
    var account = $(".bootbox #account").val();
    var address = $("#updateaddr").attr("value");
    $(".bootbox .warning-info").removeClass("appear");
    $(".bootbox .form-control-feedback").css("display", "none")
    if (phone == "" && email == "" && uname == "") {
        $(".alert").fadeIn();
        $("#editInfo span").text("请完善信息");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (uname == "") {
        $(".alert").fadeIn();
        $("#editInfo span").text("请填写用户名信息");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (/^(\w|[\u4E00-\u9FA5])*$/.test(uname) == false) {
        $(".alert").fadeIn();
        $("#editInfo span").text("请输入中文或英文或数字");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (phone == "") {
        $(".alert").fadeIn();
        $("#editInfo span").text("请填写用户电话号码");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (email == "") {
        $(".alert").fadeIn();
        $("#editInfo span").text("请填写用户电子邮箱");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (account == "") {
        $(".alert").fadeIn();
        $("#editInfo span").text("请填写登录帐号");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }

    var myreg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[0-9])[0-9]{8}$/;
    if (!myreg.test(phone)) {
        $(".alert").fadeIn();
        $("#editInfo span").text("请输入有效的手机号码");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (filter.test(email) == false) {
        $(".alert").fadeIn();
        $("#editInfo span").text("邮箱地址信息有误");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }

    $.ajax({
        type: "POST",
        url: address,
        data: {id: set, uname: uname, tel: phone, job: jobtype, email: email, account: account},
        success: function (data) {
            if (data > 0) {
                $(".bootbox-close-button").click();
                bootMessage("success", "保存成功");
                GetUserData();
            } else if (data == -1) {
                $(".alert").fadeIn();
                $("#editInfo span").text("用户名称已经存在");
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            } else if (data == -2) {
                $(".alert").fadeIn();
                $("#editInfo span").text("电话号码已经存在");
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            }
            else if (data == -3) {
                $(".alert").fadeIn();
                $("#editInfo span").text("此邮箱已经存在");
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            } else if (data == -4) {
                $(".alert").fadeIn();
                $("#editInfo span").text("此登录帐号已经存在");
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            }
            else {
                bootMessage("warning", "保存失败");
                return;
            }
        }
    });
}

//添加新用户
$("#adduser").click(function () {
    bootbox.dialog({
        message: $("#addModal").html(),
        title: "新增用户",
        className: ""
    });
    $(".modal-dialog").width(460);
});
function btnok() {
    var addaccount = $(".bootbox #addaccount").val().trim();
    var addname = $(".bootbox #addname").val().trim();
    var addiphone = $(".bootbox #addiphone").val().trim();
    var addemail = $(".bootbox #addemail").val().trim();
    var addjob = $(".bootbox #select_job").val().trim();
    var address = $("#addaddr").val();              //获取php地址

    if (addname == "" && addiphone == "" && addemail == "") {
        $(".alert").fadeIn();
        $("#addInfo span").text("请完善信息");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (addname == "") {
        $(".alert").fadeIn();
        $("#addInfo span").text("请填写用户名信息");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (/^(\w|[\u4E00-\u9FA5])*$/.test(addname) == false) {
        $(".alert").fadeIn();
        $("#addInfo span").text("请输入中文或英文或数字");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (addiphone == "") {
        $(".alert").fadeIn();
        $("#addInfo span").text("请填写用户电话号码");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (addaccount == "") {
        $(".alert").fadeIn();
        $("#addInfo span").text("请填写登录帐号");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }

    var myreg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[0-9])[0-9]{8}$/;
    if (!myreg.test(addiphone)) {
        $(".alert").fadeIn();
        $("#addInfo span").text("请输入有效的手机号码");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    if (addemail == "") {
        $(".alert").fadeIn();
        $("#addInfo span").text("请输入用户电子邮箱");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (filter.test(addemail) == false) {
        $(".alert").fadeIn();
        $("#addInfo span").text("邮箱地址信息有误");
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 1000);
        return;
    }
    $.ajax({
        type: "POST",
        url: address,
        data: {uname: addname, phone: addiphone, job: addjob, email: addemail, account: addaccount},
        success: function (data) {
            if (data[0] == 1) {         //用户名已存在
                $(".alert").fadeIn();
                $("#addInfo span").text(data[1]);
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            } else if (data[0] == 3) {
                $(".alert").fadeIn();
                $("#addInfo span").text(data[1]);
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            } else if (data[0] == 4) {
                $(".alert").fadeIn();
                $("#addInfo span").text(data[1]);
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            } else if (data[0] == 5) {
                $(".alert").fadeIn();
                $("#addInfo span").text(data[1]);
                setTimeout(function () {
                    $(".alert").fadeOut();
                }, 1000);
            } else if (data[0] == 2) {   //添加成功
                $(".bootbox-close-button").click();
                bootMessage("success", "保存成功");
                GetUserData();
                return;
            } else {                  //出现不可预测失败
                bootMessage("warning", "保存失败");
                return;
            }
        }
    });
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//批量删除操作
$("#condition_delete").click(function () {
    choice = {};
    var count = $('input[class="checkboxes"]:checked').length;
    if (!count) {
        $("#modal-info").modal({show: true});
    }
    for (var i = 0; i < count; i++) {
        choice[i] = $('input[class="checkboxes"]:checked').eq(i).val();
    }
    var url = $("#deladdr").attr("value");
    $.ajax({
        url: url,
        data: {data: choice},
        success: function (data) {
            if (data) {
                bootMessage("success", "删除成功");
            } else {
                bootMessage("warning", "删除失败");
            }
        }
    });
});

//获取删除项
var choice = {};
var num = 0;
$("#allchoice").on("click", function () {
    num = num + 1;
    if (num % 2 == 1) {
        $(".checkboxes").attr("checked", "checked");
        $(".checkboxes").prop("checked", true);
    } else {
        $(".checkboxes").removeAttr("checked");
        $(this).prop("checked", false);
    }
});
$(".checkboxes").on("click", function () {
    var check = $(this).attr("checked");
    if (check) {
        $(this).removeAttr("checked");
        $(this).prop("checked", false);
    } else {
        $(this).attr("checked", "checked");
        $(this).prop("checked", true);
    }

});
//input输入框中的搜索事件
$(".input-sm").click(function () {
    var status = $(this).focus();
    if (status) {
        $(".input-sm").attr("placeholder", "");
    }
    $(".input-sm").keydown(function (event) {
        var key = event.keyCode;
        if (key == 13) {
            var addr = $("#address").attr("value");
            var l = addr.length - 5;
            addr = addr.substring(0, l);
            var condition = $(this).val();
            condition = condition.replace(/[ ]/g, "");       //去除字符串中所有空格
            condition == "" ? condition = "" : condition = "condition/" + condition;
            window.location.href = addr + "/" + condition;
        }
    });
});