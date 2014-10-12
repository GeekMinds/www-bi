<?php
require_once("../../service/models/config.php");
require_once("../../service/models/funcs.modc.php");
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '';
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$link_editable = isset($_REQUEST['link_e']) ? $_REQUEST['link_e'] : '0';

global $db;
$parameters = getParameters($_POST, $_GET);
$parameters['id'] = $module_id;

$menu_info = getMenuCInfoDataBase($parameters);


//$principal_submenus = getSubMenuDataBase($parameters);
//$principal_submenus = $principal_submenus["rows"];
/*
for ($i = 0; $i < count($principal_submenus); $i++) {
    $parameters["parent_submenu_id"] = $principal_submenus[$i]['id'];
    $childs_submenu = getSubMenuChildsDataBase($parameters);
    $principal_submenus[$i]['childs'] = $childs_submenu['rows'];
}
*/
$menu_info = $menu_info["row"];

//$principal_submenus_js = json_encode($principal_submenus);

if($link_editable=="1"){
	$link_editable = "";
}else{
	$link_editable = " readonly ";
}

$section_tree = getAllChildsOfSectionDataBase($parameters);

$db->sql_close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="keywords" content="" /> 
        <title id='Description'>Module C Lateral</title>
        <link rel="stylesheet" href="./jqwidgets/styles/jqx.base.css" type="text/css" />

        <link rel="stylesheet" href="./css/bootstrap.min.css">

        <style>
            html{
                overflow:hidden;
            }
            .default{
                display:inline-block;
            }
			body{
				width:700px;	
			}
            #popup_addsubmenu{
                width: 390px;
                height: auto;
                display: none;
                border-radius: 10px;
                background-color: #FFF;
                padding-top: 20px;
                padding-left: 25px;
                padding-right: 25px;
                box-shadow: 3px 3px 11px #888888;
                border: solid;
                border-width: 1px;
                border-color: #888888;
				padding-bottom:20px;
            }
            input{
                width: 100%;
            }

            #confirmsubmit_upload, #confirmsubmit_upload_icon_vertical_men{
                display: none;
            }
            .progress_icon_vertical_men, .progress_icon_bar { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
            .bar_icon_vertical_men, .bar_icon_bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
            .percent_icon_vertical_men, .percent_icon_bar { position:absolute; display:inline-block; top:3px; left:48%; }


			#jqxTree{
				width:210px !important;	
			}
			
        </style>


