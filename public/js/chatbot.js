$( document ).ready(function() {
    $(".btn-save").click(function(event){
      event.preventDefault();

      let message = $("input[name=message]").val();     
      let _token   = $('meta[name="csrf-token"]').attr('content');
	  if(message.length>1){
		  $(".message-content").append("<p>"+message+"</p>");
		  $(".message-content").append("<p class='ajax-message' style='color:#808080;'>writing..</p>");
		  $.ajax({
			url: "/ajax-request",
			type:"POST",
			data:{
			  message:message,
			  _token: _token
			},
			success:function(response){			 
			  if(response) {   
				$(".ajax-message").remove();
				$(".message-content").append(response);
				$("#ajaxform")[0].reset();
			  }
			},
		   });
	  }
  });
});