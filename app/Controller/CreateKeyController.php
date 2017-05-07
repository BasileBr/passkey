<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 07/05/2017
 * Time: 15:05
 */
class CreateKeyController
{
	public function __construct()
	{
		if (!isset($_POST['key_name']) && !isset($_POST['key_type']) && !isset($_POST['key_lock'])) {
			// If we have no values, the form is displayed.
			$this->displayForm();
		} elseif (empty($_POST['key_name']) || empty($_POST['key_type']) || empty($_POST['key_lock'])) {
			// If we have not all values, error message display and form.
			$type = "danger";
			$message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$this->lockMessage($type, $message);
		} else {
			// If the sheet in datas.xlsx is not created.
			$exist = false;
			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load("datas/datas.xlsx");
			$sheets = $objPHPExcel->getAllSheets();

			foreach ($sheets as $sheet) {
				if ($sheet->getTitle() == 'Keys') {
					$exist = true;
				}
			}

			if ( !$exist) {
				$this->createKeyFile();
			}

			// If we have all the values.
			$datas = array(
				'key_name' => addslashes($_POST['key_name']),
				'key_type' => addslashes($_POST['key_type']),
				'key_lock' => addslashes($_POST['key_lock']),
				'key_number' => addslashes($_POST['key_number'])
			);

			$this->writeInFile($datas);

			$type = "success";
			$message = "La clé a bien été enregistrée.";
			$this->KeyMessage($type, $message);
		}
	}

	public function displayForm() {
		$locks = CreateLockController::getLocks();
		$composite = new CompositeView();
		$templates[] = array("name" => "head.php");
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "keys/create_key.html.twig", 'variables' => array('locks' => $locks));
		$templates[] = array("name" => "foot.php");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function keyMessage($type, $message) {
		$locks = CreateLockController::getLocks();
		$composite = new CompositeView();
		$templates[] = array("name" => "head.php");
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "submit_message.html.twig", "variables" => array("alert_type" => $type , "alert_message" => $message));
		$templates[] = array("name" => "keys/create_key.html.twig", 'variables' => array('locks' => $locks));
		$templates[] = array("name" => "foot.php");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function createKeyFile() {
		// We only modify the file datas.xlsx because we have to create doors before locks.
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");

		// Create a new worksheet called "My Data"
		$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Keys');

		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
		$objPHPExcel->addSheet($myWorkSheet);
		$objPHPExcel->setActiveSheetIndex(2);

		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Key id');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Key name');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Key type');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Key lock');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Key number');
		$objPHPExcel->getActiveSheet()->setTitle('Keys');

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save("datas/datas.xlsx");
	}

	public function writeInFile($datas) {
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");

		$objPHPExcel->setActiveSheetIndex(2);
		$lastRow = $objPHPExcel->getActiveSheet()->getHighestDataRow();
		$id = $lastRow-1;
		$row = $lastRow + 1;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $id);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $datas['key_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $datas['key_type']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $datas['key_lock']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $datas['key_number']);

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save("datas/datas.xlsx");
	}

	public static function getKeys() {
		$keys = array();
		// Read Excel file.
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");
		$objPHPExcel->setActiveSheetIndex(2);
		$sheet = $objPHPExcel->getActiveSheet();
		$lastRow = $sheet->getHighestDataRow();

		for ($i = 2; $i <= $lastRow; $i++) {
			$key_id = $sheet->getCell('A'.$i)->getValue();
			$key_name = $sheet->getCell('B'.$i)->getValue();
			$key_type = $sheet->getCell('C'.$i)->getValue();
			$key_lock = $sheet->getCell('D'.$i)->getValue();
			$key_number = $sheet->getCell('E'.$i)->getValue();

			if ($key_id != '') {
				$keys[] = array(
					'key_id' => $key_id,
					'key_name' => $key_name,
					'key_type' => $key_type,
					'key_lock' => $key_lock,
					'key_number' => $key_number
				);
			}
		}
		return $keys;
	}

	public function getKeyValues($row) {
		$values = array();
		// Read Excel file.
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");
		$objPHPExcel->setActiveSheetIndex(2);
		$sheet = $objPHPExcel->getActiveSheet();

		$key_id = $sheet->getCell('A'.$row)->getValue();
		$key_name = $sheet->getCell('B'.$row)->getValue();
		$key_type = $sheet->getCell('C'.$row)->getValue();
		$key_lock = $sheet->getCell('D'.$row)->getValue();
		$key_number = $sheet->getCell('E'.$row)->getValue();

		$values = array(
			'key_id' => $key_id,
			'key_name' => $key_name,
			'key_type' => $key_type,
			'key_lock' => $key_lock,
			'key_number' => $key_number
		);
		return $values;
	}

}