<!-- <script type="text/javascript" src="./scripts/jquery-1.10.2.min.js"></script> -->
        <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>


        <script type="text/javascript" src="./scripts/demos.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxcore.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxbuttons.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxscrollbar.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxpanel.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxtree.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxdragdrop.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxcheckbox.js"></script>


        <script type="text/javascript" src="../../js/jquery.lightbox_me.js"></script>

        <script src="./scripts/jquery.form.js"></script>


        <script type="text/javascript">
            var module_id = '<?= $module_id ?>';
            var content_id = '<?= $content_id ?>';
            var itemsAgregados = '';
            var itemsModificados = '';
            var itemsEliminados = '';
            //var principal_submenus = <?= $principal_submenus_js ?>; 
			
			var tree_in_list = <?=json_encode($section_tree)?>;
			var section_tree = null;
			
            var adding_item_type = "";

            $(document).ready(function() {
				formatTree();
                createStructure();
                // Create jqxTree
                $('#jqxTree').jqxTree({allowDrag: true, allowDrop: true, height: '630px', width: '210px'});
				
				$("#jqxTree").on('dragEnd', function (event){
						/*return false;
						var level = event.args.level;
						//alert(level);
						if (level >=3){
							alert(level);
							return false;
						}*/
				});
				

                // Create and initialize Buttons
                var button_width = '128px';

                $('#Add').jqxButton({height: '25px', width: button_width});
                $('#AddBefore').jqxButton({height: '25px', width: button_width});
                $('#AddAfter').jqxButton({height: '25px', width: button_width});
                $('#Update').jqxButton({height: '25px', width: button_width});
                $('#Remove').jqxButton({height: '25px', width: button_width});
                $('#savemodulec').jqxButton({height: '25px', width: button_width});

                // Add 
                $('#Add').click(function() {
                    adding_item_type = 'additem';
                    showAddItemPopup();

                });

                // Add After
                $('#AddAfter').click(function() {
                    adding_item_type = 'addsubitem';
                    showAddItemPopup();
                });

                $('#btn_save').click(function() {
                    if (adding_item_type == "additem") {
                        addItem();
                    } else
                    if (adding_item_type == "addsubitem") {
                        addSubItem();
                    } else
                    if (adding_item_type == "updateitem") {
                        updateItem();
                    }

                });

                //Save Module C
                $('#savemodulec').click(function() {
                    saveSubmenus();
                });


                // Add Before
                $('#AddBefore').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    var label = $('#content_title_es').attr("value");
					var link = $('#inpt_link').attr("value");

                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('addBefore', {label: label, value: link}, selectedItem.element, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    } else {
                        $('#jqxTree').jqxTree('addTo', {label: label, value: link}, null, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                });

                // Update
                $('#Update').click(function() {
                    adding_item_type = 'updateitem';
                    showAddItemPopup();
					
					
					var selectedItem = $("#jqxTree").jqxTree('selectedItem');
					var array_item = parseInt($(selectedItem.element).attr("data-index"));  
					
					//if($(selectedItem.element).attr("data-level") == "0"){
						$("#content_title_es").val($(selectedItem.element).attr("data-title_es"));
						$("#content_title_en").val($(selectedItem.element).attr("data-title_en"));
						$("#inpt_link").val($(selectedItem.element).attr("data-link"));
					//}else if($(selectedItem.element).attr("data-level") == "1"){
						//var array_child_item = parseInt($(selectedItem.element).attr("data-child_index"));
						//$("#content_title_es").val($(selectedItem.element).attr("data-title_es"));
						//$("#content_title_en").val($(selectedItem.element).attr("data-title_en"));
						//$("#inpt_link").val($(selectedItem.element).attr("data-link"));
					//}
                });



                // Remove 
                $('#Remove').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    
                    if (selectedItem != null) {
                        itemsEliminados += ' Se elimino el menu: '+$(selectedItem.element).attr("data-title_es");
                        // removes the selected item. The last parameter determines whether to refresh the Tree or not.
                        // If you want to use the 'removeItem' method in a loop, set the last parameter to false and call the 'render' method after the loop.
                        $('#jqxTree').jqxTree('removeItem', selectedItem.element, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                });

                // Disable
                $('#Disable').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('disableItem', selectedItem.element);
                    }
                });

               
			   
                $('#jqxTree').jqxTree('selectItem', $("#jqxTree").find('li:first')[0]);
                $('#jqxTree').css('visibility', 'visible');
				$('#jqxTree').jqxTree('expandAll');
				$('#jqxTree').on('checkChange', function (event) {
					$(event.currentTarget).attr("data-link", $("#inpt_link").val());
				}); 
                loadUpload();
				
            });
			
			
			
			
			function showAddItemPopup() {
				 clearInputs();
				$('#popup_addsubmenu').lightbox_me({
					centered: true,
					overlayCSS: {background: 'black', opacity: 0}
				});
				
			}

			var added_index = 1;
			function addItem() {
				var selectedItem = $('#jqxTree').jqxTree('selectedItem');
				var label = $('#content_title_es').attr("value");
                                itemsAgregados += ' Se Agrego:'+label;
				var link = $('#inpt_link').attr("value");
				var li_html = '<span id="adde_id'+added_index+'" data-link="'+link+'">'+label+'</span>';
				if (selectedItem != null) {
					
					$('#jqxTree').jqxTree('addAfter', {html:li_html}, selectedItem.element, false);
					// update the tree.
					$('#jqxTree').jqxTree('render');
				} else {
					$('#jqxTree').jqxTree('addTo', {html:li_html}, null, false);
					// update the tree.
					$('#jqxTree').jqxTree('render');
				}
				$('#popup_addsubmenu').trigger('close');
				$("#adde_id"+added_index).parent().parent().attr("data-link", link);
				$("#adde_id"+added_index).parent().parent().attr("data-title_es", $('#content_title_es').attr("value"));
				$("#adde_id"+added_index).parent().parent().attr("data-title_en", $('#content_title_en').attr("value"));
				
				clearInputs();
				added_index++;
			}
			function addSubItem() {
				var selectedItem = $('#jqxTree').jqxTree('selectedItem');

				var label = $('#content_title_es').attr("value");
                                itemsAgregados += ' Se agrego el submenu:'+label;
				var link = $('#inpt_link').attr("value");
				var li_html = '<span id="adde_id'+added_index+'" data-link="'+link+'">'+label+'</span>';
				if (selectedItem != null) {
					// adds an item with label: 'item' as a child of the selected item. The last parameter determines whether to refresh the Tree or not.
					// If you want to use the 'addTo' method in a loop, set the last parameter to false and call the 'render' method after the loop.
					$('#jqxTree').jqxTree('addTo', {html:li_html}, selectedItem.element, false);
					// update the tree.
					$('#jqxTree').jqxTree('render');
				}
				else {
					$('#jqxTree').jqxTree('addTo', {html:li_html}, null, false);
					// update the tree.
					$('#jqxTree').jqxTree('render');
				}
				$('#popup_addsubmenu').trigger('close');
				$("#adde_id"+added_index).parent().parent().attr("data-link", link);
				$("#adde_id"+added_index).parent().parent().attr("data-title_es", $('#content_title_es').attr("value"));
				$("#adde_id"+added_index).parent().parent().attr("data-title_en", $('#content_title_en').attr("value"));
				clearInputs();
				added_index++;
			}

			function updateItem() {
				var selectedItem = $('#jqxTree').jqxTree('selectedItem');
				
				var label = $('#content_title_es').attr("value");
                                itemsModificados = ' Se modifico el elemento:'+label;
				var link = $('#inpt_link').attr("value");
				$(selectedItem.element).attr("data-link", link);
				$(selectedItem.element).attr("data-title_es", $('#content_title_es').attr("value"));
				$(selectedItem.element).attr("data-title_en", $('#content_title_en').attr("value"));
				
				if (selectedItem != null) {
					$('#jqxTree').jqxTree('updateItem', {label: label}, selectedItem.element);
					// update the tree.
					$('#jqxTree').jqxTree('render');
					//$(html_element).attr("data-link", link);
				}
				$('#popup_addsubmenu').trigger('close');
				/*var item_id = $(html_element).attr("data-id");
				var item_info  = getItemInfo(item_id);
				item_info.title_es = $("#content_title_es").val();
				item_info.title_en = $("#content_title_en").val();
				//item_info.link = $("#inpt_link").val();
				setItemInfo(item_info);
				*/
				clearInputs();
			}
			


            function loadUpload() {
                var bar_icon_bar = jQuery('.bar_icon_bar');
                var percent_icon_bar = jQuery('.percent_icon_bar');
                var status_icon_bar = jQuery('#status_icon_bar');

                var bar_icon_vertical_men = jQuery('.bar_icon_vertical_men');
                var percent_icon_vertical_men = jQuery('.percent_icon_vertical_men');
                var status_icon_vertical_men = jQuery('#status_icon_vertical_men');

                $('#inpt_image').change(function() {
                    $('#confirmsubmit_upload').click();
                });

                $('#formupload_icon_bar').ajaxForm({
                    beforeSend: function() {
                        status_icon_bar.empty();
                        var percentVal = '0%';
                        bar_icon_bar.width(percentVal)
                        percent_icon_bar.html(percentVal);
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        bar_icon_bar.width(percentVal)
                        percent_icon_bar.html(percentVal);
                    },
                    success: function(e) {
                        var percentVal = '100%';
                        bar_icon_bar.width(percentVal)
                        percent_icon_bar.html(percentVal);
                    },
                    complete: function(xhr) {
                        var filename = xhr.responseText.split("}");
                        filename = filename[0];
                        $("#icon_bar").attr("value", filename);
                        $("#img_icon_bar").attr("src", "./uploadfile/files/" + filename);
                        //status.html(xhr.responseText);
                    }
                });



                $('#inpt_image_icon_vertical_men').change(function() {
                    $('#confirmsubmit_upload_icon_vertical_men').click();
                });


                $('#formupload_icon_vertical_men').ajaxForm({
                    beforeSend: function() {
                        status_icon_vertical_men.empty();
                        var percentVal = '0%';
                        bar_icon_vertical_men.width(percentVal)
                        percent_icon_vertical_men.html(percentVal);
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        bar_icon_vertical_men.width(percentVal)
                        percent_icon_vertical_men.html(percentVal);
                    },
                    success: function(e) {
                        var percentVal = '100%';
                        bar_icon_vertical_men.width(percentVal)
                        percent_icon_vertical_men.html(percentVal);
                    },
                    complete: function(xhr) {
                        var filename = xhr.responseText.split("}");
                        filename = filename[0];
                        $("#icon_vertical_men").attr("value", filename);
                        $("#img_icon_vertical_men").attr("src", "./uploadfile/files/" + filename);
                        //status.html(xhr.responseText);
                    }
                });
            }
			
			
			
			function getHTMLTree(tree, level){
				var _html = '<ul>';
				var expanded = "";
				$.each(tree, function( index, data ) {
					_html += '<li data-level="'+level+'" data-index="'+index+'" data-id="'+data.id+'"  ';
						_html += 'data-link="'+data.link+'"  data-title_es="'+data.title_es+'"  ';
						_html += 'data-title_en="'+data.title_en+'">' + data.title_es;
					if(data.childs.length>0){
						childs = data.childs;
						_html += getHTMLTree(childs, level+1);
					}
					_html += "</li>";
				});
				
				return _html+= '</ul>'; 
			}

			//section_tree
            function createStructure() {
				var _html = getHTMLTree(section_tree, 1);
				$('#jqxTree').html(_html);
                /*if (principal_submenus.length <= 0) {
                    return false;
                }
                if (principal_submenus.length == 1) {
                    if (principal_submenus[0].childs == false && typeof principal_submenus[0].title_es == "undefined") {
                        return false;
                    }
                }

                var menu_str = "<ul>";
                for (var i = 0; i < principal_submenus.length; i++) {

                    menu_str += '<li data-level="0" data-index="'+i+'" data-id="'+principal_submenus[i].id+'"  data-link="'+principal_submenus[i].link+'"  data-title_es="'+principal_submenus[i].title_es+'"  data-title_en="'+principal_submenus[i].title_en+'">' + principal_submenus[i].title_es;
                    var childs = principal_submenus[i].childs;

                    if (childs.length > 0) {
                        menu_str += "<ul>";
                    }
                    for (var j = 0; j < childs.length; j++) {
                        menu_str += '<li data-level="1" data-index="'+i+'" data-child_index="'+j+'" data-id="'+childs[j].id+'"  data-link="'+childs[j].link+'"  data-title_es="'+childs[j].title_es+'" data-title_en="'+childs[j].title_en+'">';
                        menu_str += childs[j].title_es;
                        menu_str += "</li>";
                    }

                    if (childs.length > 0) {
                        menu_str += "</ul>";
                    }

                    menu_str += "</li>";

                }
                menu_str += "</ul>";
                $('#jqxTree').html(menu_str);*/
            }


            function clearInputs() {
                $('#content_title_es').attr("value", '');
                $('#content_title_en').attr("value", '');	
                $('#inpt_link').attr("value", '');	
            }


			
            function saveSubmenus() {
				var params = {};
                var data = {};
				//saveCompleteTree();
                data.module_id = module_id;
                data.content_id = content_id;
                data.title_es = $('#content_title_es').attr('value');
                data.title_en = $('#content_title_en').attr('value');
                data.icon_bar = $('#icon_bar').attr('value');
                data.icon_vertical_menu = $('#icon_vertical_men').attr('value');
                data.link = "";
                data.msg = itemsAgregados+itemsModificados+itemsEliminados;
                /*var itemsTree = $("#jqxTree").jqxTree('getItems');
                var itemsTreeArray = {};

                for (var i = 0; i < itemsTree.length; i++) {
                    if (itemsTree[i].parentId == null || itemsTree[i].parentId == 0) {
                        var submenuchilds = {};
                        submenuchilds.title_es = $(itemsTree[i].element).attr("data-title_es");
                        submenuchilds.title_en = $(itemsTree[i].element).attr("data-title_en");
                        submenuchilds.link = $(itemsTree[i].element).attr("data-link").trim();
                        submenuchilds.childs = new Array();
                        itemsTreeArray[itemsTree[i].id] = submenuchilds;
                    }
                }

                for (var i = 0; i < itemsTree.length; i++) {
                    if (itemsTree[i].parentId != null && itemsTree[i].parentId != 0) {
                        var submenuchilds = {};
                        submenuchilds.title_es = $(itemsTree[i].element).attr("data-title_es");
                        submenuchilds.title_en = $(itemsTree[i].element).attr("data-title_en");
                        submenuchilds.link = $(itemsTree[i].element).attr("data-link").trim();
                        itemsTreeArray[itemsTree[i].parentId].childs.push(submenuchilds);
                    }
                }
                data.submenuchilds = itemsTreeArray;*/
                //data.submenuchilds = 'hola';
				
				data.submenuchilds = getCompleteTree();
				
				if(!data.submenuchilds){
					return false;
				}

                params.action = "savesubmenuchilds";
                params.data = data;

                $.post('../../service/modc.php', params, function(data) {
                    if (data.Result == "OK") {
                        alert("modulo guardado con éxito!");
						parent.closePopUp(false);
                    }else{
						alert("Ha ocurrido un error: "+data.Message);
					}
                }, "json");

				
            }
			
			
			
			
			
			function formatTree(){
				section_tree = {};
				var list_of_items = {};
				for(var i=0; i<tree_in_list.length; i++){
					tree_in_list[i].childs = new Array();
					list_of_items[tree_in_list[i].id] = tree_in_list[i];
					if(tree_in_list[i].parent_submenu_id==""){
						section_tree[tree_in_list[i].id] = tree_in_list[i];
					}else{
						if(list_of_items[tree_in_list[i].parent_submenu_id]!=undefined){
							list_of_items[tree_in_list[i].parent_submenu_id].childs.push(tree_in_list[i]);
						}
					}
				}
			}
			
			
			function getCompleteTree(){
				var itemsTree = $("#jqxTree").jqxTree('getItems');
				var itemsTreeArray = {};
				var local_ids = {};
				for(var i = 0; i < itemsTree.length; i++){
					
					if(itemsTree[i].level>3){
						alert("Error: No se permite que el árbol tenga más de 4 niveles, error detectado en el nodo "+$(itemsTree[i].element).attr("data-title_es"));
						return false;
					}
					
					var submenuchilds = {};
					submenuchilds.title_es = $(itemsTree[i].element).attr("data-title_es");
					submenuchilds.title_en = $(itemsTree[i].element).attr("data-title_en");
					submenuchilds.link = $(itemsTree[i].element).attr("data-link").trim();
					submenuchilds.sequence = i;
					submenuchilds.childs = new Array();
					local_ids[itemsTree[i].id] = submenuchilds;
					
					if (itemsTree[i].parentId == null || itemsTree[i].parentId == 0)  {//ES RAIZ
						itemsTreeArray[itemsTree[i].id] = submenuchilds;
					}else{//NO ES RAIZ, por lo que se busca dentro del diccionario de ya insertados
						if(local_ids[itemsTree[i].parentId]!=undefined){
							local_ids[itemsTree[i].parentId].childs.push(submenuchilds);
						}
					}
				}
				
				for(var i = 0; i < itemsTree.length; i++){
					local_ids[itemsTree[i].id].sequence = i;
				}
				//itemsTreeArray = sortTreeBySequence(itemsTreeArray);
				return itemsTreeArray;
			}
			
			
			
			sortObj = function(obj, type, caseSensitive) {
			  var temp_array = [];
			  for (var key in obj) {
				if (obj.hasOwnProperty(key)) {
				  if (!caseSensitive) {
					key = (key['toLowerCase'] ? key.toLowerCase() : key);
				  }
				  temp_array.push(key);
				}
			  }
			  if (typeof type === 'function') {
				temp_array.sort(type);
			  } else if (type === 'value') {
				temp_array.sort(function(a,b) {
				  var x = obj[a];
				  var y = obj[b];
				  if (!caseSensitive) {
					x = (x['toLowerCase'] ? x.toLowerCase() : x);
					y = (y['toLowerCase'] ? y.toLowerCase() : y);
				  }
				  return ((x < y) ? -1 : ((x > y) ? 1 : 0));
				});
			  } else {
				temp_array.sort();
			  }
			  var temp_obj = {};
			  for (var i=0; i<temp_array.length; i++) {
				temp_obj[temp_array[i]] = obj[temp_array[i]];
			  }
			  return temp_obj;
			};

			

        </script>
    </head>
    <body class='default'>
        <div id='jqxWidget'>
            <div style='float: left;'>
                <div id='jqxTree' style='visibility: hidden; float: left; margin-left: 20px;'>

                </div>
                <div style='margin-left: 60px; float: left;'>
                    <div style='margin-top: 10px;'>
                        <input type="button" id='Add' value="Agregar Menu" />
                    </div>
                    <div style='margin-top: 10px; display:block;'>
                        <input type="button" id='AddAfter' value="Agregar Submenu" />
                    </div>
                    <div style='margin-top: 10px; display:block;'>
                        <input type="button" id='Update' value="Editar" />
                    </div>
                    <div style='margin-top: 10px; display:block;'>
                        <input type="button" id='Remove' value="Eliminar" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='AddBefore' value="Add Before" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='Disable' value="Disable" />
                    </div>
                    <div style='margin-top: 10px;  display:none;'>
                        <input type="button" id='Expand' value="Expand" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='Collapse' value="Collapse" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='ExpandAll' value="Expand All" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='CollapseAll' value="Collapse All" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='EnableAll' value="Enable All" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='Next' value="Next Item" />
                    </div>
                    <div style='margin-top: 10px; display:none;'>
                        <input type="button" id='Previous' value="Previous Item" />
                    </div>
                    <div style='margin-top: 10px;'>
                        <input type="button" id='savemodulec' value="Guardar" />
                    </div>

                </div>
            </div>
        </div>

        <div id="popup_addsubmenu">
            <label>Título en Español:</label><br/>
            <input id="content_title_es" type="text" name="title" value=""><br/>
            <label>Título en Inglés:</label><br/>
            <input id="content_title_en" type="text" name="title" value=""><br/>
