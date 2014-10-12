<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function guardarPreguntaDB($parameters = array()) {

    global $db, $db_table_prefix;
    $mod_id = $parameters['id'];
    $idInserted;
    if ($mod_id == '-1' || $mod_id == 'undefined') {
        $sql = "INSERT INTO " . $db_table_prefix . "mod_s1_question (question_es, question_en, status) VALUES ('" . $db->sql_escape($parameters["question_es"]) . "','" . $db->sql_escape($parameters["question_en"]) . "', 1) SELECT SCOPE_IDENTITY() AS id";
        $result = $db->sql_query($sql);
        $preguntaGuardada = $db->sql_fetchrow($result);
    } else if (isset($mod_id) && !empty($mod_id)) {
        $sql = "UPDATE " . $db_table_prefix . "mod_s1_question SET
                question_es = '" . $db->sql_escape($parameters["question_es"]) . "', 
                question_en = '" . $db->sql_escape($parameters["question_en"]) . "'
                    WHERE id = '" . $db->sql_escape($mod_id) . "';";
        $result = $db->sql_query($sql);
        $preguntaGuardada['id'] = $mod_id;
    }
    //CONTAR LOS PRODUCTOS CHECKEADOS Y SI NO VIENE NINGUNO ELIMINARLOS TODOS
    //EN CASO DE QUE ALGUNO VENGA SETEADO ELIMINAR TODOS E INSERTAR EL QUE VENGA CHECKEADO
    if (count($preguntaGuardada) > 0) {
        $id_mod_s1 = $preguntaGuardada['id'];
        $registros_insertados = [];
        $sqldelete = "DELETE FROM mod_s1_question_mod_q WHERE id_mod_s1_question = $id_mod_s1";
        $resultdelete = $db->sql_query($sqldelete);
        if (count($parameters['productos']) > 0) {
            for ($j = 0; $j < count($parameters['productos']); $j++) {
                $sql3 = '';
                $sql3 = "INSERT INTO " . $db_table_prefix . "mod_s1_question_mod_q (
                    id_mod_q,
                    id_mod_s1_question) VALUES('" . $db->sql_escape($parameters['productos'][$j]) . "',
                        '" . $db->sql_escape($id_mod_s1) . "') SELECT SCOPE_IDENTITY() AS id";
                $result3 = $db->sql_query($sql3);
                $relacion_guardada = $db->sql_fetchrow($result3);
                $registros_insertados['relaciones'][$j] = $relacion_guardada['id'];
            }
        }
        if (count($parameters['respuestas']) > 0) {
            for ($i = 0; $i < count($parameters['respuestas']); $i++) {
                $sql2 = '';
                $accion = $parameters['respuestas'][$i]['accion'];
                $id_answer = $parameters['respuestas'][$i]['id'];
                if ($accion == '-1') {
                    if ($parameters['respuestas'][$i]['titulo_es'] !== '' && $parameters['respuestas'][$i]['link_es'] !== '') {
                        if ($parameters['respuestas'][$i]['titulo_en'] !== '' && $parameters['respuestas'][$i]['link_en'] !== '') {
                            $sql2 = "INSERT INTO " . $db_table_prefix . "mod_s1_answer (
                                    title_es,
                                    title_en,
                                    link_es,
                                    link_en,
                                    id_mod_s1_question,
                                    status
                                    )
                                    VALUES (
                                    '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_es']) . "',
                                    '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_en']) . "',
                                    '" . $db->sql_escape($parameters['respuestas'][$i]['link_es']) . "',
                                    '" . $db->sql_escape($parameters['respuestas'][$i]['link_en']) . "',
                                    '" . $id_mod_s1 . "', 1            
                                    ) SELECT SCOPE_IDENTITY() AS id";
                        } else {
                            $sql2 = "INSERT INTO " . $db_table_prefix . "mod_s1_answer (
                                    title_es,
                                    title_en,
                                    link_es,
                                    link_en,
                                    id_mod_s1_question, status
                                    )
                                    VALUES (
                                    '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_es']) . "',
                                    '',
                                    '" . $db->sql_escape($parameters['respuestas'][$i]['link_es']) . "',
                                    '',
                                    '" . $id_mod_s1 . "', 1            
                                    ) SELECT SCOPE_IDENTITY() AS id";
                        }
                    } elseif ($parameters['respuestas'][$i]['titulo_en'] !== '' && $parameters['respuestas'][$i]['link_en'] !== '') {
                        $sql2 = "INSERT INTO " . $db_table_prefix . "mod_s1_answer (
                                title_es,
                                title_en,
                                link_es,
                                link_en,
                                id_mod_s1_question,
                                status
                                )
                                VALUES (
                                '',
                                '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_en']) . "',
                                '',
                                '" . $db->sql_escape($parameters['respuestas'][$i]['link_en']) . "',
                                '" . $id_mod_s1 . "', 
                                1            
                                ) SELECT SCOPE_IDENTITY() AS id";
                    }
                    if (!empty($sql2) && isset($sql2)) {
                        $result2 = $db->sql_query($sql2);
                        $respuesta_guardada = $db->sql_fetchrow($result2);
                        $registros_insertados['respuestas'][$i] = $respuesta_guardada['id'];
                    }
                } 
                elseif ($accion == '-2') {
                    $sql2 = "UPDATE " . $db_table_prefix . "mod_s1_answer SET
                                    status = 0 WHERE id = $id_answer";
                    $result2 = $db->sql_query($sql2);
                } else {
                    if ($parameters['respuestas'][$i]['titulo_es'] !== '' && $parameters['respuestas'][$i]['link_es'] !== '') {
                        if ($parameters['respuestas'][$i]['titulo_en'] !== '' && $parameters['respuestas'][$i]['link_en'] !== '') {
                            $sql2 = "UPDATE " . $db_table_prefix . "mod_s1_answer SET
                                    title_es = '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_es']) . "', 
                                    title_en = '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_en']) . "',
                                    link_es = '" . $db->sql_escape($parameters['respuestas'][$i]['link_es']) . "',
                                    link_en = '" . $db->sql_escape($parameters['respuestas'][$i]['link_en']) . "',
                                    id_mod_s1_question = '" . $id_mod_s1 . "' WHERE id = $id_answer;";
                        } else {
                            $sql2 = "UPDATE " . $db_table_prefix . "mod_s1_answer SET
                                    title_es = '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_es']) . "', 
                                    title_en = '',
                                    link_es = '" . $db->sql_escape($parameters['respuestas'][$i]['link_es']) . "',
                                    link_en = '',
                                    id_mod_s1_question = '" . $id_mod_s1 . "' WHERE id = $id_answer;";
                        }
                    } elseif ($parameters['respuestas'][$i]['titulo_en'] !== '' && $parameters['respuestas'][$i]['link_en'] !== '') {
                        $sql2 = "UPDATE " . $db_table_prefix . "mod_s1_answer SET
                                    title_es = '', 
                                    title_en = '" . $db->sql_escape($parameters['respuestas'][$i]['titulo_en']) . "',
                                    link_es = '',
                                    link_en = '" . $db->sql_escape($parameters['respuestas'][$i]['link_en']) . "',
                                    id_mod_s1_question = '" . $id_mod_s1 . "' WHERE id = $id_answer;";
                    }
                    if (!empty($sql2) && isset($sql2)) {
                        $result2 = $db->sql_query($sql2);
                        $respuesta_guardada = $db->sql_fetchrow($result2);
                        $registros_insertados['respuestas'][$i] = $respuesta_guardada['id'];
                    }
                }
            }

            return $registros_insertados;
        } else {
            return $sql;
        }
        return false;
    }
}
    function guardarModS1DB($parameters = array()) {
        global $db, $db_table_prefix;

        $sql = "INSERT INTO " . $db_table_prefix . "mod_s1 (content_id) VALUES ('" . $db->sql_escape($parameters["content_id"]) . "') SELECT SCOPE_IDENTITY() AS id";
        $idInserted;
        $result = $db->sql_query($sql);
        $module = $db->sql_fetchrow($result);
        $toReturn = array();
        if (count($module) > 0) {
            $idInserted = $module['id'];
            $toReturn['mod_s1'] = $idInserted;
            $toReturn['respuestas'] = guardarPreguntaDB($parameters);
        }

        return $toReturn;
    }
function guardarEstado($parameters = array()){
    global $db, $db_table_prefix;
    $id = $parameters['id'];
    $sql = "UPDATE " . $db_table_prefix . "mod_s1_question SET
                                    status = 0 WHERE id = $id";
    $result = $db->sql_query($sql);
    if($result){
        return true;
    }
    return false;
}
?>
