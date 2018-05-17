$(function() {
    NPTheme.General();
});

var NPTheme = {};

function selectLogin(){
	$('#login-btn').addClass('active');
	$('.login-form').addClass('active');
	$('.form-tabs-underline').removeClass('active');
	$('.forms-wrapper').removeClass('active');
	$('#signup-btn').removeClass('active');
	$('.inscription-form').removeClass('active');
}

function selectSignup(){
	$('#signup-btn').addClass('active');
	$('.inscription-form').addClass('active');
	$('.form-tabs-underline').addClass('active');
	$('.forms-wrapper').addClass('active');
	$('#login-btn').removeClass('active');
	$('.login-form').removeClass('active');
}


NPTheme.General = function()
{
    $("body").on("input focus", ":input", function() {
        $(this).removeClass("error");
    });

    $("body").on("input", ".fancy-field .input", function() {
        if ($(this).val().length > 0) {
            $(this).addClass("hasvalue");
        } else {
            $(this).removeClass("hasvalue");
        }
    });

    $(".fancy-field .input").each(function(index, el) {
        $(this).trigger("input")
    });
}