<!--
            <label>Icono barra:</label><br/>

            <input id="icon_bar" type="text" name="title" value=""><br/>
            <img id="img_icon_bar" src="" width="50px" height="50px" />
            <form id="formupload_icon_bar" action="./uploadfile/file-echo.php" method="post" enctype="multipart/form-data">
                <input id="inpt_image" type="file" name="myfile"><br>
                <input id="confirmsubmit_upload" type="submit" value="Upload File to Server">
            </form>

            <div class="progress_icon_bar">
                <div class="bar_icon_bar"></div >
                <div class="percent_icon_bar">0%</div >
            </div>
            <div id="status_icon_bar"></div>

            <label>Icono menú vertical:</label><br/>
            <input id="icon_vertical_men" type="text" name="title" value=""><br/>
            <img id="img_icon_vertical_men" src="" width="50px" height="50px"/>

            <form id="formupload_icon_vertical_men" action="./uploadfile/file-echo.php" method="post" enctype="multipart/form-data">
                <input id="inpt_image_icon_vertical_men" type="file" name="myfile"><br>
                <input id="confirmsubmit_upload_icon_vertical_men" type="submit" value="Upload File to Server">
            </form>

            <div class="progress_icon_vertical_men">
                <div class="bar_icon_vertical_men"></div >
                <div class="percent_icon_vertical_men">0%</div >
            </div>
            <div id="status_icon_vertical_men"></div>
-->    
                

            <label>Link:</label><br/>
            <input id="inpt_link" type="text" name="title" value="" <?=$link_editable?> /><br/>

            <br/>

            <!-- Sub Menu
            mod_c_id
            parent_submenu_id
            link
            title_es
            title_en
            -->

            <button id="btn_save" type="button" class="btn btn-primary save">
                <i class="glyphicon glyphicon-save"></i>
                <span>OK</span>
            </button>
        </div>

    </body>
</html>
