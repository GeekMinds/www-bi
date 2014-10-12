<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$xmlstr = <<<XML
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<CRM.OUTPUT>
    <resultado>
        <HRESULT>1</HRESULT>
        <mensaje><![CDATA[Transaccion exitosa]]></mensaje>
    </resultado>
    <area-datos>
        <empresas>
            <empresa id="28">
                <nombre><![CDATA[AGENCIAS BAINSA]]></nombre>
                <tipos_gestion>
                    <tipo_gestion id="131">
                        <nombre><![CDATA[SOLICITUD BANCO]]></nombre>
                        <productos>
                            <producto id="2210">
                                <nombre><![CDATA[SOPORTE INTERNO]]></nombre>
                                <procesos>
                                    <proceso id="9490" clase="1">
                                        <nombre><![CDATA[ACTIVACION DE CUENTAS INACTIVAS SALDO MAYOR - PL]]></nombre>
                                        <descripcion><![CDATA[PROCESO PARA LA ACTIVACION DE CUENTAS INACTIVAS YA SEA EN DOLARES O EN QUETZALES]]></descripcion>
                                        <descripcion_publica><![CDATA[]]></descripcion_publica>
                                        <parametros_proceso>
                                            <parametro_proceso id="109987" requerido="1" tipo_dato_crm="0" tipo_dato="2" long="100">
                                                <nombre><![CDATA[NOMBRE COMPLETO]]></nombre>
                                                <descripcion><![CDATA[DEL CLIENTE]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109985" requerido="0" tipo_dato_crm="12" tipo_dato="2" long="15">
                                                <nombre><![CDATA[NÚMERO DE CÉDULA]]></nombre>
                                                <descripcion><![CDATA[DEL CLIENTE]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109986" requerido="0" tipo_dato_crm="0" tipo_dato="2" long="150">
                                                <nombre><![CDATA[DPI]]></nombre>
                                                <descripcion><![CDATA[(SI TUVIERA)]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109988" requerido="1" tipo_dato_crm="0" tipo_dato="1" long="12">
                                                <nombre><![CDATA[NÚMERO DE TELEFONO]]></nombre>
                                                <descripcion><![CDATA[QUE DESEA QUE SE LE LLAME AL CLIENTE]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109989" requerido="1" tipo_dato_crm="0" tipo_dato="3" long="0">
                                                <nombre><![CDATA[MONEDA (Q/$)]]></nombre>
                                                <descripcion><![CDATA[ ]]></descripcion>
                                                <valores><![CDATA[--SELECCIONE AQUI|Q.|US$.]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109990" requerido="1" tipo_dato_crm="0" tipo_dato="2" long="15">
                                                <nombre><![CDATA[NUMERO DE CUENTA MONETARIA]]></nombre>
                                                <descripcion><![CDATA[DEL CLIENTE]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109991" requerido="1" tipo_dato_crm="0" tipo_dato="2" long="50">
                                                <nombre><![CDATA[SALDO ACTUAL]]></nombre>
                                                <descripcion><![CDATA[DE LA CUENTA DEL CLIENTE]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109992" requerido="1" tipo_dato_crm="0" tipo_dato="2" long="250">
                                                <nombre><![CDATA[EMPLEADO]]></nombre>
                                                <descripcion><![CDATA[CREADOR DEL CASO]]></descripcion>
                                                <valores><![CDATA[]]></valores>
                                            </parametro_proceso>
                                            <parametro_proceso id="109993" requerido="1" tipo_dato_crm="0" tipo_dato="3" long="0">
                                                <nombre><![CDATA[AGENCIA]]></nombre>
                                                <descripcion><![CDATA[QUE CREO EL CASO]]></descripcion>
                                                <valores><![CDATA[000	CENTRAL 	|001	PARROQUIA	|002	DEL CENTRO	|003	17 CALLE	|004	MARISCAL	|006	OBELISCO	|007	ROOSEVELT	|012	SEPTIMA AVENIDA	|013	AGUILAR BATRES	|014	AUTOSEXTA	|016	ANTIGUA	|017	AMERICAS	|018	VISTA HERMOSA	|026	LA QUINTA	|027	PETAPA I	|029	VILLA NUEVA	|030	SAN JOSE PINULA	|038	CHIMALTENANGO	|040	CIUDAD SAN CRISTOBAL	|042	PUERTA PARADA	|048	PRADERA 	|049	TIKAL FUTURA	|053	MARGARITAS	|059	MIXCO	|060	ZONA 14	|062	PLAZA ZONA 4	|064	VILLA MAGNA	|065	HIPER PUERTA PARADA	|066	ATLANTICO	|069	GALILEO 	|072	PINABETES	|073	MULTIMEDICA	|074	MIRAFLORES	|080	U. MARIANO GALVEZ	|081	U. FRANCISCO MARROQUIN	|087	ATANASIO TZUL	|091 PUERTO DE SAN JOSE|092	PAMPLONA	|093	MAYA	|095	CENTRO CIVICO 	|096	HIPER PAIZ VILLA NUEVA	|103	BOCA DEL MONTE	|104	HIPER SAN NICOLAS	|105	PLAZA SAN NICOLAS	|107	GRAN PLAZA PUERTA PARADA	|117	HIPER ROOSEVELT	|126	METROCENTRO	|127	EL FRUTAL	|136	ZONA 1 EEGSA	|141	DEL ISTMO	|142	VILLA CANALES	|143	ZONA 13 (COMBEX)	|144	BANCOMATICO CENTRAL 	|149	GALERIAS MIRAFLORES 	|150	LA TERMINAL	|151	COLINAS DE MONTE MARIA 	|156	DEL VALLE 	|158	EUROPLAZA	|161	PROVAL	|169	SAN LUCAS	|172	EL NARANJO	|173 BARBERENA|177	EL FARO 	|183	COMERCIAL ATANASIO 	|185	RAFAEL LANDIVAR	|188	PRADERA CONCEPCION 	|193	PRADERA CHIMALTENANGO	|194	SANTA AMELIA 	|198	PLAZA ATANASIO TZUL 	|204	CORPORACION	|206	COMERCIAL SAN CRISTOBAL	|207	MEGACENTRO	|208	AGUILAR BATRES III	|210	MONTUFAR II	|211	MONTSERRAT	|214	COMERCIAL VILLA NUEVA 	|215 MAZATENANGO II|216	21 CALLE	|217	GREMIAL DE EXPORTADORES	|220	ANTIGUA III	|223	LA TORRE II	|230	HIPER DEL NORTE	|236	PLAZA FLORIDA	|237	METATERMINAL DEL NORTE	|245	ESCALA	|267	PNUD	|269	CHIMALTENANGO II	|270	COMERCIAL FRAIJANES	|278 NUEVA SANTA ROSA|283	SAN RAFAEL	|284	WACKENHUT	|285	SAN JUAN SACATEPEQUEZ	|286	LA BRIGADA	|288	EL ENCINAL	|289	COMERCIAL PRIMMA	|290 PRADERA CHIQUIMULA|292	PRADERA LINDA VISTA	|306	CAMINO REAL	|307	MAYCOM ZONA 10	|308	MAYCOM COTIO	|309	MAYCOM METRONORTE	|312	AEROPUERTO	|313	PLAZA EL ROBLE	|314	PREMIUM CONDADO CONCEPCION (VIP)	|315	PLAZA PRIMMA	|317	PRADERA VILLA NUEVA	|319	TECPAN	|323	PLAZA FONTABELLA	|325	OAKLAND MALL	|333	ZONA PRADERA	|335	ESCALA ROOSEVELT	|345	COMERCIAL PETAPA	|379	SANTA CLARA	|380	MIX COMERCIAL SAN CRISTOBAL	|385	TORRE III	|404	PLAZA METRONORTE	|405	VISTA HERMOSA II	|406	LAS AMERICAS II	|407	BOLIVAR	|408	PARROQUIA II	|409	SAN JUAN II	|410	CONDADO CONCEPCION	|412	ROOSEVELT IV	|425 SAN ANTONIO SUCHITEPEQUEZ|441	LA TERCERA	|709 DECO CITY]]></valores>
                                            </parametro_proceso>
                                        </parametros_proceso>
                                    </proceso>
                                </procesos>
                            </producto>
                        </productos>
                    </tipo_gestion>
                </tipos_gestion>
            </empresa>
        </empresas>
    </area-datos>
</CRM.OUTPUT>
XML;

?>