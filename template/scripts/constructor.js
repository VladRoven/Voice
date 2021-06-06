const buttonDel = $('#constructor-button-del')
const buttonSave = $('#constructor-button-save')
const buttonPost = $('#constructor-button-post')
const buttonAdd = $('#constructor-button-add')
const select = ["Закрите", "Шкала", "Відкрите"]

let arrayQuestion = []
let countQuestion = $('.interview-display').length
let selectItem
let currentSelect
let currentEditIndex

const showWinInform = () =>
{
	$('.win-inform').removeClass('hidden-voice')
	$('html').css('overflow', 'hidden')

	$('.win-inform-back').on('click', () =>
	{
		selectItem = []
		currentSelect = null
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

const openSelect = () =>
{
	if ($('.select').hasClass('active')) 
	{
		$('.select-body').slideUp('fast')
		$('.select-icon-left').css('transform', 'rotate(-34deg)');
		$('.select-icon-right').css('transform', 'rotate(34deg)');
		$('.select').removeClass('active')
	}
	else
	{
		$('.select-body').slideDown('fast')
		$('.select-icon-left').css('transform', 'rotate(34deg)');
		$('.select-icon-right').css('transform', 'rotate(-34deg)');
		$('.select').addClass('active')
	}
}

const setSelect = () =>
{
	selectItem.each(function(index, el) 
	{
		$(el).on('click', () =>
		{
			if (currentSelect === index) 
			{
				openSelect()
			}
			else
			{
				currentSelect = index
				$('.select-current')[0].textContent = select[currentSelect]
				openSelect()
				showSelect()
			}
		})
	})
}

const actionQuestionDelete = () =>
{
	$('.constructor-answer-del').each(function(index, el) 
	{
		$(el).on('click', function()
		{
			$('body').append(
			'<div class="win-inform flex jcsa alic hidden-voice">' + 
				'<div class="win-inform-back"></div>'+
				'<div class="win-inform-block flex jcc fdc">' +
					'<div class="flex jcsa fdc" style="height: 80%">' +
						'<div class="win-inform-block-text flex jcsa" id="cab-text-settings">' +
							'<p>Ви дійсно хочете видалити обране питання?</p>' +
						'</div>' +
						'<div class="win-inform-block-button flex jcsa">' +
							'<div class="win-inform-block-button-inner flex jcsb" style="width: 40%">' +
								'<button class="button" id="constructor-button-del-yes">Так</button>' +
								'<button class="button" id="constructor-button-del-no">Ні</button>' +
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

			$('#constructor-button-del-no').on('click', hideWInInform)

			$('#constructor-button-del-yes').on('click', () =>
			{
				$.ajax(
				{
					url: '/del_question',
					type: 'POST',
					data:
					{
						id: window.location.search.substr(4),
						index: index
					},
					success: response => 
					{
						if (response === 'false') 
						{
							hideWInInform() 
							actionBlock('Помилка')
							
						}
						else
						{
							hideWInInform()
							$(this).parent().parent().remove()
							countQuestion = $('.interview-display').length
							console.log(countQuestion)
							if ($('.constructor-interview-data-inner').children().length === 0) 
							{
								$('.constructor-interview-data-inner').append('<p class="flex jcc" id="empty" style="margin-bottom: 70px">Немає жодного питання</p>')
							}
							$('.constructor-answer-del').off()
							actionQuestionDelete()
							actionAnswerEdit()
						}
					}
				})
			})

			setTimeout(() =>
			{
				showWinInform()
			}, 10)
		})
	})
}

const actionButtonAddVariant = () =>
{
	if ($('input').is('.constructor-input-variant') && $('.constructor-input-variant:last').val().trim().length > 70) 
	{
		actionBlock('Максимальна кількість символів: 70')
	}
	else if (!$('input').is('.constructor-input-variant') || $('.constructor-input-variant:last').val().trim().length >= 1) 
	{
		if ($('.constructor-variant-add-block').children().length > 50) 
		{
			actionBlock('Перевищено ліміт!')
		}
		else
		{
			if ($('div').is('.constructor-variant-add-block')) 
			{
				$('.constructor-variant-add-block').append(
					'<div class="flex" style="overflow: hidden">' +
						'<div class="creator-variant-add flex alic jcc">' +
							'<input class="constructor-input-variant" type="text" placeholder="Введіть варіант"/>' +
						'</div>' +
						'<div class="constructor-del-variant flex alic"><p>&#10005</p></div>' +
					'</div>'
				)
				$('.constructor-input-variant').focus()
				actionDelVariant()
			}
			else
			{
				$('#constructor-button-add-variant').before(
					'<div class="constructor-variant-add-block">' +
						'<div class="flex" style="overflow: hidden">' +
							'<div class="creator-variant-add flex alic jcc">' +
								'<input class="constructor-input-variant" type="text" placeholder="Введіть варіант"/>' +
							'</div>' +
							'<div class="constructor-del-variant flex alic"><p>&#10005</p></div>' +
						'</div>' +
					'</div>'
				)
				$('.constructor-input-variant').focus()
				actionDelVariant()
			}
		}
	}
	else 
	{
		actionBlock('Вкажіть варіант!')
	}
}

const showQuestionEdit = (arrayResponse) =>
{
	switch (arrayResponse['type'] - 1) {
		case 0:
			currentSelect = 0
			$('.constructor-body').children().remove()
			$('.constructor-body').append(
				'<div class="constructor-button-add-inner flex" id="constructor-button-add-variant">' +
					'<div class="constructor-button-add-circle flex jcc">' +
						'<p>+</p>' + 
					'</div>' +
					'<div class="constructor-button-add-text flex fdc jcc">' +
						'<p>Додати варіант</p>' +
					'</div>' +
				'</div>'
			)
			$.each(arrayResponse['variants'], function(index, val) 
			{
			 	if ($('div').is('.constructor-variant-add-block')) 
				{
					console.log('here1')
					$('.constructor-variant-add-block').append(
						'<div class="flex" style="overflow: hidden">' +
							'<div class="creator-variant-add flex alic jcc">' +
								'<input class="constructor-input-variant" type="text" placeholder="Введіть варіант" value="' + val['variant'] + '"/>' +
							'</div>' +
							'<div class="constructor-del-variant flex alic"><p>&#10005</p></div>' +
						'</div>'
					)
					actionDelVariant()
				}
				else
				{
					$('#constructor-button-add-variant').before(
						'<div class="constructor-variant-add-block">' +
							'<div class="flex" style="overflow: hidden">' +
								'<div class="creator-variant-add flex alic jcc">' +
									'<input class="constructor-input-variant" type="text" placeholder="Введіть варіант" value="' + val['variant'] + '"/>' +
								'</div>' +
								'<div class="constructor-del-variant flex alic"><p>&#10005</p></div>' +
							'</div>' +
						'</div>'
					)
					actionDelVariant()
				}
			})
			actionDelVariant()
			$('#constructor-button-add-variant').on('click', actionButtonAddVariant)
			break
		case 1:
			currentSelect = 1
			$('.constructor-body').children().remove()
			$('.constructor-body').append(
			'<div class="constructor-scale-add flex alic jcsa">' +
				'<div class="scale-circle"></div>' +
				'<div class="scale-line"></div>' +
				'<div class="scale-circle"></div>' +
			'</div>' +
			'<div class="scale-data-enter flex jcsb">' +
				'<input class="scale-data-input input-voice" id="from" type="text" placeholder="Від" value="' + arrayResponse['from'] + '"/>' +
				'<input class="scale-data-input input-voice" id="step" type="text" placeholder="Шаг" value="' + arrayResponse['step'] + '"/>' +
				'<input class="scale-data-input input-voice" id="to" type="text" placeholder="До" value="' + arrayResponse['to'] + '"/>' +
			'</div>')
			break
		case 2:
			currentSelect = 2
			$('.constructor-body').children().remove()
			$('.constructor-body').append('<p class="constructor-body-text"></p>')
			$('.constructor-body-text')[0].textContent = 'На питання типу "Відкрите" учасник залишає свою відповідь на ваше запитання'
			$('.constructor-body-text').css({
				color: '#95BFFF',
				'font-size': '30px',
				'font-weight': '500'
			})
			break
	}
}

const appendEditableQuestion = () =>
{
	$.ajax(
	{
		url: '/edit_question',
		type: 'POST',
		data:
		{
			id: window.location.search.substr(4),
			arrayQuestion: JSON.stringify(arrayQuestion),
			index: currentEditIndex
		},
		success: response => 
		{
			if (response === 'false') 
			{
				actionBlock('Помилка')
			}
			else
			{
				selectItem = []
				currentSelect = null
				$('.constructor-interview-data-inner').children().remove()
				$('.constructor-interview-data-inner').append(response)
				countQuestion = $('.interview-display').length
				actionQuestionDelete()
				actionAnswerEdit()
			}
		}
	})
}

const editQuestion = () =>
{
	arrayQuestion = []
	switch(currentSelect)
	{
		case 0 :
			if ($('#constructor-question-name').val().trim().length === 0) 
			{
				actionBlock('Вкажіть назву!')
			}
			else
			{
				if ($('.constructor-variant-add-block').children().length < 2) 
				{
					actionBlock('Мінімальна кількість варіантів: 2')
				}
				else
				{
					if ($('.constructor-input-variant:last').val().trim().length === 0) 
					{
						actionBlock('Вкажіть варіант!')
					}
					else if ($('.constructor-input-variant:last').val().trim().length > 70) 
					{
						actionBlock('Максимальна кількість символів: 70')
					}
					else
					{
						let tempArray = []
						$('.constructor-input-variant').each(function(index, el) 
						{
							tempArray.push(
							{
								"id" : index,
								"variant" : $(el).val().trim()
							})
						})
						arrayQuestion.push(
						{
							"id" : currentEditIndex, 
							"type" : currentSelect + 1, 
							"title" : $('#constructor-question-name').val().trim(),
							"variants" : tempArray
						})

						if ($('p').is('#empty')) 
						{
							$('.constructor-interview-data-inner').children('p').remove()
						}
						hideWInInform()
						appendEditableQuestion()
					}
				}
			}
			break
		case 2 :
			if ($('#constructor-question-name').val().trim().length === 0) 
			{
				actionBlock('Вкажіть назву!')
			}
			else
			{
				arrayQuestion.push(
				{
					"id" : currentEditIndex, 
					"type" : currentSelect + 1, 
					"title" : $('#constructor-question-name').val().trim()
				})

				if ($('p').is('#empty')) 
				{
					$('.constructor-interview-data-inner').children('p').remove()
				}
				hideWInInform()
				appendEditableQuestion()
			}
			break
		case 1 :
			if ($('#constructor-question-name').val().trim().length === 0) 
			{
				actionBlock('Вкажіть назву!')
			}
			else
			{
				if (($('#from').val().trim().length === 0 || 
					$('#to').val().trim().length === 0 || 
					$('#step').val().trim().length === 0) || 
					!((/^[\d.]+$/).test($('#step').val().trim()) & 
					(/^[\d.]+$/).test($('#from').val().trim()) &
					(/^[\d.]+$/).test($('#to').val().trim())) ||
					parseFloat($('#to').val().trim()) <= parseFloat($('#from').val().trim()) || 
					parseFloat($('#step').val().trim()) > parseFloat($('#to').val().trim()) ||
					(parseFloat($('#from').val().trim()) < 0 || 
					parseFloat($('#to').val().trim()) < 0 || 
					parseFloat($('#step').val().trim()) < 0))
				{
					actionBlock('Введіть коректні дані!')
				}
				else if ((parseFloat($('#from').val().trim()) > 1000 ||
					parseFloat($('#step').val().trim()) > 1000 ||
					parseFloat($('#to').val().trim()) > 1000)) 
				{
					actionBlock('Перевищено ліміт: 1000')
				}
				else
				{
					arrayQuestion.push(
					{
						"id" : currentEditIndex, 
						"type" : currentSelect + 1, 
						"title" : $('#constructor-question-name').val().trim(),
						"from" : parseFloat($('#from').val().trim()),
						"to" : parseFloat($('#to').val().trim()),
						"step" : parseFloat($('#step').val().trim())
					})

					if ($('p').is('#empty')) 
					{
						$('.constructor-interview-data-inner').children('p').remove()
					}
					hideWInInform()
					appendEditableQuestion()
				}
			}
			break
		default:
			actionBlock('Оберіть тип питання!')
	}
}

const actionAnswerEdit = () =>
{
	$('.constructor-answer-edit').off()
	$('.constructor-answer-edit').each(function(index, el) 
	{
		$(el).on('click', function()
		{
			currentEditIndex = index
			$.ajax(
			{
				url: '/question_for_edit',
				type: 'POST',
				data:
				{
					id: window.location.search.substr(4),
					index: index
				},
				success: response => 
				{
					const arrayResponse = JSON.parse(response)
					$('body').append(
						'<div class="win-inform flex jcsa alic hidden-voice">' + 
							'<div class="win-inform-back"></div>'+
							'<div class="win-inform-block flex jcc fdc" id="adaptive-const">' +
								'<div class="flex jcsa fdc" style="height: 100%">' +
									'<div class="win-inform-block-data flex jcc">' +
										'<input class="input-voice input-voice-shadow constructor-input" id="constructor-question-name" type="text" title="Питання" placeholder="Введіть питання" value="' + arrayResponse['title'] + '">' + 
										'<div class="select">' +
											'<div class="select-header flex alic jcsb">' +
												'<span class="select-current flex">' + select[arrayResponse['type'] - 1] + '</span>' +
												'<div class="select-icon flex jcsb">' +
													'<div class="select-icon-left select-icon-line"></div>' +
													'<div class="select-icon-right select-icon-line"></div>' +
												'</div>' + 
											'</div>'+
											'<div class="select-body">' +
												'<div class="select-item">Закрите</div>' + 
												'<div class="select-item">Шкала</div>' + 
												'<div class="select-item">Відкрите</div>' + 
											'</div>'+
										'</div>'+
									'</div>' +
									'<div class="constructor-body flex alic jcc fdc">' +
									'</div>' +
									'<div class="win-inform-block-button flex jcsa">' +
										'<div class="win-inform-block-button-inner flex jcc" style="width: 40%">' +
											'<button class="button" id="constructor-button-edit-question">Змінити</button>' +
										'</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>')

					$('.win-inform-block').css({
						height: 'auto',
						maxHeight: '80%',
						width: '55%',
						padding: '70px 70px'
					})

					$('.select-header').on('click', openSelect)
					$('#constructor-button-edit-question').on('click', editQuestion)
					selectItem = $('.select-item')
					setSelect()
					showQuestionEdit(arrayResponse)

					setTimeout(() =>
					{
						showWinInform()
					}, 10)
				}
			})
		})
	})
}

const actionDelVariant = () =>
{
	$('.constructor-del-variant').off()
	$('.constructor-del-variant').each(function(index, el) 
	{
		$(el).on('click', function()
		{
			$(this).parent().remove()
			if ($('.constructor-variant-add-block').children().length === 0) 
			{
				$('.constructor-variant-add-block').remove()
			}
		})
	})
}

const showSelect = () =>
{
	switch (currentSelect)
	{
		case 0 :
			let check = true

			$('.constructor-body').children().remove()
			$('.constructor-body').append(
				'<div class="constructor-button-add-inner flex" id="constructor-button-add-variant">' +
					'<div class="constructor-button-add-circle flex jcc">' +
						'<p>+</p>' + 
					'</div>' +
					'<div class="constructor-button-add-text flex fdc jcc">' +
						'<p>Додати варіант</p>' +
					'</div>' +
				'</div>'
			)
			$('#constructor-button-add-variant').on('click', actionButtonAddVariant)
			break;
		case 2 :
			if ($('p').is('.constructor-body-text') ) 
			{
				$('.constructor-body-text')[0].textContent = 'На питання типу "Відкрите" учасник залишає свою відповідь на ваше запитання'
				$('.constructor-body-text').css({
					color: '#95BFFF',
					'font-size': '30px',
					'font-weight': '500'
				})
			}
			else
			{
				$('.constructor-body').children().remove()
				$('.constructor-body').append('<p class="constructor-body-text"></p>')
				$('.constructor-body-text')[0].textContent = 'На питання типу "Відкрите" учасник залишає свою відповідь на ваше запитання'
				$('.constructor-body-text').css({
					color: '#95BFFF',
					'font-size': '30px',
					'font-weight': '500'
				})
			}
			break;
		case 1 :
			$('.constructor-body').children().remove()
			$('.constructor-body').append(
			'<div class="constructor-scale-add flex alic jcsa">' +
				'<div class="scale-circle"></div>' +
				'<div class="scale-line"></div>' +
				'<div class="scale-circle"></div>' +
			'</div>' +
			'<div class="scale-data-enter flex jcsb">' +
				'<input class="scale-data-input input-voice" id="from" type="text" placeholder="Від"/>' +
				'<input class="scale-data-input input-voice" id="step" type="text" placeholder="Шаг"/>' +
				'<input class="scale-data-input input-voice" id="to" type="text" placeholder="До"/>' +
			'</div>')
	}

}

const appendQuestion = () =>
{
	$.ajax(
	{
		url: '/add_question',
		type: 'POST',
		data:
		{
			id: window.location.search.substr(4),
			arrayQuestion: JSON.stringify(arrayQuestion)
		},
		success: response => 
		{
			if (response === 'false') 
			{
				actionBlock('Помилка')
			}
			else
			{
				selectItem = []
				currentSelect = null
				$('.constructor-interview-data-inner').children().remove()
				$('.constructor-interview-data-inner').append(response)
				countQuestion = $('.interview-display').length
				actionQuestionDelete()
				actionAnswerEdit()
			}
		}
	})
}

const addQuestion = () =>
{
	console.log(countQuestion)
	arrayQuestion = []
	switch(currentSelect)
	{
		case 0 :
			if ($('#constructor-question-name').val().trim().length === 0) 
			{
				actionBlock('Вкажіть назву!')
			}
			else
			{
				if ($('.constructor-variant-add-block').children().length < 2) 
				{
					actionBlock('Мінімальна кількість варіантів: 2')
				}
				else
				{
					if ($('.constructor-input-variant:last').val().trim().length === 0) 
					{
						actionBlock('Вкажіть варіант!')
					}
					else if ($('.constructor-input-variant:last').val().trim().length > 70) 
					{
						actionBlock('Максимальна кількість символів: 70')
					}
					else
					{
						let tempArray = []
						$('.constructor-input-variant').each(function(index, el) 
						{
							tempArray.push(
							{
								"id" : index,
								"variant" : $(el).val().trim()
							})
						})
						arrayQuestion.push(
						{
							"id" : countQuestion, 
							"type" : currentSelect + 1, 
							"title" : $('#constructor-question-name').val().trim(),
							"variants" : tempArray
						})

						if ($('p').is('#empty')) 
						{
							$('.constructor-interview-data-inner').children('p').remove()
						}
						hideWInInform()
						appendQuestion()
					}
				}
			}
			break
		case 2 :
			if ($('#constructor-question-name').val().trim().length === 0) 
			{
				actionBlock('Вкажіть назву!')
			}
			else
			{
				arrayQuestion.push(
				{
					"id" : countQuestion, 
					"type" : currentSelect + 1, 
					"title" : $('#constructor-question-name').val().trim()
				})

				if ($('p').is('#empty')) 
				{
					$('.constructor-interview-data-inner').children('p').remove()
				}
				hideWInInform()
				appendQuestion()
			}
			break
		case 1 :
			if ($('#constructor-question-name').val().trim().length === 0) 
			{
				actionBlock('Вкажіть назву!')
			}
			else
			{
				if (($('#from').val().trim().length === 0 || 
					$('#to').val().trim().length === 0 || 
					$('#step').val().trim().length === 0) || 
					!((/^[\d.]+$/).test($('#step').val().trim()) & 
					(/^[\d.]+$/).test($('#from').val().trim()) &
					(/^[\d.]+$/).test($('#to').val().trim())) ||
					parseFloat($('#to').val().trim()) <= parseFloat($('#from').val().trim()) || 
					parseFloat($('#step').val().trim()) > parseFloat($('#to').val().trim()) ||
					(parseFloat($('#from').val().trim()) < 0 || 
					parseFloat($('#to').val().trim()) < 0 || 
					parseFloat($('#step').val().trim()) < 0))
				{
					actionBlock('Введіть коректні дані!')
				}
				else if ((parseFloat($('#from').val().trim()) > 1000 ||
					parseFloat($('#step').val().trim()) > 1000 ||
					parseFloat($('#to').val().trim()) > 1000)) 
				{
					actionBlock('Перевищено ліміт: 1000')
				}
				else
				{
					arrayQuestion.push(
					{
						"id" : countQuestion, 
						"type" : currentSelect + 1, 
						"title" : $('#constructor-question-name').val().trim(),
						"from" : parseFloat($('#from').val().trim()),
						"to" : parseFloat($('#to').val().trim()),
						"step" : parseFloat($('#step').val().trim())
					})

					if ($('p').is('#empty')) 
					{
						$('.constructor-interview-data-inner').children('p').remove()
					}
					hideWInInform()
					appendQuestion()
				}
			}
			break
		default:
			actionBlock('Оберіть тип питання!')
	}
}

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
							'<button class="button" id="constructor-button-del-yes">Так</button>' +
							'<button class="button" id="constructor-button-del-no">Ні</button>' +
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

	$('#constructor-button-del-no').on('click', hideWInInform)

	$('#constructor-button-del-yes').on('click', () =>
	{
		$.ajax(
		{
			url: '/del_interview',
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
				else
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

buttonPost.on('click', () =>
{
	$('body').append(
		'<div class="win-inform flex jcsa alic hidden-voice">' + 
			'<div class="win-inform-back"></div>'+
			'<div class="win-inform-block flex jcc fdc">' +
				'<div class="flex jcsa fdc" style="height: 80%">' +
					'<div class="win-inform-block-text flex jcsa" id="cab-text-settings">' +
						'<p>Ви дійсно хочете опублікувати опитування?</p>' +
					'</div>' +
					'<div class="win-inform-block-button flex jcsa">' +
						'<div class="win-inform-block-button-inner flex jcsb" style="width: 40%">' +
							'<button class="button" id="constructor-button-post-yes">Так</button>' +
							'<button class="button" id="constructor-button-post-no">Ні</button>' +
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

	$('#constructor-button-post-no').on('click', hideWInInform)

	$('#constructor-button-post-yes').on('click', () =>
	{
		$.ajax(
		{
			url: '/post_interview',
			type: 'POST',
			data:
			{
				id: window.location.search.substr(4),
			},
			success: response => 
			{
				if (response === 'false') 
				{
					hideWInInform()
					actionBlock('Помилка!')
				}
				else if(response === 'null')
				{
					hideWInInform()
					actionBlock('Опитування не опубліковано! Немає жодного питання.')
				}
				else
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

buttonAdd.on('click', () =>
{
	$('body').append(
		'<div class="win-inform flex jcsa alic hidden-voice">' + 
			'<div class="win-inform-back"></div>'+
			'<div class="win-inform-block flex jcc fdc" id="adaptive-const">' +
				'<div class="flex jcsa fdc" style="height: 100%">' +
					'<div class="win-inform-block-data flex jcc">' +
						'<input class="input-voice input-voice-shadow constructor-input" id="constructor-question-name" type="text" title="Питання" placeholder="Введіть питання">' + 
						'<div class="select">' +
							'<div class="select-header flex alic jcsb">' +
								'<span class="select-current flex">Тип</span>' +
								'<div class="select-icon flex jcsb">' +
									'<div class="select-icon-left select-icon-line"></div>' +
									'<div class="select-icon-right select-icon-line"></div>' +
								'</div>' + 
							'</div>'+
							'<div class="select-body">' +
								'<div class="select-item">Закрите</div>' + 
								'<div class="select-item">Шкала</div>' + 
								'<div class="select-item">Відкрите</div>' + 
							'</div>'+
						'</div>'+
					'</div>' +
					'<div class="constructor-body flex alic jcc fdc">' +
							'<p class="constructor-body-text">Оберіть тип питання</p>' +
						'</div>' +
					'<div class="win-inform-block-button flex jcsa">' +
						'<div class="win-inform-block-button-inner flex jcc" style="width: 40%">' +
							'<button class="button" id="constructor-button-add-question">Додати</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>')

	$('.win-inform-block').css({
		height: 'auto',
		maxHeight: '80%',
		width: '55%',
		padding: '70px 70px'
	})

	$('.select-header').on('click', openSelect)
	$('#constructor-button-add-question').on('click', addQuestion)
	selectItem = $('.select-item')
	setSelect()

	setTimeout(() =>
	{
		showWinInform()
	}, 10)
})

$(document).ready(function() 
{
	if ($('img').is('.constructor-answer-del')) 
	{
		actionQuestionDelete()
	}
	if ($('img').is('.constructor-answer-edit')) 
	{
		actionAnswerEdit()
	}
})