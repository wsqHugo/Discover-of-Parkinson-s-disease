$(function() 
{
	$("#vmenu ul li a").live("click",function(){
		$("#vmenu ul li a").removeClass("current");
		$(this).addClass("current");
	});
});

$(".icon-button").live("mouseover",function(){$(this).addClass('icon-rotate-90');});
$(".icon-button").live("mouseleave",function(){$(this).removeClass('icon-rotate-90');})
$("#userAccountForm").live("submit",function(){
	Alert("<div style='width:50px;'><img src='img/loader-earth.gif'/ alt='LOADING' class='displayed'></div>");
	$.ajax({
		url: 'userAccountUpdate.php',
		type: 'POST',
		data: $(this).serialize(),
		success: function(msg){Alert(msg);$("#userAccountForm input[type='password']").val("");},
		error: Error
	});
	return false;
});

if(document.getElementById("workspace")!='null'){
	loadUserInfo("AccountInfo");
}

var openRow = 
{
	"LicenseRequest":	
		function (msg){
			msg = jQuery.parseJSON(msg);
			var sOut = '<table cellpadding="2" cellspacing="0" border="0" style="padding-left:30px;">';
			sOut += '<tr><td>Request Message:</td><td>'+msg[0]+'</td></tr>';
			sOut += '<tr><td>Operator Comment:</td><td>'+msg[1]+'</td></tr>';
			sOut += '</table>';
			return sOut;
		},
	"LicenseManage":	
		function (msg){
			return msg;
		},
	"Comment":	
		function (msg){
			msg = jQuery.parseJSON(msg);
			var sOut = '<table cellpadding="2" cellspacing="0" border="0" style="padding-left:30px;">';
			sOut += '<tr><td>Last Edit Time:</td><td>'+msg[0]+'</td></tr>';
			sOut += '<tr><td>Quote:</td><td>'+msg[1]+'</td></tr>';
			sOut += '<tr><td>Comment:</td><td>'+msg[2]+'</td></tr>';
			sOut += '</table>';
			return sOut;
		},
	"GroupMemberView":	
		function (msg){
			return msg;
		},
	"GroupInvolved":	
		function (msg){
			return msg;
		},
	"default":			
		function (msg){
			msg = jQuery.parseJSON(msg);
			var sOut = '<table cellpadding="1" cellspacing="0" border="0" style="padding-left:30px;">';
			sOut += '<tr><td>'+msg[0]+'</td></tr>';
			sOut += '</table>';
			return sOut;
		}
};

var sortRule = 	
{
	"LicenseView":[[2,'desc']],
	"LicenseManage": [[2,'desc']],
	"CommentManage":[[2,'desc']],
	"GroupView":[],
	"default":[]
};
var columndef = {
	"LicenseView":	
		[
			{ "bSortable": false, "aTargets": [ 0 ] },
			{ "bSortable": false, "aTargets": [ 1 ] },
			{ "bSortable": false, "aTargets": [ 3 ] },
			{ "bSortable": false, "aTargets": [ 4 ] }
		],
	"LicenseManage": 
		[
			{ "bSortable": false, "aTargets": [ 0 ] },
			{ "bSortable": false, "aTargets": [ 3 ] }
		],
	"CommentManage":
		[
			{ "bSortable": false, "aTargets": [ 0 ] },
			{ "bSortable": false, "aTargets": [ 4 ] }
		],	
	"GroupView":
		[
			{ "bSortable": false, "aTargets": [ 1 ] },
			{ "bSortable": false, "aTargets": [ 3 ] }
		],
	"default":[]
};

var rightContextMenu =
{
	"CommentManage":{alias: "CommentManageContextMenu",width: 150,items:[
							{ text: "Delete", icon: "icon-trash", alias: "1-1", action: deleteCommentAlert }
				], onShow: applyrule},
	"default":	{}
}
function applyrule() {
	$(this).toggleClass('row_selected');
}

