<?php
	session_start();
	$usr = $_SESSION["usr"];
	if(strlen($usr) < 1) {
		header("location:index.php");
	}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Media Builder</title>
	
    <script src="js/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/jquery.easing.js" type="text/javascript"></script>
    <script src="js/jqueryFileTree.js" type="text/javascript"></script>
    <link href="js/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="js/university-ave.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="js/mediabuilder.css" rel="stylesheet" type="text/css" media="screen" />
	
</head>

<body>
	<div class="header">
        <h1>Media Builder</h1>
        <h2 id="fileat">File:</h2>
    </div>
    
	<div id="tabs">
      <ul>
        <li><a href="#tabs-1">Build Media</a></li>
        <li><a href="#tabs-2">Preview Media</a></li>
        <li><a href="#tabs-3">Media List</a></li>
        <li><a href="#tabs-4">Upload Media</a></li>
        <!--li><a href="#tabs-5">XML Builder</a></li-->
      </ul>
	
	<div id="tabs-1" class="tabody"><!-- Build Media -->
    <div class="leftcolm">
        <div id="fileTree" class="picker"></div>
        <p>Select a File to Add<br/>
        <input type="button" id="newfolder" name="newfolder" value="Create a New Folder"></p>
        <img id="thumbup" title="Media Item Thumbnail" src="../thumbs/logo220x154.jpg">
        <div id="message"></div>
    </div>
        
    <div class="rightcolm">
    <form id="mediaform" name="mediaform"  method="post" action="">
        <div class="formrow">
            <div class="small left">
                <label for="coursename">Course:</label> &nbsp; 
                <select name="coursename" id="coursename">
                    <option value="FIRST">First</option>
                    <!-- add option as needed -->
                </select>
                <input type="button" id="addcourse" title="Add Course Filter" value="+">
            </div>
            <!--input id="coursenum" name="coursenum" type="text" size="15" value=""><!--REST FORM BUTTON? -->
            <div class="small right">
                <input type="button" id="resetform" name="resetform" value="Reset Form">
            </div>
        </div>
        <!--div style="float:right; width:125px; margin-top:5px; margin-left:10px;">
        <label for="idnum">Media ID:</label> 
        <input id="idnum" name="idnum" type="text" size="4" value="">
        </div-->
        <div class="formrow">
            <hr>
            <label for="thumfile">Thumbnail:</label><input id="resethumb" type="button" title="Use Default Thumbnail"value="logo">
            <input id="thumbfile" name="thumbfile" type="text" size="52" value="logo220x154.jpg">
            <br>
        </div>
        <div class="formrow">
            <hr>
            <label for="filepath">FilePath:</label><input id="filepath" name="filepath" type="text" placeholder="path to media" size="52" value="">
        </div>
        <div class="formrow">
            <hr>
            <label>xmlPath:</label><input id="removeparam" type="button" title="Clear the xml path" value="clear">
            <input id="xmlpath" name="xmlpath" type="text" placeholder="Optional: path to xml, video" size="52" value="">
        </div>
        
        <hr>
        Media Type:
        <div id="m_type">
        <input type="radio" name="radio" id="ht" value="HTML"><label for="ht">HTML</label>
		<input type="radio" name="radio" id="fp" value="FLASH"><label for="fp">FLASH</label>
		<input type="radio" name="radio" id="vo" value="VIDEO"><label for="vo">VIDEO</label>
        </div>
        
        <br>
        <input id="table" name="table" type="hidden" placeholder="media table" size="59" value="">
        <input id="thepath" name="thepath" type="hidden" placeholder="actual path" size="59" value=""><br><!-- hidden -->
        <input id="submitmedia" name="submitmedia" type="submit" value="Add to Database">
    </form>
    <br>
    <form id="uploadimage" enctype="multipart/form-data" action="" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="90000000">
        Choose Image to create Thumbnail:
        <br><input id="uploadedfile" name="uploadedfile" type="file" title="Upload an Image" accept="image/x-png, image/jpeg" >
        <br>
    </form>
    <p>Instructions:<br/>
      Fill in the form to Add a media file to the Database.<br/>
      Select the Course it belongs to. Note: used for sorting.<br/>
      Browse/Upload an image to use for the thumbnail.<br/>
      Select a file in the upper left panel.<br/>
      Select a video or xml file if needed.</p>
    <p>Support:<a href="mailto:tjones@uvu.edu?Subject=External%20Tool">tjones@uvu.edu</a></p>
    </div>
    
    <div id="dialog-folder" title="Create a New Folder?">
      <p>Enter a name and click OK</p>
        <input id="foldername" name="foldername" value="" type="text" size="20">
    </div>    
	</div><!-- end tabs-1 -->
    
    <div id="tabs-2" class="tabody"><!-- Preview of selected Media -->
		<div class="theFile"></div>
    </div><!-- end tabs-2 -->
    
	<div id="tabs-3" class="tabody"><!-- Media List from DB -->
        <div class="leftcolm colm">
    	<fieldset>
            <label for="courses">Select A Course</label><br>
            <select name="courses" id="courses">
            <option value="all" selected="selected">All Courses</option>
            <option value="FIRST">First</option>
            <!-- add option as needed -->
          </select>
        </fieldset>
        
        <fieldset>
            <span class="resultarea">Selected id: </span>
            <span id="result" class="resultarea">none</span>
            <br><hr>
            <input type="button" id="confirmit" name="confirmit" value="Edit Selection">
        </fieldset>
   <!-- trigger dialog if confirm and no media selected ??? -->
        <fieldset>
            <p>
            To view the selected item.<br>
            Click Edit Selection then <br>
            Select the Preview tab.<br>
            </p>
            <hr>
            <img id="imgthumb" src="../thumbs/logo220x154.jpg" />
        </fieldset>
        <fieldset>
            <Input type="button" id="viewtoggle" name="viewtoggle" value="View as Tiles">
        </fieldset>
		</div>

        <div class="rightcolm">
            <ol id="selectable">
            </ol>
        </div>
    </div><!-- end tabs-3 -->
    
    <div id="tabs-4" class="tabody"><!-- Upload Media -->
        <div id="dragandrophandler">Drag & Drop<br/>Files to Upload Here</div>
        <div id="status1"></div>
    </div><!-- end tabs-4 -->
    
    <!--div id="tabs-5" class="tabody"><!-- XML Builder -->
    <!--p>To Do List:</p>
    <p>Build an Interface to create Questions &amp; Answers for the Reusable Media.</p>
    <p>Reusables currently use xml.</p>
    <p>Best option would be to Re-Code the reusables to accept JSON object instead of XML.</p>
    <p>This data would be stored in its own database and filtered by game type.</p>
    </div--><!-- end tabs-5 -->
  </div><!-- End Tabs -->
  
