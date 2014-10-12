<?php
require_once("../../service/models/config.php");
require_once("../../service/models/funcs.modc.php");
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '';
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';

global $db;
$parameters = getParameters($_POST, $_GET);
$parameters['id'] = $module_id;


$menu_info = getMenuCInfoDataBase($parameters);


$principal_submenus = getSubMenuDataBase($parameters);
$principal_submenus = $principal_submenus["rows"];

for ($i = 0; $i < count($principal_submenus); $i++) {
    $parameters["parent_submenu_id"] = $principal_submenus[$i]['id'];
    $childs_submenu = getSubMenuChildsDataBase($parameters);
    $principal_submenus[$i]['childs'] = $childs_submenu['rows'];
}

$menu_info = $menu_info["row"];

$principal_submenus_js = json_encode($principal_submenus);

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
                width: 450px;
                height: 585px;
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
            var principal_submenus = <?= $principal_submenus_js ?>;
            var adding_item_type = "";

            $(document).ready(function() {
                try{
                parent.onLoading(true);
            }catch(e){
                
            }
                createStructure();
                // Create jqxTree
                $('#jqxTree').jqxTree({allowDrag: true, allowDrop: true, height: '630px', width: '300px'});


                // Create and initialize Buttons
                var button_width = '128px';

                $('#Add').jqxButton({height: '25px', width: button_width});
                $('#AddBefore').jqxButton({height: '25px', width: button_width});
                $('#AddAfter').jqxButton({height: '25px', width: button_width});
                $('#Update').jqxButton({height: '25px', width: button_width});
                $('#Remove').jqxButton({height: '25px', width: button_width});
                $('#Disable').jqxButton({height: '25px', width: button_width});
                $('#EnableAll').jqxButton({height: '25px', width: button_width});
                $('#Expand').jqxButton({height: '25px', width: button_width});
                $('#Collapse').jqxButton({height: '25px', width: button_width});
                $('#ExpandAll').jqxButton({height: '25px', width: button_width});
                $('#CollapseAll').jqxButton({height: '25px', width: button_width});
                $('#Next').jqxButton({height: '25px', width: button_width});
                $('#Previous').jqxButton({height: '25px', width: button_width});
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


                function showAddItemPopup() {
                    $('#popup_addsubmenu').lightbox_me({
                        centered: true,
                        overlayCSS: {background: 'black', opacity: 0}
                    });
                }

                function addItem() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    var label = $('#content_title_es').attr("value");
                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('addAfter', {label: label}, selectedItem.element, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    } else {
                        $('#jqxTree').jqxTree('addTo', {label: label, url: "bancoindustrial.com"}, null, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                    $('#popup_addsubmenu').trigger('close');
                    clearInputs();
                }
                function addSubItem() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');

                    var label = $('#content_title_es').attr("value");

                    if (selectedItem != null) {
                        // adds an item with label: 'item' as a child of the selected item. The last parameter determines whether to refresh the Tree or not.
                        // If you want to use the 'addTo' method in a loop, set the last parameter to false and call the 'render' method after the loop.
                        $('#jqxTree').jqxTree('addTo', {label: label, url: "bancoindustrial.com"}, selectedItem.element, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                    else {
                        $('#jqxTree').jqxTree('addTo', {label: label, url: "bancoindustrial.com"}, null, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                    $('#popup_addsubmenu').trigger('close');
                    clearInputs();
                }

                function updateItem() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    var label = $('#content_title_es').attr("value");

                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('updateItem', {label: label}, selectedItem.element);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                    $('#popup_addsubmenu').trigger('close');
                    clearInputs();
                }

                // Add Before
                $('#AddBefore').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    var label = $('#content_title_es').attr("value");

                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('addBefore', {label: label, url: "bancoindustrial.com"}, selectedItem.element, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    } else {
                        $('#jqxTree').jqxTree('addTo', {label: label, url: "bancoindustrial.com"}, null, false);
                        // update the tree.
                        $('#jqxTree').jqxTree('render');
                    }
                });

                // Update
                $('#Update').click(function() {
                    /*var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                 
                     if (selectedItem != null) {
                     $('#jqxTree').jqxTree('updateItem', { label: 'Item' }, selectedItem.element);
                     // update the tree.
                     $('#jqxTree').jqxTree('render');
                     }*/
                    adding_item_type = 'updateitem';
                    showAddItemPopup();
                });



                // Remove 
                $('#Remove').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    if (selectedItem != null) {
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

                // Expand
                $('#Expand').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('expandItem', selectedItem.element);
                    }
                });

                // Expand
                $('#Collapse').click(function() {
                    var selectedItem = $('#jqxTree').jqxTree('selectedItem');
                    if (selectedItem != null) {
                        $('#jqxTree').jqxTree('collapseItem', selectedItem.element);
                    }
                });

                // Expand All
                $('#ExpandAll').click(function() {
                    $('#jqxTree').jqxTree('expandAll');
                });

                // Collapse All
                $('#CollapseAll').click(function() {
                    $('#jqxTree').jqxTree('collapseAll');
                });

                // Enable All
                $('#EnableAll').click(function() {
                    $('#jqxTree').jqxTree('enableAll');
                });

                // Select Next Item
                $('#Next').click(function() {
                    var selectedItem = $("#jqxTree").jqxTree('selectedItem');
                    var nextItem = $("#jqxTree").jqxTree('getNextItem', selectedItem.element);
                    if (nextItem != null) {
                        $("#jqxTree").jqxTree('selectItem', nextItem.element);
                        $("#jqxTree").jqxTree('ensureVisible', nextItem.element);
                    }
                });

                // Select Previous Item
                $('#Previous').click(function() {
                    var selectedItem = $("#jqxTree").jqxTree('selectedItem');
                    var prevItem = $("#jqxTree").jqxTree('getPrevItem', selectedItem.element);
                    if (prevItem != null) {
                        $("#jqxTree").jqxTree('selectItem', prevItem.element);
                        $("#jqxTree").jqxTree('ensureVisible', prevItem.element);
                    }
                });
                $('#jqxTree').jqxTree('selectItem', $("#jqxTree").find('li:first')[0]);
                $('#jqxTree').css('visibility', 'visible');

                loadUpload();
				
				
				if(module_id!=""){
					
				}

            });


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


            function createStructure() {
                if (principal_submenus.length <= 0) {
                    return false;
                }
                if (principal_submenus.length == 1) {
                    if (principal_submenus[0].childs == false && typeof principal_submenus[0].title_es == "undefined") {
                        return false;
                    }
                }

                var menu_str = "<ul>";
                for (var i = 0; i < principal_submenus.length; i++) {

                    menu_str += "<li>" + principal_submenus[i].title_es;
                    var childs = principal_submenus[i].childs;

                    if (childs.length > 0) {
                        menu_str += "<ul>";
                    }
                    for (var j = 0; j < childs.length; j++) {
                        menu_str += "<li>";
                        menu_str += childs[j].title_es;
                        menu_str += "</li>";
                    }

                    if (childs.length > 0) {
                        menu_str += "</ul>";
                    }

                    menu_str += "</li>";

                }
                menu_str += "</ul>";
                $('#jqxTree').html(menu_str);
            }


            function clearInputs() {
                $('#content_title_es').attr("value", '');
                $('#content_title_en').attr("value", '');
            }

            function saveSubmenus() {
                var params = {};
                var data = {};

                data.module_id = module_id;
                data.content_id = content_id;
                data.title_es = $('#content_title_es').attr('value');
                data.title_en = $('#content_title_en').attr('value');
                data.icon_bar = $('#icon_bar').attr('value');
                data.icon_vertical_menu = $('#icon_vertical_men').attr('value');
                data.link = $('#inpt_link').attr('value');

                var itemsTree = $("#jqxTree").jqxTree('getItems');
                var itemsTreeArray = {};

                for (var i = 0; i < itemsTree.length; i++) {
                    if (itemsTree[i].parentId == null || itemsTree[i].parentId == 0) {
                        var submenuchilds = {};
                        submenuchilds.title_es = itemsTree[i].label;
                        submenuchilds.title_en = itemsTree[i].label;
                        submenuchilds.link = $('#inpt_link').attr('value');
                        submenuchilds.childs = new Array();
                        itemsTreeArray[itemsTree[i].id] = submenuchilds;
                    }
                }

                for (var i = 0; i < itemsTree.length; i++) {
                    if (itemsTree[i].parentId != null && itemsTree[i].parentId != 0) {
                        var submenuchilds = {};
                        submenuchilds.title_es = itemsTree[i].label;
                        submenuchilds.title_en = itemsTree[i].label;
                        submenuchilds.link = $('#inpt_link').attr('value');
                        itemsTreeArray[itemsTree[i].parentId].childs.push(submenuchilds);
                    }
                }



                data.submenuchilds = itemsTreeArray;
                //data.submenuchilds = 'hola';


                params.action = "savesubmenuchilds";
                params.data = data;

                $.post('../../service/modc.php', params, function(data) {
                    if (data.Result == "OK") {
                        alert("modulo text guardado con éxito!");
                    }
                }, "json");


            }

        </script>
    </head>
    <body class='default'>
    	<label>Para editar dirijase a secciones</label> 
        <div id='jqxWidget'>
            <div style='float: left;'>
                <div id='jqxTree' style='visibility: hidden; float: left; margin-left: 20px;'>

                    <!--<ul>
                        <li id='home'>Home</li>
                        <li item-expanded='true'>Solutions
                            <ul>
                                <li>Education</li>
                                <li>Financial services</li>
                                <li>Government</li>
                                <li>Manufacturing</li>
                                <li>Solutions
                                    <ul>
                                        <li>Consumer photo and video</li>
                                        <li>Mobile</li>
                                        <li>Rich Internet applications</li>
                                        <li>Technical communication</li>
                                        <li>Training and eLearning</li>
                                        <li>Web conferencing</li>
                                    </ul>
                                </li>
                                <li>All industries and solutions</li>
                            </ul>
                        </li>
                        <li>Products
                            <ul>
                                <li>PC products</li>
                                <li>Mobile products</li>
                                <li>All products</li>
                            </ul>
                        </li>
                        <li>Support
                            <ul>
                                <li>Support home</li>
                                <li>Customer Service</li>
                                <li>Knowledge base</li>
                                <li>Books</li>
                                <li>Training and certification</li>
                                <li>Support programs</li>
                                <li>Forums</li>
                                <li>Documentation</li>
                                <li>Updates</li>
                            </ul>
                        </li>
                        <li>Communities
                            <ul>
                                <li>Designers</li>
                                <li>Developers</li>
                                <li>Educators and students</li>
                                <li>Partners</li>
                                <li>By resource
                                    <ul>
                                        <li>Labs</li>
                                        <li>TV</li>
                                        <li>Forums</li>
                                        <li>Exchange</li>
                                        <li>Blogs</li>
                                        <li>Experience Design</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>Company
                            <ul>
                                <li>About Us</li>
                                <li>Press</li>
                                <li>Investor Relations</li>
                                <li>Corporate Affairs</li>
                                <li>Careers</li>
                                <li>Showcase</li>
                                <li>Events</li>
                                <li>Contact Us</li>
                                <li>Become an affiliate</li>
                            </ul>
                        </li>
                    </ul>-->
                </div>
                <div style='margin-left: 60px; float: left; display:none;'>
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
            <br/>

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



            <label>Link:</label><br/>
            <input id="inpt_link" type="text" name="title" value=""><br/>

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
                <span>Save</span>
            </button>
        </div>

    </body>
</html>