function initialAccountTable(filename)
{
	var url = "userAccountProcessing.php?stype="+filename;
	var oTable = $('#userAccountTable'+filename).dataTable({
						"sScrollX": "100%",
						"bScrollCollapse": true,
						"bProcessing": true,
						"bServerSide": true,
						"sAjaxSource": url,
						"bAutoWidth": false,
						"bFilter": false,
						"bInfo": true,
						"bJQueryUI": true,
						"bLengthChange": true,
						"bPaginate": true,
						"bSort": true,
						"bSortClasses": true,
						"bSortCellsTop": true,
						"aoColumnDefs": (columndef[filename]!=undefined) ? columndef[filename] : columndef['default'],
						"aaSorting": (sortRule[filename]!=undefined) ? sortRule[filename] : sortRule['default'],
						"sPaginationType": "full_numbers",
						"fnDrawCallback": function(nTHead,oSettings) {
							initGUI();
							$(this).scroll();
							(rightContextMenu[filename]!=undefined)
								? 	$('#userAccountTable'+filename+' tbody tr').contextmenu(rightContextMenu[filename])
								: '';	
						}
					})
					.fnSetFilteringDelay();
	
}

$('#userAccount .display tbody tr').live('click contextmenu', function () {
	$(this).toggleClass('row_selected');
} );
	
$('#userAccount .display tbody td img').live('click', function () {
		var nTr = $(this).parents('tr')[0];
		var oTable = $.fn.dataTable.fnTables(true);//$('.display').dataTable();
		oTable = $(oTable).dataTable();
		if ( oTable.fnIsOpen(nTr) )
		{
			/* This row is already open - close it */
			this.src = "img/details_open.png";
			oTable.fnClose( nTr );
		}
		else
		{
			/* Open this row */
			this.src = "img/details_close.png";
			var value = $(this).attr('value');
			var mode =  $(this).attr('mod');
			oTable.fnOpen( nTr, "<img src='img/loader-earth.gif'/ alt='LOADING' class='displayed'>", 'details' );
			
			$.ajax({
				url: 'userAccountAjax.php',
				type: 'POST',
				data: {'mod':mode,'value':value},
				success: function(msg){
							oTable.fnOpen( nTr, (openRow[mode]!=undefined) ? openRow[mode](msg) : openRow['default'](msg), 'details' );
							$(".display .button").button();
						},
				error: Error
			});
			
		}
} );



