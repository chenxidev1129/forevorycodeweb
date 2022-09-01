/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function($) {
    var rootDiv = '';
    //var treeGround = null;
    var newMemberForm = '';
    // var memberName = '';
    // var memberSurname = '';
    // var memberGender = '';
    //var memberAge = '';
    var memberPic = '';
    var memberPicEdit = '';
    var memberRelation = '';
    // var memberBirthDate = '';
    // var memberDeathDate = '';
    var familyTree = new Array();
    var memberId = 0;
    //var selectedMember = null;// refrence to selected member
    var self = true;
    var memberSpace = 92;
    var memberWidth = 115;
    var memberHeight = 107;
    var memberDetails = null;
    var memberDataDetails = null;
    var options_menu = null;
    var object = new Object();
    var rut = null;
    var parent = null;
    //var editByRelation = '';

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

        createNewMemberForm();
        member_details();
        createOptionsMenu();
        document.oncontextmenu = function() {
            return false;
        };

        viewMemberDetails(selectedMember);
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
                link.attr('data-surname', obj[i].surname);
                //link.attr('data-age', obj[i].age);
                link.attr('data-gender', obj[i].gender);
                link.attr('data-relation', obj[i].relation);
                link.attr('data-dobDate', obj[i].dobDate);
                link.attr('data-dodDate', obj[i].dodDate);


                /* add contact detail value on attributes */
                link.attr('data-email', obj[i].email);
                link.attr('data-phone', obj[i].phone);
                link.attr('data-address', obj[i].address);

                /* add biographical detail value on attributes */
                link.attr('data-birthplace', obj[i].birthplace);
                link.attr('data-profession', obj[i].profession);
                link.attr('data-company', obj[i].company);


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

                /* Check for self */
                if(obj[i].relation == 'self'){
                    /* setting self as a default selected memeber */
                    selectedMember = link;
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

                // $('<div class="d-flex align-items-center justify-content-center" style="width: 65px; height:36px; overflow: hidden; display: inline-block !important;">').html(obj[i].name + ' ' + obj[i].surname).appendTo(center);


                $('<span class="memberName">').html(obj[i].name).appendTo(center);
                //$('<span>').html(extraData).appendTo(center);
                $(center).append($('<br>'));
                // if(obj[i].surname) {
                //     $('<span class="memberName">').html(obj[i].surname).appendTo(center);
                // } else {
                //     $('<span class="memberName">').html('-').appendTo(center);
                // }
                
                $(link).mousedown(function(event) {
                    if (event.button == 2) {
                        // displayPopMenu(this, event);
                        // return false;
                    } else if (event.button == 0) {
                        viewMemberDetails(this);
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
                item[m]["surname"] = $(this).attr("data-surname");
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


                /* add contact detail value on json array */
                item[m]["email"] = $(this).attr("data-email");
                item[m]["phone"] = $(this).attr("data-phone");
                item[m]["address"] = $(this).attr("data-address");


                /* add biographical detail value on json array */
                item[m]["birthplace"] = $(this).attr("data-birthplace");
                item[m]["profession"] = $(this).attr("data-profession");
                item[m]["company"] = $(this).attr("data-company");


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
        // addMemberButton();
        addBreadingGround();
        createNewMemberForm();
        member_details();
        createOptionsMenu();
        displayFirstForm();
        document.oncontextmenu = function() {
            return false;
        };
    }

    function createOptionsMenu() {
        var div = $('<div>').attr('id', 'pk-popmenu');
        var ul = $('<ul>');
        // add member button
        var liAdd = $('<li>').html('Add Member').appendTo(ul);
        liAdd.click(function(event) {
            displayForm();
            $(options_menu).css('display', 'none');
        });
        // edit member button
        var liDisplay = $('<li>').html('Edit Details').appendTo(ul);
        liDisplay.click(function(event) {
            displayData(selectedMember);
            $(options_menu).css('display', 'none');
        });
        // remove member button
        var liRemove = $('<li>').html('Remove Member').appendTo(ul);
        liRemove.click(function(event) {
            removeMember(selectedMember);
            $(options_menu).css('display', 'none');
        });
        // cancel the pop menu
        var liCancel = $('<li>').html('Cancel').appendTo(ul);
        liCancel.click(function(event) {
            //displayForm(this);
            $(options_menu).css('display', 'none');
        });
        $(div).append(ul);
        options_menu = div;
        $(options_menu).appendTo(rootDiv);

    }
    function createNewMemberForm() {
        var memberForm = $('<div>').attr('id', 'pk-memberForm');
        var cross = $('<div>').attr('class', 'pk-cross icon-close');
        var modalTopDiv = $('<div>').attr('class', 'modalTopDiv');
        var modalDiv = $('<div>').attr('class', 'modalDiv');
        // $(cross).text('X');
        $(cross).click(closeForm);
        $(cross).appendTo(modalDiv);
        $(modalDiv).appendTo(modalTopDiv);
        $(modalTopDiv).appendTo(memberForm);
        var table = $('<table>').appendTo(modalDiv);
        // name
        $('<tr>').html('<td><label>Name</label></td><td><input autocomplete="off" class="form-control" type="text" placeholder="Name" value="" id="pk-name"/></td>').appendTo(table);
        $('<tr>').html('<td><label>Surname</label></td><td><input autocomplete="off" class="form-control" type="text" placeholder="Surname" value="" id="pk-surname"/></td>').appendTo(table);
        $('<tr>').html(' <td><label>Gender</label></td><td><select autocomplete="off" class="form-control selectpicker" id="pk-gender"><option value="">Select Gender</option><option value="male">Male</option><option value="female">Female</option></select></td>').appendTo(table);
        //$('<tr>').html('<td><label>Age</label></td><td><input type="text" value="" id="pk-age"></td>').appendTo(table);
        $('<tr>').html('<td class="relations"><label>Relation</label></td><td class="relations"><select autocomplete="off" class="form-control selectpicker" id="pk-relation">\n\\n\
<option value="">Select Relations</option>\n\
<option value="Mother">Mother</option>\n\\n\
<option value="Father">Father</option>\n\\n\
<option value="Sibling">Sibling</option>\n\\n\
<option value="Child">Child</option>\n\\n\
<option value="Spouse">Spouse</option>\n\\n\
</select></td>').appendTo(table);

        $('<tr>').html(' <td><label>Birth Date</label></td><td><input autocomplete="off" class="form-control pk-birthDate" placeholder="Birth Date" type="text" id="pk-birthDate"></td>').appendTo(table);
        $('<tr>').html(' <td><label>Death Date</label></td><td><input autocomplete="off" class="form-control pk-deathDate" placeholder="Death Date" type="text" id="pk-deathDate"></td>').appendTo(table);

        $('<tr>').html('<td><label>Upload Photo</label></td><td><input onchange="readUrlForCropper(this);" type="file" id="pk-picture" class="d-none" accept="image/*"><label for="pk-picture" class="uploadFile"><em class="icon-camera"></em></label></td>').appendTo(table);

        $('<tr class="d-none">').html('<td></td><td><div class="uploadImg"><img id="memberImageAdd" src=""/></td>').appendTo(table);

        var buttonSave = $('<input>').attr('type', 'button');
        $(buttonSave).attr('value', 'Save');
        $(buttonSave).attr('class', 'btn btn-primary');
        $(buttonSave).click(saveForm);
        var td = $('<td>').attr('colspan', '2');
        td.css('text-align', 'center');
        td.append(buttonSave);
        $('<tr>').append(td).appendTo(table);
        newMemberForm = memberForm;
        $(newMemberForm).appendTo(rootDiv);
        
    }

    function member_details() {
        memberDetails = $('<div>').attr('id', 'pk-member-details');
        $(memberDetails).appendTo(rootDiv);
    }

    function closeForm() {
        $(newMemberForm).css('display', 'none');
    }

    function saveForm() {
        if($('#pk-memberForm #pk-name').val()) {
            memberName = $('#pk-memberForm #pk-name').val();
        } else {
            _toast.error("Please enter member name.");
            return false;
        }

        if($('#pk-memberForm #pk-gender').val()) {
            memberGender =  $('#pk-memberForm #pk-gender').val();
        } else {
            _toast.error("Please select gender.");
            return false;
        }

        if($('#pk-memberForm #pk-relation').val()) {
            memberRelation = $('#pk-memberForm #pk-relation').val();
        } else {
            _toast.error("Please select member relation.");
            return false;
        }
        
        if($('#pk-memberForm #pk-birthDate').val()) {
            memberBirthDate = $('#pk-memberForm #pk-birthDate').val();
        } else {
            _toast.error("Please select valid birth date.");
            return false;
        }


        //memberAge = $('#pk-age').val();
        //memberPic = $('#pk-memberForm #pk-picture');
        memberPic = $('#pk-memberForm #memberImageAdd').attr('src');
        memberSurname = $('#pk-memberForm #pk-surname').val();
        memberDeathDate = $('#pk-memberForm #pk-deathDate').val();

        //clear exsiting data from form
        $('#pk-memberForm #pk-name').val('');
        $('#pk-memberForm #pk-surname').val('');
        //$('#pk-age').val('');
        $('#pk-memberForm #pk-gender').val('')
        $('#pk-memberForm #pk-relation').val('');
        $('#pk-memberForm #pk-deathDate').val('');
        $('#pk-memberForm #pk-birthDate').val('');

    
        // after saving
        addMember();
        closeForm();
        /* send to server */
        $.send_Family({url: saveTree});
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

    function addMemberButton() {
        var member = $('<input>').attr('type', 'button');
        $(member).attr('value', 'Add Member');
        $(member).click(displayForm);
        $(member).appendTo(rootDiv);
    }
    function displayForm(input) {
        $('.relations').css('display', '');
        uploadingType = 'pk-picture';
        $('#memberImageAdd').attr('src','');
        $('#memberImageAdd').parent().parent().parent().addClass('d-none');
        $('#pk-memberForm #pk-name').val('');
        $('#pk-memberForm #pk-surname').val('');
        $('#pk-memberForm #pk-gender').val('')
        $('#pk-memberForm #pk-relation').val('');
        $('#pk-memberForm #pk-deathDate').val('');
        $('#pk-memberForm #pk-birthDate').val('');


        /* Check for partner is added */
        var partnerCount = $(selectedMember).parent().children('a').length;
        if(partnerCount >= 2) {
            /* when partner is added */
            $("#pk-memberForm #pk-relation option[value='Spouse']").hide();
        } else {
            /* when partner is not added */
            $("#pk-memberForm #pk-relation option[value='Spouse']").show();
        }

        /* Check for parent is added */
        var parentCount = $(selectedMember).parent().parent().parent().children('a');
        if(parentCount.length >= 2) {
            /* when both parent are added */
            $("#pk-memberForm #pk-relation option[value='Mother']").hide();
            $("#pk-memberForm #pk-relation option[value='Father']").hide();
        } else if(parentCount.length == 1) {
            /* when single parent is added */
            var parentRelation = $(parentCount).attr('data-relation');
            if(parentRelation == 'Child' || parentRelation == 'Sibling') {
                var parentGender = $(parentCount).attr('data-gender');
                if(parentGender == 'male') {
                    $("#pk-memberForm #pk-relation option[value='Father']").hide();
                } else if(parentGender == 'female') {
                    $("#pk-memberForm #pk-relation option[value='Mother']").hide();
                }
            } else if(parentRelation == 'Father') {
                $("#pk-memberForm #pk-relation option[value='Father']").hide();
            } else if(parentRelation == 'Mother') {
                $("#pk-memberForm #pk-relation option[value='Mother']").hide();
            }
        } else {
            /* when no parent is added */
            $("#pk-memberForm #pk-relation option[value='Mother']").show();
            $("#pk-memberForm #pk-relation option[value='Father']").show();
        }
        
        $('.selectpicker').selectpicker('refresh');

        $("#pk-birthDate, #pk-deathDate").datepicker('destroy');

        // Datepicker
        $( "#pk-deathDate" ).datepicker({
            changeMonth: true,
            changeYear: true,
            // show:true,
            // axDate: new Date, 
            yearRange: '1400:+0',
            maxDate: new Date(),
            onSelect: function (selectedDate) {
                if(selectedDate) {
                    $("#pk-birthDate").datepicker("option", "maxDate", selectedDate);
                }
            }
        });
        $( "#pk-birthDate" ).datepicker({
            changeMonth: true,
            changeYear: true,
            // show:true,
            // axDate: new Date, 
            yearRange: '1400:+0',
            maxDate: new Date(),
            onSelect: function (selectedDate) {
                if(selectedDate) {
                    $("#pk-deathDate").datepicker("option", "minDate", selectedDate);
                }
            }
            
        });
        
        $(newMemberForm).css('display', 'block');
    }
    function displayPopMenu(input, event) {
        //if ($(options_menu).css('display') == 'none') {
        if (input != selectedMember || $(options_menu).css('display') == 'none') {
            selectedMember = input;
            self = false;
            $(options_menu).css('display', 'block');
            $(options_menu).css('top', event.clientY);
            $(options_menu).css('left', event.clientX);
        }
    }

    /* view memeber details */
    function showMemberDetails(element) {
        selectedMember = element;
        
        /* highlight selected memeber */
        $('.tree-ground li a').removeClass('highlightMember');
        $(element).addClass('highlightMember');

        /* Check for parent is added */
        var parents = $(selectedMember).parent().parent().parent().children('a');
        if(parents.length >= 2) {

            /* when both parent are added */
            $('.familySidebar #myTabContent .personalInfo #addParentBtn').hide();
            $('.familySidebar #myTabContent .personalInfo #addFatherBtn').hide();
            $('.familySidebar #myTabContent .personalInfo #addMotherBtn').hide();

        } else if(parents.length == 1) {
            /* when single parent is added */
            $('.familySidebar #myTabContent .personalInfo #addParentBtn').hide();

            var parentRelation = $(parents).attr('data-relation');
            if(parentRelation == 'Child' || parentRelation == 'Sibling') {
                var parentGender = $(parents).attr('data-gender');
                if(parentGender == 'male') {

                    $('.familySidebar #myTabContent .personalInfo #addFatherBtn').hide();
                    $('.familySidebar #myTabContent .personalInfo #addMotherBtn').show();
                    
                } else if(parentGender == 'female') {

                    $('.familySidebar #myTabContent .personalInfo #addMotherBtn').hide();
                    $('.familySidebar #myTabContent .personalInfo #addFatherBtn').show();

                }
            } else if(parentRelation == 'Father') {

                $('.familySidebar #myTabContent .personalInfo #addFatherBtn').hide();
                $('.familySidebar #myTabContent .personalInfo #addMotherBtn').show();

            } else if(parentRelation == 'Mother') {

                $('.familySidebar #myTabContent .personalInfo #addMotherBtn').hide();
                $('.familySidebar #myTabContent .personalInfo #addFatherBtn').show();
                
            }
        } else {
            /* when no parent is added */
            $('.familySidebar #myTabContent .personalInfo #addParentBtn').show();
            $('.familySidebar #myTabContent .personalInfo #addFatherBtn').hide();
            $('.familySidebar #myTabContent .personalInfo #addMotherBtn').hide();
        }


        /* Check for partner is added */
        var partnerCount = $(selectedMember).parent().children('a').length;
        if(partnerCount >= 2) {
            /* when partner is added */
            $('.familySidebar #myTabContent .personalInfo #addPartnerBtn').hide();
        } else {
            /* when partner is not added */
            $('.familySidebar #myTabContent .personalInfo #addPartnerBtn').show();
        }

        

        /* Show member details */
        $('.familySidebar #myTabContent .personalInfo #familyMemberName').text($(element).attr('data-name'));
        $('.familySidebar #myTabContent .personalInfo #familyMemberSurname').text('-');
        $('.familySidebar #myTabContent .personalInfo #familyMemberSurname').text($(element).attr('data-surname'));



        // var innerContent = $('<table>');
        // var content = '';
        // var cross = $('<div>').attr('class', 'pk-cross icon-close');
        // var modalTopDiv = $('<div>').attr('class', 'modalTopDiv');
        // var modalDiv = $('<div>').attr('class', 'modalDiv');
        
        // $(cross).click(function() {
        //     $(memberDetails).css('display', 'none')
        // });
        // $(memberDetails).empty();
        // $(modalTopDiv).appendTo(memberDetails);
        // $(modalDiv).appendTo(modalTopDiv);
        // $(cross).appendTo(modalDiv);
        
        // content = content + '<tr><td><label>Name</label></td><td>' + $(element).attr('data-name') + '</td></tr>';
        // content = content + '<tr><td><label>Surname</label></td><td>' + $(element).attr('data-surname') + '</td></tr>';

        // var memeberGender = $(element).attr('data-gender');
        // var maleGenderClass = '';
        // var femaleGenderClass = '';
        // if(memeberGender == 'male') {
        //     maleGenderClass = 'selected';
        // } else {
        //     femaleGenderClass = 'selected';
        // }

        // content = content + '<tr><td><label>Gender</label></td><td>' + $(element).attr('data-gender') + '</td></tr>';

        // if ($(element).attr('data-relation')) {
        //     editByRelation = $(element).attr('data-relation');
        //     content = content + '<tr><td><label>Relation</label></td><td>' + $(element).attr('data-relation') + '</td></tr>';
        // } else {
        //     editByRelation = $(element).attr('data-relation');
        //     content = content + '<tr><td><label>Relation</label></td><td>Self</td></tr>';
        // }

        // content = content + '<tr><td><label>Birth Date</label></td><td>' + $(element).attr('data-dobDate') + '</td></tr>';
        // content = content + '<tr><td><label>Death Date</label></td><td>' + $(element).attr('data-dodDate') + '</td></tr>';

        // content = content + '<tr><td></td><td><div class="uploadImg"><img id="memberImageEdit" src="' + $(element).find('img').attr('src') + '"/></div></td></tr>';

        // $(innerContent).html(content);
        // $(modalDiv).append(innerContent);
        
        // $(memberDetails).css('display', 'block');
    }

    function displayFirstForm() {
        selectedMember = null;
        self = true;
        $('.relations').css('display', 'none');
        $(newMemberForm).css('display', 'block');
        $('#pk-relation').val('Main');
    }
    function addMember() {
        var aLink = $('<a>').attr('href', '#');
        var center = $('<center>').appendTo(aLink);
        var pic = $('<img>').attr('src', imageUrl+'/male.png');
        var extraData = "";
        if (memberGender == "male") {
            extraData = "(M)";
        } else {
            extraData = "(F)";
            $(pic).attr('src', imageUrl+'/female.png');
        }
        $(pic).appendTo(center);
        $(center).append($('<br>'));
        $('<span class="memberName">').html(memberName).appendTo(center);
        //$('<span>').html(extraData).appendTo(center);
        $(center).append($('<br>'));
        // if(memberSurname) {
        //     $('<span class="memberName">').html(memberSurname).appendTo(center);
        // } else {
        //     $('<span class="memberName">').html('-').appendTo(center);
        // }
        
        readImage(memberPic, pic);

        var li = $('<li>').append(aLink);
        $(aLink).attr('data-name', memberName);
        $(aLink).attr('data-surname', memberSurname);
        $(aLink).attr('data-gender', memberGender);
        //$(aLink).attr('data-age', memberAge);
        $(aLink).attr('data-relation', memberRelation);
        $(aLink).attr('data-dobDate', memberBirthDate);
        $(aLink).attr('data-dodDate', memberDeathDate);
        
        $(aLink).mousedown(function(event) {
            if (event.button == 0) {
                backPrev();
                showMemberDetails(this);
            }
            return true;
        });

        var sParent = $(selectedMember).parent(); // super parent
        if (selectedMember != null) {
            if (memberRelation == 'Mother') {
                var parent = $(sParent).parent();
                var parentParent = $(parent).parent();
                
                /* Check is there any parent for grand parent */
                var treeParent = $(parentParent).attr("id");

                if(treeParent == 'treeGround') {

                    console.log('adding mother alone');
                    var ul = $('<ul>').append(li);
                    $(parent).appendTo(li);
                    $(parentParent).append(ul);

                } else {
                    //var fatherElement = $(parentParent).find("a:first");
                    var fatherElement = $(parentParent).find("[data-relation='Father']:first");
                    if(fatherElement.length > 0){
                        console.log('adding adajecent to father');
                        var tmp = $(fatherElement).parent();
                        $(aLink).attr('class', 'mother');
                        //$(tmp).append(aLink);
                        $(fatherElement).after(aLink);
                    
                    }else{
                        console.log('adding mother alone');
                        var ul = $('<ul>').append(li);
                        $(parent).appendTo(li);
                        $(parentParent).append(ul);
                    }
                }
            }
            if (memberRelation == 'Spouse') {
                $(aLink).attr('class', 'spouse');
                var toPrepend = $(sParent).find('a:first');
                $(sParent).prepend(aLink);
                $(sParent).prepend(toPrepend);
            }
            if (memberRelation == 'Child') {
                var toAddUL = $(sParent).find('UL:first');
                if ($(toAddUL).prop('tagName') == 'UL') {
                    $(toAddUL).append(li);
                } else {
                    var ul = $('<ul>').append(li);
                    $(sParent).append(ul);
                }

            }
            if (memberRelation == 'Sibling') {
                $(sParent).parent().append(li);

            }
            if (memberRelation == 'Father') {
                
                var parent = $(sParent).parent();
                var parentParent = $(parent).parent();
                
                /* Check is there any parent for grand parent */
                var treeParent = $(parentParent).attr("id");
                
                if(treeParent == 'treeGround') {

                    console.log('adding father alone');
                    var ul = $('<ul>').append(li);
                    $(parent).appendTo(li);
                    $(parentParent).append(ul);

                } else {

                    //var motherElement = $(parentParent).find("a:first");
                    var motherElement = $(parentParent).find("[data-relation='Mother']:first");
                    if(motherElement.length > 0){
                        console.log('adding back to mother');
                        var tmp = $(motherElement).parent();
                        //$(tmp).append(aLink);
                        $(motherElement).before(aLink);
                        /* add class for mother element*/
                        $(motherElement).attr('class', 'mother');
                    
                    }else{
                        console.log('adding father alone');
                        var ul = $('<ul>').append(li);
                        $(parent).appendTo(li);
                        $(parentParent).append(ul);
                    }
                }
            }
            
        } else {
            var ul = $('<ul>').append(li);
            $(treeGround).append(ul);

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

        
        content = content + '<tr><td><label>Name</label></td><td><input autocomplete="off" class="form-control" type="text" value="' + $(element).attr('data-name') + '" id="pk-name"/></td></tr>';
        content = content + '<tr><td><label>Surname</label></td><td><input autocomplete="off" class="form-control" type="text" value="' + $(element).attr('data-surname') + '" id="pk-surname"/></td></tr>';
        //content = content + '<tr><td>Age</td><td>' + $(element).attr('data-age') + '</td></tr>';

        var memeberGender = $(element).attr('data-gender');
        var maleGenderClass = '';
        var femaleGenderClass = '';
        if(memeberGender == 'male') {
            maleGenderClass = 'selected';
        } else {
            femaleGenderClass = 'selected';
        }

        content = content + '<tr><td><label>Gender</label></td><td><select autocomplete="off" class="form-control selectpicker" id="pk-gender"><option value="male" '+ maleGenderClass +'>Male</option><option value="female"'+ femaleGenderClass +'>Female</option></select></td></tr>';

        if ($(element).attr('data-relation')) {
            editByRelation = $(element).attr('data-relation');
            content = content + '<tr><td><label>Relation</label></td><td>' + $(element).attr('data-relation') + '</td></tr>';
        } else {
            editByRelation = $(element).attr('data-relation');
            content = content + '<tr><td><label>Relation</label></td><td>Self</td></tr>';
        }

        content = content + '<tr><td><label>Birth Date</label></td><td><input autocomplete="off" class="form-control pk-birthDate1" type="text" id="pk-birthDate1" value="' + $(element).attr('data-dobDate') + '"></td></tr>';
        content = content + '<tr><td><label>Death Date</label></td><td><input autocomplete="off" class="form-control pk-deathDate1" type="text" id="pk-deathDate1" value="' + $(element).attr('data-dodDate') + '"></td></tr>';

        content = content + '<tr><td><label>Upload Photo</label></td><td><input onchange="readUrlForCropper(this);" type="file" id="pk-picture-edit" class="d-none" accept="image/*"><label for="pk-picture-edit" class="uploadFile"><em class="icon-camera"></em></label></td><tr>';

        content = content + '<tr><td></td><td><div class="uploadImg"><img id="memberImageEdit" src="' + $(element).find('img').attr('src') + '"/></div></td></tr>';

        //content = content + '<tr><td colspan="2" class="text-center"><input class="btn btn-primary" type="button" value="Update"></td><tr>';

        $(innerContent).html(content);
        $(modalDiv).append(innerContent);

        uploadingType = 'pk-picture-edit';

        /* Define submit update button */
        var buttonUpdate = $('<input>').attr('type', 'button');
        $(buttonUpdate).attr('value', 'Update');
        $(buttonUpdate).attr('class', 'btn btn-primary');
        $(buttonUpdate).click(updateForm);

        var td = $('<td>').attr('colspan', '2');
        td.css('text-align', 'center');
        td.append(buttonUpdate);

        $('<tr>').append(td).appendTo(innerContent);
        

        $('.selectpicker').selectpicker('refresh');

        $("#pk-birthDate1, #pk-deathDate1").datepicker('destroy');

        // define Datepicker
        $( "#pk-deathDate1" ).datepicker({
            changeMonth: true,
            changeYear: true,
            // show:true,
            // axDate: new Date, 
            yearRange: '1400:+0',
            maxDate: new Date,
            onSelect: function (selectedDate) {
                if(selectedDate) {
                    $("#pk-birthDate1").datepicker("option", "maxDate", selectedDate);
                }
            }
        });
        $( "#pk-birthDate1" ).datepicker({
            changeMonth: true,
            changeYear: true,
            // show:true,
            // axDate: new Date, 
            yearRange: '1400:+0',
            maxDate: new Date,
            onSelect: function (selectedDate) {
                if(selectedDate) {
                    $("#pk-deathDate1").datepicker("option", "minDate", selectedDate);
                }
            }
        });
        
        $(memberDetails).css('display', 'block');
    }
    function readImage(input, pic) {
        if(input) {
            $(pic).attr('src', input);
        }
        return;

        var files = $(input).prop('files');
        console.log(files);
        if (files && files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(pic).attr('src', e.target.result);
            }

            // reader.readAsDataURL(files[0]);
        }
    }

    function removeMember(member) {
        if ($(member).attr('data-relation') == 'Sibling') {
            $(member).parent().remove();
        }
        if ($(member).attr('data-relation') == 'Child') {
            var cLen = $(member).parent().parent().children('li').length;
            if (cLen > 1) {
                //$(member).remove();
                $(member).parent().remove();
            } else {
                //$(member).parent().remove();
                $(member).parent().parent().remove();
            }
        }
        if ($(member).attr('data-relation') == 'Father') {
            
            /*check for mother and remove class of mother */
            var checkMotherElement = $(member).parent().find("[data-relation='Mother']:first");

            if($(checkMotherElement).siblings().is($(member))) {
                var child = $(member).children('ul');
                var parent = $(member).parent().parent();
                $(child).appendTo(parent);
                $(member).remove();

                $(checkMotherElement).attr('class', '');
            } else{
                var child = $(member).parent().children('ul').children('li');
                var parent = $(member).parent().parent();
                $(child).appendTo(parent);
                $(member).parent().remove();
            }

            // if(checkMotherElement.length > 0){
                
            //     var child = $(member).children('ul');
            //     var parent = $(member).parent().parent();
            //     $(child).appendTo(parent);
            //     $(member).remove();

            //     $(checkMotherElement).attr('class', '');
            // } else {
            //     var child = $(member).parent().children('ul').children('li');
            //     var parent = $(member).parent().parent();
            //     $(child).appendTo(parent);
            //     $(member).parent().remove();
            // }

        }
        if ($(member).attr('data-relation') == 'Spouse') {
            $(member).remove();
        }
        if ($(member).attr('data-relation') == 'Mother') {

            /*check for father and remove */
            var checkFatherElement = $(member).parent().find("[data-relation='Father']:first");
            
            if($(checkFatherElement).siblings().is($(member))) {
                var child = $(member).children('ul');
                var parent = $(member).parent().parent();
                $(child).appendTo(parent);
                $(member).remove();
            } else {
                var child = $(member).parent().children('ul').children('li');
                var parent = $(member).parent().parent();
                $(child).appendTo(parent);
                $(member).parent().remove();
            }


            // if(checkFatherElement.length > 0){
            //     var child = $(member).children('ul');
            //     var parent = $(member).parent().parent();
            //     $(child).appendTo(parent);
            //     $(member).remove();
            // } else {
            //     var child = $(member).parent().children('ul').children('li');
            //     var parent = $(member).parent().parent();
            //     $(child).appendTo(parent);
            //     $(member).parent().remove();
            // }
            
        }

        /* send to server */
        $.send_Family({url: saveTree});
    }

    
    function updateForm() {
        if($('#pk-member-details #pk-name').val()) {
            memberName = $('#pk-member-details #pk-name').val();
        } else {
            _toast.error("Please enter member name.");
            return false;
        }

        if($('#pk-member-details #pk-gender').val()) {
            memberGender =  $('#pk-member-details #pk-gender').val();
        } else {
            _toast.error("Please select gender.");
            return false;
        }
        
        if($('#pk-member-details #pk-birthDate1').val()) {
            memberBirthDate = $('#pk-member-details #pk-birthDate1').val();
        } else {
            _toast.error("Please select valid birth date.");
            return false;
        }


        //memberAge = $('#pk-age').val();
        //memberPicEdit = $('#pk-member-details #pk-picture-edit');
        memberSurname = $('#pk-member-details #pk-surname').val();
        memberPicEdit = $('#pk-member-details img#memberImageEdit').attr('src');
        memberDeathDate = $('#pk-member-details #pk-deathDate1').val();

        //clear exsiting data from form
        $('#pk-member-details #pk-name').val('');
        $('#pk-member-details #pk-surname').val('');
        //$('#pk-age').val('');
        $('#pk-member-details #pk-gender').val('')
        $('#pk-member-details #pk-deathDate1').val('');
        $('#pk-member-details #pk-birthDate1').val('');
        
        // after saving
        updateMember();
        closeUpdateForm();

        /* send to server */
        setTimeout(function() {
            $.send_Family({url: saveTree});
        }, 1000)
        
    }
    
    function updateMember() {
        if (selectedMember != null) {
            
            /* get element */
            var sParent = $(selectedMember).parent(); // super parent
            var toPrepend = $(sParent).find("[data-relation='"+editByRelation+"']:first");
            
            /* remove old data */
            $(toPrepend).html('');

            /* Add new data */
            var center = $('<center>').appendTo(toPrepend);
            var editPic = $('<img>').attr('src', imageUrl+'/male.png');
            var extraData = "";
            if (memberGender == "male") {
                extraData = "(M)";
            } else {
                extraData = "(F)";
                $(editPic).attr('src', imageUrl+'/female.png');
            }
            $(editPic).appendTo(center);
            $(center).append($('<br>'));
            $('<span class="memberName">').html(memberName).appendTo(center);
            //$('<span>').html(extraData).appendTo(center);
            $(center).append($('<br>'));

            // if(memberSurname) {
            //     $('<span class="memberName">').html(memberSurname).appendTo(center);
            // } else {
            //     $('<span class="memberName">').html('-').appendTo(center);
            // }
            
            readImage(memberPicEdit, editPic);

            /* Add new attributes */
            $(toPrepend).attr('data-name', memberName);
            $(toPrepend).attr('data-surname', memberSurname);
            $(toPrepend).attr('data-gender', memberGender);
            $(toPrepend).attr('data-dobDate', memberBirthDate);
            $(toPrepend).attr('data-dodDate', memberDeathDate);
        }
    }

    function closeUpdateForm() {
        $(memberDetails).css('display', 'none');
    }

    window.addEventListener('click', function(e){   
        if (!document.getElementById('pk-popmenu').contains(e.target)){
            $(options_menu).css('display', 'none');
        }
    });

}(jQuery));