<script type="text/javascript">
//http://mediafiles.uvu.edu/extool/seconded/ltitool/mediabuilder.php
// interactive media is located in /seconded/media/...
var tmp = window.location.href.split("/");
//console.log(tmp.length, tmp);// >6 =
var server=tmp[0]+"//"+tmp[2];
var dept = "/"+tmp[3]+"/"+tmp[4];// only for /extools/...
console.log(server,dept);
//var server= "https://mediafiles.uvu.edu";
//var dept = "/extools/seconded";// get from consumers ???

var startat = "../../media/";// /seconded/media/
	// path/file.ext?filename=xmlpath/file.xml
	var filepath_global="";// for DB with param
	var folderpath=startat;// for upload and new folder
    var piclist=[];
    
    function createFileTree() {
        //http://www.abeautifulsite.net/jquery-file-tree/
        // add a callback for finding open folders ???
        $("#fileTree").fileTree({
            root: startat, 
            script: "js/jqueryFileTree.php", 
            folderEvent: "click", expandSpeed: 300, collapseSpeed: 300, multiFolder: false
            }, function(file) {
                //console.log(file);// NOT folders
            var nupath = file.split(startat);
            var filepath = dept+"/media/"+nupath[1];
            //extract the file extention to determine if param
            var filext = nupath[1].split(".");
            var ext = filext[filext.length-1];
            ///console.log("__file Ext:"+ext);
            if(ext == "jpg" || ext == "png") {
                /* use selected for thumbnail */
                var imag = new Image();
                imag.src = filepath;
                imag.onload = function() {
                    //console.log('thumbFile:',filepath);
                    //console.log('width:', imag.width);
                    //console.log('height:', imag.naturalHeight);
                    if(imag.width > 220 || imag.height > 154) {
                        // resize image http://localhost/extools/seconded/ltitool/mediabuilder.php
                        $.post('scripts/ajax_upload.php',{imagepath:server+filepath}, newthumb);
                    } else {
                        // use it. Will break tiled view and is not located in /thumbs/
                        //$("#thumbup").attr("src",'../thumbs/'+filepath);
                        //var temp = filepath.split('/');
                        //var name = temp[temp.length-1];
                        //$("#thumbfile").val(name);
                        // if smaller resize works but looks terrible
                        $.post('scripts/ajax_upload.php',{imagepath:server+filepath}, newthumb);
                    }
                }
            }
            if(ext == "xml" || ext == "flv" || ext == "f4v" || ext == "mp4") {
                $("#xmlpath").val(filepath);
            }
            if(ext == "swf" || ext == "html") {
                $("#filepath").val(filepath);
            }
            if(ext == "zip") {
                console.log('folderpath: '+folderpath);// worked for me
                console.log('ZIP FILE '+filepath);// may need to keep this
                $.post('scripts/unzip.php',{filename:filepath,path:folderpath}, function(e){
                    //console.log(e);
                    createFileTree();
                });
            }
            // adjust full path to media?filename=
            adjustFilePath();
        });
        
        // set folderpath back to startat if none selected
        $('#fileTree').on('mouseup', function(e){
            setTimeout(function(){
                var expanded = $('.picker').find('.expanded');
                //console.log('length:',expanded.length);
                if(expanded.length==0){ folderpath=startat; }
                if(expanded.length==1){
                    var displayed = $(expanded[0]).parent().attr('style');
                    //console.log('displayed:',displayed);//display: none;
                    if(displayed == 'display: none;') {
                     folderpath=startat;   
                    }
                }
                //console.log('folderpath:',folderpath);
            }, 400);// wait
        });
    }
    createFileTree();// create first tree
    
    /* if an image is selected in fileTree, the $.post returns here */
    function newthumb(thum) {
        //console.log('newthumb:',thum);
        $("#thumbup").attr("src",'../thumbs/'+thum);
        $("#thumbfile").val(thum);
    }
    
    
