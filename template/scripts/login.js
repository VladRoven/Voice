const email = $('#log-email')
const pass = $('#log-pass')
const button = $('#login-button-in')
const buttonForgot = $('#login-button-forgot')
const buttonLogError = $('#log-button-error')

const emailCheck = /^[a-z0-9.]+@[a-z]+.[a-z]{2,4}$/i
const passCheck = /^[a-zA-Z0-9]+$/

const error = elem => 
{
	$(elem).addClass('error')
	button.attr('disabled', 'disabled')
	setTimeout(() => 
	{
		$(elem).removeClass('error')
		button.removeAttr('disabled')
	}, 2000)
}

const hideWinInform = () =>
{
	$(".win-inform").addClass('hidden-voice')
	$("html").css('overflow', 'visible')
}

const hidenFormSendNewPassBefore = () =>
{
	$(".win-inform").addClass('hidden-voice')
	$("html").css('overflow', 'visible')

	setTimeout(() =>
	{
		$('#log-email-newpass').remove()
		$('#log-button-newpass').off()
		$('#log-button-newpass').attr('id', '#log-button-error')
		buttonLogError.on('click', hideWinInform)
		buttonLogError[0].textContent = 'Зрозумiв'
		$('#win-inform-block-text').css({
			display: 'block'
		})

		$('#win-inform-block-text')[0].textContent = 'Невірний e-mail або пароль!'
		buttonLogError.attr('style', 'margin-bottom: 20px; transition: .1s')

	}, 200)
}

const hidenFormSendNewPassAfter = () =>
{
	$(".win-inform").addClass('hidden-voice')
	$("html").css('overflow', 'visible')

	$('#win-inform-block-text')[0].textContent = 'Невірний e-mail або пароль!'
	buttonLogError[0].textContent = 'Зрозумiв'
}


const changeFormSendNewPass = (buttonText, text) =>
{
	$('#log-email-newpass').remove()
	$('#log-button-newpass').off()
	$('#log-button-newpass').attr('id', '#log-button-error')
	buttonLogError.on('click', hideWinInform)
	buttonLogError[0].textContent = buttonText
	$('#win-inform-block-text').css({
		display: 'block',
		width: '85%'
	});

	$('#win-inform-block-text')[0].textContent = text
	$('.win-inform-block-text').css({
		transform: 'translateY(-10px)'
	})

	buttonLogError.css({
		transform: 'translateY(0)'
	})

	setTimeout(() =>
	{
		buttonLogError.attr('style', 'margin-bottom: 20px; transition: .1s');
	}, 400)
}

const repeatFormSendNewPass = () =>
{
	buttonLogError.css({
		transition: '.4s',
		transform: 'translateY(100px)'
	})

	$('.win-inform-block-text').css({
		transition: '.4s',
		transform: 'translateY(-110px)'
	})

	setTimeout(() =>
	{
		$(".win-inform-back").off()
		$('#win-inform-block-text').css('display', 'none')
		$('.win-inform-block-text').append('<input class="input-voice input-voice-shadow" id="log-email-newpass" type="e-mail" title="E-mail" placeholder="Введiть e-mail">')
		$('#log-email-newpass').css({
			width: '60%'
		})
		buttonLogError.off()
		buttonLogError[0].textContent = 'Вiдправити'
		buttonLogError.attr('id', 'log-button-newpass')

		$('.win-inform-block-text').css({
			transition: '.4s',
			transform: 'translateY(0)',
			'z-index': '2'
		})

		$('#log-button-newpass').css({
			transition: '.4s',
			transform: 'translateY(0)'
		})

		setTimeout(() =>
		{
			$('#log-button-newpass').attr('style', 'margin-bottom: 20px;')
			$('#log-button-newpass').on('click', buttonSendNewPassAction)
			$(".win-inform-back").on('click', hidenFormSendNewPassBefore)
		}, 400)

	}, 400)
}

const buttonSendNewPassAction = () =>
{
	if (!$('#log-email-newpass').val().trim().length || !emailCheck.test($('#log-email-newpass').val())) 
	{
		$('#log-email-newpass')[0].placeholder = 'Невiрно вказано e-mail'
		error($('#log-email-newpass'))
		$('#log-email-newpass').val('')
	}
	else
	{
		$('.win-inform-block-text').css({
			transition: '.4s',
			transform: 'translateY(-100px)'
		})

		$('#log-button-newpass').css({
			transition: '.4s',
			transform: 'translateY(100px)'
		})

		$.ajax(
		{
			url: '/send_pass',
			type: 'POST',
			data:
			{
				email: $('#log-email-newpass').val()
			},
			success: response => 
			{
				if (response === 'true') 
				{
					changeFormSendNewPass('Зрозумiв', 'На вказаний e-mail було надіслано новий пароль від аккаунту.')
				}
				else if (response === 'unreg')
				{
					setTimeout(() =>
					{
						changeFormSendNewPass('Повторити', 'Аккаунт з даним e-mail не зареєстрований.')
						buttonLogError.off()
						$(".win-inform-back").off()

						$(".win-inform-back").on('click', repeatFormSendNewPass)
						buttonLogError.on('click', repeatFormSendNewPass)
					}, 400)
				}
				else
				{
					setTimeout(() =>
					{
						changeFormSendNewPass('Зрозумiв', 'Виникла помилка. Спробуйте ще раз.')
					}, 400)
				}
			}
		})
	}
}

$(".win-inform-back").on('click', hideWinInform)

buttonLogError.on('click', hideWinInform)

button.on('click', () =>
{

	let check = true

	if (!email.val().trim().length || !emailCheck.test(email.val())) 
	{
		email[0].placeholder = 'Невiрно вказано e-mail'
		error(email)
		email.val('')
		check = false
	}

	if (!pass.val().trim().length || !passCheck.test(pass.val())) 
	{
		pass[0].placeholder = 'Невiрно вказано пароль'
		error(pass)
		pass.val('')
		check = false
	}

	if (check) 
	{
		$.ajax(
		{
			url: '/check_log',
			type: 'POST',
			data:
			{
				email: email.val(),
				pass: pass.val()
			},
			success: response => 
			{
				if (response === 'error') 
				{
					$("#log-error").removeClass('hidden-voice')
					$("html").css('overflow', 'hidden');
				}
				else
				{
					if (window.location.search !== '') 
					{
						window.location.href = '/interview' + window.location.search
					}
					else 
					{
						window.location.href = '/cabinet'
					}
				}
			}
		})
	}

})

buttonForgot.on('click', () => 
{
	$(".win-inform-back").off()
	$("#log-error").removeClass('hidden-voice')
	$("html").css('overflow', 'hidden')
	$('#win-inform-block-text').css('display', 'none')
	$('.win-inform-block-text').append('<input class="input-voice input-voice-shadow" id="log-email-newpass" type="e-mail" title="E-mail" placeholder="Введiть e-mail">')
	$('#log-email-newpass').css({
		width: '60%',
		zIndex: '2'
	})
	buttonLogError.off()
	buttonLogError[0].textContent = 'Вiдправити'
	buttonLogError.attr('id', 'log-button-newpass')
	$('#log-button-newpass').css('margin-bottom', '20px')
	$(".win-inform-back").on('click', hidenFormSendNewPassBefore)

	$('#log-button-newpass').on('click', buttonSendNewPassAction)
	
})
