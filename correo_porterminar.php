<?php
	//Incluimos librería y archivo de conexión
	session_start();
	require 'Classes/PHPExcel.php';
	require 'conexion.php';
   	require 'funcs/funcs.php';
   	require 'PHPMailer/PHPMailerAutoload.php';
   	setlocale(LC_TIME, 'es_ES.UTF-8');
      	
	//Consulta
	$sql = "SELECT vigente, id, nombre, marca, modelo, serie, proveedor, convenio, fechainicio, fechatermino, licitacion, contrato, oc FROM contratos";
	$resultado = $mysqli->query($sql);
	$fila = 2; //Establecemos en que fila inciara a imprimir los datos

	//Objeto de PHPExcel
	$objPHPExcel  = new PHPExcel(); 
	//$now = date("d-m-Y (H:i:s)", (strtotime ("-4 Hours")));
	
	$now1 = strftime("%A %e de %B del %Y");
	$tresmeses1 = strftime("%A %e de %B del %Y", (strtotime ("+3 Month")));
	$now = date("d-m-Y");
    $tresmeses = date("d-m-Y", (strtotime ("+3 Month")));
    $i = 0;
    
	//Propiedades de Documento
	$objPHPExcel->getProperties()->setCreator("Cristopher Diaz")->setDescription("Reporte de Contratos");
	
	//Establecemos la pestaña activa y nombre a la pestaña
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle("Contratos");

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Vigente');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Marca');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Modelo');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Serie');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Proveedor');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Convenio');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Fecha de Inicio');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Fecha de Termino');
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Licitacion');
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Contrato');
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Orden de Compra');
    
	//Recorremos los resultados de la consulta y los imprimimos
	while($rows = $resultado->fetch_assoc()){
		
		if(strtotime($rows['fechatermino']) <= strtotime($tresmeses) && strtotime($rows['fechatermino']) >= strtotime($now)){
		    
    		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $rows['vigente']);
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $rows['nombre']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $rows['marca']);
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $rows['modelo']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $rows['serie']);
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $rows['proveedor']);
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $rows['convenio']);
    		$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $rows['fechainicio']);
    		$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $rows['fechatermino']);
    		$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $rows['licitacion']);
    		$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $rows['contrato']);
    		$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $rows['oc']);
    		$i = 1;
    		$fila++; //Sumamos 1 para pasar a la siguiente fila
		}
	}
	
    if ($i > 0){
    
    	$sql = "SELECT nombre, correo FROM usuarios";
    	$result2 = $mysqli->query($sql);
    	
    	while($row2 = $result2->fetch_array(MYSQLI_ASSOC)){
    	    
    	           $nombre = $row2['nombre'];
        	    $email = $row2['correo'];
        	    echo $nombre.$email."<br>";
        	    
        	       $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            		$objWriter->save('Files/Excel - Por Terminar/ReporteContratosPorterminar'.$now.'.xlsx');
    	
        	   	$mail = new PHPMailer();
        		$mail->isSMTP();
        		$mail->SMTPAuth = true;
        		$mail->SMTPSecure = 'ssl';
        		$mail->Host = 'mail.emgestionhgf.com';
        		$mail->Port = '465';
        		
        		$mail->Username = 'emgestio@emgestionhgf.com';
        		$mail->Password = 'lagzero1342';
        		
        		$mail->setFrom('emgestio@emgestionhgf.com', 'Gestion de Contratos');
        		$mail->addAddress($email, $nombre);
        		
        		$mail->Subject = "Contrato por Terminar dentro de los proximos meses";
        		$mail->Body    = "Estimado(a) ".$nombre."<br><br>Se entrega el registro de los equipos asociados a contratos POR TERMINAR entre hoy ".$now1." y el ".$tresmeses1;
        		$mail->AddAttachment('Files/Excel - Por Terminar/ReporteContratosPorterminar'.$now.'.xlsx', 'ReporteContratosPorterminar '.$now.'.xlsx');
        		$mail->IsHTML(true);
        		
        		if($mail->send()){
        		unlink('Files/Excel - Por Terminar/ReporteContratosPorterminar'.$now.'.xlsx');
        		return true;
        		} else {
        		return false;
            	}
    	  }
    }
    
    
?>