//xmlPath clear btn
	$('#removeparam').click(function(e) {
        $('#xmlpath').val('');
		adjustFilePath();
    });
	$('#xmlpath').change(function(){ adjustFilePath(); });
	
// create the global path for DB
// load the media into Preview Media tabs-2
// set the media type radio btns
	function adjustFilePath() {
		///media_id=0;// adjusted so NEW item ???
		// may need a Update db button instead
		var af=$('#filepath').val();///afile;
		var ap=$('#xmlpath').val();///aparam;
		var ex=af;
		if(af != "") { filepath_global=af; }
		if(ap != "") { filepath_global=af+'?filename='+ap; ex=ap; }
		//console.log('globalPath: '+filepath_global);
		$('#fileat').html(filepath_global);
		$('#thepath').val(filepath_global);//hidden form input
		
		var frame='<iframe src="'+filepath_global+'" width="970" height="570"></iframe>';
		$('.theFile').html(frame);//preview media
		//console.log('filepath_global='+filepath_global);
		
		// set radio button for m_type
		var filext = ex.split(".");
		//console.log("filext.length:"+filext.length);
		var ext = filext[filext.length-1];
		//console.log("__file Ext:"+ext); none if folder
		if(ext == "f4v" || ext == "mp4") { $("#vo").prop("checked",true); }
		if(ext == "swf") { $("#fp").prop("checked",true); }
		if(ext == "html") { $("#ht").prop("checked",true); }
		$('#m_type').buttonset("refresh");// tell jqUI
	}

