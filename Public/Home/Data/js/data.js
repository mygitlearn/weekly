$(function () {
    var isAlert = false;
    var save_cannel_html = '<a id = "ssss" class="save_btn btn btn-success btn-xs save">' +
        '<i class="fa fa-save"></i> 保存</a>' +
        ' <a id = "sss" class="save-cal-btn btn btn-warning btn-xs cancel"><i class="fa fa-times"></i> 取消</a>';
    var edit_delete_html = '<a class="btn btn-info btn-xs edit click_edit"><i class="fa fa-edit"></i> 编辑</a> <a class="click_delete btn btn-danger btn-xs delete"><i class="fa fa-trash-o"></i> 删除</a>';

    //当弹出提示后，监听 Eeter 或者空格，使按下 Eeter 或者空格等同于单击这个按钮
    $(document.body).on('keydown', function () {
        if (isAlert && (event.keyCode == "13" || event.keyCode == "32")) {
            $('.modal-message .modal-footer .btn ').trigger('click');
            isAlert = false;
            return false;
        }
    });

    $(document.body).on('keydown', function (event) {
        if (event.keyCode == 13) {
            $('.add_btn').trigger('click');
        }
    });

    $(document).keydown(function (event) {
        if (event.keyCode == 13) {
            $('.save_btn').click();
        }
    });

    //操作成功后调用方法
    /*function success_alert() {
     $('#success_alert').trigger('click');
     isAlert = true;
     }*/

    //操作失败后调用方法
    /*function failure_alert() {
     $('#warning_alert').trigger('click');
     isAlert = true;
     }*/

    $(document.body).on('click', '.modal-message .modal-footer .btn', function () {
        window.location.reload();
    });

    //单击事件
    function click_event() {
        //单击编辑按钮
        $(document.body).on('click', '.click_edit', function () {
            var edit_pts = $(this).parent().parent('tr');
            var selfs = $(this);
            var old_data = $.trim($($(selfs).parent().parent().children()[0]).text());
            $(edit_pts.children()[0]).html('<input type="text" class="ss" value="' + old_data + '" />');
            $(edit_pts.children()[1]).html(save_cannel_html);

            $(edit_pts.children()[1]).find('.save').attr("old_data", old_data);
            $(edit_pts.children()[1]).find('.save').attr("save", false);
            $($(edit_pts.children()[0]).find('input')).change(function () {
                var that = $(this).parents('tr').find('.save');
                var new_data = $.trim($(this).val());
                var old_data = that.attr('old_data');
                if (new_data != old_data && new_data != "") {
                    that.attr("save", true);
                } else {
                    that.attr("save", false);
                }
            });
        });

        //单击删除按钮
        $(document.body).on('click', '.click_delete', function () {
            var selfs = $(this).parent().parent().attr('id');
            $("#info_delete").trigger("click");
            $("#cancle").click(function () {
                $(".close").click();
            });

            $("#ok_btnn").click(function () {
                var url = $('#url').val() + '/delete_data';
                var del_data = selfs;
                $.ajax({
                    url: url,
                    data: {
                        'id': del_data
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data.status != 0) {
                            $(".close").click();
                            bootMessage('success', '删除成功');
//                            success_alert();
//                            $('.success_modal').html('').html('删除成功');
//                            refush_table();
                        } else {
                            $(".close").click();
                            bootMessage('warning', '删除失败');
//                            failure_alert();
//                            $('.warning_modal').html('').html('删除失败');
//                            refush_table();
                        }
                    }
                });
            });
        });

        //单击新增后进行保存按钮
        $(document.body).on('click', '.add_btn', function () {
            var type_id = $('#type_id').attr('type_id');
            var tbody_id = $('tbody').children(0).attr('id');
            var id = tbody_id;
            var input_val = $($('#' + id).children()[0]).find('input').val();
            if (input_val == "") {
                bootMessage('warning', '保存失败');
//                failure_alert();
//                $('.warning_modal').html('').html('保存失败');
            } else {
                var url = $('#url').val() + '/add';
                var add_data = {
                    'name': $('input[name = "add_save"]').val(),
                    'type': type_id
                };
                $.ajax({
                    url: url,
                    data: add_data,
                    type: 'POST',
                    success: function (data) {
                        if (data.status > 0) {
                            $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
                            $($('#' + id).children()[1]).html(edit_delete_html);
                            bootMessage('success', '保存成功');
//                            success_alert();
//                            $('.success_modal').html('').html('保存成功');
                        } else if (data.status == -1) {
                            bootMessage('warning', '名称已存在');
                        } else {
                            bootMessage('warning', '保存失败');
//                            failure_alert();
//                            $('.warning_modal').html('').html('保存失败');
                        }
                    }
                });
            }
        });

        //单击取消修改按钮
        $(document.body).on('click', '.save-cal-btn', function () {
            var self = $(this);
            var id = $(self).closest('tr').attr('id');
            $($('#' + id).children()[0]).html($("#" + id).find(".save").attr("old_data"));
            $($('#' + id).children()[1]).html(edit_delete_html);
        });

        //单击取消添加按钮
        $(document.body).on('click', '.cal_btn', function () {
            $(this).parents('tr').remove();
        });

        //单击新增按钮
        $(document.body).on('click', '#add_button', function () {
            var tbody_ids = $('tbody').children().length;
            if (tbody_ids == 0) {
                $('#data_table').html('<tr role="row" id="">' +
                '<td>' +
                '<input class="add_inp" type="text" name="add_save" value="">' +
                '</td>' +
                '<td>' +
                '<a class="add_btn btn btn-success btn-xs"><i class="fa fa-edit"></i> 保存</a> ' +
                '<a class="cal_btn btn btn-warning btn-xs cancel"><i class="fa fa-times"></i> 取消</a>' +
                '</td>' +
                '<td>' +
                '<a class="btn btn-default btn-xs up_btn"><i class="fa fa-angle-double-up"></i> 上移</a>' +
                '<a class="btn btn-default btn-xs down_btn"><i class="fa fa-angle-double-down"></i> 下移</a>' +
                '</td>' +
                '</tr>');
            } else {
                var tbody_id = $('tbody').children(0).attr('id');
                $('#' + tbody_id).before('<tr role="row" id="">' +
                '<td>' +
                '<input class="add_inp" type="text" name="add_save" value="">' +
                '</td>' +
                '<td>' +
                '<a class="add_btn btn btn-success btn-xs"><i class="fa fa-edit"></i> 保存</a> ' +
                '<a class="cal_btn btn btn-warning btn-xs cancel"><i class="fa fa-times"></i> 取消</a>' +
                '</td>' +
                '<td>' +
                '<a class="btn btn-default btn-xs up_btn"><i class="fa fa-angle-double-up"></i> 上移</a>' +
                ' <a class="btn btn-default btn-xs down_btn"><i class="fa fa-angle-double-down"></i> 下移</a>' +
                '</td>' +
                '</tr>');

                //给新添加的“<tr>”添加id属性
                $('#' + tbody_id).prev().attr('id', tbody_id - (-1));

                //使新添加的 input 获得焦点
                $('.add_inp').focus();
            }
        });

        //单击编辑按钮后进行保存
        $(document.body).on('click', '.save_btn', function () {
            var tbody_id = $(this).parent().parent().attr('id');
            //获取type_id，用于在保存数据时对应到相应的type
            var type_id = $('#type_id').attr('type_id');
            var id = tbody_id;
            var input_val = $($('#' + id).children()[0]).find('input').val();
            var res = $(this).attr("save");
            if (input_val == "" || res == "false") {
                $(this).parents('tr').find(".save-cal-btn").click();
//                $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
//                $($('#' + id).children()[1]).html(edit_delete_html);
//                success_alert();
//                $('.success_modal').html('').html('保存成功');
            } else {
                var url = $('#url').val() + '/save';
                var save_data = {
                    'name': input_val,
                    'type': type_id,
                    'id': id
                };
                console.log(save_data);
                $.ajax({
                    url: url,
                    data: save_data,
                    type: 'POST',
                    success: function (data) {
                        if (data['status'] > 0) {
                            $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
                            $($('#' + id).children()[1]).html(edit_delete_html);
                            bootMessage('success', '保存成功');
//                            success_alert();
//                            $('.success_modal').html('').html('保存成功');
                        } else if (data['status'] == -1) {
                            bootMessage('warning', '名称已存在');
                        } else {
                            bootMessage('warning', '保存失败');
//                            failure_alert();
//                            $('.warning_modal').html('').html('保存失败');
                        }
                    }
                });
            }
        });

        //上移
        $(document.body).on('click', '.up_btn', function () {
            var self = $(this);
            if ($(self).closest('tr').prev('tr').attr('id')) {
                //当前行的id 和 order
                var id = $(self).closest('tr').attr('id');
                var order = $(self).closest('tr').attr('order');
                //当前行的上一行的id 和 order
                var up_id = $('#' + id).prev().attr('id');
                var up_order = $('#' + id).prev().attr('order');
                //当前行tr中HTML
                var tr_id = $('#' + id).html();
                var tr_html = '<tr role="row" class order="' + order + '" id="' + id + '">' + tr_id + '</tr>';

                $('#' + id).remove();
                $('#' + up_id).before(tr_html);

                var url = $('#url').val() + '/orderFix';
                var order_data = {
                    'id': id,
                    'order': order,
                    'up_id': up_id,
                    'up_order': up_order
                };
                console.log(order_data);
                $.ajax({
                    url: url,
                    data: order_data,
                    type: 'POST',
                    success: function (data) {
                        if (data.status != 0) {
//                        alert('上移成功');
                        } else {
//                        alert('上移失败');
                        }
                    }
                });
            } else {
                console.log('不上移');
            }
        });

        //下移
        $(document.body).on('click', '.down_btn', function () {
            var self = $(this);
            if ($(self).closest('tr').next('tr').attr('id')) {
                //当前行的id
                var id = $(self).closest('tr').attr('id');
                var order = $(self).closest('tr').attr('order');
                //当前行的下一行的id
                var down_id = $('#' + id).next().attr('id');
                var down_order = $('#' + id).next().attr('order');
                //当前行tr中HTML
                var tr_id = $('#' + id).html();
                var tr_html = '<tr role="row" class order="' + order + '" id="' + id + '">' + tr_id + '</tr>';

                $('#' + id).remove();
                $('#' + down_id).after(tr_html);

                var up_id = down_id;
                var up_order = down_order;

                var url = $('#url').val() + '/orderFix';
                var order_data = {
                    'id': id,
                    'order': order,
                    'up_id': up_id,
                    'up_order': up_order
                };
                console.log(order_data);
                $.ajax({
                    url: url,
                    data: order_data,
                    type: 'POST',
                    success: function (data) {
                        if (data.status != 0) {
//                            refush_table();
                        } else {
//                            refush_table();
                        }
                    }
                });
            } else {
                console.log('不下移');
            }
        });
    }

    click_event();

    //单击排序
    $('#name_order').on('click', function () {
        var order_num = parseInt($(this).attr('order'));
        if (0 == order_num) {
            $(this).attr('order', 1);
            $(this).removeClass('sorting').addClass('sorting_asc');
        } else {
            if (1 == order_num) {
                $(this).removeClass('sorting_asc').addClass('sorting_desc');
            } else {
                $(this).removeClass('sorting_desc').addClass('sorting_asc');
            }
            $(this).attr('order', 0 - order_num);
        }
        refush_table();
    });
    //表格刷新
    function refush_table() {
        $('.pagination > li.active > a').trigger('click');
        return true;
    }

    //分页
    var $prev = $('.pagination > .prev');
    var $next = $('.pagination > .next');
    $prev.next().addClass('active');

    var page_obj = {
        page: 1,
        html: '',
        now: 1,
        param: '',
        init: function () {
            this.html = '';
        },
        createP: function (all_page, now_page, param) {
            this.page = parseInt(all_page);
            this.now = parseInt(now_page);
            this.param = param || '';
            this.init();
            this.createPrev();
            this.createPage();
            this.createNext();
            return page_obj.html;
        },
        createPrev: function () {
            if (this.now > 1) {
                this.html += '<li class = "prev"><a href="#" param = "' + this.param + '" page = "' + (this.now - 1) + '" class = "page">上一页</a></li>'
            } else {
                this.html += '<li class = "prev disabled"><a>上一页</a></li>'
            }
        },
        createNext: function () {
            if (this.now < this.page) {
                this.html += '<li class = "next"><a href="#" param = "' + this.param + '" page = "' + (this.now + 1) + '"  class = "page">下一页</a></li>'
            } else {
                this.html += '<li class = "next disabled"><a>下一页</a></li>'
            }
        },
        createPage: function () {
            for (var i = 1; i <= this.page; i++) {
                if (this.now == i) {
                    this.html += '<li class = "active"><a href="#" param = "' + this.param + '" class = "page" page = "' + i + '">' + i + '</a></li>';
                } else {
                    this.html += '<li><a href="#" param = "' + this.param + '" class = "page" page = "' + i + '">' + i + '</a></li>';
                }

            }
        }
    };

    var page_click = function (param) {
        var p = 1;
        var where = '';
        var now_count = $('#simpledatatable_length > select').val();
        if ('undefined' == typeof param || 'object' == typeof param) {
            p = $(this).attr('page');
            where = $(this).attr('param');
        } else {
            where = param;
        }

        $.ajax({
            url: $('.pagination').attr('_href'),
            data: {
                p: p,
                where: where,
                order: $('#name_order').attr('order') || 0,
                type: $('#data_table').attr('_type'),
                count: now_count
            },
            type: 'POST',
            success: function (result) {
                if (result.result == null) {
                    failure_alert();
                    $('.warning_modal').html('').html('没有此条数据');
                } else {
                    var all_count = result.all_count;
                    var return_count = result.result.length;
                    var begin_num = (p - 1) * now_count + 1;
                    if (return_count == now_count) {
                        $('#table_intro').text('从 ' + begin_num + ' 到 ' + p * now_count + ' 条记录，总记录数为 ' + all_count + ' 条');
                    } else {
                        var over_num = (p - 1) * now_count + return_count;
                        $('#table_intro').text('从 ' + begin_num + ' 到 ' + over_num + ' 条记录，总记录数为 ' + all_count + ' 条');
                    }

                    var all_page = result.all_page;
                    result = result.result;
                    var data = [];
                    for (var item in result) {
                        data.push(result[item]);
                    }
                    $('#data_table').html('');
                    $('#data_table_temp').tmpl(data).appendTo('#data_table');

                    $('.pagination').html(page_obj.createP(all_page, p, where));
                    $('a.page').on('click', page_click);
                }

            }
        });
        return false;
    };

    $('a.page').on('click', page_click);

    //按下Enter键进行搜索搜索
    $('#search').keydown(function () {
        if (event.keyCode == "13") {
            page_click($(this).val());
            $('#search').blur();
        }
    });

    $('#simpledatatable_length > select').on('change', function () {
        refush_table();
    });

});