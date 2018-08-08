$(function() {
	//----- OPEN
	$('[data-popup-open]').on('click', function(e) {
		var targeted_popup_class = jQuery(this).attr('data-popup-open');
		$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

        e.preventDefault();

        Webcam.set({
			width: 480,
			height: 480,
			image_format: 'png',
			jpeg_quality: 90
		});
		Webcam.attach( '#my_camera' );
	});

	//----- CLOSE
	$('[data-popup-close]').on('click', function(e) {
		var targeted_popup_class = jQuery(this).attr('data-popup-close');
		$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

        e.preventDefault();

	});
});
    

    



    
		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
                // display results in page
				document.getElementById('results').innerHTML =
                    '<img style="width:80px; height:80px" src="'+data_uri+'"/>'+
                    '<input type="hidden" value="'+data_uri+'" name="check_in"/>'

            } );
            // $("#my_camera").remove();
		}
	

  
        window.onload = function(){
    //Check File API support
    if(window.File && window.FileList && window.FileReader)
    {
        $('#files').on("change", function(event) {
            var files = event.target.files; //FileList object
            var output = document.getElementById("result");
            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];
                //Only pics
                // if(!file.type.match('image'))
                if(file.type.match('image.*')){
                    if(this.files[0].size < 2097152){
                  // continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load",function(event){
                        var picFile = event.target;
                        var span = document.createElement("span");
                        span.innerHTML = "<img class='thumbnail1' style='width:250px; height:250px' src='" + picFile.result + "'" +
                                "title='preview image'/>";
                        output.insertBefore(span,null);
                    });
                    //Read the image
                    $('#clear, #result').show();
                    picReader.readAsDataURL(file);
                    }else{
                        alert("Image Size is too big. Minimum size is 2MB.");

                    }
                }else{
                alert("You can only upload image file.");
                
            }
            }

        });
    }
}

   $('#files').on("click", function() {
        $('.thumbnail1').parent().remove();
        $('#result').hide();
        $(this).val("");
    });

    $('#clear').on("click", function() {
        $('.thumbnail1').parent().remove();
        $('#result').hide();
        $('#files').val("");
        $(this).hide();
    });