//media type: HTML - FLASH - VIDEO
	$( "#m_type" ).buttonset();//jqUI style
  
// Build Media tabs-1 Course selection dropdown
	$( "#coursename" ).selectmenu({
      change: function( event, data ) {
		///console.log('coursename data:'+data.item.value);
		//media_id=0;// reset? for addMedia
		///$("#coursenum").val(data.item.value);
      }
	});
	
// tabs-1 Build Media Reset Form
	$('#resetform').button();
	$('#resetform').click(function() {
		resetTheForm();
	});
	// called at add media submitmedia
	function resetTheForm() {
		$('#fileat').html('path: Filename:');// header
		$('.theFile').empty();// preview media
		$('#thepath').val('');// hidden
		$('#filepath').val('');
		$('#xmlpath').val('');
		$('#thumbfile').val('logo220x154.jpg');
		$('#thumbup').attr('src','../thumbs/logo220x154.jpg');
		$("#uploadedfile").val('');
		$("#vo").prop("checked",false);//media type
		$("#fp").prop("checked",false);
		$("#ht").prop("checked",false);
		$('#m_type').buttonset("refresh");// tell jqUI
		///$('#idnum').val('');// form media id REDUNDANT ??? VISUAL ONLY
		media_id=0;// 
		
		// reset coursename to first option in list 
		$("#coursename option").prop('selected', false).filter(function() {
    		//return $(this).text() == '';
		}).prop('selected', true);
		$("#coursename").selectmenu("refresh");
	}
// Add to Database button Build Media tabs-1
	$('#submitmedia').button();
	$('#submitmedia').click(function(e) {
		e.preventDefault();
		$("#message").empty();
	
		// Update if editing an existing item from tab-3 selectable list
		///console.log('SubmitMedia: media_id = '+media_id);
		// if ID update existing record else addMedia
		if(media_id != 0) {
			console.log('Update media item '+media_id);
			$("#message").html('Update Item Id: '+media_id);
			//update internal JSON and refresh media list
			var itm = $.grep(mediadata.items, function(element, index){ 
				if(element.id == media_id) {
					element.filename=$('#filepath').val();
					element.course=$('#coursename').val();
					element.extra=$('#xmlpath').val();
					element.thumb=$('#thumbfile').val();
					element.type=$("input:radio[name=radio]:checked").val();
				}
				return element.id == media_id;
			});
			//update record for media_id with itm[]
			///console.log(itm);// mediadata);
			$.ajax({
					url: "scripts/updatemediaitem.php",
					type: "POST",
					data: {item:itm,table:table},
					success: function(data)
					{
						///console.log('success.data:'+data);
						$("#message").html(data);//returns 'success' or ERROR+err
						generateList(course);// REFRESH LIST
						resetTheForm();
					}
				});// end ajax
			
		} else {
			// validate if at least FilePath is set before adding
			if($('#filepath').val() == '') {
				$("#message").html('Choose a File');
			} else {
				///console.log('Add NEW media item');
				$("#message").html('NEW media item');
				
				$.ajax({
					url: "scripts/addMedia.php",
					type: "POST",
					data: $("#mediaform").serialize(), // form fields and values
					success: function(data)
					{
						//console.log('success.data:'+data);
						$("#message").html(data);//returns 'success' or ERROR+err
						if(data == 'success') {
						//Add item to internal JSON and refresh media list
							var last = mediadata.items[mediadata['items'].length-1];
							//console.log('nuid:'+last+' id:'+last.id);
							var nuid = parseInt(last.id)+1;
							var nuitem={ "id":nuid.toString() }
							
							nuitem["filename"]=$('#filepath').val();
							nuitem["course"]=$('#coursename').val();
							nuitem["extra"]=$('#xmlpath').val(); 
							nuitem["thumb"]=$('#thumbfile').val(); 
							nuitem["type"]=$("input:radio[name=radio]:checked").val();
							
							mediadata.items.push(nuitem);
							console.log(mediadata);
							generateList(course);// REFRESH LIST
							resetTheForm();
						}
					}
				});// end ajax
			}
		}
	});
    
    
