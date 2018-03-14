<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>AVSC Media Selector Instructor</title>
	<link rel="stylesheet" href="js/university-ave.css">
	<style>
		BODY {
			font-family:Arial, "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, sans-serif;
			font-size: 0.9em;
			background: #efe2c7;/*uvu pale*/
			padding: 5px;
			margin: 0px;
			width:100%;
		}
		iframe { border:none; }
		/* media items list */
		#selectable .ui-selecting { background: #E7CF29; }
		#selectable .ui-selected { background: #4D7123; color: white; }
		#selectable .ui-selected { background: #4D7123; color: white; /*border-width:medium; border-color:white; border-style:solid;*/ }
		#selectable { list-style-type: none; margin: 0; padding: 0; width:690px; }
		#selectable li { margin: 3px; padding: 1px; cursor:pointer; }
		.listview { font-size: 0.9em; width: 610px; height:57px; }
		.tileview { float:left;  margin: 5; }
		.tilethumb { z-index:1; width: 220px; height: 154px; background-repeat:no-repeat;  }
		#selectable .ui-selected .tiletxt { color: #4d7123; }
		.tiletxt { z-index:2; text-align:center; font-size:2.5em; padding-top:40px; color: #f5f3e5; }
		
		.container { width:100%; }
		.thumb { float:left; width:81px; height:57px; margin-right:10px; }
		.leftcolm { float:left; width:250px; padding-right:10px; }
		.rightcolm { float:left; width:710px; height:550px; overflow:auto; }
		.tabody { width:1024px; height:570px; overflow:auto; }
		#hideme { display:none; }
		#courses { width:165px; }
		#preview { 
		 	margin-top: -20px;
			margin-left: -20px;
			border:none;/*iframe*/
		}
    </style>
</head>

<body>
<div class="container" id="main">
    <div id="tabs">
      <ul>
        <li><a href="#tabs-1">Design</a></li>
        <li><a href="#tabs-2">Preview</a></li>
        <li><a href="#tabs-3">Instructions</a></li>
      </ul>
	
	<div id="tabs-1" class="tabody"><!-- Design tab -->
	<div class="leftcolm">
    	<fieldset>
        <label for="courses">Filter Media by Course</label>
        <select name="courses" id="courses">
        	<option value="all" selected="selected">All Courses</option>
            <option value="SCED">SC-ED</option>
            <!-- add option as needed -->
        </select>
        </fieldset>
        
        <fieldset>
        <span>Selected Media ID: </span>
        <span id="result">none</span>
        <br><hr>
        <!-- submit hidden form -->
        <button type="submit" id="confirmit" name="confirmit">Confirm Selection</button>
        </fieldset>
   <!-- trigger dialog if confirm and no media selected ??? -->
        <fieldset>
        <br>Select Preview tab to see the selected item.<br><hr>
        <img id="imgthumb" src="../thumbs/logo220x154.jpg" />
        </fieldset>
        <fieldset>
            <Input type="button" id="viewtoggle" name="viewtoggle" value="View as Tiles">
        </fieldset>
        <div id="message"></div>
	</div>

    <div class="rightcolm">
        <ol id="selectable">
          <li class="ui-widget-content" data-id="1"><!-- SAMPLE ITEM-->
			<img class="thumb" src="../thumbs/logo220x154.jpg" width="89" height="50">
			Course Name:<br>File Name:<br>Extra:
          </li><!-- SAMPLE ITEM-->
        </ol>
    </div>

    </div>
    
    <div id="tabs-2" class="tabody"><!-- show preview of selected media -->
    	<iframe id="preview" src="" width="960" height="564"></iframe>
    </div>
    
    <div id="tabs-3" class="tabody">
      <p>Select the Design tab and choose the media you want to present.
      It will be added to the External Tool URL as ?media=id#<br>
      You can filter the selection list by changing the Course
      with the dropdown. Any media item can be placed in any course.<br>
      The list can also be viewed as thumbnail tiles.<br>
      
      <br>
      The Confirm Selection button will post the External Tool URL back to Canvas and finalize your selected media.</p>
      <p>Select the Preview tab to see the media.
      <br>For questions or feedback contact: <a href="mailto:tjones@uvu.edu?Subject=External%20Tool">tjones@uvu.edu</a></p>
      <p>The POST parameters sent from Canvas:</p>
      <?php foreach($_POST as $key => $value ) { echo "$key = $value<br/>"; } ?>
	</div>
    <!--hidden form for lti launch url -->
    <div id="hideme" class="tabody">
    	<p>[Confirm Selection] button will post this redirect url back to Canvas.
        <br>If not the first launch, the Confirm button will update the assignment[external_tool_tag_attributes][url]</p>
		<form id="form1" name="form1" method="post" action="" >
            <!-- launch_presentation_return_url: -->
            <!-- form is submitted to the returnAddr -->
            <input id="addr" name="addr" type="text" value="<?php echo $_POST['launch_presentation_return_url']; ?>" size="90"><br>
            <label for="item1">return_type=</label><!-- constant -->
            <input type="text" name="item1" id="item1" placeholder="?return_type=lti_launch_url" value="?return_type=lti_launch_url" size="30">
			<br><!-- toolurl is sent to returnAddr  Must be set for each tool -->
            <label for="toolurl">url=</label> <input type="text" name="toolurl" id="toolurl" placeholder="&url" value="" size="85" />
            <br><!-- selected media id -->
            <label for="medianum">media=</label> <input type="text" name="medianum" id="medianum" placeholder="?media=id#" value="" size="30"><br>
            <!-- The Confirm Selection button in Design tab will submit form1 -->
		</form>
        <div id="returndata"></div>
    </div>
