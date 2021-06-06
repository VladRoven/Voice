$(document).ready(function() {
	let time = 10

	$('#interview-complete')[0].textContent = time
	$('footer').css('margin-top', '70px');
	
	setInterval(() =>
	{
		time = time - 1
		if (time === 0) 
		{
			window.location.href = '/cabinet'
		}
		else
		{
			$('#interview-complete')[0].textContent = time
		}

	}, 1000)
});