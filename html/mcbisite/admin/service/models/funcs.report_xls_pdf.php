<?php

    require_once("../models/config.php");
    getreport_export();
    GLOBAL $styleArray ;

  function getreport_export(){
        $contador=0;
           

        //para mostrar errores phpexcel
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE); 
        ini_set('display_startup_errors', TRUE); 
        date_default_timezone_set('America/Guatemala');

        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        /** Include PHPExcel */
        require_once ('lib/Classes/PHPExcel.php');
		



    global $db,$db_table_prefix, $db_name;
    //$page=1;
    //Aqui se arma la consulta respectivamente de los filtros
    $a="";
    $sql_get="";
    $sql_parms="";
    $sqls=array();
    $cont=0;

      //$titulo=$parameters["titulo"];
		$parameters_array=json_decode(( $_POST["datos"]),TRUE);  

				if ($parameters_array["tipo"]==2){
			$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
			$rendererLibrary = 'mPDF1';
			$rendererLibraryPath = 'lib/Classes/PHPExcel/Writer/PDF/' . $rendererLibrary;
			}

  
		$objPHPExcel = new PHPExcel();


     if ($parameters_array["tipo"]==1){
     //   $objPHPExcel->getActiveSheet()->setShowGridlines(FALSE);
      


		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('PHPExcel logo');
		$objDrawing->setDescription('PHPExcel logo');
		$objDrawing->setPath('../../css/media/general/logo.png');      
		//$objDrawing->setHeight(50);                 
		$objDrawing->setCoordinates('A1');
		$objDrawing->setOffsetX(5);    
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

      }  //properities excel 
        $objPHPExcel->getProperties()->setCreator("Banco Industrial")
                             ->setLastModifiedBy("Maarten Balliauw")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");

        // Set default font

        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);



        if ($parameters_array["tipo"]==2){
     //   $objPHPExcel->getActiveSheet()->setShowGridlines(FALSE);
      }



                  if ($parameters_array["tiene_titulo"]==1){
                    //va a titulos
                    $titulo =$parameters_array["titulo"];
                         foreach ($titulo as $dato) {
                          add_title($dato["columna"],$dato["fila"],$dato["description"],$objPHPExcel,3);
                    }
                  
                  }



                  if ($parameters_array["tiene_columnas"]==1){
                    //va a titulos columnas
                    $columna_titulos =$parameters_array["columnas"];
                    $titulo_columnas =$columna_titulos["titulo"];
                    $codigo_ascii_inicial =ord($columna_titulos["colum_in"] );
                    $codigo_ascii_siguiente=$codigo_ascii_inicial;
                    $fila =$parameters_array["columnas"]["fila_in"] ;

                    $contador=0;


                foreach ($titulo_columnas as $dato) {

                      add_title(chr($codigo_ascii_siguiente), $fila, $dato ,$objPHPExcel,1);
                      $objPHPExcel->getActiveSheet()->getColumnDimension("".chr($codigo_ascii_siguiente)."")->setAutoSize(true);
                      $codigo_ascii_siguiente++;
                      $contador++;
                    }
                      cellColor_header("".chr($codigo_ascii_inicial)."".$fila.":".chr($codigo_ascii_siguiente-1)."".$fila."", $objPHPExcel);



                 }


                    //va a filtros 
                 if ($parameters_array["tiene_filtros"]==1){
                    $columna_filtros =$parameters_array["filtros"];

                    $codigo_ascii_siguiente =ord($columna_filtros["columna_ini"] );
                    $fila =$columna_filtros["fila_ini"];
                    $titulos_filtros =$columna_filtros["titulo"];
                    $valores_filtros =$columna_filtros["values"];

                foreach (array_combine($titulos_filtros, $valores_filtros) as $titulos => $valores) {

                                add_title(chr($codigo_ascii_siguiente), $fila, $titulos ,$objPHPExcel,4);
                                add_title(chr($codigo_ascii_siguiente+1), $fila, $valores ,$objPHPExcel,4);

                                $fila++;

                  }
                 } 
                          


                    //va obtener las personalizaciones
              if ($parameters_array["tiene_personalizado"]==1){
                $array_filtros =$parameters_array["personalizados"];
                
                for ($i = 0; $i <=(count($array_filtros)-1) ; $i++) {
                 application_rango( $array_filtros[$i]["type_personalization"], $array_filtros[$i]["range"],$objPHPExcel );
    
                }
              }
                

                    //va obtener los papas 
              if ($parameters_array["tiene_papas"]==1){
                $array_papas =$parameters_array["papas"];
                for ($i = 0; $i <=(count($array_papas)-1) ; $i++) {
                 add_title( $array_papas[$i]["columna"], $array_papas[$i]["fila"],$array_papas[$i]["titulo"],$objPHPExcel );
    
                  }
                }  




                 //va a tipo dinamicos
	
              if ($parameters_array["tiene_contenido_dinamico"]==1){


                    $columna_contenido_titulos =$parameters_array["contenido_dinamico"]["cabecera"];
                    $columna_contenido_detalle =$parameters_array["contenido_dinamico"]["detalle"];
                    $text_columna_inicial =$parameters_array["contenido_dinamico"]["columna"];
                    $codigo_ascii_columna_inicial =ord( $text_columna_inicial );
                    $fila_inicial=$parameters_array["contenido_dinamico"]["fila"];
                    $fila_titulo_cabecera=$parameters_array["contenido_dinamico"]["titulo_cabecera"];
                    $fila_titulo_detalle=$parameters_array["contenido_dinamico"]["titulo_detalle"];
                    $codigo_ascii_siguiente= $codigo_ascii_columna_inicial;


                          $contador=0;
                          $firt_column=0;
                          $contador_titulo=0;
						              $first_resize =true;

                         foreach ($columna_contenido_titulos as $dato) {
                           
                                
                              $datos  =explode("||",$dato) ;

                                    foreach ($datos as $value) {
                                    
                                    add_title(chr($codigo_ascii_siguiente),( $fila_inicial-1), $fila_titulo_cabecera[$contador_titulo], $objPHPExcel,2);
                                    add_title(chr($codigo_ascii_siguiente), $fila_inicial, $value ,$objPHPExcel,2);
                                    
                        
                                        if ($first_resize){
												                        $objPHPExcel->getActiveSheet()->getColumnDimension("".chr($codigo_ascii_siguiente)."")->setAutoSize(true);
                                        }
                                            $codigo_ascii_siguiente++;
                                            $contador_titulo++;

                                    }
									
									$first_resize=false;


                                    cellColor_header("".chr($codigo_ascii_columna_inicial)."".$fila_inicial.":".chr($codigo_ascii_siguiente-1)."".$fila_inicial."", $objPHPExcel);
                                    //regreso a los valores iniciales 

                                    $codigo_ascii_siguiente=$codigo_ascii_columna_inicial;
                                    $fila_inicial=$fila_inicial+2;


                                
                                        
                                foreach ($columna_contenido_detalle[$contador] as $contenido_detalle) {
                                           $contador_titulo=0;
                                        
                                     $valores_detalle  =explode("||", $contenido_detalle ) ;
                                
                                            foreach ($valores_detalle as $value) {
                                                                if ($firt_column==0){
                                                                        add_title(chr($codigo_ascii_siguiente),( $fila_inicial-1), $fila_titulo_detalle[$contador_titulo], $objPHPExcel,2);
                                                                     }
                                                                add_title(chr($codigo_ascii_siguiente), $fila_inicial, $value ,$objPHPExcel,4);
                                                                $codigo_ascii_siguiente++;
                                                                $contador_titulo++;
                                                        }
                                                    $codigo_ascii_siguiente=$codigo_ascii_columna_inicial;
                                                    $fila_inicial++;
                                                    $firt_column++;
                                                    
                                        
                                        
                                            }
                                        
                                        $fila_inicial=$fila_inicial+4;
                                        $contador++;
                                        $firt_column=0;
                                        $contador_titulo=0;
                                        
                                    
                            }
                          }    


                            //va a contenido fjio
                  if ($parameters_array["tiene_contenido_fijo"]==1){

                    $columna_contenido_fijo =$parameters_array["contenido_fijo"]["contenido"];
                    $text_columna_inicial =$parameters_array["contenido_fijo"]["columna"];
                    $codigo_ascii_columna_inicial =ord( $text_columna_inicial );
                    $fila_inicial=$parameters_array["contenido_fijo"]["fila"];


                             foreach ($columna_contenido_fijo as $dato) {
                           
                                
                              $datos  =explode("||",$dato) ;

                                    foreach ($datos as $value) {
                                            add_title(chr($codigo_ascii_siguiente),( $fila_inicial), $value, $objPHPExcel,4);
                                            $codigo_ascii_siguiente++;

                                    }
                                    $codigo_ascii_siguiente=$codigo_ascii_columna_inicial;
                                    $fila_inicial++;
                    

                    
                              }


                    }








      $objPHPExcel->getActiveSheet()->setTitle('Reporte Modificaciones');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


	$date=new DateTime(); 
	$date_format = $date->format('d_m_Y_H_i_s');
	$nombre_file=$parameters_array["titulo"][0]["description"].$date_format;


			if ($parameters_array["tipo"]==2){
					
					
					if (!PHPExcel_Settings::setPdfRenderer(
							$rendererName,
							$rendererLibraryPath
						)) {
						die(
							'NOTICE: Sigue tronando' .
							'<br />' .
							'at the top of this script as appropriate for your directory structure'
						);
					}
					
					
					
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
					$objWriter->save("lib/Classes/PHPExcel/reportes/".$nombre_file.".pdf");
					
					
			}else{

					$callStartTime = microtime(true);
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					$objWriter->save("lib/Classes/PHPExcel/reportes/".$nombre_file.".xlsx");
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					$callStartTime = microtime(true);
					$objPHPExcel = PHPExcel_IOFactory::load("lib/Classes/PHPExcel/reportes/".$nombre_file.".xlsx");
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
			}


	echo $nombre_file;

 
 return ;


  }


    function add_title($columna,$fila,$titulo,$objPHPExcel,$filter){

        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $objPHPExcel->getActiveSheet()->setCellValue("".$columna."".$fila."","".$titulo."");


        switch($filter){
                  case 0:
                        //COMBINADO
                        $objPHPExcel->getActiveSheet()->mergeCells("".$columna."".$fila.":".$columna."".$fila."");
                       // $objPHPExcel->getActiveSheet()->getStyle("".$columna."".$fila.":".$columna."".$fila."")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                  break;

                  case 1:
                        // SOMBREADO 
                        $objPHPExcel->getActiveSheet()->getStyle("".$columna."".$fila.":".$columna."".$fila."")->applyFromArray($styleArray);
                  break;

                  case 2:
                        //COMBINADO Y SOMBREADO
                        $objPHPExcel->getActiveSheet()->mergeCells("".$columna."".$fila.":".$columna."".$fila."");
                  //      $objPHPExcel->getActiveSheet()->getStyle("".$columna."".$fila.":".$columna."".$fila."")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle("".$columna."".$fila.":".$columna."".$fila."")->applyFromArray($styleArray);
                    
                  break;

                  case 3:
                        $objPHPExcel->getActiveSheet()->getStyle("".$columna."".$fila.":".$columna."".$fila."")->getFont()->setSize(16);
                  break;


               }
			

    }

    function application_rango($filter,$rango,$objPHPExcel){
  //estilo que va a dar bordes
      $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

            switch($filter){
                  case 0:
                        //COMBINADO
                        $objPHPExcel->getActiveSheet()->mergeCells("".$rango."");
                        $objPHPExcel->getActiveSheet()->getStyle("".$rango."")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                  break;

                  case 1:
                        // SOMBREADO 
                        $objPHPExcel->getActiveSheet()->getStyle("".$rango."")->applyFromArray($styleArray);
                  break;

                  case 2:
                        //COMBINADO Y SOMBREADO
                        $objPHPExcel->getActiveSheet()->mergeCells("".$rango."");
                        $objPHPExcel->getActiveSheet()->getStyle("".$rango."")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle("".$rango."")->applyFromArray($styleArray);
                    
                  break;

                  
                }
    }

    function cellColor_header($cells,$objPHPExcel){
       $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $color='C8EFCF';
        
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => $color)) );
        $objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
    }

	

    ?>