<!-- end tabs -->

</div>
<!-- end main container Instructor View -->
</div>

<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
/*
Instructor View FRAGMENT
load media_id into tabs-2 #preview
media default size is 960 x 564

 	https://jqueryui.com/selectable/
	http://api.jqueryui.com/selectable/
	http://www.tutorialspoint.com/jqueryui/jqueryui_selectable.htm
	CSS can be customized with ThemeRoller
	http://jqueryui.com/themeroller/
	use this for buttons, selection, tabs
	
	Generate the list dynamically from db (media table)
	can be a filtered list for a Course
	set data-id attribute to db id#
	only use the selected item from the top down
	unselect multiples
	media_id is set from the selected item attribute data-id
 	
	add: question banks ?
	add: API calls ?
*/
//============== Instructor View ========================================
// set redirect address in hidden form 
	
//https://uvu.instructure.com/courses/384724/external_content/success/external_tool_dialog
/* if this is the 1st launch, assignment id = ""
    on 2nd launch it is set so,
    update the Assignment: external_tool_tag_attributes: { url:
    instead of posting form1
*/
var assignmentID = '<?php echo $assignmentID; ?>';
	//console.log(assignmentID);
// default=1
var media_id='<?php echo $media_id; ?>';// holds selected media item id default=1
    if(media_id == ''){ media_id=1; }
	$('#medianum').val("?media="+media_id);// form input value
    
var table ='<?php echo $table; ?>';

// generate toolurl dynamically to place in form
var tmp='<?php echo $toolurl; ?>'.split("?");
var toolurl=tmp[0];// for form1
    $("#toolurl").val(toolurl);
    console.log('toolurl:',toolurl);
	console.log('Instructor View media_id:'+media_id);
    console.log('Table:',table);
    
    tmp = tmp[0].split("/");
var dept = tmp[4];// for tracking
var course='all';// for generateList()
var mediadata='';// media items as json

// jquery-UI
	$( "#tabs" ).tabs({
      //collapsible: true
    });
	$('#confirmit').button();// ui style
	$('#confirmit').click(function() {
        //console.log('assignmentID:'+assignmentID);// "" or ##
        
		if(assignmentID == "") {
            var domain ="<?php echo $_POST['custom_canvas_api_domain']; ?>";
            var courseTitle="<?php echo $_POST['context_title']; ?>";
            var courseID="<?php echo $_POST['custom_canvas_course_id']; ?>";
            var userid="<?php echo $_POST['lis_person_contact_email_primary']; ?>";
            var designer="<?php echo $_POST['lis_person_name_full']; ?>";
            //console.log('userid:',userid, '-', 'courseTitle:',courseTitle);
            //console.log('courseID:',courseID, '-', 'dept:',dept);
            /*
             track course id, course Title, user , dept
             use toolurl to find dept
             use domain to filter .beta .test
             userid is their email, can be used to filter members
            */
            $.post('https://mediafiles.uvu.edu/common/mediafilesAdmin/trackClicked.php',
                {userid:userid, coursetitle:courseTitle, courseid:courseID, domain:domain, dept:dept},
                function(data){
                    //console.log(data);// unused
                    // wait till returned from post?
                    $('#form1').submit();// 1st LAUNCH
            });//end tracking
            
		} else {
            // 2nd LAUNCH, add confirmation dialog/alert or token request?
			var title="Are you sure?";
			var msg="Select Yes to change the media shown in this assignment<br><br>Select No to cancel.";
			$.alert(msg,title);
		}
	});
