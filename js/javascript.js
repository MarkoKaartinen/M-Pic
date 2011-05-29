$(document).ready(function(){
	markoEditor("#frontpagetxt");
	markoEditor("#aboutpagetxt");
	markoEditor("#adminaboutpagetxt");
	$(".markoEditor img").click(markoEditorClick);
});

$(document).ready(function() {
	$("a.kuva").fancybox({
		'imageScale': true,
		'padding': 5,
		'zoomOpacity': true,
		'zoomSpeedIn': 500,
		'zoomSpeedOut': 500,
		'zoomSpeedChange': 300,
		'overlayShow': true,
		'overlayColor': "#FFFFFF",
		'overlayOpacity': 0.8,
		'enableEscapeButton': true,
		'showCloseButton': true,
		'hideOnOverlayClick': true,
		'hideOnContentClick': false,
		'frameWidth':  640,
		'frameHeight':  500,
		'callbackOnStart': null,
		'callbackOnShow': null,
		'callbackOnClose': null,
		'centerOnScroll': true,
		'titlePosition'  : 'inside'
	});	
});

function markoEditor(txt){
	$(txt).width(500);
	$(txt).height(150);
	$(txt).before('<div class="markoEditor" id="'+txt+'_div">' +
		'<img src="icons/text_bold.png" alt="bold" />' +
		'<img src="icons/text_italic.png" alt="italic" />' +
		'<img src="icons/link.png" alt="link" />' +
		'<img src="icons/image.png" alt="image" />' +
	'</div>');
}

function getSelection() {
	return (!!document.getSelection) ? document.getSelection() :
	       (!!window.getSelection)   ? window.getSelection() :
	       document.selection.createRange().text;
}

function markoEditorClick(){
	var txtid = $(this).parent("div").attr("id").split("_")[0];
	var kohde = $(this).attr("alt");
	var arvo = $(txtid).val();
	if(kohde == "link"){
		var url = prompt("Give url:", "http://");
		var text = prompt("Give link text:", "");
		$(txtid).val(arvo+"[url="+url+"]"+text+"[/url]");
	}
	if(kohde == "bold"){
		$(txtid).val(arvo+"[b][/b]");
	}
	if(kohde == "italic"){
		$(txtid).val(arvo+"[i][/i]");
	}
	if(kohde == "image"){
		var img = prompt("Give image id:");
		$(txtid).val(arvo+"[image]"+img+"[/image]");
	}
	$(txtid).focus();
}