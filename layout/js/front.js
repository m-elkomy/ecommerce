$(function(){
    //fire selectboxit
    $("select").selectBoxIt({
        autoWidth:false
    });
	//initalize placeholder on focus
	$('[placeholder]').focus(function(){
		$(this).attr('data-text',$(this).attr('placeholder'));
		$(this).attr('placeholder','');
	}).blur(function(){
		$(this).attr('placeholder',$(this).attr('data-text'));
	});

	//adding asterisk after required input
	$('input').each(function(){
		if($(this).attr('required')==='required'){
			$(this).after('<span class="asterisk">*</span>');
		}
	});

	//switch between login and signup
	$('.login-page h1 span').click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		$('.login-page form').hide();
		$('.'+$(this).data('class')).fadeIn(200);
	});

	$('.live').keyup(function(){
		$($(this).data('class')).text($(this).val());
	});
	//live preview of image
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#live-avatar').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function() {
        readURL(this);
    });

    //confirm message before delete
    $('.confirm').click(function(){
        return confirm('Are You Sure ?');
    });




});