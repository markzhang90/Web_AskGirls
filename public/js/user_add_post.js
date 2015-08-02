/**
 * Created by mark on 2015/7/31.
 */
$(document).ready(function () {

    $(".btn-group > .btn").click(function(){
        //alert("1");
        $(".btn-group > .btn").removeClass("active");
        $(this).addClass("active");
        var velue = $(this).val();
        $("#bt-choice").val(velue);
    });

    $('#submit').click(function (event) {
        $("#message_field").empty();

        if ($('#title').val().length < 10) {
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
                " <a class=\"panel-close close\" data-dismiss=\"alert\">×</a> " +
                "<i class=\"fa fa-coffee\"></i> <strong>Title should at least contain 10 words</strong></div>");
            event.preventDefault();
        }

        if ($('#title').val().length > 50) {
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
                " <a class=\"panel-close close\" data-dismiss=\"alert\">×</a> " +
                "<i class=\"fa fa-coffee\"></i> <strong>Title is limited Under 50 words</strong></div>");
            event.preventDefault();
        }

        if ($('#content').val().length < 30) {
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
                " <a class=\"panel-close close\" data-dismiss=\"alert\">×</a> " +
                "<i class=\"fa fa-coffee\"></i> <strong>Content should at least contain 30 words</strong></div>");
            event.preventDefault();
        }

    });
});

