<?php
class crm_xml_parse{

    public function parse ($xmlstr){
        
        $xmlstr = str_replace("<![CDATA[", '' , $xmlstr);
        $xmlstr = str_replace("]]>",'', $xmlstr);
        //$xmlstr = str_replace("--",'', $xmlstr);
        $xmlstr = str_replace('encoding="ISO-8859-1','encoding="UTF-8', $xmlstr);
        $xmlstr = str_replace(array("\n", "\r", "\t"), '', $xmlstr);
        $xmlstr = trim(str_replace('"', "'", $xmlstr));
        $xmlstr = simplexml_load_string($xmlstr);
        $json = json_encode($xmlstr);
        $result_array = json_decode($json, TRUE);
        $parametros_proceso = $result_array['area-datos']['empresas']['empresa']['tipos_gestion']['tipo_gestion']['productos']['producto']['procesos']['proceso']['parametros_proceso']['parametro_proceso'];
        $u = 0;
        foreach($parametros_proceso as $parametro):
            if(!empty($parametro['descripcion'])){
                $label =  $parametro['nombre'] .' '. $parametro['descripcion'];
            }
            else
            {
                $label =  $parametro['nombre'];
            }
            $toEncode['result'][$u]['cid']        = '-2';
            $toEncode['result'][$u]['field_type'] = $parametro['@attributes']['tipo_dato'];
            $toEncode['result'][$u]['getvar']     = '';
            $toEncode['result'][$u]['hidden']     = 'false';
            $toEncode['result'][$u]['label']      = $label;
            $toEncode['result'][$u]['maxlength']  = $parametro['@attributes']['long'];
            $toEncode['result'][$u]['minlength']  = '0';
            $toEncode['result'][$u]['module_mail'] = '';
            $toEncode['result'][$u]['module_name'] = $result_array['area-datos']['empresas']['empresa']['tipos_gestion']['tipo_gestion']['productos']['producto']['procesos']['proceso']['nombre'];
            $toEncode['result'][$u]['postvar']    = '';
            $toEncode['result'][$u]['required']   = $parametro['@attributes']['requerido'];
            if($parametro['@attributes']['tipo_dato'] =='3'){
                $pieces = explode("|", $parametro['valores']);
                $i = 0;
                foreach($pieces as $elemeto):
                    $value = substr($elemeto, 0, 3);
                    $name = substr($elemeto, 3);
                    if( $value == '--S'){
                        $toEncode['result'][$u]['options'][$i]['value'] = '000';
                        $toEncode['result'][$u]['options'][$i]['name'] = $elemeto;
                    }
                    elseif( $value == 'Q.'){
                        $toEncode['result'][$u]['options'][$i]['value'] = '001';
                        $toEncode['result'][$u]['options'][$i]['name'] = $elemeto;
                    }elseif( $value == 'US$'){
                        $toEncode['result'][$u]['options'][$i]['value'] = '002';
                        $toEncode['result'][$u]['options'][$i]['name'] = $elemeto;
                    }
                    else
                    {
                        $toEncode['result'][$u]['options'][$i]['value'] = $value;
                        $toEncode['result'][$u]['options'][$i]['name'] = $name;
                    }
                    $i++;
                endforeach;
            }
            $u++;
        endforeach;
        return $toEncode;
    }
}
