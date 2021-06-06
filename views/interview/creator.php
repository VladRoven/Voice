<?php require_once (ROOT.'/models/interview.php'); ?>

<title>Опитування</title>
<div class="conteiner flex fdc jcsb">
	
	<?php require_once(ROOT."/views/layouts/header.php"); ?>
	
	<div class="constructor">
		<div class="constructor-name flex jcc">
			<p><?php echo Interview::getName($_GET['id']) ?></p>
		</div>
		
		<div class="interview-staistic flex jcc">
			<div class="interview-staistic-inner flex jcsa fw">
				<p id="date-start">Дата початку: <?php echo Interview::getDateStart($_GET['id']); ?></p>
				<p id="date-end">Дата завершення: <?php echo Interview::getDateEnd($_GET['id']); ?></p>
				<p id="count">Кількість учасників: <?php echo Interview::getCountInterview($_GET['id']) ?></p>
			</div>
		</div>

		<div class="interview-data flex jcc">
			<div class="interview-data-inner flex fdc jcc">
				<?php echo Interview::getQuestionsForCreator($_GET['id']); ?>
			</div>
		</div>

		<div class="constructor-button-action flex jcc" style="margin-top: 20px">
			<div class="constructor-button-action-inner flex jcsb" style="width: 60%">
				<button class="button constructor-button-action-end" id="interview-button-del">Видалити</button>
				<button class="button constructor-button-action-end" id="interview-button-status"><?php echo (Interview::getStatus($_GET['id']) == 1) ? 'Закрити' : 'Відкрити' ?></button>
				<button class="button constructor-button-action-end" id="interview-button-report">Звіт</button>
			</div>
		</div>	
	</div>

	<script src="/template/scripts/action_block.js"></script>
	<script src="/template/scripts/interview_creator.js"></script>

	<?php require_once(ROOT."/views/layouts/footer.php"); ?>

</div>