function Alert(msg)
{
	$("#workSpaceAlert").html(msg);
	$("#workSpaceAlert").dialog({title:"","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
}
function Error()
{
	$("#workSpaceAlert").html("<div class='ui-state-error error'>Connection Failure<div>");
	$("#workSpaceAlert").dialog({title:"","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
	return false;
}

function deleteCommentAlert(){
	var out = "<div style='text-align:center'>Do you want to delete all selected comments?<br/><br/>";
	out +="<span onclick='deleteComment()'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Delete Comment","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
}

function deleteCommentAlertById(id){
	var out = "<div style='text-align:center'>Do you want to delete this comment?<br/><br/>";
	out +="<span onclick='deleteComment(\""+id+"\")'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Delete Comment","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
}

function deleteComment(id){
	var data = {'mod':'CommentDelete','id':[]};
	var oTable = $($.fn.dataTable.fnTables(true));


	if(id)
	{
		data['id'].push(id);
	}
	else
	{
		var anSelected = oTable.$('tr.row_selected');
		anSelected.each(function(i){
			data['id'].push($(this).children('td').children().attr('value'));
		});
	}
	if(data['id'].length>0)
	{
		
		$.ajax({
			url: 'userAccountUpdate.php',
			type: 'POST',
			dataType: 'JSON',
			data: data,
			success: function(msg){
						closeWorkSpaceAlert();
						oTable.each(function(){
							$(this).dataTable().fnDraw();
						});
					},
			error: Error
		});
	}
	else
		closeWorkSpaceAlert();
}

function addCommentAlert(Target,replyTo)
{
	$("#workSpaceAlert").html("<img src='img/loader-earth.gif'/ alt='LOADING' class='displayed'>");
	$("#workSpaceAlert").dialog({title:"Loading","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
	$.ajax({
			url: 'userAccountContent.php',
			type: 'GET',
			data: {"mod":"CommentAddForm","id":Target,"replyTo":replyTo},
			success: function(msg){
						$("#workSpaceAlert").html(msg);
						$('.button').button();
						$("#workSpaceAlert").dialog({title:"Reply","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
					},
			error: Error
		});
}

function Comment(event,formId)
{
	if (event.ctrlKey && event.keyCode == 13) {
		// Ctrl-Enter pressed
		$("#"+formId+" input[type=submit]").click();
	}
}

function editCommentAlert(Target,commentID)
{
	$("#workSpaceAlert").html("<img src='img/loader-earth.gif'/ alt='LOADING' class='displayed'>");
	$("#workSpaceAlert").dialog({title:"Loading","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
	$.ajax({
			url: 'userAccountContent.php',
			type: 'GET',
			data: {"mod":"CommentEditForm","id":Target,"commentID":commentID},
			success: function(msg){
						$("#workSpaceAlert").html(msg);
						$('.button').button();
						$("#workSpaceAlert").dialog({title:"Edit Comment","width":'auto',"modal":true,"resizable":false,position: { my: "center", at: "center", of: window }});
					},
			error: Error
		});
}

$("#commentForm,#commentFormReply,#commentFormEdit").live("submit",function(){
	var oTable = $.fn.dataTable.fnTables(true);
	$.ajax({
		url: 'userAccountUpdate.php',
		type: 'POST',
		data: $(this).serialize(),
		success: 
			function(msg){
				if(msg=='0')
				{
					closeWorkSpaceAlert();
					Alert("<div class='ui-state-error error'>ACCESS DENIED<div>");
				}
				else if(msg=='1')
				{
					closeWorkSpaceAlert();
					$(oTable).each(function(){
						var oTable= $(this).dataTable();
						oTable.fnPageChange("last");
					})
				}
				else if(msg=='2')
				{
					closeWorkSpaceAlert();
					$(oTable).each(function(){
						$(this).dataTable().fnDraw(false);
					})
				}
				else if(msg=='4')
				{
					closeWorkSpaceAlert();
					Alert("<div class='ui-state-error error'>MINMUM REPLY INTERVAL: 15 SECONDS<div>");
				}
				else
				{
					closeWorkSpaceAlert();
					Alert("<div class='ui-state-error error'>SEVERICE ERROR<div>");
				}
			},
		error: Error
	});
	return false;
});

function commentFilterByUser(user){
	$("#commentTable_filter input:text").val('');
	$("#commentTable").dataTable().fnFilter(user,0);

}

function commentPage(commentID)
{
	$.ajax({
		url: 'userAccountAjax.php',
		type: 'POST',
		data: {"mod":"CommentTrack","commentID":commentID,"id":commentPageId},
		success: 
			function(msg){
				$("#commentTable_filter input:text").val('');
				var oTable = $("#commentTable").dataTable()
				oTable.fnFilter('',0);
				oTable.fnDisplayStart(parseInt(msg));
				window.location.assign("#commentTable");
			},
		error: Error
	});
		return false;
}

function deleteGroupAlert(id,name){
	var out = "<div style='text-align:center'>Do you want to delete Group "+name+"?<br/><br/>";
	out +="<span onclick='deleteGroup(\""+id+"\")'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Delete Group","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function deleteGroup(id){
	var data = {'mod':'GroupDelete','id':id};
	var oTable = $($.fn.dataTable.fnTables(true)).dataTable();;

	if(id!=undefined)
	{
		
		$.ajax({
			url: 'userAccountUpdate.php',
			type: 'POST',
			data: data,
			success: function(msg){
						if(msg=='1')
						{
							closeWorkSpaceAlert();
							oTable.fnClearTable();
						}
						else
						{
							Alert("<div class='ui-state-error error'>ACCESS DENIED<div>");
						}
					},
			error: Error
		});
	}
	else
		closeWorkSpaceAlert();
}

function renameGroupAlert(id,name){
	var out = "<form id='userAccountGroupForm'><div style='text-align:center'>";
	out +="<input class='text' type='text' name='groupName' maxlength='20' palceholder='Group Name' value='"+name+"' required /><br/><br/>";
	out +="<span id='userAccountGroupFormError' style='margin:8px'></span>";
	out +="<input type='hidden' name='groupID' value='"+id+"'/>";
	out +="<input type='hidden' name='mod' value='GroupRename'/>";
	out +="<span><input class='button' type='submit' value='Rename'></span>&nbsp&nbsp&nbsp&nbsp<button class='button' onclick='closeWorkSpaceAlert()'>Cancel</button>";
	out +="</div></form>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert .button").button();
	$("#workSpaceAlert").dialog({title:"Rename Group","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function addGroupMemberAlert(id){
	var out = "<form id='userAccountGroupForm'><div style='text-align:center'>";
	out +="<input class='text' type='email' name='email' placeholder='Email Address' required /><br/><br/>";
	out +="<span id='userAccountGroupFormError' style='margin:8px'></span>";
	out +="<input type='hidden' name='groupID' value='"+id+"'/>";
	out +="<input type='hidden' name='mod' value='GroupMemberAdd'/>";
	out +="<span><input class='button' type='submit' value='Add  '></span>&nbsp&nbsp&nbsp&nbsp<button class='button' onclick='closeWorkSpaceAlert()'>Cancel</button>";
	out +="</div></form>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert .button").button();
	$("#workSpaceAlert").dialog({title:"Add Group Member","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function deleteGroupMemberAlert(){
	var out = "<div style='text-align:center'>Do you want to delete all selected Group Member?<br/><br/>";
	out +="<span onclick='deleteGroupMember()'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Delete Group Member","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function deleteGroupMemberAlertById(id,name){
	var out = "<div style='text-align:center'>Do you want to delete Group Member "+name+"?<br/><br/>";
	out +="<span onclick='deleteGroupMember(\""+id+"\")'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Delete Group Member","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function deleteGroupMember(id){
	var data = {'mod':'GroupMemberDelete','id':[]};
	var oTable = $($.fn.dataTable.fnTables(true)).dataTable();;
	if(id)
	{
		data['id'].push(id);
	}
	else
	{
		var anSelected = oTable.$('tr.row_selected');
		anSelected.each(function(i){
			data['id'].push($(this).children('td').children().attr('value'));
		});
	}
	if(data['id'].length>0)
	{
		
		$.ajax({
			url: 'userAccountUpdate.php',
			type: 'POST',
			dataType: 'JSON',
			data: data,
			success: function(msg){
						closeWorkSpaceAlert();
						oTable.fnClearTable();
					},
			error: Error
		});
	}
	else
		closeWorkSpaceAlert();
}

function quitGroupAlert(){
	var out = "<div style='text-align:center'>Do you want to quit all selected Group?<br/><br/>";
	out +="<span onclick='quitGroup()'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Quit Group","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function quitGroupAlertById(id,name){
	var out = "<div style='text-align:center'>Do you want to quit Group "+name+"?<br/><br/>";
	out +="<span onclick='quitGroup(\""+id+"\")'>Yes</span>&nbsp&nbsp&nbsp&nbsp<span onclick='closeWorkSpaceAlert()'>No</span>";
	out +="</div>";
	$("#workSpaceAlert").html(out);
	$("#workSpaceAlert span").button();
	$("#workSpaceAlert").dialog({title:"Quit Group","width":'auto',"modal":true,"resizable":false,"position":{my: "center", at: "center", of: window }});
}

function quitGroup(id){
	var data = {'mod':'GroupQuit','id':[]};
	var oTable = $($.fn.dataTable.fnTables(true)).dataTable();;
	if(id)
	{
		data['id'].push(id);
	}
	else
	{
		var anSelected = oTable.$('tr.row_selected');
		anSelected.each(function(i){
			data['id'].push($(this).children('td').children().attr('value'));
		});
	}
	if(data['id'].length>0)
	{
		
		$.ajax({
			url: 'userAccountUpdate.php',
			type: 'POST',
			dataType: 'JSON',
			data: data,
			success: function(msg){
						closeWorkSpaceAlert();
						oTable.fnClearTable();
					},
			error: Error
		});
	}
	else
		closeWorkSpaceAlert();
}
$("#userAccountGroupForm").live("submit",function(){
	var oTable = $($.fn.dataTable.fnTables(true)).dataTable();;
	$.ajax({
		url: 'userAccountUpdate.php',
		type: 'POST',
		data: $(this).serialize(),
		success: 
			function(msg){
				if(msg=='1')
				{
					closeWorkSpaceAlert();
					oTable.fnClearTable();
				}
				else if(msg=='2')
				{
					$("#userAccountGroupFormError")
						.html("<div class='ui-state-error error'>Group Name Length > 20<div>");
				}
				else if(msg=='3')
				{
					$("#userAccountGroupFormError")
						.html("<div class='ui-state-error error'>This email has not been registered.<div>");
				}
				else if(msg=='4')
				{
					$("#userAccountGroupFormError")
						.html("<div class='ui-state-error error'>You have add this member before.<div>");
				}
				else if(msg=='5')
				{
					$("#userAccountGroupFormError")
						.html("<div class='ui-state-error error'>You try to add your self.<div>");
				}
				else if(msg=='-1')
				{
					$("#userAccountGroupFormError")
						.html("<div class='ui-state-error error'>Connection error.<div>");
				}
				else
				{
					$("#userAccountGroupFormError")
						.html("<div class='ui-state-error error'>ACCESS DENIED or CONNECTION ERROR.<div>");
				}
			},
		error: Error
	});
	return false;
});

function licenseManage(licenseID,option)
{
	var oTable = $($.fn.dataTable.fnTables(true)).dataTable();
	var row = document.getElementById(licenseID).parentNode.parentNode;
	var formData = $(document.getElementById(licenseID)).serialize()+"&mod=UserLicense&licenseID="+licenseID+"&option="+option;
	$.ajax({
		url: 'userAccountUpdate.php',
		type: 'POST',
		data: formData,
		success: 
			function(msg){
				if(msg=='0'){
					oTable.fnClearTable();
				}else if(msg=='1'){
					Alert("<div class='ui-state-error error'>ACCESS DENIED<div>");
				}else if(msg=='2'){
					Alert("<div class='ui-state-error error'>New License Level should be 1~89<div>");
				}else if(msg=='3'){
					Alert("<div class='ui-state-error error'>The operation is invalid<div>");
				}else if(msg=='4'){
					Alert("<div class='ui-state-error error'>Invalid REQUEST LICENSE ID or <br/> HAS BEEN OPERATORED BY OTHER USER<div>");
				}else
					Alert("<div class='ui-state-error error'>ACCESS DENIED<div>");
			},
		error: Error
	});
	return false;
}

function closeGroup()
{
	var oTable = $('#userAccountTableGroupView').dataTable();
	$("#userAccountGroupView").html("<legend style='float:left'>Group List</legend>");
	oTable.fnReloadAjax( "userAccountProcessing.php?stype=GroupView" );
}
function openGroup(groupID,groupName)
{
	var oTable = $('#userAccountTableGroupView').dataTable();
	oTable.fnReloadAjax( "userAccountProcessing.php?stype=GroupMemberView&GroupID="+groupID );
	var string = "<legend style='float:left'>Current Group:"+groupName+"</legend>";
	string += " <span class='button' title='Go Back to Group List' style='float:right' onclick='closeGroup()'><i class='icon-signout'> </i>Back</span> ";
	string += "<span class='button' title='Delete Group Member' style='float:right' onclick='deleteGroupMemberAlert()'><i class='icon-trash'></i></span>";
	string += "<span class='button' title='Add Group Member' style='float:right' onclick='addGroupMemberAlert(\""+groupID+"\")'><i class='icon-plus'></i> <i class='icon-user'></i></span>";
	$("#userAccountGroupView").html(string);
}
function closeWorkSpaceAlert()
{
	$("#workSpaceAlert").dialog();
	$("#workSpaceAlert").dialog("destroy");
	$("#workSpaceAlert").html('');
}
function loadUserInfo(filename)
{
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//document.getElementById("workspace").innerHTML=xmlhttp.responseText;
			$("#workspace").html(xmlhttp.responseText);
			$( "#workSpaceContent" ).tabs({
				show: function(event, ui) {

					
				},
				beforeLoad: function( event, ui ) {
					ui.jqXHR.error(function() {
						ui.panel.html(
							"Couldn't load this tab. We'll try to fix this as soon as possible. " +
							"If this wouldn't be a demo." 
						);
					});
					ui.panel.html(
							"<img src='img/loader-earth.gif'/ alt='LOADING' class='displayed'>"
						);
					var table = $.fn.dataTable.fnTables(true);

					if ( table.length > 0 ) {
						$(table).dataTable().fnDestroy();
					}
					
				},
				load: function( event, ui ) {
					
					filename = 	$(ui.tab).attr('value');	
					$(".button").button();
					initialAccountTable(filename);
					
				},
				heightStyle: "content"
			});
			initGUI();
			
			initialAccountTable(filename);
		}
	}
	xmlhttp.open("get","userAccountContent.php?mod="+filename,true);
	xmlhttp.send();

}

function showFamily()
{
	var button=document.getElementById("showFamily");
	var div=document.getElementById("family");
	if (button.value=="Show Family")
	{
		button.value="Hide Family";
		div.style.display="inline";
	}
	else
	{
		button.value="Show Family";
		div.style.display="none";
	}
}

function showPPIExample()
{
	var text=document.getElementById("protein");
	text.value="At4g00020 At5g20850 At5g61380 At2g46790 junkData";
}