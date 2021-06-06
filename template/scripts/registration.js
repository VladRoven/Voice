const name = $("#reg-name")
const email = $("#reg-email")
const password = $("#reg-password")
const button = $('#reg-button')
const buttonRegError = $('#reg-button-error')

// const emailCheck = /^\w+@\w+\.\w{2,4}$/i
const emailCheck = /^[a-z0-9.]+@[a-z]+.[a-z]{2,4}$/i
const nameChek = /^[А-ЯЁЇІЄҐ][а-яёїієґ]+$/u
// const passCheck = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^\w\s]).{6,}/
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

$('.win-inform-back').on('click', () =>
{
	$('.win-inform').addClass('hidden-voice')
	$("html").css('overflow', 'visible')
})

buttonRegError.on('click', () => 
{
	$("#reg-error").addClass('hidden-voice')
	$("html").css('overflow', 'visible');
})

button.on('click',  function check()
{
	let check = true

	if (!name.val().trim().length || !nameChek.test(name.val())) 
	{
		name[0].placeholder = 'Невiрно вказане iм’я'
		error(name);
		name.val('');
		check = false
	}

	if (!email.val().trim().length || !emailCheck.test(email.val())) 
	{
		email[0].placeholder = 'Невiрно вказано e-mail'
		error(email)
		email.val('')
		check = false
	}

	console.log(!passCheck.test(password.val()))

	if (!password.val().trim().length || password.val().trim().length < 6 || !passCheck.test(password.val())) 
	{
		if (!passCheck.test(password.val())) 
		{
			password[0].placeholder = 'Пароль повинен складатися з цифр і букв'
			error(password)
			password.val('')
			check = false
		}
		else 
		{
			password[0].placeholder = 'Мінімальная кількість символів: 6'
			error(password)
			password.val('')
			check = false
		}
	}

	if (check) 
	{
		$.ajax({
			url: '/check_reg',
			type: 'POST',
			data: 
			{
				name: name.val(),
				email: email.val(),
				password: password.val()
			},
			success: function(response) {
				if (response === 'reg') 
				{
					$("#reg-error").removeClass('hidden-voice')
					$("html").css('overflow', 'hidden');
				}
				else if (response === 'success reg') 
				{
					$("#reg-error").removeClass('hidden-voice')
					$("html").css('overflow', 'hidden')
					$("#win-inform-block-text").text('Вітаю, аккаунт зареєстровано!')
					buttonRegError.attr('id', 'reg-button-accept')
					$("#reg-button-accept").on('click', () => 
					{
						$("#reg-error").addClass('hidden-voice')
						$("html").css('overflow', 'visible')
						window.location.href = '/login'
					})
					$('.win-inform-back').on('click', () =>
					{
						window.location.href = '/login'
					})
				}
				else if (response === 'fail reg') 
				{
					$("#reg-error").removeClass('hidden-voice')
					$("html").css('overflow', 'hidden')
					$("#win-inform-block-text").text('Виникла помилка! Спробуйте ще раз.')
				}
			}
		})
	}
})