//Create a New Folder or Course filter dialog
    $('#dialog-folder').dialog({
        resizable: false,
        height: 210,
        modal: true,
        buttons: {
            "Cancel": function() {
            $(this).dialog('close');
            },
            "OK": function() {
                $(this).dialog('close');
            var foldername=$('#foldername').val();
            console.log('dialogAction:',dialogAction);  
                if(dialogAction == 'folder') {
                    console.log(folderpath+':'+foldername);
                    $.post('scripts/makeFolder.php', {foldername:foldername, openfolder:folderpath}, folderResponse );
                    
                }
                if(dialogAction == 'course') {
                    // add select option to both
                    $('#courses').append('<option value="'+foldername+'" selected>'+foldername+'</option>');
                    $('#coursename').append('<option value="'+foldername+'" selected>'+foldername+'</option>');
                    $("#courses").selectmenu("refresh");
                    $("#coursename").selectmenu("refresh");
                    course=foldername;
                    generateList(course);// refresh Media List tabs-4
                }
            }
        }
    });
    $('#dialog-folder').dialog('close');
    
    // only allow alphanumeric in Text input #foldername
    $('#foldername').bind('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9\-\_]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });
    
    var dialogAction='';// determines OK button condition
    $('#newfolder').button();// ui style
    $('#newfolder').on('click', function(e) {
        //console.log(e.currentTarget.id);
        dialogAction='folder';
        $('.ui-dialog-title').text('Create a new folder');
        ///id="dialog-folder" title="Create a New Folder?"
        ///$('dialog-folder').attr('title','Create a New Folder');
        // use popup with input, cancel/ok
        $('#foldername').val('').focus();
        $('#dialog-folder').dialog('open');
    });
    /*  Add option to course filter lists tabs-1 & 4
        if used for a new media item, the option will be added to both select lists
        when the mediadata.items are re loaded
    */
    $('#addcourse').on('click', function(e) {
        // reuse dialog-folder , set an var to determine OK action
        //console.log(e.currentTarget.id);
        dialogAction='course';
        ///$('dialog-folder').attr('title','Create a New Course Item');
        $('.ui-dialog-title').text('Create a New Course Filter');
        $('#foldername').val('').focus();
        $('#dialog-folder').dialog('open');
    });
    // makeFolder callback
    function folderResponse(resp) {
        console.log('makeFolder: '+resp);
        $("#message").html(resp);
        $("#fileTree").empty();
        createFileTree();//refresh
    }
/*
Manually create an image using OS, browser addon, other
give fullsized image a proper name
Upload it using Choose Image... Browse... btn
create thumbnail: 220x124 from fullsized image using php GD
upload names it: name###.jpg for use in Instructor View
Stored in /../thumbs/
add thumb name to DB

http://www.formget.com/ajax-image-upload-php/
*/
// triggered by successful Browse upload
	$("#uploadimage").on("submit",(function(e) {
		e.preventDefault();
		$("#message").empty();
		// move into uploadedfile.change ???
		$.ajax({
			url: "scripts/ajax_upload.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false, /* To send DOMDocument or non processed data file */
			success: function(data)
			{
				var result = data.split('&');
				//console.log('success.data:'+data);
				//console.log('success.data:'+result[0]+' : '+result[1]);
				$("#message").html(result[0]);
				//if not err then replace logo logo220x154 with generated thumb
				if(result.length>1) {
					$("#thumbfile").val(result[1]);// set path to thumb
					$("#thumbup").attr("src","../thumbs/"+result[1]);
				}
			}
		});
	}));
	// Function to upload & preview the thumbnail after validation
	$("#uploadedfile").change(function() {
		$("#message").empty(); //remove the previous message
		var file = this.files[0];
		var imagefile = file.type;
		///console.log("file.type:"+imagefile);
		var match= ["image/jpeg","image/png","image/jpg"];
		if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
		{
			$('#thumbup').attr('src','../thumbs/logo220x154.jpg');
			$("#message").html("<p>Please Select A valid Image File<br>Only jpeg, jpg and png<br>Image types allowed</p>");
			return false;
		} else {
			
			$("#uploadimage").submit();
		}
	});
	
	// reset to logo in thumbfile.value 
	$('#resethumb').click(function(e) {
        $('#thumbfile').val('logo220x154.jpg');
		$('#thumbup').attr('src','../thumbs/logo220x154.jpg');
    });


// tabs-3 media list selectable ******************************
var media_id=0;// holds selected media item id NONE 
var course='all';// for generateList() in tabs-3 Media List
var mediadata='';

// jquery-UI tabs
	$( "#tabs" ).tabs({
      //collapsible: true
    });
/*
    tab clicked
    change fileat to relevent context
    db path, toolpath, folderpath
*/
    $('#tabs li').on('click', function(e) {
        var tb = $(e.currentTarget).attr('aria-controls');
        //console.log('tab:',tb);
        // Build Media
        if(tb == 'tabs-1') { $('#fileat').html( "File: "+$('#filepath').val() ); }
        // Upload Media
        if(tb == 'tabs-4') { $('#fileat').html('Upload to '+folderpath); }
    });
    
// Edit Selection button tabs-3 Media List
	$('#confirmit').button();// ui style
	$('#confirmit').click(function(e) {
		e.preventDefault();
		//console.log('Confirm [EDIT SELECTION] was clicked');
		// copy media item selected into mediaform
		var id = $('#confirmit').attr('data-id');
		//console.log('id:'+id);
		if(id=='undefined') { return; }
		///$('#idnum').val(id);
		//console.log(mediadata.items["id"]["filename"]);
		var itm = $.grep(mediadata.items, function(element, index){ return element.id == id; });
		//console.log(itm);
		//console.log(itm[0]["course"]);
		//console.log(itm[0]["filename"]);
		//console.log(itm[0]["extra"]);
		//console.log(itm[0]["thumb"]);
		
		// coursename selected
		$("#coursename option").prop('selected', false).filter(function() {
    		return $(this).text() == itm[0]["course"];  
		}).prop('selected', true);
		$("#coursename").selectmenu("refresh");
		
		$('#thumbfile').val(itm[0]["thumb"]);
		$('#filepath').val(itm[0]["filename"]);
		$('#xmlpath').val(itm[0]["extra"]);
		// show thumbnail of item
		$('#thumbup').attr('src','../thumbs/'+itm[0]['thumb']);
		// sets media type from extension and preview iframe
		adjustFilePath();// and loads into preview
		media_id=id;// Update item# when submitmedia clicked
		///console.log('media_id:'+media_id);
		$('#message').html('Editing Media ID: '+media_id);
		// set media type radio btns
		var ext = itm[0]["type"];//HTML,FLASH,VIDEO
		///console.log("CONFIRMIT: Ext:"+ext);
		if(ext == "VIDEO") { $("#vo").prop("checked",true); }
		if(ext == "FLASH") { $("#fp").prop("checked",true); }
		if(ext == "HTML") { $("#ht").prop("checked",true); }
		$('#m_type').buttonset("refresh");// tell jqUI
		//Switch to tabs-1 Build Media
		$("#tabs").tabs("option", "active", 0 );//0,1,2
	});
	
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
	
// Select Media item
    $( "#selectable" ).selectable({
		// on mouse up
		selected: function() {
			var result = $( "#result" ).empty();
			var items=[];// accumulated selections when mouse dragged
			$( ".ui-selected", this ).each(function() {
			 var index = $( "#selectable li" ).index( this );
			 items.push(this);// push each to unselect
			});
			
			var itm1 = items.shift();// save first element
			// unselect all others
			if(items.length>0) {
				for(var i=0; i<items.length; i++) {
					$(items[i]).removeClass("ui-selected"); 
				}
			}
			
			var id=$(itm1).attr('data-id');
			result.html( id );// selected id:
			//store id in Edit Selection button
			$('#confirmit').attr('data-id',id);
			$('#fileat').text('https://mediafiles.uvu.edu/extools/seconded/ltitool/mediatool.php?media='+id);
			// show selected item thumbnail
			var itm = $.grep(mediadata.items, function(element, index){ return element.id == id; });
			$('#imgthumb').attr('src','../thumbs/'+itm[0]['thumb']);// show thumbnail of item
		}
	});
	
// Filter by Course Media List tabs-3
	$( "#courses" ).selectmenu({
		change: function( event, data ) {
			//console.log('select data:'+data.item.value);
			course=data.item.value;// global
			generateList(course);// adjust the #selectable list
		}
	});
	
// Generate the #selectable <li>st
	function generateList(coors) {
		//console.log('show items in course '+coors);
		$( "#result" ).html(media_id);
		// reset #imgthumb to media_id thumb from db data
		$('#imgthumb').attr('src','../thumbs/logo220x154.jpg');
		//media_id=0;// reset selected
		
		var nulist;
		if(coors=='all') {
			nulist=mediadata.items;
		} else {
			nulist = $.grep(mediadata.items, function(element, index){
				return element.course == coors;
			});
		}
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
		}
	}
    
    
	// create the dropdown options in tabs-1 & 4 course filter
    function generateOptions() {
        $('#courses').empty();
        $('#coursename').empty();
        var courselist = [];
        // add All Courses to tabs-4 Media List
        $('#courses').append('<option value="all" selected>All Courses</option>');
        // add unique course names to options
        for(var i=0; i<mediadata.items.length; i+=1) {
            //console.log(mediadata.items[i].course);
            // only if unique
            if(courselist.indexOf(mediadata.items[i].course) == -1) { 
                courselist.push(mediadata.items[i].course);
                $('#courses').append('<option value="'+mediadata.items[i].course+'">'+mediadata.items[i].course+'</option>');
                $('#coursename').append('<option value="'+mediadata.items[i].course+'">'+mediadata.items[i].course+'</option>'); 
            }
        }
        // refresh both
        $("#courses").selectmenu("refresh");
        $("#coursename").selectmenu("refresh");
        //console.log(courselist);
    }

