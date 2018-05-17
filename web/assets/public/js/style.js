function openMenu(){
	$(".navigation").addClass('active');
    $("#page-content").addClass('active');
}

function closeMenu(){
	$(".navigation").removeClass('active');
    $("#page-content").removeClass('active');
}

function menu(){
	if ($(".navigation").hasClass('active')) {
		closeMenu();
	}
	else{
		openMenu();
	}
}


$(function() {
    NPTheme.General();
});

var NPTheme = {};

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