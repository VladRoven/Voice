<?php require_once (ROOT."/views/layouts/header.php"); ?>

<title>Авторизація</title>

<div class="win-inform flex jcsa alic hidden-voice" id="log-error">
	<div class="win-inform-back"></div>
	<div class="win-inform-block flex jcsa fdc" id="block-login">
		<div class="win-inform-block-text flex jcsa" id="block-text-login">
			<p id="win-inform-block-text">Невірний e-mail або пароль!</p>
		</div>
		<div class="win-inform-block-button flex jcsa">
			<div class="log-button-error">
				<button class="button" id="log-button-error">Зрозумiв</button>
			</div>
		</div>
	</div>
</div>

<div class="login flex jcsa">
	<div class="login-block">
		<div class="login-block-inner flex fdc jcsb">
			<div class="login-text flex jcsa">
				<p>Авторизація</p>
			</div>
			<div class="login-input flex jcsa">
				<div class="login-input-inner flex fdc jcsa">
					<input class="input-voice" id="log-email" type="e-mail" title="E-mail" placeholder="E-mail">
					<input class="input-voice" id="log-pass" type="password" title="Пароль" placeholder="Пароль">
				</div>
			</div>
			<div class="block-login-button flex jcsa">
					<div class="block-login-button-inner flex jcsb">
						<button class="button button-login" id="login-button-in">Увiйти</button>
						<button class="button button-login" id="login-button-forgot">Забув пароль?</button>
					</div>
				</div>
		</div>
	</div>
</div>

<script src="/template/scripts/login.js"></script>

<?php require_once (ROOT."/views/layouts/footer.php"); ?>