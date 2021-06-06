function actionBlock(text) 
{
	if (!$('.action-block').length) 
	{
		$('body').append('<div class="action-block flex fdc alife"></div>')
	}
	$('.action-block').append(
		'<div class="action-block-inner flex jcc">' + 
			'<p class="flex fdc jcc">' + text + '</p>' +
		'</div>')
	$('.action-block-inner').animate({width: '100%'}, 200)
	
	if ($('.action-block-inner').length <= 5) 
	{
		setTimeout(() =>
		{
			$('.action-block-inner:first').fadeOut(300)
			setTimeout(() =>
			{
				$(".action-block-inner:first").remove()
			}, 300)
		}, 4000)
	}
	else if ($('.action-block-inner').length > 5) 
	{
		$(".action-block-inner:first").remove()
	}
}