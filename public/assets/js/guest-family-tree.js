/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function($) {
    var rootDiv = '';
    var treeGround = null;
    var memberDetails = null;
    var object = new Object();
    var rut = null;
    var parent = null;

    $.fn.pk_family = function(options) {
        if (rootDiv == null) {
            // error message in console
            jQuery.error('wrong id given');
            return;
        }
        rootDiv = this;
        init();
    }

    // function to create tree from json data
    $.fn.pk_family_create = function(options) {
        if (rootDiv == null) {
            // error message in console
            jQuery.error('wrong id given');
            return;
        }
        rootDiv = this;
        var settings = $.extend({
            // These are the defaults.
            data: "",
        }, options);
        var obj = jQuery.parseJSON(settings.data);
        addBreadingGround();
        parent = $('<ul>');
        $(parent).appendTo(treeGround);
        traverseObj(obj);

        member_details();
        //createOptionsMenu();
        document.oncontextmenu = function() {
            return false;
        };

    }
    function tempTest(obj) {
        for (var i in obj) {
            document.write(i + " &nbsp;");
            if (i.indexOf('a') > -1 && i.length == 2) {
                ;
            } else {
                tempTest(obj[i]);
            }
        }
        return;
    }
    function traverseObj(obj) {

        for (var i in obj) {
            if (i.indexOf("li") > -1) {
                var li = $('<li>');
                $(li).appendTo(parent);
                parent = li;
                traverseObj(obj[i]);
				parent = $(parent).parent();
            }
            if (i.indexOf("a") > -1 && i.length == 2) {
                var link = $('<a>');
                link.attr('data-name', obj[i].name);
                //link.attr('data-age', obj[i].age);
                link.attr('data-gender', obj[i].gender);
                link.attr('data-relation', obj[i].relation);
                link.attr('data-dobDate', obj[i].dobDate);
                link.attr('data-dodDate', obj[i].dodDate);
				if(obj[i].relation == 'Spouse'){
					link.attr('class', 'spouse');
				}
                /* Check for father and add before mother */
                if(obj[i].relation == 'Mother'){
                    var checkFatherElement = $(parent).find("a:first");
                    if(checkFatherElement.length > 0){
					    link.attr('class', 'mother');
                    }
				}
                
                var center = $('<center>').appendTo(link);
                var pic = $('<img>').attr('src', obj[i].pic);
                var extraData = "";
                if (obj[i].gender == "male") {
                    extraData = "(M)";
                } else {
                    extraData = "(F)";
                }
                $(pic).appendTo(center);
                $(center).append($('<br>'));
                $('<span>').html(obj[i].name + " " + extraData).appendTo(center);
                $(link).mousedown(function(event) {
                    if (event.button == 0) {
                        displayData(this);
                        return false;
                    }
                    return true;
                });
                $(link).appendTo(parent);
            }

            if (i.indexOf("ul") > -1) {
                var ul = $('<ul>');
                $(ul).appendTo(parent);
                parent = ul;
                traverseObj(obj[i]);
                parent = $(parent).parent();
                return;
            }
        }
        return;
    }

    // function to send data to server
   $.send_Family =  $.fn.pk_family_send = function(options) {
        if (rootDiv == null) {
            // error message in console
            jQuery.error('wrong id given');
            return;
        }
        var settings = $.extend({
            // These are the defaults.
            url: "",
        }, options);
        var data = createSendURL();
        data = data.replace(new RegExp(']', 'g'), ""); 
        data = data.replace(new RegExp('\\[', 'g'), ""); 
        jQuery.ajax({
            type: 'POST',
            url: settings.url,
            data: {"tree":data,"profile_id":profileId,"_token":$('meta[name=_token]').attr('content')}
        }).done(function(response) {
            _toast.success("Tree updated succefully.");
        });
    }

    function createSendURL() {
        rut = $(treeGround).find("ul:first");
        parent = object;
        object = createJson(rut);
        return (JSON.stringify(object));

    }

    function createJson(root) {
        var thisObj = [];
        if ($(root).prop('tagName') == "UL") {
            var item = {};
            var i = 0;
            $(root).children('li').each(function() {
                item["li" + i] = createJson(this);
                i++;
            });
            thisObj.push(item);
            return thisObj;
        }
        if ($(root).prop('tagName') == "LI") {
            var item = {};
            var i = 0;
            $(root).children('a').each(function() {
                var m = "a" + i;
                item[m] = {};
                item[m]["name"] = $(this).attr("data-name");
                //item[m]["age"] = $(this).attr("data-age");
                item[m]["gender"] = $(this).attr("data-gender");
                item[m]["dobDate"] = $(this).attr("data-dobDate");
                item[m]["dodDate"] = $(this).attr("data-dodDate");
                try {
                    item[m]["relation"] = $(this).attr("data-relation");
                } catch (e) {
                    item[m]["relation"] = "self";
                }
                item[m]["pic"] = $(this).find('img:first').attr("src");
                i++;
            });

            if ($(root).find('ul:first').length) {
                parent = thisObj;
                item["ul"] = createJson($(root).find('ul:first'));
            }
            thisObj.push(item);
            return thisObj;
        }
    }
    function init() {

        addBreadingGround();
        member_details();
        
        document.oncontextmenu = function() {
            return false;
        };
    }

    

    function member_details() {
        memberDetails = $('<div>').attr('id', 'pk-member-details');
        $(memberDetails).appendTo(rootDiv);
    }

    function addBreadingGround() {
        var member = $('<div>').attr('id', 'treeGround');
        $(member).attr('class', 'tree-ground');
        $(member).appendTo(rootDiv);
        treeGround = member;
        if($(window).width() > 1200){
            $(treeGround).draggable();
        }
    }
    

// will show existing user info
    function displayData(element) {
        var innerContent = $('<table>');
        var content = '';
        var cross = $('<div>').attr('class', 'pk-cross icon-close');
         var modalTopDiv = $('<div>').attr('class', 'modalTopDiv');
         var modalDiv = $('<div>').attr('class', 'modalDiv');
        // $(cross).text('X');
        $(cross).click(function() {
            $(memberDetails).css('display', 'none')
        });
        $(memberDetails).empty();
        $(modalTopDiv).appendTo(memberDetails);
        $(modalDiv).appendTo(modalTopDiv);
        $(cross).appendTo(modalDiv);
        // $(innerContent).appendTo(modalDiv);

        
        content = content + '<tr><td><label>Name</label></td><td>' + $(element).attr('data-name') + '</td></tr>';
        
        var memeberGender = $(element).attr('data-gender');
        var maleGenderClass = '';
        var femaleGenderClass = '';
        if(memeberGender == 'male') {
            maleGenderClass = 'selected';
        } else {
            femaleGenderClass = 'selected';
        }

        content = content + '<tr><td><label>Gender</label></td><td>'+ $(element).attr('data-gender') +'</td></tr>';

        if ($(element).attr('data-relation')) {
            editByRelation = $(element).attr('data-relation');
            content = content + '<tr><td><label>Relation</label></td><td>' + $(element).attr('data-relation') + '</td></tr>';
        } else {
            editByRelation = $(element).attr('data-relation');
            content = content + '<tr><td><label>Relation</label></td><td>Self</td></tr>';
        }

        content = content + '<tr><td><label>Birth Date</label></td><td>' + $(element).attr('data-dobDate') + '</td></tr>';
        content = content + '<tr><td><label>Death Date</label></td><td>' + $(element).attr('data-dodDate') + '</td></tr>';

        content = content + '<tr><td></td><td><div class="uploadImg"><img id="memberImageEdit" src="' + $(element).find('img').attr('src') + '"/></div></td></tr>';

        $(innerContent).html(content);
        $(modalDiv).append(innerContent);
        
        $(memberDetails).css('display', 'block');
    }

}(jQuery));