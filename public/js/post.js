/**
 * Created by mark on 2015/8/1.
 */
$(document).ready(function () {

    $('#submit').click(function (event) {
        $.ajax({
            type: "POST",
            url: "http://localhost/Web_AskGirls/public/check_session",
            dataType:"json",
            cache: false,
            data: $("#comment-form").serialize(),
            success:function (data) {
                //$( "#comment-form" ).submit();
                if(data['key'] == 0){
                    alert(data);
                }else{
                    event.preventDefault();
                }

            }
        });

        //if ($('#comment').val().length < 1) {
        //    $("#message_field").append("<div class=\"alert alert-warning alert-dismissable\">" +
        //        " <a class=\"panel-close close\" data-dismiss=\"alert\">¡Á</a> " +
        //        "<i class=\"fa fa-coffee\"></i> <strong>Nickname</strong>  not not be NONE. </div>");
        //    event.preventDefault();
        //}

    });

});
