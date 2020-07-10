<?php
class RPresupuestoRetroactivoXls
{
    private $docexcel;
    private $objWriter;
    private $numero;
    private $equivalencias=array();
    private $objParam;
    var $datos_detalle;
    var $datos_titulo;
    public  $url_archivo;
    function __construct(CTParametro $objParam)
    {
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


        $this->equivalencias=array( 0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
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

    public function addHoja($name,$index){
        //$index = $this->docexcel->getSheetCount();
        //echo($index);
        $this->docexcel->createSheet($index)->setTitle($name);
        $this->docexcel->setActiveSheetIndex($index);
        return $this->docexcel;
    }

    function generarDatos(){

        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );

        $styleTitulos2 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'FFFFFF'
                )

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '0066CC'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));

        $styleTitulos1 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'FFFFFF'
                )

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '626eba'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));

        $styleTitulos3 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'FFFFFF'
                )

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '3287c1'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));

        $this->numero = 1;
        $fila = 3;
        $columna = 8;
        $datos = $this->objParam->getParametro('datos');//var_dump($datos);exit;
        //subtotales
        $tipo_contrato = '';
        $desc_func = '';
        $codigo_pres = '';

        $numberFormat = '#,##0.00';
        $cant_datos = count($datos);
        $total_bono = 0;
        $total_sueldo = 0;
        $total_frontera = 0;
        $codigo_col = '';
        $index = 0;
        $color_pestana = array('ff0000','1100ff','55ff00','3ba3ff','ff4747','697dff','78edff','ba8cff',
            'ff80bb','ff792b','ffff5e','52ff97','bae3ff','ffaf9c','bfffc6','b370ff','ffa8b4','7583ff','9aff17','ff30c8');


        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        foreach ($datos as $value){ //var_dump($value);exit;
            //var_dump($value['tipo_contrato']);exit;
            if($tipo_contrato != $value['tipo_contrato']){
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $total_sueldo);
                $this->addHoja($value['tipo_contrato'],$index);
                $this->docexcel->getActiveSheet()->getTabColor()->setRGB($color_pestana[$index]);
                $this->docexcel->getActiveSheet()->mergeCells('A1:A2');
                $this->docexcel->getActiveSheet()->mergeCells('B1:B2');
                $this->docexcel->getActiveSheet()->mergeCells('C1:C2');
                $this->docexcel->getActiveSheet()->mergeCells('D1:D2');
                $this->docexcel->getActiveSheet()->mergeCells('E1:E2');
                $this->docexcel->getActiveSheet()->mergeCells('F1:F2');
                $this->docexcel->getActiveSheet()->mergeCells('G1:G2');
                $this->docexcel->getActiveSheet()->mergeCells('H1:H2');

                /*$this->docexcel->getActiveSheet()->mergeCells('I1:P1');
                    $this->docexcel->getActiveSheet()->mergeCells('Q1:U1');
                    $this->docexcel->getActiveSheet()->mergeCells('V1:AC1');*/
                $this->docexcel->getActiveSheet()->mergeCells('I1:M1');
                $this->docexcel->getActiveSheet()->mergeCells('N1:R1');
                $this->docexcel->getActiveSheet()->mergeCells('S1:W1');

                /*$this->docexcel->getActiveSheet()->getStyle('A1:AC2')->getAlignment()->setWrapText(true);
                $this->docexcel->getActiveSheet()->getStyle('A1:AC2')->applyFromArray($styleTitulos3);*/
                $this->docexcel->getActiveSheet()->getStyle('A1:W2')->getAlignment()->setWrapText(true);
                $this->docexcel->getActiveSheet()->getStyle('A1:W2')->applyFromArray($styleTitulos3);

                $this->docexcel->getActiveSheet()->freezePaneByColumnAndRow(0,3);
                $fila=3;
                $this->numero=1;
                $columna = 8;

                $total_bono = 0;
                $total_sueldo = 0;
                $total_frontera = 0;
                $this->docexcel->getActiveSheet()->setTitle($value['tipo_contrato']);
                $this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);//categoria
                $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);//pres
                $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);//ci
                $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);//funcionario
                $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);//cargo
                $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);//fecha
                $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);//fecha
                $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);//mes

                $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);//mes
                $this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);//mes


                $this->docexcel->getActiveSheet()->setCellValue('A1','Nro');
                $this->docexcel->getActiveSheet()->setCellValue('B1','Categoria Programática');
                $this->docexcel->getActiveSheet()->setCellValue('C1','Presupuesto');
                $this->docexcel->getActiveSheet()->setCellValue('D1','CI');
                $this->docexcel->getActiveSheet()->setCellValue('E1','Funcionario');
                $this->docexcel->getActiveSheet()->setCellValue('F1','Cargo');
                $this->docexcel->getActiveSheet()->setCellValue('G1','Fecha Inicio');
                $this->docexcel->getActiveSheet()->setCellValue('H1','Fecha Fin');
                $this->docexcel->getActiveSheet()->setCellValue('I1','Bono Frontera');
                $this->docexcel->getActiveSheet()->setCellValue('I2',$meses[0]);
                $this->docexcel->getActiveSheet()->setCellValue('J2',$meses[1]);
                $this->docexcel->getActiveSheet()->setCellValue('K2',$meses[2]);
                $this->docexcel->getActiveSheet()->setCellValue('L2',$meses[3]);
                //$this->docexcel->getActiveSheet()->setCellValue('M2',$meses[4]);
                //$this->docexcel->getActiveSheet()->setCellValue('N2',$meses[5]);
                //$this->docexcel->getActiveSheet()->setCellValue('O2',$meses[6]);
                //$this->docexcel->getActiveSheet()->setCellValue('P2','Total');
                $this->docexcel->getActiveSheet()->setCellValue('M2','Total');

                /*$this->docexcel->getActiveSheet()->setCellValue('Q1','Bono Antiguedad');
                $this->docexcel->getActiveSheet()->setCellValue('Q2',$meses[0]);
                $this->docexcel->getActiveSheet()->setCellValue('R2',$meses[1]);
                $this->docexcel->getActiveSheet()->setCellValue('S2',$meses[2]);
                $this->docexcel->getActiveSheet()->setCellValue('T2',$meses[3]);
                $this->docexcel->getActiveSheet()->setCellValue('U2','Total');*/
                $this->docexcel->getActiveSheet()->setCellValue('N1','Bono Antiguedad');
                $this->docexcel->getActiveSheet()->setCellValue('N2',$meses[0]);
                $this->docexcel->getActiveSheet()->setCellValue('O2',$meses[1]);
                $this->docexcel->getActiveSheet()->setCellValue('P2',$meses[2]);
                $this->docexcel->getActiveSheet()->setCellValue('Q2',$meses[3]);
                $this->docexcel->getActiveSheet()->setCellValue('R2','Total');

                $this->docexcel->getActiveSheet()->setCellValue('S1','Sueldo Basico');
                $this->docexcel->getActiveSheet()->setCellValue('S2',$meses[0]);
                $this->docexcel->getActiveSheet()->setCellValue('T2',$meses[1]);
                $this->docexcel->getActiveSheet()->setCellValue('U2',$meses[2]);
                $this->docexcel->getActiveSheet()->setCellValue('V2',$meses[3]);
                $this->docexcel->getActiveSheet()->setCellValue('W2','Total');
                /*$this->docexcel->getActiveSheet()->setCellValue('V1','Sueldo Basico');
                $this->docexcel->getActiveSheet()->setCellValue('V2',$meses[0]);
                $this->docexcel->getActiveSheet()->setCellValue('W2',$meses[1]);
                $this->docexcel->getActiveSheet()->setCellValue('X2',$meses[2]);
                $this->docexcel->getActiveSheet()->setCellValue('Y2',$meses[3]);
                $this->docexcel->getActiveSheet()->setCellValue('Z2',$meses[4]);
                $this->docexcel->getActiveSheet()->setCellValue('AA2',$meses[5]);
                $this->docexcel->getActiveSheet()->setCellValue('AB2',$meses[6]);
                $this->docexcel->getActiveSheet()->setCellValue('AC2','Total');*/

                //if($desc_func != $value['desc_func'] || $codigo_pres!=$value['codigo_pres']) {
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['categoria_prog']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['presupuesto'].'['.$value['codigo_pres'].']');
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['ci']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['desc_func']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, date_format(date_create($value['fecha_ini']),'d/m/Y'));
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, date_format(date_create($value['fecha_fin']),'d/m/Y'));
                switch ($value['periodo']){

                    case 1:

                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT'){
                            $total_bono += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['valor']); break;
                        }
                    case 2:

                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT'){
                            $total_bono += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['valor']); break;
                        }

                    case 3:
                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT') {
                            $total_bono += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['valor']);break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['valor']); break;
                        }
                    case 4:
                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT') {
                            $total_bono += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['valor']);break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['valor']); break;
                        }
                    /*case 5:
                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT') {
                            $total_bono += $value['valor'];
                            //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['valor']); break;
                        }
                    case 6:
                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT') {
                            $total_bono += $value['valor'];
                            //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['valor']); break;
                        }
                    case 7:
                        if($value['codigo_columna'] == 'BONFRONTERA'){
                            $total_frontera += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REINBANT') {
                            $total_bono += $value['valor'];
                            //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                        }else if($value['codigo_columna'] == 'REISUELDOBA'){
                            $total_sueldo += $value['valor'];
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['valor']); break;
                        }*/
                    /*default:
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, 0); break;*/
                }


                //}
                $columna++;
                //$fila++;
                $this->numero++;
                $index++;
                $codigo_col = $value['codigo_columna'];

            }else {
                if($desc_func != $value['desc_func'] || $codigo_pres!=$value['codigo_pres']){
                    if($desc_func != $value['desc_func']){
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $total_sueldo);
                    }

                    $total_bono = 0;
                    $total_sueldo = 0;
                    $total_frontera = 0;
                    $fila++;
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['categoria_prog']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['presupuesto'].'['.$value['codigo_pres'].']');
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['ci']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['desc_func']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, date_format(date_create($value['fecha_ini']),'d/m/Y'));
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, date_format(date_create($value['fecha_fin']),'d/m/Y'));


                    switch ($value['periodo']){

                        case 1:

                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT'){
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['valor']); break;
                            }
                        case 2:

                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT'){
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['valor']); break;
                            }

                        case 3:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['valor']);break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['valor']); break;
                            }
                        case 4:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['valor']);break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['valor']); break;
                            }
                        /*case 5:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['valor']); break;
                            }
                        case 6:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['valor']); break;
                            }
                        case 7:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['valor']); break;
                            }*/
                        /*default:
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, 0); break;*/
                    }



                    $this->numero++;
                    $columna = 8;
                    $codigo_col = $value['codigo_columna'];
                }else{
                    switch ($value['periodo']){

                        case 1:

                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT'){
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['valor']); break;
                            }
                        case 2:

                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT'){
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['valor']); break;
                            }

                        case 3:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['valor']);break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['valor']); break;
                            }
                        case 4:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['valor']);break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['valor']); break;
                            }
                        /*case 5:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['valor']); break;
                            }
                        case 6:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['valor']); break;
                            }
                        case 7:
                            if($value['codigo_columna'] == 'BONFRONTERA'){
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            }else if($value['codigo_columna'] == 'REISUELDOBA'){
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['valor']); break;
                            }*/
                        /*default:
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, 0); break;*/
                    }
                    if($codigo_col!=$value['codigo_columna']){
                        if($codigo_col == 'BONFRONTERA'){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $total_frontera);
                        }else if($codigo_col == 'REINBANT'){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $total_bono);
                        }else if($codigo_col == 'REISUELDOBA'){
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $total_sueldo);
                        }
                    }
                    $codigo_col = $value['codigo_columna'];
                    $columna++;
                }

                //$fila++;
                //$this->numero++;
            }

            $tipo_contrato = $value['tipo_contrato'];
            $desc_func = $value['desc_func'];
            $codigo_pres = $value['codigo_pres'];
        }
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $total_sueldo);
        $modalidad = '';
        //Pestaña Modalidades
        foreach ($datos as $value){
            if($value['tipo_contrato'] == 'PLA') { //var_dump($value);exit;
                if ($modalidad != $value['modalidad']) {
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $total_sueldo);
                    $this->addHoja($value['modalidad'], $index);
                    $this->docexcel->getActiveSheet()->getTabColor()->setRGB($color_pestana[$index]);
                    $this->docexcel->getActiveSheet()->mergeCells('A1:A2');
                    $this->docexcel->getActiveSheet()->mergeCells('B1:B2');
                    $this->docexcel->getActiveSheet()->mergeCells('C1:C2');
                    $this->docexcel->getActiveSheet()->mergeCells('D1:D2');
                    $this->docexcel->getActiveSheet()->mergeCells('E1:E2');
                    $this->docexcel->getActiveSheet()->mergeCells('F1:F2');
                    $this->docexcel->getActiveSheet()->mergeCells('G1:G2');
                    $this->docexcel->getActiveSheet()->mergeCells('H1:H2');

                    /*$this->docexcel->getActiveSheet()->mergeCells('I1:P1');
                    $this->docexcel->getActiveSheet()->mergeCells('Q1:U1');
                    $this->docexcel->getActiveSheet()->mergeCells('V1:AC1');*/
                    $this->docexcel->getActiveSheet()->mergeCells('I1:M1');
                    $this->docexcel->getActiveSheet()->mergeCells('N1:R1');
                    $this->docexcel->getActiveSheet()->mergeCells('S1:W1');


                    /*$this->docexcel->getActiveSheet()->getStyle('A1:AC2')->getAlignment()->setWrapText(true);
                    $this->docexcel->getActiveSheet()->getStyle('A1:AC2')->applyFromArray($styleTitulos3);*/
                    $this->docexcel->getActiveSheet()->getStyle('A1:W2')->getAlignment()->setWrapText(true);
                    $this->docexcel->getActiveSheet()->getStyle('A1:W2')->applyFromArray($styleTitulos3);
                    $this->docexcel->getActiveSheet()->freezePaneByColumnAndRow(0, 3);
                    $fila = 3;
                    $this->numero = 1;
                    $columna = 8;

                    $total_bono = 0;
                    $total_sueldo = 0;
                    $total_frontera = 0;
                    $this->docexcel->getActiveSheet()->setTitle($value['modalidad']);
                    $this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                    $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);//categoria
                    $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);//pres
                    $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);//ci
                    $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);//funcionario
                    $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);//cargo
                    $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);//fecha
                    $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);//fecha
                    $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);//mes

                    $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);//mes
                    $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);//mes
                    //$this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);//mes
                    //$this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);//mes
                    //$this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);//mes
                    //$this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);//mes
                    //$this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);//mes
                    //$this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);//mes


                    $this->docexcel->getActiveSheet()->setCellValue('A1','Nro');
                    $this->docexcel->getActiveSheet()->setCellValue('B1','Categoria Programática');
                    $this->docexcel->getActiveSheet()->setCellValue('C1','Presupuesto');
                    $this->docexcel->getActiveSheet()->setCellValue('D1','CI');
                    $this->docexcel->getActiveSheet()->setCellValue('E1','Funcionario');
                    $this->docexcel->getActiveSheet()->setCellValue('F1','Cargo');
                    $this->docexcel->getActiveSheet()->setCellValue('G1','Fecha Inicio');
                    $this->docexcel->getActiveSheet()->setCellValue('H1','Fecha Fin');
                    $this->docexcel->getActiveSheet()->setCellValue('I1','Bono Frontera');
                    $this->docexcel->getActiveSheet()->setCellValue('I2',$meses[0]);
                    $this->docexcel->getActiveSheet()->setCellValue('J2',$meses[1]);
                    $this->docexcel->getActiveSheet()->setCellValue('K2',$meses[2]);
                    $this->docexcel->getActiveSheet()->setCellValue('L2',$meses[3]);
                    //$this->docexcel->getActiveSheet()->setCellValue('M2',$meses[4]);
                    //$this->docexcel->getActiveSheet()->setCellValue('N2',$meses[5]);
                    //$this->docexcel->getActiveSheet()->setCellValue('O2',$meses[6]);
                    //$this->docexcel->getActiveSheet()->setCellValue('P2','Total');
                    $this->docexcel->getActiveSheet()->setCellValue('M2','Total');

                    /*$this->docexcel->getActiveSheet()->setCellValue('Q1','Bono Antiguedad');
                    $this->docexcel->getActiveSheet()->setCellValue('Q2',$meses[0]);
                    $this->docexcel->getActiveSheet()->setCellValue('R2',$meses[1]);
                    $this->docexcel->getActiveSheet()->setCellValue('S2',$meses[2]);
                    $this->docexcel->getActiveSheet()->setCellValue('T2',$meses[3]);
                    $this->docexcel->getActiveSheet()->setCellValue('U2','Total');*/
                    $this->docexcel->getActiveSheet()->setCellValue('N1','Bono Antiguedad');
                    $this->docexcel->getActiveSheet()->setCellValue('N2',$meses[0]);
                    $this->docexcel->getActiveSheet()->setCellValue('O2',$meses[1]);
                    $this->docexcel->getActiveSheet()->setCellValue('P2',$meses[2]);
                    $this->docexcel->getActiveSheet()->setCellValue('Q2',$meses[3]);
                    $this->docexcel->getActiveSheet()->setCellValue('R2','Total');

                    $this->docexcel->getActiveSheet()->setCellValue('S1','Sueldo Basico');
                    $this->docexcel->getActiveSheet()->setCellValue('S2',$meses[0]);
                    $this->docexcel->getActiveSheet()->setCellValue('T2',$meses[1]);
                    $this->docexcel->getActiveSheet()->setCellValue('U2',$meses[2]);
                    $this->docexcel->getActiveSheet()->setCellValue('V2',$meses[3]);
                    $this->docexcel->getActiveSheet()->setCellValue('W2','Total');
                    /*$this->docexcel->getActiveSheet()->setCellValue('V1','Sueldo Basico');
                    $this->docexcel->getActiveSheet()->setCellValue('V2',$meses[0]);
                    $this->docexcel->getActiveSheet()->setCellValue('W2',$meses[1]);
                    $this->docexcel->getActiveSheet()->setCellValue('X2',$meses[2]);
                    $this->docexcel->getActiveSheet()->setCellValue('Y2',$meses[3]);
                    $this->docexcel->getActiveSheet()->setCellValue('Z2',$meses[4]);
                    $this->docexcel->getActiveSheet()->setCellValue('AA2',$meses[5]);
                    $this->docexcel->getActiveSheet()->setCellValue('AB2',$meses[6]);
                    $this->docexcel->getActiveSheet()->setCellValue('AC2','Total');*/

                    //if($desc_func != $value['desc_func'] || $codigo_pres!=$value['codigo_pres']) {
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['categoria_prog']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['presupuesto'] . '[' . $value['codigo_pres'] . ']');
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['ci']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['desc_func']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, date_format(date_create($value['fecha_ini']), 'd/m/Y'));
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, date_format(date_create($value['fecha_fin']), 'd/m/Y'));
                    switch ($value['periodo']) {

                        case 1:

                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['valor']);
                                break;
                            }
                        case 2:

                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['valor']);
                                break;
                            }

                        case 3:
                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['valor']);
                                break;
                            }
                        case 4:
                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['valor']);
                                break;
                            }
                        /*case 5:
                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['valor']);
                                break;
                            }
                        case 6:
                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['valor']);
                                break;
                            }
                        case 7:
                            if ($value['codigo_columna'] == 'BONFRONTERA') {
                                $total_frontera += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']);
                                break;
                            } else if ($value['codigo_columna'] == 'REINBANT') {
                                $total_bono += $value['valor'];
                                //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                            } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                $total_sueldo += $value['valor'];
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['valor']);
                                break;
                            }*/
                        /*default:
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, 0); break;*/
                    }


                    //}
                    $columna++;
                    //$fila++;
                    $this->numero++;
                    $index++;
                    $codigo_col = $value['codigo_columna'];

                } else {
                    if ($desc_func != $value['desc_func'] || $codigo_pres != $value['codigo_pres']) {
                        if ($desc_func != $value['desc_func']) {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $total_sueldo);
                        }

                        $total_bono = 0;
                        $total_sueldo = 0;
                        $total_frontera = 0;
                        $fila++;
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['categoria_prog']);
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['presupuesto'] . '[' . $value['codigo_pres'] . ']');
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['ci']);
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['desc_func']);
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, date_format(date_create($value['fecha_ini']), 'd/m/Y'));
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, date_format(date_create($value['fecha_fin']), 'd/m/Y'));


                        switch ($value['periodo']) {

                            case 1:

                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['valor']);
                                    break;
                                }
                            case 2:

                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['valor']);
                                    break;
                                }

                            case 3:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['valor']);
                                    break;
                                }
                            case 4:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['valor']);
                                    break;
                                }
                            /*case 5:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['valor']);
                                    break;
                                }
                            case 6:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['valor']);
                                    break;
                                }
                            case 7:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['valor']);
                                    break;
                                }*/
                            /*default:
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, 0); break;*/
                        }


                        $this->numero++;
                        $columna = 8;
                        $codigo_col = $value['codigo_columna'];
                    } else {
                        switch ($value['periodo']) {

                            case 1:

                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['valor']);
                                    break;
                                }
                            case 2:

                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['valor']);
                                    break;
                                }

                            case 3:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['valor']);
                                    break;
                                }
                            case 4:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['valor']);
                                    break;
                                }
                            /*case 5:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['valor']);break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['valor']);
                                    break;
                                }
                            case 6:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['valor']); break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['valor']);
                                    break;
                                }
                            case 7:
                                if ($value['codigo_columna'] == 'BONFRONTERA') {
                                    $total_frontera += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']);
                                    break;
                                } else if ($value['codigo_columna'] == 'REINBANT') {
                                    $total_bono += $value['valor'];
                                    //$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['valor']); break;
                                } else if ($value['codigo_columna'] == 'REISUELDOBA') {
                                    $total_sueldo += $value['valor'];
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nombre_cargo']);
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['valor']);
                                    break;
                                }*/
                            /*default:
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, 0); break;*/
                        }
                        if ($codigo_col != $value['codigo_columna']) {
                            if ($codigo_col == 'BONFRONTERA') {
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $total_frontera);
                            } else if ($codigo_col == 'REINBANT') {
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $total_bono);
                            } else if ($codigo_col == 'REISUELDOBA') {
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $total_sueldo);
                            }
                        }
                        $codigo_col = $value['codigo_columna'];
                        $columna++;
                    }

                    //$fila++;
                    //$this->numero++;
                }
                $modalidad = $value['modalidad'];
                $tipo_contrato = $value['tipo_contrato'];
                $desc_func = $value['desc_func'];
                $codigo_pres = $value['codigo_pres'];
            }
        }
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $total_sueldo);
    }
    function obtenerFechaEnLetra($fecha){
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $dia= date("d", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        // var_dump()
        $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $mes = $mes[(date('m', strtotime($fecha))*1)-1];
        return $dia.' de '.$mes.' del '.$anno;
    }
    function generarReporte(){
        $this->generarDatos();
        $this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
    }

}
?>