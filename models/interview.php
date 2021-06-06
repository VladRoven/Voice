<?php 


require_once (ROOT.'/components/Gen.php');
include(ROOT.'/components/Classes/PHPExcel.php');

class Interview
{
	public static function isSetInterview($id)
	{
		$db = Database::getConnection();
		$query = "SELECT id_interview FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();
		$result = $result->fetch(PDO::FETCH_ASSOC)['id_interview'];

		if (!is_null($result)) 
		{
			return true;
		}
		return false;
	}

	public static function getName($id)
	{
		$db = Database::getConnection();
		$query = "SELECT name FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['name'];
	}

	public static function getIdInterview($id)
	{
		$db = Database::getConnection();
		$query = "SELECT id_interview FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['id_interview'];
	}

	public static function getId($id)
	{
		$db = Database::getConnection();
		$query = "SELECT id FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['id'];
	}

	public static function getPath($id)
	{
		$db = Database::getConnection();
		$query = "SELECT `path` FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['path'];
	}

	public static function getStatus($id)
	{
		$db = Database::getConnection();
		$query = "SELECT status FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['status'];
	}

	public static function deleteInterview($id, $path)
	{
		$db = Database::getConnection();
		$query = "DELETE FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		
		if ($result->execute()) 
		{
			if (file_exists($path)) 
			{
				unlink($path);
				return true;
			}
			return false;
		}

		return false;
	}

