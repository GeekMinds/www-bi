<?php
class crm_xml_parse{

    public function parse ($xmlstr){
        $xmlstr = str_replace("1?xml",'<?xml', $xmlstr);
        $xmlstr = mb_convert_encoding($xmlstr, "UTF-8", mb_detect_encoding($xmlstr, "UTF-8, ISO-8859-1", true));
        $xmlstr = str_replace("á",'a', $xmlstr);
        $xmlstr = str_replace('encoding="ISO-8859-1','encoding="UTF-8', $xmlstr);
        $xmlstr = str_replace("<![CDATA[", '' , $xmlstr);
        $xmlstr = str_replace("]]>",'', $xmlstr);
        $xmlstr = str_replace(array("\n", "\r", "\t"), '', $xmlstr);
        $xmlstr = trim(str_replace('"', "'", $xmlstr));
        //var_dump($xmlstr);
        $xmlstr = simplexml_load_string($xmlstr);
        $json = json_encode($xmlstr);
        $result_array = json_decode($json, TRUE);
        $parametros_proceso = $result_array['area-datos']['empresas']['empresa']['tipos_gestion']['tipo_gestion']['productos']['producto']['procesos']['proceso']['parametros_proceso']['parametro_proceso'];
        //error_log(json_encode($parametros_proceso));
        $u = 0;
        foreach($parametros_proceso as $parametro):
            if(!empty($parametro['descripcion'])){
                $label =  $parametro['nombre'] .' '. $parametro['descripcion'];
            }
            else
            {
                $label =  $parametro['nombre'];
            }
            
            if($parametro['@attributes']['requerido'] == "1"){
                $valTF = true;
            }else
            {
                $valTF = false;
            }
            switch($parametro['@attributes']['tipo_dato']){
                case '1': 
                    $tipoDATA = 'number';
                    break;
                case '2':
                    $tipoDATA = 'text';
                    break;
                case '3':
                    $tipoDATA = 'dropdown';
                    break;
                case '4':
                    $tipoDATA = 'date';
                    break;
                case '5':
                    $tipoDATA = 'time';
                    break;
                case '6':
                    $tipoDATA = 'number';
                    break;
                default:
                    $tipoDATA = 'text';
                    break;
                                
            }
           // $toEncode[$u]['cid']        = '';
            
            $toEncode[$u]['crm_field_id'] = $parametro['@attributes']['id'];
            $toEncode[$u]['field_type'] = $tipoDATA;
            $toEncode[$u]['getvar']     = ' ';
            $toEncode[$u]['hidden']     = false;
            $toEncode[$u]['label']      = $label;
            $toEncode[$u]['label_en']   = $label;
            $toEncode[$u]['maxlength']  = $parametro['@attributes']['long'];
            $toEncode[$u]['minlength']  = '0';
            $toEncode[$u]['module_mail'] = '';
            $toEncode[$u]['module_name_es'] = $result_array['area-datos']['empresas']['empresa']['tipos_gestion']['tipo_gestion']['productos']['producto']['procesos']['proceso']['nombre'];
            $toEncode[$u]['module_name_en'] = $result_array['area-datos']['empresas']['empresa']['tipos_gestion']['tipo_gestion']['productos']['producto']['procesos']['proceso']['nombre'];
            $toEncode[$u]['postvar']    = '';
            $toEncode[$u]['required']   = $valTF;
            if($parametro['@attributes']['tipo_dato'] =='3'){
                $pieces = explode("|", $parametro['valores']);
                $i = 0;
                foreach($pieces as $elemeto):
                    $value = substr($elemeto, 0, 3);
                    $name = substr($elemeto, 3);
                    if( $value == '--S'){
                        $toEncode[$u]['field_options']['options'][$i]['checked'] = false;
                        $toEncode[$u]['field_options']['options'][$i]['label'] = $elemeto;
                    }
                    elseif( $value == 'Q.'){
                        $toEncode[$u]['field_options']['options'][$i]['checked'] = false;
                        $toEncode[$u]['field_options']['options'][$i]['label'] = $elemeto;
                    }elseif( $value == 'US$'){
                        $toEncode[$u]['field_options']['options'][$i]['checked'] = false;
                        $toEncode[$u]['field_options']['options'][$i]['label'] = $elemeto;
                    }
                    else
                    {
                        $toEncode[$u]['field_options']['options'][$i]['checked'] = false;
                        $toEncode[$u]['field_options']['options'][$i]['label'] = $name;
                    }
                    $i++;
                endforeach;
            }
            $u++;
        endforeach;
        return $toEncode;
    }
}
