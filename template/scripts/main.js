$(document).ready(function() {
	const up = $('.next-up')
	const down = $('.next-down')
	const next = $('.next')

	const currentUp = up.css('margin-top')
	const currentDown = down.css('margin-top')

	const posUp = up.offset().top
	const posDown = up.offset().top

	function anim() 
	{
		const speed = 500
		up.animate({ top : (parseInt(currentUp) + 20) + 'px', opacity : 1 }, speed + 400, function() 
		{
			down.removeClass('hidden')
			down.css({ opacity : 1 })
			down.animate({ top : (parseInt(currentDown) + 20) + 'px', opacity : 0}, speed - 200, function() 
			{ 
				down.addClass('hidden')
				up.css({ opacity : 0 });
				up.offset({ top : parseInt(posUp) })
				down.offset({ top : parseInt(posDown) })
				anim() 
			})
		})
	}
	anim()
});