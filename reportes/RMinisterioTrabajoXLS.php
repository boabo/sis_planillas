<?php
//incluimos la libreria
//echo dirname(__FILE__);
//include_once(dirname(__FILE__).'/../PHPExcel/Classes/PHPExcel.php');
class RMinisterioTrabajoXLS
{
	private $docexcel;
	private $objWriter;
	private $nombre_archivo;
	private $hoja;
	private $columnas=array();
	private $fila;
	private $equivalencias=array();
	
	private $indice, $m_fila, $titulo;
	private $swEncabezado=0; //variable que define si ya se imprimi� el encabezado
	private $objParam;
	public  $url_archivo;
	private $resumen = array();	
	
	function __construct(CTParametro $objParam){
		
		//reducido menos 23,24,26,27,29,30
		$this->objParam = $objParam;
		$this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
		//ini_set('memory_limit','512M');
		set_time_limit(400);
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize'  => '10MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$this->docexcel = new PHPExcel();
		$this->docexcel->getProperties()->setCreator("PXP")
							 ->setLastModifiedBy("PXP")
							 ->setTitle($this->objParam->getParametro('titulo_archivo'))
							 ->setSubject($this->objParam->getParametro('titulo_archivo'))
							 ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Report File");
		
		$sheetId = 1;
		$this->docexcel->createSheet(NULL, $sheetId);	
		$this->docexcel->setActiveSheetIndex($sheetId);	
		$this->docexcel->getActiveSheet()->setTitle('OVTPLA-T01');
		
		$this->docexcel->setActiveSheetIndex(0);
		
		$this->equivalencias=array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
								9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
								18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
								26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
								34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
								42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
								50=>'AY',51=>'AZ',
								52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
								60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
								68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
								76=>'BY',77=>'BZ');		
									
	}			
	
	function imprimeDatosSueldo(){
		$this->docexcel->getActiveSheet()->setTitle('OVTPLA-T02');
		$datos = $this->objParam->getParametro('datos');	
		$columnas = 0;
		$this->docexcel->setActiveSheetIndex(0);
		
		$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
		$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(6);
		$this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AO')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AP')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AR')->setWidth(25);
		
		$styleTitulos = array(
		    'font'  => array(
		        'bold'  => true,
		        'size'  => 8,
		        'name'  => 'Arial'
		    ),
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		    ),
			'fill' => array(
        		'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
            		'rgb' => 'c5d9f1'
            	)
        	),
			'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN
	        )
	    ));
		$this->docexcel->getActiveSheet()->getStyle('A1:AR1')->getAlignment()->setWrapText(true); 	
		
		$this->docexcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleTitulos);
		$styleTitulos['fill']['color']['rgb'] = '8DB4E2';
		$this->docexcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleTitulos);
		$styleTitulos['fill']['color']['rgb'] = 'FFFFFF';
		$this->docexcel->getActiveSheet()->getStyle('P1:AR1')->applyFromArray($styleTitulos);
		//*************************************Cabecera*****************************************
		$this->docexcel->getActiveSheet()->setCellValue('A1','Nº');
		$this->docexcel->getActiveSheet()->setCellValue('B1','Tipo de documento de identidad');
		$this->docexcel->getActiveSheet()->setCellValue('C1','Número de documento de identidad');
		$this->docexcel->getActiveSheet()->setCellValue('D1','Extensión del documento de identidad');
		$this->docexcel->getActiveSheet()->setCellValue('E1','AFP a la que aporta');
		$this->docexcel->getActiveSheet()->setCellValue('F1','NUA/CUA');
		$this->docexcel->getActiveSheet()->setCellValue('G1','Apellido Paterno');
		$this->docexcel->getActiveSheet()->setCellValue('H1','Apellido Materno');
		$this->docexcel->getActiveSheet()->setCellValue('I1','Apellido de casada');
		$this->docexcel->getActiveSheet()->setCellValue('J1','Primer nombre');		
		$this->docexcel->getActiveSheet()->setCellValue('K1','Otros nombres');
		$this->docexcel->getActiveSheet()->setCellValue('L1','País de nacionalidad');
		$this->docexcel->getActiveSheet()->setCellValue('M1','Fecha de nacimiento');
		$this->docexcel->getActiveSheet()->setCellValue('N1','Sexo');
		$this->docexcel->getActiveSheet()->setCellValue('O1','Jubilado');
		$this->docexcel->getActiveSheet()->setCellValue('P1','Clasificación laboral');
		$this->docexcel->getActiveSheet()->setCellValue('Q1','Cargo');
		$this->docexcel->getActiveSheet()->setCellValue('R1','Fecha de ingreso');
		$this->docexcel->getActiveSheet()->setCellValue('S1','Modalidad de contrato');
		$this->docexcel->getActiveSheet()->setCellValue('T1','Fecha de retiro');
		$this->docexcel->getActiveSheet()->setCellValue('U1','Horas pagadas (día)');
		$this->docexcel->getActiveSheet()->setCellValue('V1','Días pagados (mes)');
		$this->docexcel->getActiveSheet()->setCellValue('W1','Nº de dominicales');
		$this->docexcel->getActiveSheet()->setCellValue('X1','Domingos trabajados');
		$this->docexcel->getActiveSheet()->setCellValue('Y1','Horas extra');
		$this->docexcel->getActiveSheet()->setCellValue('Z1','Horas de recargo nocturno');
		$this->docexcel->getActiveSheet()->setCellValue('AA1','Horas extra dominicales');
		$this->docexcel->getActiveSheet()->setCellValue('AB1','Haber básico');
		$this->docexcel->getActiveSheet()->setCellValue('AC1','Salario dominical');
		$this->docexcel->getActiveSheet()->setCellValue('AD1','Monto pagado por domingo trabajado');
		$this->docexcel->getActiveSheet()->setCellValue('AE1','Monto pagado por horas extra');
		$this->docexcel->getActiveSheet()->setCellValue('AF1','Monto pagado por horas nocturnas');
		$this->docexcel->getActiveSheet()->setCellValue('AG1','Monto pagado por horas extra dominicales');
		$this->docexcel->getActiveSheet()->setCellValue('AH1','Bono de antiguedad');
		$this->docexcel->getActiveSheet()->setCellValue('AI1','Bono de producción');
		$this->docexcel->getActiveSheet()->setCellValue('AJ1','Subsidio de frontera');
		$this->docexcel->getActiveSheet()->setCellValue('AK1','Otros bonos o pagos');
		$this->docexcel->getActiveSheet()->setCellValue('AL1','Total ganado');
		$this->docexcel->getActiveSheet()->setCellValue('AM1','Aporte a las AFPs');
		$this->docexcel->getActiveSheet()->setCellValue('AN1','RC-IVA');
		$this->docexcel->getActiveSheet()->setCellValue('AO1','Otros descuentos');
		$this->docexcel->getActiveSheet()->setCellValue('AP1','Total descuentos');
		$this->docexcel->getActiveSheet()->setCellValue('AQ1','Líqido pagable');
		$this->docexcel->getActiveSheet()->setCellValue('AR1','Sucursal o ubicación adicional');
		
		
		
		//*************************************Detalle*****************************************
		$numero = 0;
		$columna = 0;
		$fila = 1;
		$this->resumen['basico'] = 0;
		$this->resumen['antiguedad'] = 0;
		$this->resumen['frontera'] = 0;
		$this->resumen['otros_bonos'] = 0;
		$this->resumen['total_ganado'] = 0;
		$this->resumen['afp'] = 0;
		$this->resumen['iva'] = 0;
		$this->resumen['otros_descuentos'] = 0;
		$this->resumen['total_descuentos'] = 0;
		$this->resumen['liquido_pagable'] = 0;
		$this->resumen['trabajadores_varones'] = 0;
		$this->resumen['trabajadores_mujeres'] = 0;
		$this->resumen['jubilados_varones'] = 0;
		$this->resumen['jubilados_mujeres'] = 0;
		$this->resumen['extranjeros_varones'] = 0;
		$this->resumen['extranjeros_mujeres'] = 0;
		$this->resumen['discapacitados_varones'] = 0;
		$this->resumen['discapacitados_mujeres'] = 0;
		$this->resumen['contrato_varones'] = 0;
		$this->resumen['contrato_mujeres'] = 0;
		$this->resumen['retiro_varones'] = 0;
		$this->resumen['retiro_mujeres'] = 0;
		foreach($datos as $value) {
			
			if ($numero != $value['fila']) {
				$fila++;
				$columna = 0;
				if ($value['sexo']== 1) {
					$this->resumen['trabajadores_varones']++;
					if ($value['jubilado']== 1) {
						$this->resumen['jubilados_varones']++;
					}
					
					if ($value['nacionalidad']!= 'Bolivia') {
						$this->resumen['extranjeros_varones']++;
					}
					
					if ($value['discapacitado']== 'si') {
						$this->resumen['discapacitados_varones']++;
					}

					if ($value['contrato_periodo']== 'si') {
						$this->resumen['contrato_varones']++;
					}
					
					if ($value['retiro_periodo']== 'si') {
						$this->resumen['retiro_varones']++;
					}
				}//varon			
				else {
					$this->resumen['trabajadores_mujeres']++;
					if ($value['jubilado']== 1) {
						$this->resumen['jubilados_mujeres']++;
					}
					if ($value['nacionalidad']!= 'Bolivia') {
						$this->resumen['extranjeros_mujeres']++;
					}
					if ($value['discapacitado']== 'si') {
						$this->resumen['discapacitados_mujeres']++;
					}

					if ($value['contrato_periodo']== 'si') {
						$this->resumen['contrato_mujeres']++;
					}
					
					if ($value['retiro_periodo']== 'si') {
						$this->resumen['retiro_mujeres']++;
					}
				}				
				foreach ($value as $key => $val) {
					if ($key != 'codigo_columna' && $key != 'valor'&& $key != 'oficina' && $key != 'discapacitado'&& $key != 'contrato_periodo'&& $key != 'retiro_periodo') {
						$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,$val);
						$columna++;
					}
				}
				$numero = $value['fila'];
				
			}
			
			if ($value['codigo_columna'] == 'BONFRONTERA') {
				$this->resumen['frontera'] = $this->resumen['frontera'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'SUELDOBA') {
				$this->resumen['basico'] = $this->resumen['basico'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'BONANT') {
				$this->resumen['antiguedad'] = $this->resumen['antiguedad'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'REINBANT') {
				$this->resumen['otros_bonos'] = $this->resumen['otros_bonos'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'COTIZABLE') {
				$this->resumen['total_ganado'] = $this->resumen['total_ganado'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'AFP_LAB') {
				$this->resumen['afp'] = $this->resumen['afp'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'IMPURET') {
				$this->resumen['iva'] = $this->resumen['iva'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'OTRO_DESC') {
				$this->resumen['otros_descuentos'] = $this->resumen['otros_descuentos'] + $value['valor'];
			}
			
			if ($value['codigo_columna'] == 'TOT_DESC') {
				$this->resumen['total_descuentos'] = $this->resumen['total_descuentos'] + $value['valor'];
			}
			if ($value['codigo_columna'] == 'LIQPAG') {
				$this->resumen['liquido_pagable'] = $this->resumen['liquido_pagable'] + $value['valor'];
			}
			
			
			$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,$value['valor']);
			$columna++;
			
			if ($columna == 22) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 23) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 24) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 25) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 26) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 28) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 29) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 30) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 31) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 32) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			
			if ($columna == 34) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			if ($columna == 43) {
				$this->docexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columna,$fila,$value['oficina']);
				$columna++;
			}
		}

		
		//************************************************Fin Detalle***********************************************
	}

	function imprimeDatosSueldoReducido(){
		
		$datos = $this->objParam->getParametro('datos');	
		$columnas = 0;
		$this->docexcel->setActiveSheetIndex(1);
		//reducido menos 23,24,26,27,29,30,32,33
		$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
		$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(6);
		$this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);		
		$this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);		
		
		$styleTitulos = array(
		    'font'  => array(
		        'bold'  => true,
		        'size'  => 8,
		        'name'  => 'Arial'
		    ),
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		    ),
			'fill' => array(
        		'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
            		'rgb' => 'c5d9f1'
            	)
        	),
			'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN
	        )
	    ));
		$this->docexcel->getActiveSheet()->getStyle('A1:AH1')->getAlignment()->setWrapText(true); 	
		
		$this->docexcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleTitulos);
		$styleTitulos['fill']['color']['rgb'] = '8DB4E2';
		$this->docexcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleTitulos);
		$styleTitulos['fill']['color']['rgb'] = 'FFFFFF';
		$this->docexcel->getActiveSheet()->getStyle('P1:AH1')->applyFromArray($styleTitulos);
		//*************************************Cabecera*****************************************
		$this->docexcel->getActiveSheet()->setCellValue('A1','Nº');
		$this->docexcel->getActiveSheet()->setCellValue('B1','Tipo de documento de identidad');
		$this->docexcel->getActiveSheet()->setCellValue('C1','Número de documento de identidad');
		$this->docexcel->getActiveSheet()->setCellValue('D1','Extensión del documento de identidad');
		$this->docexcel->getActiveSheet()->setCellValue('E1','AFP a la que aporta');
		$this->docexcel->getActiveSheet()->setCellValue('F1','NUA/CUA');
		$this->docexcel->getActiveSheet()->setCellValue('G1','Apellido Paterno');
		$this->docexcel->getActiveSheet()->setCellValue('H1','Apellido Materno');
		$this->docexcel->getActiveSheet()->setCellValue('I1','Apellido de casada');
		$this->docexcel->getActiveSheet()->setCellValue('J1','Primer nombre');		
		$this->docexcel->getActiveSheet()->setCellValue('K1','Otros nombres');
		$this->docexcel->getActiveSheet()->setCellValue('L1','País de nacionalidad');
		$this->docexcel->getActiveSheet()->setCellValue('M1','Fecha de nacimiento');
		$this->docexcel->getActiveSheet()->setCellValue('N1','Sexo');
		$this->docexcel->getActiveSheet()->setCellValue('O1','Jubilado');
		$this->docexcel->getActiveSheet()->setCellValue('P1','Clasificación laboral');
		$this->docexcel->getActiveSheet()->setCellValue('Q1','Cargo');
		$this->docexcel->getActiveSheet()->setCellValue('R1','Fecha de ingreso');
		$this->docexcel->getActiveSheet()->setCellValue('S1','Modalidad de contrato');
		$this->docexcel->getActiveSheet()->setCellValue('T1','Fecha de retiro');
		$this->docexcel->getActiveSheet()->setCellValue('U1','Horas pagadas (día)');
		$this->docexcel->getActiveSheet()->setCellValue('V1','Días pagados (mes)');		
		$this->docexcel->getActiveSheet()->setCellValue('W1','Horas extra');		
		$this->docexcel->getActiveSheet()->setCellValue('X1','Haber básico');		
		$this->docexcel->getActiveSheet()->setCellValue('Y1','Monto pagado por horas extra');		
		$this->docexcel->getActiveSheet()->setCellValue('Z1','Bono de antiguedad');
		$this->docexcel->getActiveSheet()->setCellValue('AA1','Otros bonos o pagos');
		$this->docexcel->getActiveSheet()->setCellValue('AB1','Total ganado');
		$this->docexcel->getActiveSheet()->setCellValue('AC1','Aporte a las AFPs');
		$this->docexcel->getActiveSheet()->setCellValue('AD1','RC-IVA');
		$this->docexcel->getActiveSheet()->setCellValue('AE1','Otros descuentos');
		$this->docexcel->getActiveSheet()->setCellValue('AF1','Total descuentos');
		$this->docexcel->getActiveSheet()->setCellValue('AG1','Líqido pagable');
		$this->docexcel->getActiveSheet()->setCellValue('AH1','Sucursal o ubicación adicional');
		
		
		
		
		//*************************************Detalle*****************************************
		$numero = 0;
		$columna = 0;
		$fila = 1;
		
		foreach($datos as $value) {
			
			if ($numero != $value['fila']) {
				$fila++;
				$columna = 0;				
				foreach ($value as $key => $val) {
					if ($key != 'codigo_columna' && $key != 'valor'&& $key != 'oficina'&& $key != 'discapacitado'&& $key != 'contrato_periodo'&& $key != 'retiro_periodo') {
						$this->docexcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($columna,$fila,$val);
						$columna++;
					}
				}
				$numero = $value['fila'];
				
			}
			
			if ($value['codigo_columna'] != 'BONFRONTERA') {
				$this->docexcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($columna,$fila,$value['valor']);
				$columna++;
			}
			
			if ($columna == 22) {
				$this->docexcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			
			if ($columna == 24) {
				$this->docexcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($columna,$fila,0);
				$columna++;
			}
			
			if ($columna == 33) {
				$this->docexcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($columna,$fila,$value['oficina']);
				$columna++;
			}
		}
		//************************************************Fin Detalle***********************************************
	}
	function imprimeResumen(){
		$sheetId = 2;
		$this->docexcel->createSheet(NULL, $sheetId);	
		$this->docexcel->setActiveSheetIndex($sheetId);	
		$this->docexcel->getActiveSheet()->setTitle('Resumen');
		
		$this->docexcel->setActiveSheetIndex(2);
		
		$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(55);
		$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(55);
		$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		
		$styleTitulos = array(
		    'font'  => array(
		        'bold'  => true,
		        'size'  => 8,
		        'name'  => 'Arial'
		    ),
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		    ),
			'fill' => array(
        		'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
            		'rgb' => 'c5d9f1'
            	)
        	),
			'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN
	        )
	    ));
		
		$this->docexcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleTitulos);
		$this->docexcel->getActiveSheet()->getStyle('A4:C4')->applyFromArray($styleTitulos);
		$this->docexcel->getActiveSheet()->getStyle('A6:F6')->applyFromArray($styleTitulos);
		
		$this->docexcel->getActiveSheet()->getStyle('C12:D12')->applyFromArray($styleTitulos);
		$this->docexcel->getActiveSheet()->getStyle('D14:F14')->applyFromArray($styleTitulos);
		
		$this->docexcel->getActiveSheet()->setCellValue('A1','Seguro social a largo plazo');
				
		$this->docexcel->getActiveSheet()->setCellValue('A2','4.4 Nº total de afiliados al seguro a largo plazo (AFP´s)');
		$this->docexcel->getActiveSheet()->setCellValue('B2',$this->resumen['trabajadores_varones'] + $this->resumen['trabajadores_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('C2','4.5 Monto aportado (Total aporte de los trabajadores Bs)');
		$this->docexcel->getActiveSheet()->setCellValue('D2',$this->resumen['afp']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A4','5. COMPOSICIÓN SALARIAL');
		$this->docexcel->getActiveSheet()->setCellValue('C4','6. TRABAJADORES');
		
		$this->docexcel->getActiveSheet()->setCellValue('A6','Concepto');
		$this->docexcel->getActiveSheet()->setCellValue('B6','Monto (Bs)');
		$this->docexcel->getActiveSheet()->setCellValue('D6','Varones');
		$this->docexcel->getActiveSheet()->setCellValue('E6','Mujeres');
		$this->docexcel->getActiveSheet()->setCellValue('F6','Total');
		
		$this->docexcel->getActiveSheet()->setCellValue('A7','5.1 Haber básico');
		$this->docexcel->getActiveSheet()->setCellValue('B7',$this->resumen['basico']);
		$this->docexcel->getActiveSheet()->setCellValue('C7','6.1 Total trabajadores');
		$this->docexcel->getActiveSheet()->setCellValue('D7',$this->resumen['trabajadores_varones']);
		$this->docexcel->getActiveSheet()->setCellValue('E7',$this->resumen['trabajadores_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('F7',$this->resumen['trabajadores_varones'] + $this->resumen['trabajadores_mujeres']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A8','5.2 Bono de antigüedad');
		$this->docexcel->getActiveSheet()->setCellValue('B8',$this->resumen['antiguedad']);
		$this->docexcel->getActiveSheet()->setCellValue('C8','6.2 Personas jubiladas');
		$this->docexcel->getActiveSheet()->setCellValue('D8',$this->resumen['jubilados_varones']);
		$this->docexcel->getActiveSheet()->setCellValue('E8',$this->resumen['jubilados_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('F8',$this->resumen['jubilados_mujeres'] + $this->resumen['jubilados_varones']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A9','5.3 Bono de producción');
		$this->docexcel->getActiveSheet()->setCellValue('B9',0);
		$this->docexcel->getActiveSheet()->setCellValue('C9','6.3 Personas extranjeras');
		$this->docexcel->getActiveSheet()->setCellValue('D9',$this->resumen['extranjeros_varones']);
		$this->docexcel->getActiveSheet()->setCellValue('E9',$this->resumen['extranjeros_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('F9',$this->resumen['extranjeros_varones'] + $this->resumen['extranjeros_mujeres']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A10','5.4 Subsidio de frontera');
		$this->docexcel->getActiveSheet()->setCellValue('B10',$this->resumen['frontera']);
		$this->docexcel->getActiveSheet()->setCellValue('C10','6.4 Personas con discapacidad');
		$this->docexcel->getActiveSheet()->setCellValue('D10',$this->resumen['discapacitados_varones']);
		$this->docexcel->getActiveSheet()->setCellValue('E10',$this->resumen['discapacitados_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('F10',$this->resumen['discapacitados_varones'] + $this->resumen['discapacitados_mujeres']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A11','5.5 Trabajo extraordinario y nocturno');
		$this->docexcel->getActiveSheet()->setCellValue('B11',0);
		$this->docexcel->getActiveSheet()->setCellValue('C11','');
		$this->docexcel->getActiveSheet()->setCellValue('D11','');
		$this->docexcel->getActiveSheet()->setCellValue('E11','');
		$this->docexcel->getActiveSheet()->setCellValue('F11','');
		
		$this->docexcel->getActiveSheet()->setCellValue('A12','5.6 Pago dominical y domingo trabajado');
		$this->docexcel->getActiveSheet()->setCellValue('B12',0);
		$this->docexcel->getActiveSheet()->setCellValue('C12','7. INFORMACIÓN TRIMESTRAL, ACCIDENTES Y ENFERMEDADES DE TRABAJO');
		$this->docexcel->getActiveSheet()->setCellValue('D12','');
		$this->docexcel->getActiveSheet()->setCellValue('E12','');
		$this->docexcel->getActiveSheet()->setCellValue('F12','');
		
		$this->docexcel->getActiveSheet()->setCellValue('A13','5.7 Otros bonos');
		$this->docexcel->getActiveSheet()->setCellValue('B13',$this->resumen['otros_bonos']);
		$this->docexcel->getActiveSheet()->setCellValue('C13','');
		$this->docexcel->getActiveSheet()->setCellValue('D13','');
		$this->docexcel->getActiveSheet()->setCellValue('E13','');
		$this->docexcel->getActiveSheet()->setCellValue('F13','');
		
		$this->docexcel->getActiveSheet()->setCellValue('A14','5.8 Total ganado');
		$this->docexcel->getActiveSheet()->setCellValue('B14',$this->resumen['total_ganado']);
		$this->docexcel->getActiveSheet()->setCellValue('C14','');
		$this->docexcel->getActiveSheet()->setCellValue('D14','Varones');
		$this->docexcel->getActiveSheet()->setCellValue('E14','Mujeres');
		$this->docexcel->getActiveSheet()->setCellValue('F14','Total');
		
		$this->docexcel->getActiveSheet()->setCellValue('A15','5.9 Aporte a las AFPs');
		$this->docexcel->getActiveSheet()->setCellValue('B15',$this->resumen['afp']);
		$this->docexcel->getActiveSheet()->setCellValue('C15','7.1 Personas contratadas en el trimestre');
		$this->docexcel->getActiveSheet()->setCellValue('D15',$this->resumen['contrato_varones']);
		$this->docexcel->getActiveSheet()->setCellValue('E15',$this->resumen['contrato_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('F15',$this->resumen['contrato_varones'] + $this->resumen['contrato_mujeres']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A16','5.10 RC-IVA');
		$this->docexcel->getActiveSheet()->setCellValue('B16',$this->resumen['iva']);
		$this->docexcel->getActiveSheet()->setCellValue('C16','7.2 Personas retiradas en el trimestre');
		$this->docexcel->getActiveSheet()->setCellValue('D16',$this->resumen['retiro_varones']);
		$this->docexcel->getActiveSheet()->setCellValue('E16',$this->resumen['retiro_mujeres']);
		$this->docexcel->getActiveSheet()->setCellValue('F16',$this->resumen['retiro_varones'] + $this->resumen['retiro_mujeres']);
		
		$this->docexcel->getActiveSheet()->setCellValue('A17','5.11 Otros descuentos');
		$this->docexcel->getActiveSheet()->setCellValue('B17',$this->resumen['otros_descuentos']);
		$this->docexcel->getActiveSheet()->setCellValue('C17','');
		$this->docexcel->getActiveSheet()->setCellValue('D17','');
		$this->docexcel->getActiveSheet()->setCellValue('E17','');
		$this->docexcel->getActiveSheet()->setCellValue('F17','');
		
		$this->docexcel->getActiveSheet()->setCellValue('A18','5.12 Total descuentos');
		$this->docexcel->getActiveSheet()->setCellValue('B18',$this->resumen['total_descuentos']);
		$this->docexcel->getActiveSheet()->setCellValue('C18','');
		$this->docexcel->getActiveSheet()->setCellValue('D18','');
		$this->docexcel->getActiveSheet()->setCellValue('E18','');
		$this->docexcel->getActiveSheet()->setCellValue('F18','');
		
		$this->docexcel->getActiveSheet()->setCellValue('A19','5.13 Líquido pagable');
		$this->docexcel->getActiveSheet()->setCellValue('B19',$this->resumen['liquido_pagable']);
		$this->docexcel->getActiveSheet()->setCellValue('C19','');
		$this->docexcel->getActiveSheet()->setCellValue('D19','');
		$this->docexcel->getActiveSheet()->setCellValue('E19','');
		$this->docexcel->getActiveSheet()->setCellValue('F19','');
		
		
		
	}
	function generarReporte(){
		//echo $this->nombre_archivo; exit;
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->docexcel->setActiveSheetIndex(0);
		
		$this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
		$this->objWriter->save($this->url_archivo);	
		
	}	
	

}

?>