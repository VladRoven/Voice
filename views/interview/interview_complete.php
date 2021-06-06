<title>Опитування</title>
<div class="conteiner flex fdc jcsb">
	
	<?php require_once(ROOT."/views/layouts/header.php"); ?>

	<div class="constructor">
		<div class="constructor-name flex jcc">
			<p style="font-size: 40px; width: 70%"><?php if(Interview::getStatus($_GET['id']) == 1) {echo "Опитування вже було пройдено. Ви не можете пройти одне опитування більше одного разу!";} elseif(Interview::getStatus($_GET['id']) == 0) {echo "Опитування було закрито творцем!";} ?><br><br>Вас будете переміщено автоматично через <cite style="font-family: 'Ubuntu', sans-serif; font-style: normal;" id="interview-complete"></cite> сек. <br>на сторінку власного кабінету.</p>
		</div>
	</div>

	<script src="/template/scripts/interview_complete.js"></script>

	<?php require_once(ROOT."/views/layouts/footer.php"); ?>

</div>