	public static function postInterview($id)
	{
		$name = Gen::name();

		$db = Database::getConnection();
		$query = "UPDATE interviews SET status = '1', id_interview = :idInterview, id_constructor = NULL WHERE id_constructor = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->bindParam(':idInterview', $name, PDO::PARAM_STR);

		
		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function getDataJson($id)
	{
		$db = Database::getConnection();
		$query = "SELECT `path` FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		$result = ROOT.$result->fetch(PDO::FETCH_ASSOC)['path'];

		if (file_exists($result)) 
		{
			$jsonData = file_get_contents($result);
			$json = json_decode($jsonData, true);
			return $json;
		}
		return false;
	}

	public static function getCountInterview($id)
	{
		$db = Database::getConnection();
		$query = "SELECT count FROM interviews WHERE id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['count'];
	}

	public static function getStatisticVariant($id_interview, $id_question, $id_variant)
	{
		$db = Database::getConnection();
		$query = "SELECT COUNT(variant) FROM answers WHERE id_interview = :id_interview AND id_question = :id_question AND variant = :id_variant";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->bindParam(':id_variant', $id_variant, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['COUNT(variant)'];
	}

	public static function getStatisticScale($id_interview, $id_question)
	{
		$db = Database::getConnection();
		$query = "SELECT SUM(scale) / COUNT(scale) FROM answers WHERE id_interview = :id_interview AND id_question = :id_question";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['SUM(scale) / COUNT(scale)'];
	}

	public static function getStatisticOpen($id_interview, $offset, $id_question)
	{
		$db = Database::getConnection();
		$query = "SELECT u.name, a.open FROM users AS u, answers AS a WHERE a.id_user = u.id AND a.id_interview = :id_interview AND a.id_question = :id_question AND a.open !='' ORDER BY a.id DESC LIMIT 3 OFFSET :offset";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->bindParam(':offset', $offset, PDO::PARAM_INT);
		$result->execute();

		return $result->fetchAll();
	}

	public static function getQuestionsForCreator($id)
	{
		$array = Interview::getDataJson($id);
		if (!empty($array)) 
		{
			foreach ($array as $value) 
			{	
				switch ($value['type']) 
				{
					case 1 :
						echo '<div class="interview-display interview-display-inrv">';
						echo '<p class="question-title flex jcc">'.$value['title'].'</p>';
						echo '<div class="interview-variants flex fdc">';
						foreach ($value['variants'] as $val) 
						{
							$temp = Interview::getStatisticVariant($id, $value['id'], $val['id']);
							if ($temp != 0) 
							{
								$temp = round((($temp / Interview::getCountInterview($id)) * 100), 1);
							}
						 	echo '<div class="creator-variant flex alic" style="background: linear-gradient(to right, rgba(173, 205, 252, 0.4) '.$temp.'%, white '.$temp.'%)"><p class="flex jcfs" title="'.$val['variant'].'">'.mb_strimwidth($val['variant'], 0, 20, '..').'</p><p class="flex fdc jcc">'.$temp.'%</p></div>';
						}
						echo '</div>';
						echo '</div>';
						break;
					case 2:
						echo 
						'<div class="interview-display interview-display-inrv">
							<p class="question-title flex jcc">'.$value['title'].'</p>
							<div class="creator-slider flex jcc">
								<p>Середня відповідь: '.round(Interview::getStatisticScale($id, $value['id']), 1).'</p>
							</div>
						</div>';
						break;
					case 3:
					echo
					'<div class="interview-display open interview-display-inrv" id="'. $value['id'].'">
						<p class="question-title flex jcc">'.$value['title'].'</p>';
						echo Interview::showOpenAnswer($id, 0, $value['id']);
					echo '</div>';
					break;
				}
			}
		}
	}

	public static function getQuestionsForConstructor($id)
	{
		$array = Interview::getDataJson($id);
		if (!empty($array)) 
		{
			foreach ($array as $value) 
			{	
				switch ($value['type']) 
				{
					case 1 :
						echo 
						'<div class="interview-display">
							<div class="flex jcsb">
								<img src="/template/images/constructor_delete.svg" alt="Видалити" title="Видалити" class="constructor-answer-del">
								<p class="question-title flex jcc">'.$value['title'].'</p>
								<img src="/template/images/constructor_edit.svg" alt="Редагувати" title="Редагувати" class="constructor-answer-edit">
							</div>
						<div class="interview-variants flex fdc">';
						foreach ($value['variants'] as $val) 
						{
						 	echo '<div class="constructor-variant-show flex jcc"><p class="flex jcc fdc">'.$val['variant'].'</p></div>';
						}
						echo '</div>';
						echo '</div>';
						break;
					case 2:
						echo 
						'<div class="interview-display">
							<div class="flex jcsb">
								<img src="/template/images/constructor_delete.svg" alt="Видалити" title="Видалити" class="constructor-answer-del">
								<p class="question-title flex jcc">'.$value['title'].'</p>
								<img src="/template/images/constructor_edit.svg" alt="Редагувати" title="Редагувати" class="constructor-answer-edit">
							</div>
							<div class="flex jcc">
								<div class="constructor-scale-add flex alic jcsa">
									<div class="scale-circle"></div>
									<div class="scale-line"></div>
									<div class="scale-circle"></div>
								</div>
							</div>
							<div class="creator-slider flex jcsb" style="padding: 0 5%;margin-top: 30px">
								<div class="constructor-scale-param-show flex jcsa">
									<p class="flex jcc fdc constructor-scale-param-text" style="color: white">'.$value['from'].'</p>
								</div>
								<div class="flex jcc">
									<p class="flex jcc fdc constructor-scale-param-text">Шаг: '.$value['step'].'</p>
								</div>
								<div class="constructor-scale-param-show flex jcc">
									<p class="flex jcc fdc constructor-scale-param-text" style="color: white">'.$value['to'].'</p>
								</div>
							</div>
						</div>';
						break;
					case 3:
						echo
						'<div class="interview-display">
							<div class="flex jcsb">
								<img src="/template/images/constructor_delete.svg" alt="Видалити" title="Видалити" class="constructor-answer-del">
								<p class="question-title flex jcc">'.$value['title'].'</p>
								<img src="/template/images/constructor_edit.svg" alt="Редагувати" title="Редагувати" class="constructor-answer-edit">
							</div>
							<p class="constructor-open-show flex jcc">Дане питання є відкритим.</p>
						</div>';
						break;
				}
			}
		}
		else
		{
			echo 'false';
		}
	}

	public static function getCountQuestions($id)
	{
		$array = Interview::getDataJson($id);
		return count($array);
	}

	public static function getQuestion($id, $itr)
	{
		$array = Interview::getDataJson($id);
		if ($itr < count($array)) 
		{
			$question = $array[$itr];
			switch ($question['type']) 
			{
				case 1 :
					echo '<div class="interview-display interview-display-inrv">';
					echo '<p class="question-title flex jcc">'.$question['title'].'</p>';
					echo '<div class="interview-variants flex fdc">';
					foreach ($question['variants'] as $val) 
					{
					 	echo '<div class="interview-variant flex jcc"><p class="flex fdc jcc">'.$val['variant'].'</p></div>';
					}
					echo '</div>';
					echo '</div>';
					break;
				case 2:
					echo 
					'<div class="interview-display interview-display-inrv">
						<p class="question-title flex jcc">'.$question['title'].'</p>
						<div class="slider flex jcc">
							<div class="slider-inner flex fdc jcfe">
								<div class="input-slider flex jcsa">
									<div class="flex jcc">
										<p class="flex jcc fdc">'.$question['from'].'</p>
									</div>
									<input id="slider" type="range" min="'.$question['from'].'" max="'.$question['to'].'" step="'.$question['step'].'" value="'.$question['from'].'">
									<div class="flex jcc">
										<p class="flex jcc fdc">'.$question['to'].'</p>
									</div>
								</div>
								<div class="value flex jcc">
									<p class="slider-value flex jcc fdc">'.$question['from'].'</p>
								</div>
							</div>
						</div>
					</div>';
					break;
				case 3:
				echo
				'<div class="interview-display interview-display-inrv">
					<p class="question-title flex jcc">'.$question['title'].'</p>
					<div class="text-field flex jcc">
						<div class="text-field-inner flex jcc">
							<textarea class="text-field-input" placeholder="Ваше повідомлення (можна залишити пустим)" maxlength="2000"></textarea>
						</div>
					</div>
				</div>';
				break;
			}
		}
	}

	public static function setQuestion($id, $itr)
	{
		if ($itr < 0 || $itr + 1 > Interview::getCountQuestions($id)) 
		{
			return false;
		}
		else
		{
			return Interview::getQuestion($id, $itr);
		}
	}

	public static function addAnswerVariant($id_interview, $id_user, $id_question, $variant)
	{
	    $db = Database::getConnection();
		$query = "INSERT INTO answers (id_user, id_interview, id_question, variant) VALUES (:id_user, :id_interview, :id_question, :variant)";

		$result = $db->prepare($query);
		$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->bindParam(':variant', $variant, PDO::PARAM_INT);


		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function addAnswerScale($id_interview, $id_user, $id_question, $variant)
	{
	    $db = Database::getConnection();
		$query = "INSERT INTO answers (id_user, id_interview, id_question, scale) VALUES (:id_user, :id_interview, :id_question, :variant)";

		$result = $db->prepare($query);
		$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->bindParam(':variant', $variant, PDO::PARAM_INT);


		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function addAnswerText($id_interview, $id_user, $id_question, $variant)
	{
	    $db = Database::getConnection();
		$query = "INSERT INTO answers (id_user, id_interview, id_question, open) VALUES (:id_user, :id_interview, :id_question, :variant)";

		$result = $db->prepare($query);
		$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->bindParam(':variant', $variant, PDO::PARAM_STR);


		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function updateCount($id)
	{
		$db = Database::getConnection();
		$query = "UPDATE interviews SET count = count + 1 WHERE id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
	
		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function checkUserAnswer($id_user, $id_interview)
	{
		$db = Database::getConnection();
		$query = "SELECT id_user FROM answers WHERE id_interview = :id_interview AND id_user = :id_user";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id_interview, PDO::PARAM_STR);
		$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
		$result->execute();

		$result = $result->fetch(PDO::FETCH_ASSOC)['id_user'];

		if ($result == $id_user) 
		{
			return false;
		}
		return true;
	}

	public static function getDateStart($id)
	{
		$db = Database::getConnection();
		$query = "SELECT date_start FROM interviews WHERE id_interview = :id_interview";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id, PDO::PARAM_STR);
		$result->execute();

		$result = $result->fetch(PDO::FETCH_ASSOC)['date_start'];

		$monthsList = array(".01." => "січеня", ".02." => "лютого", 
		".03." => "березеня", ".04." => "квітеня", ".05." => "травня", ".06." => "червня", 
		".07." => "липня", ".08." => "серпня", ".09." => "вересня",
		".10." => "жовтня", ".11." => "листопада", ".12." => "грудня");

		date_default_timezone_set('UTC');
		$date = new DateTime($result);
		$month = $date->format(".m.");

		return $date->format('d') . ' ' . $monthsList[$month] . ' ' . $date->format('Y');
	}

	public static function getDateEnd($id)
	{
		$db = Database::getConnection();
		$query = "SELECT date_end FROM interviews WHERE id_interview = :id_interview";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id, PDO::PARAM_STR);
		$result->execute();

		$result = $result->fetch(PDO::FETCH_ASSOC)['date_end'];
		
		if (is_null($result)) 
		{
			return 'Не завершено';
		}
		$monthsList = array(".01." => "січеня", ".02." => "лютого", 
		".03." => "березеня", ".04." => "квітеня", ".05." => "травня", ".06." => "червня", 
		".07." => "липня", ".08." => "серпня", ".09." => "вересня",
		".10." => "жовтня", ".11." => "листопада", ".12." => "грудня");

		date_default_timezone_set('europe/kiev');
		$date = new DateTime($result);
		$month = $date->format(".m.");

		return $date->format('d') . ' ' . $monthsList[$month] . ' ' . $date->format('Y');
	}

	public static function getCountOpenAnswer($id, $id_question)
	{
		$db = Database::getConnection();
		$query = "SELECT COUNT(open) FROM answers WHERE id_interview = :id_interview AND id_question = :id_question AND open != ''";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['COUNT(open)'];
	}

	public static function showOpenAnswer($id, $offset, $id_question)
	{
		$arrayOpen = Interview::getStatisticOpen($id, $offset, $id_question);
		$count = Interview::getCountOpenAnswer($id, $id_question);
		$result = '';

		if ($count < 1) 
		{
			$result = 
			'<div class="constructor-interview-data flex jcc">
				<div class="constructor-interview-data-inner" style="width: 100%">
					<p class="flex jcc" id="empty">Немає жодної відповіді</p>
				</div>
			</div>';
			return $result;
		}
		if (empty($arrayOpen)) 
		{
			return '';
		}
		foreach ($arrayOpen as $value) 
		{
			if ($value['open'] != '') 
			{
				$result = $result .
				'<div class="statistic-text-field">
					<p>'.$value['name'].'</p>
					<p>'.$value['open'].'</p>
				</div>';
			}
		}
		if (count($arrayOpen) == 3) 
		{
			$result = $result .
			'<div class="next" style="margin-top: 20px">
				<div class="next-inner" style="cursor: pointer">
					<div class="next-up">
						<div class="next-one"></div>
						<div class="next-two"></div>
					</div>
					
					<div class="next-down hidden">
						<div class="next-one"></div>
						<div class="next-two"></div>
					</div>
				</div>
			</div>';
		}
		return $result;
	}

	public static function changeStatus($id)
	{
		$currentStatus = Interview::getStatus($id);
		$status = ($currentStatus == '1') ? '0' : '1';
		$dateEnd = ($status == '0') ? date('Y-m-d') : null;
		$db = Database::getConnection();
		$query = "UPDATE interviews SET status = :status, date_end = :date_end WHERE id_interview = :id";

		$result = $db->prepare($query);
	
		if ($result->execute([
			':id' => $id,
			':status' => $status,
			':date_end' => $dateEnd
		])) 
		{
			return true;
		}

		return false;
	}

	public static function deleteInterviewActive($id)
	{
		$path = Interview::getPath($id);
		$del_json = Interview::deleteInterview($id, ROOT.$path);

		if ($del_json) 
		{
			$db = Database::getConnection();
			$query = "DELETE FROM answers WHERE id_interview = :id";

			$result = $db->prepare($query);
			$result->bindParam(':id', $id, PDO::PARAM_STR);
			
			if ($result->execute())
			{
				return true;
			}
			return false;
		}
		return false;
	}

	public static function setJson($id, $array)
	{
		$arrayJson = json_decode($array);
		$arrayGet = Interview::getDataJson($id);
		if (is_null($arrayGet)) 
		{
			$arrayGet = [];
		}
		foreach ($arrayJson as $value) 
		{
			array_push($arrayGet, $value);
		}
		$arrayGet = json_encode($arrayGet);
		$path = Interview::getPath($id);
		if (file_exists(ROOT.$path)) 
		{
			$jsonData = file_put_contents(ROOT.$path, $arrayGet);
			if ($jsonData > 0) 
			{
				return true;
			}
			return false;
		}
		return false;
	}

	public static function deleteQuestion($id, $index, $array)
	{
		$path = Interview::getPath($id);
		$setId = 0;
		unset($array[$index]);

		foreach ($array as $key => $value) 
		{
			$array[$key]['id'] = $setId++;
		}

		$array = json_encode(array_values($array));
		if (file_exists(ROOT.$path)) 
		{
			$jsonData = file_put_contents(ROOT.$path, $array);
			if ($jsonData > 0) 
			{
				return true;
			}
			return false;
		}
		return false;
	}

	public static function editJson($id, $array, $index)
	{
		$path = Interview::getPath($id);
		$arrayJson = Interview::getDataJson($id);
		$arrayJson[$index] = json_decode($array)[0];
		$arrayJson = json_encode(array_values($arrayJson));
		if (file_exists(ROOT.$path)) 
		{
			$jsonData = file_put_contents(ROOT.$path, $arrayJson);
			if ($jsonData > 0) 
			{
				return true;
			}
			return false;
		}
		return false;
	}

	public static function getCountAnswerUser($id, $id_question, $variant)
	{
		$db = Database::getConnection();
		$query = "SELECT COUNT(variant) FROM answers WHERE id_interview = :id_interview AND id_question = :id_question AND variant = :variant";

		$result = $db->prepare($query);
		$result->bindParam(':id_interview', $id, PDO::PARAM_STR);
		$result->bindParam(':id_question', $id_question, PDO::PARAM_INT);
		$result->bindParam(':variant', $variant, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['COUNT(variant)'];
	}

	public static function report($id)
	{
		$name = Interview::getName($id);
		$gen_name = Gen::name();
		$excel = new PHPExcel();
		$array = Interview::getDataJson($id);
		$itr = 1;

		$excel->setActiveSheetIndex(0);
		$active_sheet = $excel->getActiveSheet();
		$active_sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    	$active_sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    	$active_sheet->setTitle("Лист 1");
    	$active_sheet->getColumnDimension('A')->setWidth(30);
    	$active_sheet->getStyle('B:C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    	$active_sheet->getStyle('A:B')->getAlignment()->setWrapText(true);
    	$active_sheet->getStyle('C')->getAlignment()->setWrapText(true);
    	$active_sheet->getStyle('A:B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    	$active_sheet->getStyle('C')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    	$active_sheet->getColumnDimension('C')->setWidth(12);

	    if (!empty($array)) 
		{
			foreach ($array as $value) 
			{
				switch ($value['type']) 
				{
					case 1:
						$active_sheet->getStyle('A'.$itr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$active_sheet->getStyle('A'.$itr.':C'.$itr)->getFont()->setBold(true);
						$active_sheet->getStyle('A'.$itr.':C'.$itr)->getFont()->setSize(11);
						$active_sheet->getStyle('A'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7E9AFF');
						$active_sheet->getStyle('C'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7291FF');
						$active_sheet->mergeCells('A'.$itr.':B'.$itr);

						$active_sheet->setCellValue('C'.$itr, 'Кіл-ть голосів');
						$active_sheet->setCellValue('A'.$itr++, $value['title']);
						foreach ($value['variants'] as $val) 
						{
							$count = Interview::getCountAnswerUser($id, $value['id'], $val['id']);
							$temp = Interview::getStatisticVariant($id, $value['id'], $val['id']);
							if ($temp != 0) 
							{
								$temp = round((($temp / Interview::getCountInterview($id)) * 100), 1);
							}
						 	$active_sheet->setCellValue('A'.$itr, $val['variant']);
						 	$active_sheet->setCellValue('C'.$itr, $count);
						 	$active_sheet->getStyle('A'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('D0DAFF');
						 	$active_sheet->getStyle('C'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('D0DAFF');
						 	$active_sheet->getStyle('B'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('BAC9FF');

						 	$active_sheet->setCellValue('B'.$itr++, $temp.'%');
						}
						break;
					
					case 2:
						$active_sheet->getStyle('A'.$itr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$active_sheet->getStyle('A'.$itr)->getFont()->setBold(true);
						$active_sheet->getStyle('A'.$itr)->getFont()->setSize(11); 
						$active_sheet->getStyle('A'.$itr.':B'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7E9AFF');
						$active_sheet->getStyle('C'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7E9AFF');
						$active_sheet->mergeCells('A'.$itr.':B'.$itr);

						$active_sheet->setCellValue('A'.$itr++, $value['title']);
						$active_sheet->mergeCells('A'.$itr.':B'.$itr);
						$active_sheet->setCellValue('A'.$itr, 'Середня відповідь');
						$active_sheet->getStyle('A'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('D0DAFF');
						$active_sheet->getStyle('C'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('BAC9FF');
						$active_sheet->setCellValue('C'.$itr++, round(Interview::getStatisticScale($id, $value['id']), 1));
						break;
				}
			}
		}
		$active_sheet->setCellValue('A'.$itr, 'Дата початку/закінчення');
		$active_sheet->mergeCells('A'.$itr.':B'.$itr);
		$active_sheet->getStyle('A'.$itr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$active_sheet->getStyle('A'.$itr.':C'.$itr)->getFont()->setBold(true);
		$active_sheet->getStyle('A'.$itr.':C'.$itr)->getFont()->setSize(11);
		$active_sheet->getStyle('A'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7E9AFF');
		$active_sheet->getStyle('C'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('7291FF');
		$active_sheet->setCellValue('C'.$itr++, 'Учасників');

		$active_sheet->setCellValue('A'.$itr, 'Початок: '.Interview::getDateStart($id));
		$active_sheet->getStyle('A'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('D0DAFF');
		$active_sheet->setCellValue('C'.$itr, Interview::getCountInterview($id));
		$active_sheet->getStyle('C'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('BAC9FF');
		$active_sheet->mergeCells('A'.$itr.':B'.$itr);
		$active_sheet->getStyle('C'.$itr)->getFont()->setSize(15);
		$active_sheet->mergeCells('C'.$itr.':C'.++$itr);
		$active_sheet->mergeCells('A'.$itr.':B'.$itr);
		$active_sheet->setCellValue('A'.$itr, 'Кінець: '.Interview::getDateEnd($id));
		$active_sheet->getStyle('A'.$itr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('D0DAFF');




	    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		header('Content-Type: application/vnd.ms-Excel');
		header('Content-Disposition: attachment;filename='.$gen_name.'.xlsx');
		header('Cache-Control: max-age=0');
		$objWriter->save(ROOT.'/data/'.$gen_name.'.xlsx');
		return [$name, $gen_name];
	}
}
		

 ?>