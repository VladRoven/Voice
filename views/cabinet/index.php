<?php require_once (ROOT.'/models/user.php'); ?>
<?php require_once (ROOT.'/views/layouts/header.php'); ?>

<title>Кабiнет</title>


<div class="win-inform flex jcsa alic hidden-voice">
	<div class="win-inform-back"></div>
	<div class="win-inform-block flex jcsa fdc" id="win-inform-block">
		<div class="win-inform-block-text flex jcsa" id="cab-text-settings">
			<p>Налаштування</p>
		</div>
		<div class="block-settings">
			<div class="block-settings-inner flex fdr jcsb">
				<div class="block-settings-inner-input flex fdc jcsa">
					<input type="text" class="input-voice input-voice-shadow" placeholder="Ім’я" id="cab-settings-name" value=<?php echo User::getName($_SESSION['id']) ?>>
					<input disabled="disabled" type="email" class="input-voice input-voice-shadow" placeholder="E-mail" id="cab-settings-email" value=<?php echo User::getEmail($_SESSION['id']) ?>>
					<div class="flex jcsa">
						<button class="button" id="cab-settings-button-pass">Змінити пароль</button>
					</div>
				</div>
				<div class="block-settings-inner-logo flex fdc jcsa">
					<div class="cab-logo">
						<div class="cab-logo-rectangle"></div>
						<div class="cab-logo-circle">
							<p>V</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="win-inform-block-button flex jcsa">
			<div class="reg-button-eror">
				<button class="button" id="cab-button">Прийняти</button>
			</div>
		</div>
	</div>
</div>

<div class="header-text flex jcsa">
	<div class="header-text-inner">
		<p>Власний кабінет</p>
	</div>
</div>

<div class="cabinet-details flex jcsa">
	<div class="cabinet-details-inner flex">
		<div class="cabinet-details-user cabinet-details-cabinet flex jcsa">
			<div class="cabinet-details-user-inner flex fdc jcc alic">
				<div class="cabinet-details-user-img flex jcsa">
					<div class="cabinet-details-user-img-hover flex jcsa alic" id="cabinet-settings">
						<img src="/template/images/cabinet_settings.svg" alt="">
					</div>
					<img src="/template/images/cabinet_person.svg" alt="">
				</div>
				<div class="cabinet-details-user-name flex jcc">
					<p id="cab-name"><?php echo mb_strimwidth(User::getName($_SESSION['id']), 0, 11, '..'); ?></p>
				</div>
				<div class="cabinet-details-user-count">
					<p>Кількість опитувань: <?php echo User::getCountInterviews($_SESSION['id']); ?></p>
				</div>
				<div class="cabinet-details-user-button flex jcsa">
					<a href="/logout">
						<button class="button" id="cabinet-details-user-button">Вийти</button>
					</a>
				</div>
			</div>
		</div>
		<div class="cabinet-details-interview cabinet-details-cabinet">
			<div class="flex jcc alic cabinet-details-interview-header">
				<p>Опитування</p>
			</div>
			<div class="cabinet-details-interview-interviews">
				<div class="cabinet-details-interview-interviews-inner">
					<?php if (!User::getInterviews($_SESSION['id'])) { ?>
						<div class="cabinet-interview-none flex alic">
							<p class="flex jcc">Опитувань немає</p>
						</div>
					<?php } else {?>
						<?php foreach (User::getInterviews($_SESSION['id']) as $val) { ?>
						<div class="cabinet-interview-display flex" title="<?php echo $val['name'];?>">
							<div class="cabinet-interview-display-inner-first flex jcc fdc" id="<?php echo ($val['status'] == '') ? $val['id_constructor'] : $val['id_interview']; ?>">
								<div class="cabinet-interview-display-inner-info flex jcsb alic">
									<p><?php echo mb_strimwidth($val['name'], 0, 23, '..')?></p>
									<?php if ($val['status'] == '1') {?> 
										<div class="cabinet-interview-status" title="Відкрито" style="background-color: #F2F6FC"></div>
									<?php } elseif($val['status'] == '0') {?>
										<div class="cabinet-interview-status" title="Закрито" style="background-color: #95BFFF"></div>
									<?php } else{?>
										<div class="cabinet-interview-status" title="В розробці" style="background: linear-gradient(to left, #F2F6FC 50%, #95BFFF 50%);"></div>
									<?php }?>
								</div>
							</div>
							<div class="cabinet-interview-display-inner-second flex jcc" id="<?php echo ($val['status'] == 1) ? $val['id_interview'] : ''; ?>" title="Копіювати посилання" >
								<div class="flex fdc jcc">
									<img src="/template/images/cabinet_share.png" alt="Копіювати посилання">
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>	
				</div>
			</div>
		</div>
		<div class="cabinet-details-new-interview cabinet-details-cabinet">
			<div class="cabinet-details-new-interview-inner flex jcc alic">
				<div class="cabinet-details-new-interview-button flex jcc fdc">
					<div class="flex jcc">
						<div class="cabinet-details-new-interview-button-circle flex jcc alic">
							<p>+</p>
						</div>
					</div>
					<div class="cabinet-details-new-interview-button-text flex jcc">
						<p>Нове опитування</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="/template/scripts/cabinet.js"></script>
<script src="/template/scripts/action_block.js"></script>

<?php require_once (ROOT.'/views/layouts/footer.php'); ?>