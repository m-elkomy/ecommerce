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

	//showing password on hover on eye icon
	var password = $('.password');
	$('.show-pass').hover(function(){
		password.attr('type','text');
	},function(){
		password.attr('type','password');
	});
	//confirm message before delete
	$('.confirm').click(function(){
		return confirm('Are You Sure ?');
	});
	//view option
	$('.category .cats h3').click(function(){
		$(this).next('.full-view').fadeToggle(200);
	});
	$('.category .ordering span').click(function(){
		$(this).addClass('active').siblings('span').removeClass('active');
		if($(this).data('view')==='full'){
			$('.full-view').fadeIn(200);
		}else{
			$('.full-view').fadeOut(200);
		}
	});
	//toggle hide and show panel 
	$('.toggle-info').click(function(){
		$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(200);
		if($(this).hasClass('selected')){
			$(this).html('<i class="fa fa-plus"></i>');
		}else{
			$(this).html('<i class="fa fa-minus"></i>');
		}
	});
	//show delte button on child cats
	$('.child-link').hover(function(){
		$(this).find('.show-delete').fadeIn(200);
	},function(){
        $(this).find('.show-delete').fadeOut(200);
	});

});