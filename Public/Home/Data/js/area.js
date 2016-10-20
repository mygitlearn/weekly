$(function () {
    var isAlert = false;
    var save_cannel_html = '<a id = "ssss" class="btn btn-success btn-xs save">' +
        '<i class="fa fa-save"></i> Save</a>' +
        ' <a id = "sss" class="save-cal-btn btn btn-warning btn-xs cancel"><i class="fa fa-times"></i> Cancel</a>';
//    save_cannel_html = '<a class="btn btn-success btn-xs save" data-toggle="modal" data-target="#modal-success">' +
//        '<i class="fa fa-save"></i> Save</a>' +
//        ' <a class="save-cal-btn btn btn-warning btn-xs cancel" data-toggle="modal" data-target="#modal-warning"><i class="fa fa-times"></i> Cancel</a>';
    var edit_delete_html = '<a class="btn btn-info btn-xs edit click_edit"><i class="fa fa-edit"></i> Edit</a> <a class="click_delete btn btn-danger btn-xs delete"><i class="fa fa-trash-o"></i> Delete</a>';
    $(document.body).on('keydown', function () {
        if (isAlert  && (event.keyCode == "13" || event.keyCode == "32")) {
            $('.modal-message .modal-footer .btn ').trigger('click');
            isAlert = false;
            return false;
        }

    });
    //成功后调用方法
    function success_alert() {
        $('#success_alert').trigger('click');
        isAlert = true;
    }

    //失败后调用方法
    function failure_alert() {
        $('#warning_alert').trigger('click');
        isAlert = true;
    }

    //单击事件
    function click_event () {
        //单击编辑按钮
        $(document.body).on('click', '.click_edit', function () {
//            success_alert();
            var self = event.target;
            var edit_pt = $(self).parent().parent('tr');
            var old_data = $.trim($($(self).parent().parent().children()[0]).text());
            $(edit_pt.children()[0]).html('<input type="text" class="ss" value="'+old_data+'" />');
            $(edit_pt.children()[1]).html(save_cannel_html);

            $(edit_pt.children()[1]).find('.save').attr("old_data",old_data);
            $($(edit_pt.children()[0]).find('input')).change(function(){
                var that = $(this).parents('tr').find('.save');
                var new_data = $(this).val();
                var old_data = that.attr('old_data');
                if(new_data != old_data){
                    that.attr("save",true);
                }else{
                    that.attr("save",false);
                }
            });
        });

        //单击删除按钮
        $(document.body).on('click', '.click_delete', function () {
            var self = event.target;
            var id = $(self).parent().parent().attr('id');
            console.log(id);
            var url = $('#url').val() + '/delete';
            var del_data = id;
            $.ajax({
                url : url,
                data : {
                    'id' : del_data
                } ,
                type : 'POST',
                success : function(data){
                    if(data.status != 0){
                        success_alert();
                        refush_table();
                    }else{
                        failure_alert();
                        refush_table();
                    }
                }
            });
        });

        //单击保存按钮
        $(document.body).on('click', '.add_btn', function () {
            var tbody_id = $('tbody').children(0).attr('id');
            var id = tbody_id;
            var input_val = $($('#' + id).children()[0]).find('input').val();
//        $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
//        $($('#' + id).children()[1]).html(edit_delete_html);
//        var input_val = $($('#' + id).children()[0]).find('input').val();
            console.log(id);
            console.log(tbody_id);
            console.log(input_val);
            if(input_val == "") {
//            alert("为空");
//            $('#' + tbody_id).before("");
                failure_alert();
            }else {
//            alert("不为空");
//            return;
                var url = $('#url').val() + '/add';
                var add_data = {
                    'name' : $('input[name = "add_save"]').val(),
                    'type' : 1
                };
                $.ajax({
                    url : url,
                    data : add_data ,
                    type : 'POST',
                    success : function(data){
                        if(data.status != 0){
                            $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
                            $($('#' + id).children()[1]).html(edit_delete_html);
                            success_alert();
                            refush_table();
                        }else{
                            failure_alert();
                        }
                    }
                });
            }
        });

        //单击取消修改按钮
        $(document.body).on('click', '.save-cal-btn', function(){
            var self = event.target;
            var id = $(self).closest('tr').attr('id');
            $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
            $($('#' + id).children()[1]).html(edit_delete_html);
        });

        //单击取消添加按钮
        $(document.body).on('click', '.cal_btn', function () {
            $(this).parents('tr').remove();
        });

        //单击新增按钮
        $(document.body).on('click', '#add_button', function () {
//        $(".alert").toggle();
            var tbody_id = $('tbody').children(0).attr('id');
            $('#'+ tbody_id).before('<tr role="row" id="">' +
                '<td>' +
                '<input type="text" name="add_save" value="">' +
                '</td>' +
                '<td>' +
                '<a class="add_btn btn btn-success btn-xs"><i class="fa fa-edit"></i> Save</a> ' +
                '<a class="cal_btn btn btn-warning btn-xs cancel"><i class="fa fa-times"></i> Cancel</a>' +
                '</td>' +
                '</tr>');

            //给新添加的“<tr>”添加id属性
            $('#' + tbody_id).prev().attr('id', tbody_id - (-1));

        });

        //单击编辑按钮后进行保存
        $(document).on('click','.save',function(){
            var tbody_id = $('tbody').children(0).attr('id');
            var id = tbody_id;
            var input_val = $($('#' + id).children()[0]).find('input').val();
            var res = $(this).attr("save");

            if(input_val == "" || res == 'false' || !res) {
                failure_alert();
                refush_table();
            }else{
//                alert('ok');
                var url = $('#url').val() + '/save';
                var save_data = {
                    'name' : input_val,
                    'type' : 1,
                    'id'   :id
                };
                $.ajax({
                    url : url,
                    data : save_data ,
                    type : 'POST',
                    success : function(data){
                        if(data['status'] != 0){
                            $($('#' + id).children()[0]).html($($('#' + id).children()[0]).children('input').val());
                            $($('#' + id).children()[1]).html(edit_delete_html);
                            success_alert();
                            refush_table();
                        }else{
                            failure_alert();
                            refush_table();
                        }
                    }
                });
            }
        })
    }
    click_event();

    //单击排序
    $('#name_order').on('click', function () {
        var a = parseInt($(this).attr('order'));
        console.log(a);
        if (0 == a) {
            $(this).attr('order', 1);
            $(this).removeClass('sorting').addClass('sorting_asc');
        } else {
            if (1 == a) {
                $(this).removeClass('sorting_asc').addClass('sorting_desc');
            } else {
                $(this).removeClass('sorting_desc').addClass('sorting_asc');
            }
            $(this).attr('order', 0 - a);
        }
        refush_table();
    });
    //表格刷新
    function refush_table () {
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
                this.html += '<li class = "prev"><a href="#" param = "'+this.param+'" page = "' + (this.now - 1) + '" class = "page">Prev</a></li>'
            } else {
                this.html += '<li class = "prev disabled"><a>Prev</a></li>'
            }
        },
        createNext: function () {
            if (this.now < this.page) {
                this.html += '<li class = "next"><a href="#" param = "'+this.param+'" page = "' + (this.now + 1) + '"  class = "page">Next</a></li>'
            } else {
                this.html += '<li class = "next disabled"><a>Next</a></li>'
            }
        },
        createPage: function () {
            for (var i = 1; i <= this.page; i++) {
                if (this.now == i) {
                    this.html += '<li class = "active"><a href="#" param = "'+this.param+'" class = "page" page = "'+i+'">'+i+'</a></li>';
                } else {
                    this.html += '<li><a href="#" param = "'+this.param+'" class = "page" page = "'+i+'">'+i+'</a></li>';
                }

            }
        }
    };

    var page_click = function (param) {
        var p = 1;
        var where = '';
        var now_count = $('#simpledatatable_length > select').val();
        if ('undefined' == typeof param || 'object' == typeof param) {
            p =  $(this).attr('page');
            where = $(this).attr('param');
        } else {
            where = param;
        }

        $.ajax({
            url: $('.pagination').attr('_href'),
            data: {
                p: p,
                where: where,
                order : $('#name_order').attr('order') || 0,
                type: $('#data_table').attr('_type'),
                count: now_count
            },
            type: 'POST',
            success: function (result) {
                var all_count = result.all_count;
//                $('#table_intro').text('共'+all_count+'条');
                var return_count = result.result.length;
                var begin_num = (p-1)*now_count+1;
                if(return_count==now_count){
                    $('#table_intro').text('Showing '+ begin_num +' to '+ p*now_count +' of '+all_count+' entries');
                }else{
                    var over_num = (p-1)*now_count+return_count;
                    $('#table_intro').text('Showing '+ begin_num +' to '+ over_num +' of '+all_count+' entries');
                }


                var all_page = result.all_page;
                result = result.result;
                var data = [];
                for(var item in result) {
                    data.push(result[item]);
                }
                $('#data_table').html('');
                $('#data_table_temp').tmpl(data).appendTo('#data_table');

                $('.pagination').html(page_obj.createP(all_page, p, where));
                $('a.page').on('click', page_click);
            }
        });
        return false;
    };

    $('a.page').on('click', page_click);

    //按下Enter键进行搜索搜索
    $('#search').keydown(function() {
        if (event.keyCode == "13") {
            page_click($(this).val());
        }
    });

//    $(document.body).keydown(function() {
//        console.log(event.keyCode);
//        if (event.keyCode == "13" || event.keyCode == "32") {
//            $('.btn-success').trigger('click');
//            $('.btn-warning').trigger('click');
//            return false;
//        }
//    });

//    $(document.body).keydown(function() {
//        if (event.keyCode == "13" || event.keyCode == "32") {
//            $('.btn-warning').click();
//        }
//    });

    $('#simpledatatable_length > select').on('change', function () {
        refush_table();
    });

});