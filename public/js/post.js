/**
 * Created by mark on 2015/8/1.
 */
$(document).ready(function () {

    $('#submit').click(function (event) {

        if ($('#comment').val().length < 1) {
            $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
                " <a class=\"panel-close close\" data-dismiss=\"alert\">¡Á</a> " +
                "<i class=\"fa fa-coffee\"></i> <strong>Nickname</strong>  not not be NONE. </div>");
            event.preventDefault();
        }

    });

});
