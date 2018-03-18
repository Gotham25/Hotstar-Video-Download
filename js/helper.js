//helper js file

$(document).ready(function(){
	$("#downloadVideo").click(function(){
		
		$(this).hide();
		var videoUrl = $('#urlText').val();
		
		$.ajax({ 
		url: "downloadVideo.php", 
		type: "POST", 
		data: {
		   action: 'downloadVideo',
		   url: $('#urlText').val(),
		}, 
		success: function(result, status, xhr) { 

		           alert("Download link generated. Click to download");
		           
		           var downloadLinkTag = '<a href="download.php">Click Here</a> to download';  
		           
		           $("#downloadLink").html(downloadLinkTag);
		         },
		error: function(xhr, status, error) { 
		           alert(status+" "+error+" Please refresh the page and try again");
		         },
		});
		
	});

});