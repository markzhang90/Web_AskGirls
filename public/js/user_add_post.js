/**
 * Created by mark on 2015/7/31.
 */
$(".btn-group > .btn").click(function(){
    //alert("1");
    $(".btn-group > .btn").removeClass("active");
    $(this).addClass("active");
});
