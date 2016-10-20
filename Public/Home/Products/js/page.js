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
    /*var now_count = $('#simpledatatable_length > select').val();*/
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