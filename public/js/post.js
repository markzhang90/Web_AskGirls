/**
 * Created by mark on 2015/8/1.
 */


$(document).ready(function () {
    alert("cool");
    $('#pagination').twbsPagination({
        totalPages: 10,
        visiblePages: 5,
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

});
