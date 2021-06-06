const settings = $('#cabinet-settings')
const buttonClose = $('#cab-button')
const nameSettings = $('#cab-settings-name')
const nameCab = $('#cab-name')
const nameChek = /^[А-ЯЁЇІЄҐ][а-яёїієґ]+$/u
const nameInterviewChek = /^[А-ЯЁЇІЄҐ][А-ЯЁЇІЄҐа-яёїієґ"'-:,.\s]+$/u
const passCheck = /^[a-zA-Z0-9]+$/
const buttonChangePass = $('#cab-settings-button-pass')

let nameSettingsChange = ''
let checkShowHidePass = true

const error = elem => 
{
	$(elem).addClass('error')
	setTimeout(() => 
	{
		$(elem).removeClass('error')
	}, 2000)
}

const hideShowPass = () =>
{
	if (checkShowHidePass) 
	{
		$('#password-hide-show').children().attr('src', '/template/images/password_show.svg')
		$('.input-change-pass').attr('type', 'text')
		checkShowHidePass = false
	}
	else
	{
		$('#password-hide-show').children().attr('src', '/template/images/password_hide.svg')
		$('.input-change-pass').attr('type', 'password')
		checkShowHidePass = true
	}
}

const eventBack = () =>
{
	$('.win-inform').addClass('hidden-voice')
	$("html").css('overflow', 'visible')
	setTimeout(() => 
	{
		nameSettings.val(nameSettingsChange)
		nameSettings[0].placeholder = 'Iм’я'
	}, 200)
}

const errorPass = elem =>
{
	if (!elem.val().trim().length 
		|| elem.val().trim().length < 6
		|| !passCheck.test(elem.val()))
	{
		if (!passCheck.test(elem.val())) 
		{
			elem[0].placeholder = 'Пароль повинен складатися з цифр і букв'
			error(elem)
			elem.val('')
			return false
		}
		else 
		{
			elem[0].placeholder = 'Мінімальная кількість символів: 6'
			error(elem)
			elem.val('')
			return false
		}
	}
	else 
	{
		return true	
	}
}

const eventButtonClose = () =>
{
	if (nameSettingsChange !== nameSettings.val()) 
	{
		if (!nameSettings.val().trim().length || !nameChek.test(nameSettings.val())) 
		{
			nameSettings[0].placeholder = 'Невiрно вказане iм’я'
			error(nameSettings);
			nameSettings.val('');
		}
		else
		{
			$.ajax(
			{
				url: '/update_name',
				type: 'POST',
				data:
				{
					name: nameSettings.val()
				},
				success: response => 
				{
					if (response !== 'false') 
					{
						response.length > 11 ? nameCab[0].textContent = response.substring(0, 9) + '..' : nameCab[0].textContent = response.substring(0, 11)
						$('.win-inform').addClass('hidden-voice')
						$("html").css('overflow', 'visible')
					}
				}
			})
		}
	}
	else 
	{
		$('.win-inform').addClass('hidden-voice')
		$("html").css('overflow', 'visible')
	}
}

const hideChangePass = () =>
{
	$('.block-settings-change-pass').css({
		opacity: '0',
		transform: 'scale(0)'
	})

	$('#cab-text-settings').css({
		transform: 'translateY(-130px)'
	})

	$('.reg-button-eror').css({
		transition: '.4s',
		transform: 'translateY(130px)'
	})

	setTimeout(() =>
	{
		$('#cab-text-settings').children()[0].textContent = 'Налаштування'
		$('#cab-text-settings').css({
			transform: 'translateY(0)'
		});

		$('#button-change-pass')[0].textContent = 'Прийняти'
		$('#button-change-pass').attr('id', 'cab-button')
		buttonClose.on('click', eventButtonClose)
		$('#password-hide-show').off()
		$('#password-hide-show').remove()
		$('.block-settings-inner').css('height', '100%')
		$('.reg-button-eror').css({
			transform: 'translateY(0)'
		})

		$('.block-settings-change-pass').remove()
		$('.block-settings-inner-input').removeClass('hidden-voice')
		$('.block-settings-inner-logo').removeClass('hidden-voice')
		$('.block-settings').removeClass('flex fdc alic')

		$('.block-settings-inner-input').css({
			transform: 'translateX(0)'
		})

		$('.block-settings-inner-logo').css({
			transform: 'translateX(0)'
		})

		$('.win-inform-back').off()
		$('.win-inform-back').on('click', eventBack)
	}, 400)
}

const hideCreateInterview = () =>
{
	$('.win-inform-back').on('click', eventBack)
	buttonClose.on('click', eventButtonClose)

	$('.win-inform').addClass('hidden-voice')
	$("html").css('overflow', 'visible')

	setTimeout(() =>
	{
		$('.cabinet-create-new-interview-name').remove()
		buttonClose[0].textContent = 'Прийняти'
		buttonClose.off()
		buttonClose.on('click', eventButtonClose)
	}, 130)
}

settings.on('click', () => 
{
	$('.block-settings').removeClass('hidden-voice')
	$('.block-settings').css('height', '50%')
	$('#win-inform-block').css('height', '75%')
	$('#cab-text-settings').children()[0].textContent = 'Налаштування'
	$('.win-inform').removeClass('hidden-voice')
	$("html").css('overflow', 'hidden')
	nameSettingsChange = nameSettings.val()
})

buttonClose.on('click', eventButtonClose)

$('.win-inform-back').on('click', eventBack)

buttonChangePass.on('click', () => 
{
	$('.win-inform-back').off()
	$('.block-settings-inner-input').css({
		transition: '.4s',
		transform: 'translateX(-100vh)'
	});

	$('.block-settings-inner-logo').css({
		transition: '.4s',
		transform: 'translateX(100vh)'
	});

	$('#cab-text-settings').css({
		transition: '.4s',
		transform: 'translateY(-130px)'
	});

	buttonClose.off()
	buttonClose.attr('id', 'button-change-pass')
	$('.reg-button-eror').css({
		transition: '.4s',
		transform: 'translateY(130px)'
	});

	setTimeout(() =>
	{
		$('.block-settings-inner-input').addClass('hidden-voice')
		$('.block-settings-inner-logo').addClass('hidden-voice')
	}, 300)

	setTimeout(() =>
	{
		$('#cab-text-settings').children()[0].textContent = 'Зміна пароля'
		$('#button-change-pass')[0].textContent = 'Змінити пароль'
		$('.reg-button-eror').addClass('flex')
		$('.reg-button-eror').append(
			'<button class="button" id="password-hide-show">' +
				'<img src="/template/images/password_hide.svg" alt="">' +
			'</button>')
		$('#password-hide-show').css({
			'padding-top': '5px',
			width: '90px',
			'margin-left': '20px'
		})
		$('#cab-text-settings').css({
			transform: 'translateY(0)'
		})
		$('.reg-button-eror').css('transform', 'translateY(0)');
		$('.block-settings-inner').css('height', '0')
		$('.block-settings').addClass('flex fdc alic')
		$('.block-settings').append(
			'<div class="block-settings-change-pass flex fdc jcsb" style="width: 40%; height: 100%; margin-bottom: 2%; margin-top: 2%; transition: .7s; opacity: 0; transform: scale(0)">' + 
				'<input type="password" class="input-change-pass input-voice input-voice-shadow" id="input-change-pass-old" placeholder="Старий пароль">' +
				'<input type="password" class="input-change-pass input-voice input-voice-shadow" id="input-change-pass-new" placeholder="Новий пароль">' +
				'<input type="password" class="input-change-pass input-voice input-voice-shadow" id="input-change-pass-confirm" placeholder="Підтвердження пароля">' +
			'</div>')
		$('#password-hide-show').on('click', hideShowPass)
	}, 400)

	setTimeout(() =>
	{
		$('.block-settings-change-pass').css({
			opacity: '1',
			transform: 'scale(1)'
		})
	}, 430)

	setTimeout(() =>
	{
		$('.win-inform-back').on('click', hideChangePass)
		const passOld = $('#input-change-pass-old'),
			  passNew = $('#input-change-pass-new'),
			  passConfirm = $('#input-change-pass-confirm')

		$('.reg-button-eror').attr('style', '');
		$('#button-change-pass').on('click', () =>
		{
			let check = true

			check = errorPass(passOld)
			check = errorPass(passNew)
			check = errorPass(passConfirm)

			if (passConfirm.val() !== passNew.val()) 
			{
				passConfirm[0].placeholder = 'Різні паролі'
				passNew[0].placeholder = 'Різні паролі'
				error(passConfirm)
				error(passNew)
				passConfirm.val('')
				passNew.val('')
				check = false
			}

			if (check) 
			{
				$.ajax(
				{
					url: '/check_old_pass',
					type: 'POST',
					data:
					{
						pass: passOld.val()
					},
					success: response => 
					{
						console.log(response)
						if (response === 'false') 
						{
							error(passOld)
							passOld[0].placeholder = 'Неправильний пароль'
							passOld.val('')
						}
						else
						{
							$.ajax(
							{
								url: '/update_pass',
								type: 'POST',
								data:
								{
									pass: passConfirm.val()
								},
								success: response => 
								{
									if (response) 
									{
										hideChangePass()
									}
									else
									{
										error(passOld)
										passOld[0].placeholder = 'Помилка'
										passOld.val('')

										error(passNew)
										passNew[0].placeholder = 'Помилка'
										passNew.val('')

										error(passConfirm)
										passConfirm[0].placeholder = 'Помилка'
										passConfirm.val('')
									}
								}
							})
						}
					}
				})
			}
		})
	}, 800)

})

$('.cabinet-interview-display-inner-second').on('click', function()
{
	if ($(this).prop('id') != '') 
	{
		let link = 'http://' + location.hostname + '/interview?id=' + $(this).prop('id')
		let temp = $("<input>")

	    $("body").append(temp)
	    temp.val(link).select()
	    document.execCommand("copy")
	    temp.remove()

		actionBlock('Посилання скопійовано в буфер обміну.')
	}
	else 
	{
		actionBlock('Посилання не скопійовано! Опитування закрито або в розробці.')
	}
})

$('.cabinet-details-new-interview-button-circle').on('click', () =>
{
	$('.win-inform-back').off()
	buttonClose.off()

	buttonClose[0].textContent = 'Створити'
	$('.win-inform').removeClass('hidden-voice')
	$("html").css('overflow', 'hidden')

	$('.block-settings').addClass('hidden-voice')
	$('.block-settings').css('height', '0')
	$('#win-inform-block').css('height', '45%')

	$('#cab-text-settings').children()[0].textContent = 'Нове опитування'
	$('.block-settings').after(
		'<div class="cabinet-create-new-interview-name flex jcc" style="z-index: 2">' +
			'<input type="text" class="input-voice input-voice-shadow" id="cabinet-create-new-interview-name" placeholder="Введіть назву">' +
		'</div>')
	$('#cabinet-create-new-interview-name').css({
		width: '70%',
		'margin-bottom': '10px'
	});


	buttonClose.on('click', () =>
	{
		const nameInterview = $('#cabinet-create-new-interview-name')

		if (!nameInterview.val().trim().length || !nameInterviewChek.test(nameInterview.val())) 
		{
			nameInterview[0].placeholder = 'Невiрно вказана назва'
			error(nameInterview);
			nameInterview.val('');
		}
		else
		{
			$.ajax(
			{
				url: '/create_interview',
				type: 'POST',
				data:
				{
					name: nameInterview.val()
				},
				success: response => 
				{
					if (response === 'false') 
					{
						nameInterview[0].placeholder = 'Помилка'
						error(nameInterview);
						nameInterview.val('');
					}
					else
					{
						buttonClose.off()
						hideCreateInterview()
						window.location.href = '/constructor?id=' + response
					}
				}
			})
		}
	})


	$('.win-inform-back').on('click', hideCreateInterview)
})

$('.cabinet-interview-display-inner-first').on('click', function()
{
	let id = $(this).prop('id')

	$.ajax(
	{
		url: '/check_status',
		type: 'POST',
		data:
		{
			id: id
		},
		success: response => 
		{
			console.log(response)
			if (response === '') 
			{
				window.location.href = '/constructor?id=' + id
			}
			else
			{
				window.location.href = '/interview?id=' + id
			}
		}
	})
})