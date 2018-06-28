
$(document).ready(function() {
	
	//var listItems = "<option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option>";
	//$(".videoFormats").append(listItems);
	var chosenVideoFormat = "";
	var playlistId = "";
	var videoUrl = "";
	var videoId = "";
	var source = "";
	var videoTitle = "";
	var videoDescription = "";
	var formatToUrl = new Array();
	
	$('.container2').hide();
	$('.container3').hide();
	
	$('#responseText').hide();
	$('#showHideConsole').click(function (e){
		$('#responseText').toggle();
	  $(this).text($('#responseText').is(':visible') ? 'Hide Console' : 'Show Console');
	});

	$('#videoFormatGenerator').click(function() {
     videoUrl = $('#url').val();
     if(isValidHotstarUrl(videoUrl)){
       $.post("getAvailableVideoFormats.php",{url: $('#url').val()}, function(data,status,xhr){
       	
       	  //Response received from server. Dismiss the loader dialog
       	  stopLoading();
     	     
     	     var stringifiedData = JSON.stringify(data);
          //alert("status : "+status+"\n\ndata : "+stringifiedData+"\n\nxhr : "+xhr);
          //console.log("status : "+status+"\n\ndata : "+data+"\n\nxhr : "+xhr);

          /*
          $.each(data, function(k, v){ 
             console.log(k + ' = ' + v ); // k is key/index and v is value
          });
          */
          
          if(data["status"] === "true"){
          	   //alert("video exist");
          	   
          	   //show success popup and dismiss after 2 seconds
          	   showSuccessDialog("Located video in the playlist for the given URL");
          	   
          	   //Hide container and show container2 only on availability of video formats
       	      $('.container').hide();
       	      $('.container2').show();
			  
			  source = data['source'];
			  
			  //Check if source is ydl or api
			  if(source === "ydl"){
				
				var videoFormats = "";
				   playlistId = data["playlistId"];
				   videoId = data["videoId"];
				   $.each(data, function(k, v){ 
					 if(isValidFormat(k)){
						   //add the maching keys to the dropDown
						   videoFormats += "<option value='"+k+"'>"+k+" "+v+"</option>";
					 }
				  });
				 
			  }else{
				  
				  playlistId = data['episodeNumber'];
				  videoId = data["videoId"];
				  videoTitle = data["title"];
				  videoDescription = data["description"];
				  $.each(data, function(k, v){ 
					 if(isValidFormat(k)){
						   videoFormats += "<option value='"+k+"'>"+k+"</option>";
						   formatToUrl[k] = v;
					 }
				  });
				  
			  }
			  
			   $(".videoFormats").append(videoFormats);
          	   
          	   
          }else{
          	   //alert("no video found");
          	   showErrorDialog("Video not found in the playlist");
          }
          
        }, "json");
        
        //show loading dialog after post request
        showLoading();
        
      }else{
     	   showErrorDialog("Invalid URL. Must start with any one of below,\nhttp://www.hotstar.com (or)\nhttps://www.hotstar.com (or)\nhttp://hotstar.com (or)\nhttps://hotstar.com (or)\nwww.hotstar.com (or)\nhotstar.com");
      }
	});
	
	$(".videoFormats").change(function (){
		  //Remove the defaultOption in the videoFormats dropDown
		  $(".defaultOption").remove();
		  
		  chosenVideoFormat = $(".videoFormats option:selected").val();
		  
		  //check if source is ydl or api
		  if(source === "api"){
			  videoUrl = formatToUrl[chosenVideoFormat];
		  }
		  
	});
	
	$('#downloadVideo').click(function () {
		   //alert("Chosen video format : "+chosenVideoFormat+"\nPlaylist id : "+playlistId+"\nVideo id : "+videoId+"\nVideo URL : "+videoUrl);
		   
		   $('.container2').hide();
		   $('.container3').show();
		   
		   $.getJSON("https://api.ipify.org/?format=json", function(e) { 
       
       var ipAddr_userAgent = e.ip + "_" + navigator.userAgent;
       ipAddr_userAgent = ipAddr_userAgent.replace(/(\r\n\t|\n|\r\t)/gm,"");
	   
	   //alert("videoUrl = "+videoUrl+"\n\nplaylistId = "+playlistId+"\n\nvideoId = "+videoId+"\n\nchosenVideoFormat = "+chosenVideoFormat);
       
	   
	   if(source === "ydl"){
		   sendPostRequest(ipAddr_userAgent, videoUrl, playlistId, videoId, chosenVideoFormat);
	   }else{
		   sendPostRequest(ipAddr_userAgent, videoUrl, playlistId, videoId);
	   }
	   
       
    });
    
	});
	
	function sendPostRequest(ipAddr_userAgent, url, pId, vId){
		
		$.ajax({ 
		   url: "generateVideo.php", 
		   type: "POST", 
	    	data: {
			  src: source,
		      videoUrl: url,
		      playlistId: pId,
		      videoId: vId,
			  title: videoTitle,
			  description: videoDescription,
        uniqueId: ipAddr_userAgent,
		   },
		})
		.done(function() {
			console.log("POST request completed successfully");
		})
		.fail(function() {
			console.error("Error occured in POST request completion");
		});
		
	}
	
	function sendPostRequest(ipAddr_userAgent, url, pId, vId, vFormat){
		
		$.ajax({ 
		   url: "generateVideo.php", 
		   type: "POST", 
	    	data: {
			  src: source,
		      videoUrl: url,
		      playlistId: pId,
		      videoId: vId,
		      videoFormat: vFormat,
        uniqueId: ipAddr_userAgent,
		   },
		})
		.done(function() {
			console.log("POST request completed successfully");
		})
		.fail(function() {
			console.error("Error occured in POST request completion");
		});
		
	}
 
	
	function isValidHotstarUrl(url){
		   return url.match("^(http(s)?\:\/\/)?(www\.)?hotstar\.com");
	}
	
	function isValidFormat(key){
		   return key.match("^hls");
	}
	
	function showSuccessDialog(successMessage){
		 swal({
		 	  type: 'success',
		 	  title: successMessage,
		 	  showConfirmButton: false,
		 	  timer: 2000, //dismiss after 2 seconds
		 });
	}
	
	function showErrorDialog(errorMessage){
		swal({
			type: 'error',
			title: 'Error in fetching the video format',
			text: errorMessage,
			footer: 'Try again with valid video URL',
		});
	}
	
	function showLoading(){
		swal({
			title: 'Fetching available video formats',
			allowOutsideClick: () => false,
			onOpen: () => {
				   swal.showLoading();
			}
		});
	}
	
	function stopLoading(){
		swal.close();
	}

});