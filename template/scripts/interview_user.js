const buttonNext = $('#interview-button-next')
const buttonPerv = $('#interview-button-back')
const counter = $('#interview-counter')[0].textContent

let variant = $('.interview-variant')
let tempVariant
let itr = 1
let arrayAnswer = []
let isClickBtnEnd = false

$(document).ready(function() 
{
	$('#interview-counter')[0].textContent = (itr) + ' / ' + counter
	setActionOnVariant()

	if (itr == counter) 
	{
		buttonEndInterwiev()
	}
})

const setActionSlider = () =>
{
	$('#slider').on('input', function()
	{
		$('.slider-value')[0].textContent = $(this).val()
	})
}

const setActionOnVariant = () =>
{
	variant.each(function() 
	{
		$(this).on('click', function () 
		{
			if (tempVariant == null) 
			{
				tempVariant = $(this)
				tempVariant.css({
					background: 'rgba(126, 166, 246, 0.4)',
					color: 'white'
				})
				tempVariant.addClass('active')
			}
			else
			{
				tempVariant.removeAttr('style')
				tempVariant.removeClass('active')
				tempVariant = $(this)
				tempVariant.css({
					background: 'rgba(126, 166, 246, 0.4)',
					color: 'white'
				})
				tempVariant.addClass('active')
			}
		})
	})
}

const selectVariantNext = () =>
{
	let notActive = true

	variant.each(function(index, el) 
	{
		if ($(el).hasClass('active')) 
		{
			arrayAnswer.push(
			{
				"type" : 1,
				"answer" : index
			})
			notActive = false
		}
	})

	if (notActive) 
	{
		arrayAnswer.push(-1)
	}
}

const selectScale = () =>
{
	arrayAnswer.push(
	{
		"type" : 2,
		"answer" : parseFloat($('#slider').val())
	})
}

const selectText = () =>
{
	if (!$('.text-field-input').val().trim().length && $('.text-field-input').val() != '') 
	{
		arrayAnswer.push(-1)
	}
	else
	{
		arrayAnswer.push(
		{
			"type" : 3,
			"answer" : $('.text-field-input').val()
		})
	}
}

const selectAnswerPerv = () =>
{
	let temp = arrayAnswer.pop()
	

	if (temp['type'] == 1) 
	{
		if (temp['answer'] !== -1) 
		{
			let index = temp['answer']
			setTimeout(() =>
			{
				$(variant[index]).trigger('click')
			}, 10)
		}
	}
	else if (temp['type'] == 2) 
	{
		setTimeout(() =>
		{
			$('#slider').val(temp['answer'])
			$('.slider-value')[0].textContent = temp['answer']
		}, 10)
	}
	else if (temp['type'] == 3) 
	{
		setTimeout(() =>
		{
			$('.text-field-input')[0].textContent = temp['answer']
		}, 10)
	}

}

