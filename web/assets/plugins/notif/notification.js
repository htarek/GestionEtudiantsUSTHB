$("body").on('click', ".close-notification", function(){
	$(this).parent().parent().remove();
});

function isMessage(datas){
	if ( datas.isMessage ){
		if (datas.success){
			var type = "success";
		} else {
			var type = "error";
		}
		$("body").prepend("<section><div class='notification "+type+"'>"+
				"<span class='title'>!&nbsp;&nbsp;&nbsp;&nbsp;Success</span>"+
            	datas.message+
            	"<span class='close-notification'>X</span>"+
        	"</div></section>");
	}
}

$(document).ready(function (){
	setTimeout(function() {
		$(".close-notification").click();
	}, 10000);
});