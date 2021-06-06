<?php require_once (ROOT.'/models/interview.php'); ?>
<?php require_once(ROOT."/views/layouts/header.php"); ?>

<title>Конструктор</title>
<div class="conteiner flex fdc jcsb">
	<div class="constructor-name flex jcc">
		<p><?php echo Interview::getName($_GET['id']) ?></p>
	</div>
	
	<div class="constructor-interview-data flex jcc">
		<div class="constructor-interview-data-inner">
			<?php if (Interview::getDataJson($_GET['id']) == '' || count(Interview::getDataJson($_GET['id'])) < 1) { ?>
				<p class="flex jcc" id="empty" style="margin-bottom: 70px">Немає жодного питання</p>
			<?php } else { Interview::getQuestionsForConstructor($_GET['id']); } ?>
		</div>
	</div>

	<div class="constructor-button-add flex jcc">
		<div class="constructor-button-add-inner flex" id="constructor-button-add">
			<div class="constructor-button-add-circle flex jcc">
				<p>+</p>
			</div>
			<div class="constructor-button-add-text flex fdc jcc">
				<p>Додати питання</p>
			</div>
		</div>
	</div>

	<div class="constructor-button-action flex jcc">
		<div class="constructor-button-action-inner flex jcsa">
			<button class="button constructor-button-action-end" id="constructor-button-del">Видалити</button>
			<button class="button constructor-button-action-end" id="constructor-button-post">Опублікувати</button>
		</div>
	</div>

	<script src="/template/scripts/constructor.js"></script>
	<script src="/template/scripts/action_block.js"></script>

	<?php require_once(ROOT."/views/layouts/footer.php"); ?>
</div>