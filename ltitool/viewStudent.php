<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Student View</title>
<style>
	html, body { height:100%; }
	body { margin:0; padding:0; overflow:auto; }
	#display { position:relative; width:100%; height:100%; }
	#fram { position:absolute; top:0; left:0; width:100%; height:100%; }
	iframe { border:none; }
</style>
</head>

<body>
	<div class="container" id="display">
        <iframe id="fram" src="" allowfullscreen="allowfullscreen" webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen >
        </iframe>
    </div>

<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script type="text/javascript">
//============== STUDENT VIEW ===========================================
	
	var mediaid='<?php echo $media_id; ?>';// holds selected media DB item id
	//console.log('Student View: media_id:'+mediaid);
	
	var akey = '<?php echo $consumer_key; ?>';//oauth_consumer_key used to look up secret
	var returnurl='<?php echo $outcome_url; ?>';
	var sourcedid ='<?php echo $outcome_id; ?>';
	//console.log('sourcedid='+sourcedid);// this student in this course assignment
	var table ='<?php echo $table; ?>';
	// content will come from media table['m_path'] for media_id
	var content = '';

/* 
	SELECT record FROM table WHERE id=mediaid to get file path of content
	reusables will have ?filename=/path/file.xml or video.mp4
	
	load content into iframe
	look up 'secret' in gradeMedia.php for key sent
	
	.swf files
	are sent outcome_id, outcome_url, key to content with flashvars
	send back, with score(0~1), to gradeMedia.php to mark grade in Canvas
	called from within the flash file.
	
	.html
	when completed dispatch event heard in viewStudent
    window.parent.postMessage({"score":1, "*"});// 0.5=50% 1=100%
    to send grade to Canvas
*/
// begin:
    $.post('scripts/getmediaitem.php', {mediaid:mediaid,table:table}, function (data) {
        
        //console.log('success.data:'+data);
        content=data;// path to media

        var contquery = content.split('?');
        var fname="";// must have a query for html split
        if(contquery.length == 1) { fname="?filename="; }

        var fvars = fname+'&key='+akey+'&url='+returnurl+'&sourcedid='+sourcedid;
        var pathvars=content+fvars;
            //console.log("cq.len:"+contquery.length);
            //console.log(pathvars);
        $('#fram').attr('src',pathvars);
        
        $('#fram').load(function() {
            ///console.log('fram loaded');
            // listen for Video completed event
            $('#fram').contents().on('completed', function(e) {
                var score = e.originalEvent.detail['score'];
                //console.log('frame received score '+score);
                gradeMedia(score);
            });
        });
    });

    // listen for post message from media to trigger grade
    if (window.addEventListener) {
        window.addEventListener("message", displayMessage, false);
    } else {
        window.attachEvent("onmessage", displayMessage);// IE
    }
    function displayMessage(e) {
        //console.log('origin='+e.origin);
        //console.log('score= '+e.data['score']);
        if (e.origin == 'https://mediafiles.uvu.edu') {
            gradeMedia(e.data['score']);
        }
    }
    // end postMessage listener
	// if html5 content, Call when media completed event heard
	function gradeMedia(score) {
		if(score == "") { score=1; }
		if(score > 1) { score=1; }
		//console.log('Sending Grade '+score);
		
		$.ajax({
            method: "POST",
            url: "blti/gradeMedia.php",
            data: {
                score: score,
                skey: akey,
                surl: returnurl,
                srcid: sourcedid
            }
		}).done(function(msg) {
			//console.log('ajax done: should be Graded!');//+msg);//is the xml
		});
	}
//============== STUDENT VIEW ===========================================

</script>
</body>
</html>