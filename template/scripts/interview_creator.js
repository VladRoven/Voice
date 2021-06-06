let offset = 0
let arrayOpen = []

const buttonStatus = $('#interview-button-status')
const buttonDel = $('#interview-button-del')
const buttonReport = $('#interview-button-report')

const animNextAfterShow = () =>
{
	$('.next').children('.next-inner').children().stop()
	$('.next').children('.next-inner').children().removeAttr('style')
	setTimeout(() =>
	{
		$('.next').children('.next-inner').each(function() 
		{
			animNext($(this))
		})
	}, 410)
}

const showNext = (elem, index) =>
{
	const parent = elem.parent().parent()
	offset = parent.children('.statistic-text-field').length
	console.log(arrayOpen)

	$.ajax(
	{
		url: '/show_open',
		type: 'POST',
		data:
		{
			id: window.location.search.substr(4),
			question: arrayOpen[index],
			offset: offset
		},
		success: response => 
		{
			if (response.indexOf('next') > 0) 
			{
				parent.children('.next').remove()
				parent.append(response)
				parent.children('.statistic-text-field').slideDown('400')
				parent.children('.next').children('.next-inner').each(function(index, el) 
				{
					$(this).on('click', function()
					{
						showNext($(this), index)
					})
				})
				animNextAfterShow()
			}
			else 
			{
				parent.append(response)
				parent.children('.statistic-text-field').slideDown('400')
				parent.children('.next').remove()
				animNextAfterShow()
			}
		}
	})
}

const showWinInform = () =>
{
	$('.win-inform').removeClass('hidden-voice')
	$('html').css('overflow', 'hidden')

	$('.win-inform-back').on('click', () =>
	{
		$('.win-inform').addClass('hidden-voice')
		$('html').css('overflow', 'visible')
		setTimeout(() =>
		{
			$('.win-inform').remove()
		}, 200)
	})
}

const hideWInInform = () =>
{
	$('.win-inform').addClass('hidden-voice')
	$('html').css('overflow', 'visible')
	setTimeout(() =>
	{
		$('.win-inform').remove()
	}, 200)
}

const animNext = el =>
{
	const up = $(el).children('.next-up')
	const down = $(el).children('.next-down')
	const next = $(el).children('.next')

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
}

$(document).ready(function() 
{
	$('.open').each(function()
	{
		arrayOpen.push($(this).prop('id'))
		$(this).removeAttr('id')
	})

	if ($('div').is('.next')) 
	{
		$('.statistic-text-field').slideDown('400')
		setTimeout(() =>
		{
			$('.next-inner').each(function(index, el) 
			{
				animNext(el)
			})
		}, 450)

		$('.next-inner').each(function(index, el) 
		{
			$(this).on('click', function()
			{
				showNext($(this), index)
			})
		})
	}
	else 
	{
		$('.statistic-text-field').slideDown('400')
	}
})

buttonStatus.on('click', () =>
{
	$('body').append(
		'<div class="win-inform flex jcsa alic hidden-voice">' + 
			'<div class="win-inform-back"></div>'+
			'<div class="win-inform-block flex jcc fdc">' +
				'<div class="flex jcsa fdc" style="height: 80%">' +
					'<div class="win-inform-block-text flex jcsa" id="cab-text-settings">' +
						'<p>Ви дійсно хочете ' + buttonStatus[0].textContent.toLowerCase() + ' опитування?</p>' +
					'</div>' +
					'<div class="win-inform-block-button flex jcsa">' +
						'<div class="win-inform-block-button-inner flex jcsb" style="width: 40%">' +
							'<button class="button" id="interview-button-del-yes">Так</button>' +
							'<button class="button" id="interview-button-del-no">Ні</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>')

	$('.win-inform-block').css({
		height: '30%',
		width: '50%'
	})
	$('.win-inform-block-text').css({
		'margin-top': '0'
	})
	$('.win-inform-block-text').children().css('font-size', '30px')
	$('.win-inform-block-button-inner').children().css('width', '40%')

	$('#interview-button-del-no').on('click', hideWInInform)

	$('#interview-button-del-yes').on('click', () =>
	{
		$.ajax(
		{
			url: '/update_status',
			type: 'POST',
			data:
			{
				id: window.location.search.substr(4)
			},
			success: response => 
			{
				let array = $.parseJSON(response)
				if (array['change'] === 'false') 
				{
					hideWInInform()
					actionBlock('Помилка')	
				}
				else
				{
					hideWInInform()
					if (array['status'] === '1') 
					{
						buttonStatus[0].textContent = 'Закрити'
					}
					else if (array['status'] === '0') 
					{
						buttonStatus[0].textContent = 'Відкрити'
					}
					$('#date-end')[0].textContent = 'Дата завершення: ' + array['date']
				}

			}
		})
	})

	setTimeout(() =>
	{
		showWinInform()
	}, 10)
})

buttonDel.on('click', () =>
{
	$('body').append(
		'<div class="win-inform flex jcsa alic hidden-voice">' + 
			'<div class="win-inform-back"></div>'+
			'<div class="win-inform-block flex jcc fdc">' +
				'<div class="flex jcsa fdc" style="height: 80%">' +
					'<div class="win-inform-block-text flex jcsa" id="cab-text-settings">' +
						'<p>Ви дійсно хочете видалити опитування?</p>' +
					'</div>' +
					'<div class="win-inform-block-button flex jcsa">' +
						'<div class="win-inform-block-button-inner flex jcsb" style="width: 40%">' +
							'<button class="button" id="interview-button-del-yes">Так</button>' +
							'<button class="button" id="interview-button-del-no">Ні</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>')

	$('.win-inform-block').css({
		height: '30%',
		width: '50%'
	})
	$('.win-inform-block-text').css({
		'margin-top': '0'
	})
	$('.win-inform-block-text').children().css('font-size', '30px')
	$('.win-inform-block-button-inner').children().css('width', '40%')

	$('#interview-button-del-no').on('click', hideWInInform)

	$('#interview-button-del-yes').on('click', () =>
	{
		$.ajax(
		{
			url: '/del_inter_active',
			type: 'POST',
			data:
			{
				id: window.location.search.substr(4)
			},
			success: response => 
			{
				if (response === 'false') 
				{
					hideWInInform()
					actionBlock('Помилка')
				}
				else if(response === 'true')
				{
					window.location.href = '/cabinet'
				}
			}
		})
	})

	setTimeout(() =>
	{
		showWinInform()
	}, 10)
})



$('#interview-button-report').on('click', () =>
{
	$.ajax(
	{
		url: '/check_status',
		type: 'POST',
		data:
		{
			id: window.location.search.substr(4)
		},
		success: response => 
		{
			if (response === '1') 
			{
				actionBlock('Для формування звіту потрібно закрити опитування')
			}
			else
			{
				let id = window.location.search.substr(4)
				// console.log(id)
				window.location.href = '/get_report?id=' + id
			}
		}
	})
})