/*
	To Do:
	Secure login to limit access 
	http://www.phpsnips.com/4/Simple-User-Login#.VeReKc5bOQY
	Clean up code !
	
	Function to De-Acivate an item? [see getallmedia]
	m_active=0 is used in place of delete item from database
	only =1 items are returned in the json from getAllMedia
	
*/
    
/* START: Get all media items and format as JSON object
    generate the Media List selectable items

    get media_table from consumers
    by matching url with consumers config_url?
*/
    var locate = window.location.href;
    var pieces = locate.split('/');
    //console.log('pieces:',pieces);
    var match = 'https://mediafiles.uvu.edu/'+pieces[3]+'/'+pieces[4]+'/ltitool/config.xml';
    //console.log('match:',match);
    var table=pieces[4]+'media';// mostly
    //console.log('table:',table);//not nursemedia or aviation
    
    // get consumer match then return table & allmedia
    $.post('scripts/findMedia.php', {match:match}, mediaResponse );
    function mediaResponse(data) {
        //console.log(data);// if != 'No rows'
        mediadata = $.parseJSON(data);
        ///console.log(mediadata);
        table=mediadata.table;
        console.log('TABLE:',table);
        $('#table').val(table);// addMedia

        $("#message").html('Media Items Received');
        //create course filter options from m_course (#courses,#coursename)
        generateOptions();
        generateList(course);//Start with all media in tabs-4 Media List
    }
    