// confirm Change Media on second attempt only
	$.extend({alert: function(msg,title) {
		$("<div></div>").dialog({
			buttons: {
				"Yes": function() {
                    $(this).dialog("close");
                    changeAssignment();
                },
				"No": function() { $(this).dialog("close"); }
			},
			close: function(event,ui){this.remove();},
			resizable:false,
			title:title,
			modal:true
		}).html(msg);
	} });
	
// Select Media item Toggle Views
	var toggle=true;//true =list
	$("#viewtoggle").button();
	$("#viewtoggle").click(function(){
		if(toggle){
			$("#viewtoggle").val("View as List");
			toggle=false;//=tiles
		}else{
			toggle=true;
			$("#viewtoggle").val("View as Tiles");
		}
		generateList(course);
	});
	
// Select Media item from list
    $( "#selectable" ).selectable({
		// on mouse up
		selected: function() {
			var result = $( "#result" ).empty();
			var items=[];// accumulated selections if mouse dragged
			$( ".ui-selected", this ).each(function() {
			 var index = $( "#selectable li" ).index( this );
			 items.push(this);// push each to unselect
			});
			
			var itm1 = items.shift();// save first element
			//console.log('len:'+items.length+' items:'+items);
			// unselect all others
			if(items.length>0) {
				for(var i=0; i<items.length; i++) {
					$(items[i]).removeClass("ui-selected");//.addClass("ui-unselecting"); 
				}
			}
			
			result.html( $(itm1).attr('data-id') );// 0 based
			media_id=$(itm1).attr('data-id');// global variable for later usage
			$('#medianum').val("?media="+media_id);
			
			var itm = $.grep(mediadata.items, function(element, index){ return element.id == media_id; });
			$('#imgthumb').attr('src','../thumbs/'+itm[0]['thumb']);// show thumbnail of item
			
			// update path & load media into #preview iframe src
			var filepath=itm[0]['filename']+'?filename='+itm[0]['extra']
			console.log('filename:'+filepath);
			$('#preview').attr('src',filepath);
		}
	});
	
// Filter by Course
	$( "#courses" ).selectmenu({
        change: function( event, data ) {
		//console.log('select data:'+data.item.value);
		course=data.item.value;// global
		generateList(course);// adjust the #selectable list
      }
	});
	function updateCourses(coursename){
		// coursename selected
		$("#courses option").prop('selected', false).filter(function() {
    		return $(this).text() == coursename;  
		}).prop('selected', true);
		$("#courses").selectmenu("refresh");
	}
// Generate the #selectable <li>st 
	function generateList(coors) {
		//console.log('show items in course '+coors);
		$( "#result" ).html(media_id);
		// reset #imgthumb to media_id thumb from db data
		$('#imgthumb').attr('src','../thumbs/logo220x154.jpg');
		
		var nulist;
		if(coors=='all') {
			nulist=mediadata.items;
		} else {
			nulist = $.grep(mediadata.items, function(element, index){
				return element.course == coors;
			});
		}
		//console.log('len:'+nulist.length);//+' nulist:'+nulist);
		// repopuluate #selectable with nulist.items
		$('#selectable').empty();// empty the list
		for(var i=0; i<nulist.length; i+=1) {
			//console.log(i+': course:'+nulist[i]["course"]+' file:'+nulist[i]["filename"]);
			var txt='';// toggle view list or thumb
			if(toggle){
			// list view:
				txt='<img class="thumb" src="../thumbs/'+nulist[i]["thumb"]+'">';
				txt += nulist[i]["course"]+'<br/>'+nulist[i]["filename"]+'<br/>'+nulist[i]["extra"];
				$('#selectable').append('<li class="ui-widget-content listview" data-id="'+nulist[i]["id"]+'">'+txt+'</li>');
			} else {
			// tile view: <li class="ui-state-default">1</li>
				txt='<div class="tilethumb" style="background-image:url(../thumbs/'+nulist[i]["thumb"]+')">';
				txt += '<div class="tiletxt">'+nulist[i]["course"]+'</div></div>';// float on top
				//add course,filename,extra to data-course, data-file, data-extra
				$('#selectable').append('<li class="tileview" data-id="'+nulist[i]["id"]+'">'+txt+'</li>');
			}
			// if selected item Hilight & Show thumbnail
			if(nulist[i]["id"] == media_id) {
				$('#selectable li').last().addClass("ui-selected");
				$('#imgthumb').attr('src','../thumbs/'+nulist[i]['thumb']);
			}
		}
	}
    
