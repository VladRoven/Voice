<?php require_once (ROOT."/views/layouts/header.php"); ?>

<title>Реєстрація</title>

<div class="win-inform flex jcsa alic hidden-voice" id="reg-error">
	<div class="win-inform-back"></div>
	<div class="win-inform-block flex jcsa fdc">
		<div class="win-inform-block-text flex jcsa">
			<p id="win-inform-block-text">Даний e-mail вже зареєстрований!</p>
		</div>
		<div class="win-inform-block-button flex jcsa">
			<div class="reg-button-eror">
				<button class="button" id="reg-button-error">Зрозумiв</button>
			</div>
		</div>
	</div>
</div>

<div class="reg flex jcsa">
	<div class="reg-inner flex jcsa">
		<div class="block-reg">
			<div class="block-reg-text flex jcsa">
				<p>Реєстрація</p>
			</div>
			<div class="block-reg-input flex jcsa">
				<div class="block-reg-input-inner flex fdc jcsb">		
					<input class="input-voice" id="reg-name" type="text" title="Ім’я" placeholder="Ім’я">
					<input class="input-voice" id="reg-email" type="e-mail" title="E-mail" placeholder="E-mail">
					<input class="input-voice" id="reg-password" type="password" title="Пароль" placeholder="Пароль">
				</div>
			</div>
			<div class="block-reg-button flex jcsa">
				<div class="block-reg-button-inner">
					<button class="button" id="reg-button">Зареєструватись</button>
				</div>
			</div>
		</div>
		<div class="block-reg-copyright">
			<div class="block-reg-copyright-inner">
				<p id="block-reg-copyright-text">Створи своє опитування та поширюй його</p>
			</div>
			<div class="block-reg-copyright-inner">
				<div class="block-reg-logo flex jcsa">
					<div class="block-reg-circle">
						<p id="block-reg-logo-v">V</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="/template/scripts/registration.js"></script>

<?php require_once (ROOT."/views/layouts/footer.php"); ?>
