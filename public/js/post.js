/**
 * Created by mark on 2015/8/1.
 */

var total, post_id, totalPage; //total page
var pageSize = 10;

$(document).ready(function () {
    post_id = $("#pid").val();
    getList(1);
    Pagination();

});


function Pagination(){
    var json = '{ "pid":"' + post_id + '", "pageSize":"' + pageSize + '"}';
    $.ajax({
        type: 'POST',
        url: 'http://localhost/Web_AskGirls/public/get_total_page',
        contentType: 'application/json; charset=utf-8',
        data: json,
        cache: false,
        beforeSend:function(){
            //alert(json);
        },
        success: function (result) {
            totalPage = result;
            $('#pagination').twbsPagination({
                        totalPages: totalPage,
                        visiblePages: 5,
                        onPageClick: function (event, page) {
                            $('#page-content').text('Page ' + page);
                            getList(page);
                        }
                    });
        },
        complete: function(){
        },
        error: AjaxFailed
    });
}
function getList(pageNum){

    page = pageNum -1;
    var json = '{ "pageNum":"' + page + '", "pageSize":"'+pageSize+'", "pid":"' + post_id + '"}';

        $.ajax({
            type: 'POST',
            url: 'http://localhost/Web_AskGirls/public/load_comments',
            contentType: 'application/json; charset=utf-8',
            data: json,
            cache: false,
            beforeSend:function(){
                //alert(json);
            },
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                //alert(result);
                if(obj[0].user_nickname){
                    $('#comments-area').empty();
                    $.each(obj, function(index) {
                        $('#comments-area').append('<div class="media"> ' +
                            '<a class="pull-left" href="#">' +
                            '<img class="media-object img-circle fixed-user_avatar profile-avatar" src="'+ obj[index].icon_image+ '" alt=""> </a> ' +
                            '<div class="media-body fixed-left">' +
                            ' <h4 class="media-heading">'+obj[index].user_nickname+'   <small>     '+obj[index].time+'</small> ' +
                            '</h4>' +
                            '<blockquote>' +
                        '<div id="media-body-content">'+nl2br(obj[index].content)+'</div></blockquote></div></div>' +
                        '<hr>'
                        );
                    });
                    $('html, body').animate({
                        scrollTop: $("#add-comment").offset().top
                    }, 1000);
                }else{
                        $('#comments-area').append('<p class="lead">No Comments</p>');
                    }
            },
            complete: function(){
            },
            error: AjaxFailed
        });
}

function AjaxFailed(result) {
    alert(result.status + ' ' + result.statusText);
}

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}