// create the dropdown options in tabs-1 course filter
    function generateOptions() {
        $('#courses').empty();
        var courselist = ['all'];
        // add All Courses to tabs-4 Media List
        $('#courses').append('<option value="all" selected>All Courses</option>');
        // add unique course names to options
        for(var i=0; i<mediadata.items.length; i+=1) {
            //console.log(mediadata.items[i].course);
            // only if unique
            if(courselist.indexOf(mediadata.items[i].course) == -1) { 
                courselist.push(mediadata.items[i].course);
                $('#courses').append('<option value="'+mediadata.items[i].course+'">'+mediadata.items[i].course+'</option>'); 
            }
        }
        // refresh both
        $("#courses").selectmenu("refresh");
        //console.log(courselist);// testing unique list
    }

// START: Get all media db items and format as JSON object
// generate the Media List selectable items
    $.post('scripts/getallmedia.php', {table:table}, function (data) {
        //console.log(data);// if != 'No rows'
        mediadata = $.parseJSON(data);
        ///console.log(mediadata);
        $("#message").html('Media Items Received');
        //create course filter options from m_course (#courses,#coursename)
        generateOptions();
        generateList(course);//Start with all media in tabs-4 Media List
    });
    
    /* 
        For 2nd launch... update/PUT assignment external_tool_tag_attributes: {url:
        https://canvas.instructure.com/doc/api/assignments.html#method.assignments_api.update
        
        scripts/changeURL.php
        send global media_id & api url
        
        check for existing token
        if none, returns getoken
            open dialog to authorize token
            store token and try again
                
        else use token to update url
        
        GET the assignment obj, replace media=##, 
        then PUT "assignment[external_tool_tag_attributes][url]="+nurl;
    */
    // canvas API to GET/PUT Assignment json
	var canvasapi = '<?php echo $api; ?>';//for this assignment
	//console.log("canvasapi: "+canvasapi);
    function changeAssignment() {
        
        $('#message').html("processing...");
        $.ajax({
			url: 'scripts/changeURL.php',
			type: 'get',
			data: {"api":canvasapi,"mediaid":media_id},
			success: function(data) {
				console.log('DATA:',data);
                
                if(data == 'getoken') {
                    $('#message').html('Authorizing Token');
                    // call authorize to get a token
                    $('#dialog').dialog('open');
                    $('.ui-dialog-content').css({padding:'0em'});

                    var fram = '<iframe id="fram" src="scripts/authorizetoken.php" width="790" height="430" scrolling="no"></iframe>';
                    $('#dialogbox').html(fram);
                    // token will be stored and trigger changeAssignment again
                } else {
                    $('#message').html(data);// the updated assignment object
                }
            }
        });
    }

    $('#dialog').dialog({
        //dialogClass: 'no-close',
        closeOnEscape: true,
        draggable: false,
        modal: true,
        height: 500,
        width: 800
    });
    $('#dialog').dialog('close');
    
    // listen for post message from authorizetoken
    if (window.addEventListener) {
        window.addEventListener("message", displayMessage, false);
     } else {
        window.attachEvent("onmessage", displayMessage);// IE
     }
     function displayMessage(e) {
        //console.log('origin='+e.origin);// mediafiles
        // close dialog
        $('#dialog').dialog('close');
        // if stored then try again
        if(e.data['access'] == 'stored') { changeAssignment(); }
        $('#message').html('displayMessage: '+e.data);
     }

//============== Instructor View ========================================
});// end document.ready
</script>
</body>
</html>