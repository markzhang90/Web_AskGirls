/**
 * Created by mark on 2015/8/2.
 */
var curPage = 1; //current page
var total,pageSize,totalPage; //total page

function getList(pageNum){
    page = pageNum -1;
    var json = '{ "pageNum":"' + page + '"}';
    var post_id =
//alert(json);
    $.ajax({
        type: 'POST',
        url: 'http://localhost/Web_AskGirls/public/load_comments',
        contentType: 'application/json; charset=utf-8',
        data: json,
        dataType: 'json',
        cache: false,
        beforeSend:function(){
//        $("#span_middle").append("<h2 id='loading'>loading...</h2>");//œ‘ æº”‘ÿ∂Øª≠
        },
        success: function (result) {
            removeList();
            changeListContent(result, pageNum);
        },
        complete: function(){
            getPageBar();
        },
        error: AjaxFailed
    });
}

function AjaxFailed(result) {
    alert(result.status + ' ' + result.statusText);
}


function removeList() {
    var myNode = document.getElementById("mid-post-area");
    while (myNode.firstChild) {
        myNode.removeChild(myNode.firstChild);
    }
}


function changeListContent(json, pageNum){
    total = json.total;
    pageSize = json.pageSize;
    curPage = pageNum;
    totalPage = json.totalPage;

    var one_blog = "";
    var lists = json.list
    for (var i = 0; i < lists.length; i++) {
        one_blog += "<div id = \"span_middle\" ></div><div class=\"blog\"><div class=\"blog-info\">" +
            "<div class=\"blog-top\"><img src=\""+ lists[i]["icon_image"] +"\" alt=\"\" />" +
            "<h4>"+lists[i]["user_nickname"]+"</h4>" +
            "<label>"+lists[i]["ct"]+" Comments</label></div>" +
            "<div class=\"blog-bot\"><p>"+lists[i]["title"]+"</p></div></div></div>"
    }

    document.getElementById("mid-post-area").innerHTML = one_blog;
}
