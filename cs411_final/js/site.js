//popup
var popupStatus = 0;

//Jmol
var jmoljarpath = "structure"; // where JmolApplet*.jar is to be found on a server
var jmoljarfile = "JmolApplet0.jar";  // set to JmolApplet0.jar to use incremental loading of parts as needed. 
var dataDir = ".";      // where the model AND .spt files are to be found.  Used by JmolPopIn.js
jmolInitialize(jmoljarpath, jmoljarfile);
jmolSetDocument(0);

$(function() {
	initGUI();

	initDataTables();
	
	$('#refSelect').change( function() {
		$('#refForm').submit();
	});
	$("#treeBrowseTable").treetable({ expandable: true });
	
	//Jmol
	/*if($('.JmolPanels').length) {
		//boxbgcolor, boxfgcolor, progresscolor
		jmolSetAppletColor('#C6D5B6', '#C6D5B6', '#4CA20B');
		var script = 'load pdb_structure.php; cpk off; spin on; wireframe off;cartoon; color cartoon structure;';
		$('#Jmol0').html(jmolApplet("100%",script));
	}*/
	
	/* Slide Show */
	var currentPosition = 0;
	var slideWidth = 790;
	var slides = $('.slide');
	var numberOfSlides = slides.length;

	// Remove scrollbar in JS
	$('#slidesContainer').css('overflow', 'hidden');

	// Wrap all .slides with #slideInner div
	slides
		.wrapAll('<div id="slideInner"></div>')
		// Float left to display horizontally, readjust .slides width
		.css({
			'float' : 'left',
			'width' : slideWidth
		});

	// Set #slideInner width equal to total width of all slides
	$('#slideInner').css('width', slideWidth * numberOfSlides);

	// Insert left and right arrow controls in the DOM
	$('#slideshow')
		.prepend('<span class="control-bg" id="leftControl-bg"><span class="control" id="leftControl">Move left</span></span>')
		.append('<span class="control-bg" id="rightControl-bg"><span class="control" id="rightControl">Move right</span></span>');

	// Hide left arrow control on first load
	manageControls(currentPosition);

	// Create event listeners for .controls clicks
	$('.control')
		.bind('click', function(){
			// Determine new position
			currentPosition = ($(this).attr('id')=='rightControl')
			? currentPosition+1 : currentPosition-1;

			// Hide / show controls
			manageControls(currentPosition);
			// Move slideInner using margin-left
			$('#slideInner').animate({
				'marginLeft' : slideWidth*(-currentPosition)
			});
		});
	
	// manageControls: Hides and shows controls depending on currentPosition
	function manageControls(position){
		// Hide left arrow if position is first slide
		if(position==0){ $('#leftControl').hide() }
		else{ $('#leftControl').show() }
		// Hide right arrow if position is last slide
		if(position==numberOfSlides-1){ $('#rightControl').hide() }
		else{ $('#rightControl').show() }
	}
	
	/* menu bar */
	$(".lavaLamp").lavaLamp({ fx: "easeOutBack", speed: 700 })
		.find('li.back').addClass('ui-corner-all');
		
	/* Accordion */
	$( "#accordion" ).accordion({
		autoHeight: false,
		navigation: true,
		clearStyle: true
	});
	
	//Form validator
	$("#usageinfo").validate();
	
	/* Download Page Popup */
	$('#downloadbttn').click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopup();
	});
	
	$(window).bind("resize", function(){
		$("#backgroundPopup").css("height", $(window).height());
		centerPopup();
	});
	
	$("#popupContactClose, #popupSkip").click(function(){  
		disablePopup();  
	});  
	//Click out event!  
	$("#backgroundPopup").click(function(){  
		disablePopup();  
	});  
	//Press Escape event!
	$(document).keypress(function(e){  
		if(e.keyCode==27 && popupStatus==1){  
			disablePopup();
		}
	});
});

// init jquery ui features
function initGUI() {
	$('.button, input:submit, input:button').button(); //jQuery UI buttons
	$('select').not("#browseTable_length select,.dataTables_wrapper select").selectBox(); //Custom select box
	$('.selectBox-options LI A').each(function() {
		var $this = $(this);
		var text = $this.text();
		$this.html('<table><tr><td><span class="ui-icon ui-icon-bullet" style="float: left; margin-right: .3em;"></span></td><td>'+text+'</td></tr></table>');
	});
}