const buttonEndInterwiev = () =>
{
	buttonNext.off()
	buttonNext.attr('id', 'interview-button-end')
	$('#interview-button-end')[0].textContent = 'Завершити'
	$('#interview-button-end').on('click', () =>
	{
		let check = false
		isClickBtnEnd = true

		if (arrayAnswer.length == counter) 
		{
			arrayAnswer.pop()
		}
		
		if ($('.interview-display').children('.interview-variants').length > 0) 
		{
			selectVariantNext()
		}
		else if ($('.interview-display').children('.slider').length > 0) 
		{
			selectScale()
		}
		else if($('.interview-display').children('.text-field').length > 0)
		{
			selectText()
		}

		

		arrayAnswer.forEach((el) =>
		{
			if (el == -1) 
			{
				check = true
			}
		})

		if (check) 
		{
			actionBlock('Ви відповіли не на всі питання')
		}
		else
		{
			$.ajax(
			{
				url: '/update_answer',
				type: 'POST',
				data:
				{
					vars: arrayAnswer,
					id: window.location.search.substr(4)
				},
				success: response => 
				{
			
					if (response === 'true') 
					{
						$('body').append(
							'<div class="win-inform flex jcsa alic hidden-voice">' + 
								'<div class="win-inform-back"></div>'+
								'<div class="win-inform-block flex jcc fdc">' +
									'<div class="flex jcsa fdc" style="height: 80%">' +
										'<div class="win-inform-block-text flex jcsa" id="cab-text-settings">' +
											'<p>Опитування було пройдено! Дякуємо за увагу!</p>' +
										'</div>' +
										'<div class="win-inform-block-button flex jcsa">' +
											'<div class="win-inform-block-button-inner flex jcsb">' +
												'<button class="button" id="interview-button-close">Зрозумів</button>' +
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
						$('#interview-button-close').on('click', () => {window.location.href = '/cabinet'})
						$('.win-inform-back').on('click', () => {window.location.href = '/cabinet'})
						$('#interview-button-end').off()
						buttonPerv.off()

						setTimeout(() =>
						{
							$('.win-inform').removeClass('hidden-voice')
							$('html').css('overflow', 'hidden')
						}, 10)

					}
					else if (response === 'false')
					{
						actionBlock('Помилка')
					}
					else if (response === 'close') 
					{
						window.location.href = '/interview' + window.location.search
					}
				}
			})
		}
	})
}

const buttonNextAction = () =>
{
	if (itr < counter) 
	{
		$.ajax(
		{
			url: '/counter_question',
			type: 'POST',
			data:
			{
				itr: itr,
				id: window.location.search.substr(4)
			},
			success: response => 
			{
				if (response !== 'false') 
				{
					if ($('.interview-display').children('.interview-variants').length > 0) 
					{
						selectVariantNext()
					}
					else if ($('.interview-display').children('.slider').length > 0) 
					{
						selectScale()
					}
					else if($('.interview-display').children('.text-field').length > 0)
					{
						selectText()
					}
					

					itr = itr + 1
					$('.interview-display').css('transform', 'scale(0)')
					setTimeout(() =>
					{
						$('.interview-data-inner').children().remove()
						$('.interview-data-inner').append(response)
						variant = $('.interview-variant')
						setActionOnVariant()
						setActionSlider()
						$('.interview-display').css({
							transition: '.2s',
							transform: 'scale(0)'
						})
						$('#interview-counter')[0].textContent = (itr) + ' / ' + counter
						setTimeout(() =>
						{
							$('.interview-display').css({
								transform: 'scale(1)'
							})
						}, 40)
					}, 200)

					if (itr == counter) 
					{
						buttonEndInterwiev()
					}
				}
			}
		})
	}
}

buttonNext.on('click', buttonNextAction)

buttonPerv.on('click', () =>
{
	if (itr > 1) 
	{
		if (isClickBtnEnd) 
		{
			arrayAnswer.pop()
			isClickBtnEnd = false
		}
		itr = itr - 2
		$.ajax(
		{
			url: '/counter_question',
			type: 'POST',
			data:
			{
				itr: itr,
				id: window.location.search.substr(4)
			},
			success: response => 
			{
				if (response !== 'false') 
				{
					$('.interview-display').css('transform', 'scale(0)')

					if ($('button').is($('#interview-button-end'))) 
					{
						$('#interview-button-end').off()
						$('#interview-button-end').attr('id', 'interview-button-next')
						buttonNext[0].textContent = 'Наступне'
						buttonNext.on('click', buttonNextAction)
					}

					setTimeout(() =>
					{
						$('.interview-data-inner').children().remove()
						$('.interview-data-inner').append(response)
						variant = $('.interview-variant')
						selectAnswerPerv()
						setActionOnVariant()
						setActionSlider()
						$('.interview-display').css({
							transition: '.2s',
							transform: 'scale(0)'
						})
						itr = itr + 1
						$('#interview-counter')[0].textContent = (itr) + ' / ' + counter
						setTimeout(() =>
						{
							$('.interview-display').css({
								transform: 'scale(1)'
							})
						}, 40)
					}, 200)
				}
			}
		})
	}
})