/**
 * Created by mark on 15-5-23.
 */

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();

    $('#submit').click(function (event) {
        $("#message_field").empty();
        var data = $('#user_passwd').val();
        var len = data.length;

        if ($('#user_nickname').val().length < 1) {
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
            " <a class=\"panel-close close\" data-dismiss=\"alert\">×</a> " +
            "<i class=\"fa fa-coffee\"></i> <strong>Nickname</strong>  not not be NONE. </div>");
            event.preventDefault();
        }




        if (data != $('#confirm_pw').val()) {
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
            " <a class=\"panel-close close\" data-dismiss=\"alert\">×</a> " +
            "<i class=\"fa fa-coffee\"></i> <strong>Password</strong> does not not be MATCH. </div>");
            event.preventDefault();
        }

        if (/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,12}$/.test(data)){
        } else{
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
            " <a class=\"panel-close close\" data-dismiss=\"alert\">×</a> " +
            "<i class=\"fa fa-coffee\"></i> <strong>Password</strong>  at least contain one letter and at least 6 digits </div>");
            event.preventDefault();
        }

    });

});