function initDataTables() {
	// build url for server-side/ajax table processing
	var url = '';
	var stype = $.getUrlVar('stype');
	var getvars = new Array();
	url += '?stype=' + ((stype==undefined)? '' : stype);
	if(stype=='pro_s' || stype==undefined){
		url = 'server_processing.php' + url;
		getvars = ["org", "ref", "acc", "desc"];
	} else if (stype=='pep_s'){
		url = 'server_processing.php' + url;
		getvars = ["org", "ref", "seq"];
	} else if (stype=='pro_b'){
		url = 'blast.php' + url;
		getvars = ["seq", "org", "ref", "eval"];
	} else if (stype=='pep_b'){
		url = 'blast.php' + url;
		getvars = ["seq", "org", "ref", "eval"];
	} else if (stype=='kinase'||stype=='phosphatase'||stype=='domain'||stype=='PPI'){
		url = 'kinase_processing.php' + url;
	}
	
	for(var i=0; i < getvars.length; i++){
		var getVar = $.getUrlVar(getvars[i]);
		url += '&' + getvars[i] + '=' + ((getVar==undefined)? '' : getVar);
	}
	
	if(stype=='pro_s' || stype=='pep_s' || stype=='kinase' ||stype=='phosphatase' ||stype=='domain'||stype=='PPI'|| stype==undefined){
		var getVar = $.getUrlVar("sSearchHome");
		var oTable = $('#browseTable').dataTable({
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": url,
			"bAutoWidth": false,
			"bFilter": true,
			"bInfo": true,
			"bJQueryUI": true,
			"bLengthChange": true,
			"bPaginate": true,
			"bSort": true,
			"bSortClasses": true,
			"bSortCellsTop": true,
			"sPaginationType": "full_numbers",
			"fnDrawCallback": function() {
				initGUI();
				$(this).scroll();
			}
		}).fnSetFilteringDelay();
		// Put the quick search text from the home page into the browse search box
		if(getVar != undefined) {
			$("#browseTable_filter input:text").val(getVar);
			oTable.fnFilter(getVar);
		}
		$("thead input").each(function (i) {
			if ( this.value != "" )
			{
				oTable.fnFilter(this.value,$("thead input").index(this));
			}
		});
		if($("#protein").val()!=undefined)
		{
			oTable.fnFilter($("#protein").val());
			//oTable.fnFilter($("#protein").val(),1);
		}
	} else {i
		var oTable = $('#browseTable').dataTable( {
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bProcessing": true,
			"sAjaxSource": url,
			"bAutoWidth": false,
			"bFilter": true,
			"bInfo": true,
			"bJQueryUI": true,
			"bLengthChange": true,
			"bPaginate": true,
			"bSort": true,
			"bSortClasses": true,
			//"sDom": 'RC<"clear">lfrtip',
			"sPaginationType": "full_numbers",
			"fnDrawCallback": function() {
				initGUI();
				$(this).scroll();
			}
		} ).fnSetFilteringDelay();
	}
	
	$('.simple_table').dataTable({
		"sScrollX": "100%",
		"bAutoWidth": true,
		"bFilter": false,
		"bInfo": false,
		"bJQueryUI": true,
		"bLengthChange": true,
		"bPaginate": false,
		"sPaginationType": "full_numbers",
		"bSort": true,
		"bSortClasses": true,
		"aaSorting":[]
	});
	
	$('#treeBrowseTable').dataTable({
		"bAutoWidth": false,
		"bFilter": false,
		"bInfo": false,
		"bJQueryUI": true,
		"bLengthChange": false,
		"bPaginate": false,
		"bSort": false,
		"bSortClasses": true
	});
	
	$(".treeLevel").live("click",function(){
		var treeLevel = $(this).attr('value');
		$("tfoot input[name='treeLevel']:text,thead input[name='treeLevel']").val(treeLevel);
		oTable.fnFilter(treeLevel,0);
		//oTable.fnFilterClear();
	});
	$("#proteinSearch").live("click",function(){
		oTable.fnFilter($("#protein").val());
		//oTable.fnFilter($("#protein").val(),1);
	});
	
	var delayTime = 0;
	var delay = 500;
	
	$("tfoot input").live( "keyup",function () {
		window.clearTimeout(delayTime);
		var $this = this;
		delayTime = window.setTimeout(function() {
			/* Filter on the column (the index) of this element */
			var table = $.fn.dataTable.fnTables(true);
			
			$(table).each(function(i){
				this.dataTable().fnFilter( $this.value, $("tfoot input").index($this) );
			});

			//oTable;
		}, delay); 
	} );
	$("thead input").live( "keyup",function () {
		window.clearTimeout(delayTime);
		var $this = this;
		delayTime = window.setTimeout(function() {
			/* Filter on the column (the index) of this element */
			var table = $.fn.dataTable.fnTables(true);
			
			$(table).each(function(i){
				$(this).dataTable().fnFilter( $this.value, $("thead input").index($this) );
			});

		}, delay); 
	} );
	

	var spectrumTable = $('#spectrum_table').dataTable({
		"sScrollX": "100%",
		"bScrollCollapse": true,
		"bAutoWidth": false,
		"bFilter": false,
		"bInfo": false,
		"bJQueryUI": true,
		"bLengthChange": false,
		"bPaginate": false,
		"bSort": false,
		"bSortClasses": false,
		"fnDrawCallback": function() {
			$(this).scroll();
		}
	}).fnSetFilteringDelay();
	if(spectrumTable.length > 0) spectrumTable.fnFakeRowspan(0).fnDraw();
	
	$('div.dataTables_scrollBody').scroll(function () {
		var $this = $(this);
		var scrollable = $this.parent('.dataTables_scroll');
		var tbody = $this.find('tbody');
		var visibleLeft = $this.offset().left;
		var visibleRight = visibleLeft + $this.width();
		var left = tbody.offset().left;
		var right = left + tbody.width();
		//alert('('+left+' '+visibleLeft+')('+right+' '+visibleRight+')');
		
		if(left < visibleLeft) {
			scrollable.addClass('shadow_left');
		} else if(left >= visibleLeft) {
			scrollable.removeClass('shadow_left');
		}
		if(right > visibleRight) {
			scrollable.addClass('shadow_right');
		} else if(right <= visibleRight) {
			scrollable.removeClass('shadow_right');
		}
	}).scroll();
}

//loading popup
function loadPopup(){
	//loads popup only if it is disabled  
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.5"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupContact").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupContact").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup  
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	
	if(windowHeight <= popupHeight) {
		popupHeight = windowHeight;
	}
	
	//centering
	$("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
}
