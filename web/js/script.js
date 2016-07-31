$(document).ready(function () {
	var src = new XMLSerializer().serializeToString(document);
	url = 'https://html5.validator.nu';
	url = 'https://checker.html5.org';
	url = 'http://validator.w3.org/nu/';
	
	var blob = new Blob([src], { type: "text/html; charset=UTF-8", ending:"transparent"});
	
	data = new FormData();
	data.append("file", blob);
	data.append("docselect", "file");
	data.append("showsource", "yes");

	$.ajax({
	  url: url,
	  type: 'POST',
	  crossDomain: true,
	  contentType: false,
	  cache: false,
	  processData: false,
	  data: data,
	  success: function(data, textStatus, xhr) {
	    //called when successful
	    var result = $(data).find('p.success, p.failure');
	    if ($(result).hasClass('success')) {
	    	text = '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
	    } else if ($(result).hasClass('failure')) {
	    	text = '<i class="fa fa-times-circle-o" aria-hidden="true"></i>';
	    } else {
	    	text = '<i class="fa fa-question-circle-o" aria-hidden="true"></i>';
	    }
	    $('#html5-report').append(text);
	  },
	  error: function(xhr, textStatus, errorThrown) {
	    //called when there is an error
	    console.error('error with ajax request');
	  }
	});	
});