/* Drag Drop file upload *** tab-4 Upload Media ***
also use similar for thumbnail image ???

    Drag and Drop single or group of files to the selected folder
	Drop a single .zip will extract and remove original .zip file
	Cannot drop folders.
    Use path to media folder
*/

    
    var obj = $("#dragandrophandler");
	obj.on('dragenter', function (e) 
	{
		e.stopPropagation();
		e.preventDefault();
		$(this).css('border', '2px solid #0B85A1');
	});
	obj.on('dragover', function (e) 
	{
		 e.stopPropagation();
		 e.preventDefault();
	});
	obj.on('drop', function (e) 
	{
		 $(this).css('border', '2px dotted #0B85A1');
		 e.preventDefault();
		 var files = e.originalEvent.dataTransfer.files;
	 
		 //Send dropped files to Server
		 handleFileUpload(files,obj);
	});
	$(document).on('dragenter', function (e) 
	{
		e.stopPropagation();
		e.preventDefault();
	});
	$(document).on('dragover', function (e) 
	{
	  e.stopPropagation();
	  e.preventDefault();
	  obj.css('border', '2px dotted #0B85A1');
	});
	$(document).on('drop', function (e) 
	{
		e.stopPropagation();
		e.preventDefault();
	});
    
    function sendFileToServer(formData,status)
    {
        //var uploadURL =;
        //var extraData ={} //Extra Data.
        var jqXHR=$.ajax({
                xhr: function() {
                var xhrobj = $.ajaxSettings.xhr();
                if (xhrobj.upload) {
                        xhrobj.upload.addEventListener('progress', function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //Set progress
                            status.setProgress(percent);
                        }, false);
                    }
                return xhrobj;
            },
        url: "scripts/ddupload.php",
        type: "POST",
        contentType:false,
        processData: false,
            cache: false,
            data: formData,
            success: function(data){
                status.setProgress(100);
                //console.log('success:',data);// success: ["Ethics_qz.zip"]
                //$("#status1").append("Done<br/>");
                
                // refresh filetree to see new files
                $('#fileTree').empty();
                createFileTree();
            },
            error: function(err) { console.log("Error:",err); }
        }); 

        status.setAbort(jqXHR);
    }

    var rowCount=0;
    function createStatusbar(obj)
    {
         rowCount++;
         var row="odd";
         if(rowCount %2 ==0) row ="even";
         this.statusbar = $("<div class='statusbar "+row+"'></div>");
         this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
         this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
         this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
         this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
         obj.after(this.statusbar);

        this.setFileNameSize = function(name,size)
        {
            var sizeStr="";
            var sizeKB = size/1024;
            if(parseInt(sizeKB) > 1024)
            {
                var sizeMB = sizeKB/1024;
                sizeStr = sizeMB.toFixed(2)+" MB";
            }
            else
            {
                sizeStr = sizeKB.toFixed(2)+" KB";
            }

            this.filename.html(name);
            this.size.html(sizeStr);
        }
        this.setProgress = function(progress)
        {       
            var progressBarWidth =progress*this.progressBar.width()/ 100;  
            this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
            if(parseInt(progress) >= 100)
            {
                this.abort.hide();
            }
        }
        this.setAbort = function(jqxhr)
        {
            var sb = this.statusbar;
            this.abort.click(function()
            {
                jqxhr.abort();
                sb.hide();
            });
        }
    }
    function handleFileUpload(files,obj)
    {
        for (var i = 0; i < files.length; i++) 
        {
            var fd = new FormData();
            fd.append('folderpath', folderpath);// $output_dir
            fd.append('file', files[i]);
            var status = new createStatusbar(obj); //Using this we can set progress.
            status.setFileNameSize(files[i].name,files[i].size);
            sendFileToServer(fd,status);
        }
    }
/*
http://hayageek.com/docs/jquery-upload-file.php

http://stackoverflow.com/questions/2840755/how-to-determine-the-max-file-upload-limit-in-php
http://php.net/manual/en/function.ini-set.php
http://www.htaccess-guide.com/
https://www.sitepoint.com/community/t/ini-set-and-post-max-size/7414/2

*/    
</script>
</body>
</html>