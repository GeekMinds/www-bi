/* 
 *Author: Javier Cifuentes
 */
var openedMenu = false;
$(function() {
    $('.nav li a').click(function(e) {
        if ($(this).attr('href') === '#') {
            e.preventDefault();
            if ($(this).parent().hasClass('active')) {
                $('.submenu').slideUp("fast");
                $(this).parent().parent().find('li').removeClass('active');
                $(this).parent().parent().find('li').removeClass('arrow');
                openedMenu = false;
            } else {
                var menu = $(this).parent();
                $('.submenu').slideUp("fast");
                $('.submenu').slideDown("fast", function() {
                    menu.addClass('arrow');
                });
                $(this).parent().parent().find('li').removeClass('active');
                $(this).parent().parent().find('li').removeClass('arrow');
                $(this).parent().addClass('active');
            }

        } else {
            //window.top.location = $(this).attr('href');
        }
    });
    $('.parentSelectMenu li.hasChildren').click(function(e) {
        e.preventDefault();
        
        if($(this).siblings('.active').length>0){
            $('#selectMenu').slideUp("fast");
            $('#selectMenu').slideDown("slow");
            $(this).parent().find('li').removeClass('active');
            $(this).addClass('active');
        }else{
            if($('.parentSelectMenu li.hasChildren').siblings('.active').length>0){
                $('#selectMenu').slideUp();
                $(this).parent().find('li').removeClass('active');
            }else{
                $('#selectMenu').slideDown("slow");
                $(this).addClass('active');
            }
        }
        
       
    });
});
