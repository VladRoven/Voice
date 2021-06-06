<?php require_once (ROOT.'/models/interview.php'); ?>

<link rel="shortcut icon" href="/template/images/logo.png" type="image/png">

<title>Опитування</title>
<div class="conteiner flex fdc jcsb">
	
	<?php require_once(ROOT."/views/layouts/header.php"); ?>
	
	<div class="constructor">
		<div class="constructor-name flex jcc">
			<p><?php echo Interview::getName($_GET['id']) ?></p>
		</div>

		<div class="interview-data flex jcc">
			<div class="interview-data-inner flex fdc jcc">
				<?php echo Interview::getQuestion($_GET['id'], 0); ?>
			</div>
		</div>	
		<div class="interview-button flex jcc" style="margin-top: 0">
			<div class="interview-button-inner flex jcsa">
				<button class="button" id="interview-button-back">Попереднє</button>
				<div class="interview-counter flex fdc jcc">
					<p class="flex jcc" id="interview-counter"><?php echo Interview::getCountQuestions($_GET['id']); ?></p>
				</div>
				<button class="button" id="interview-button-next" >Наступне</button>
			</div>
		</div>
	</div>

	<script src="/template/scripts/interview_user.js"></script>
	<script src="/template/scripts/action_block.js"></script>

	<?php require_once(ROOT."/views/layouts/footer.php"); ?>

</div>