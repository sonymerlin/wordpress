jQuery(document).ready(function ($) {

	var x = 0;
	var count = 0;

	// Delete all the form submissions
	$("#cb-select").change(function () {
		$(".urr_checkbox").prop('checked', $(this).prop("checked"));

		if($("select[name='urr-dropdown']").val() == "trash") {
			alert("Are you sure to delete all the entries? If you do that, all of them will be removed from the entire website.");
		} 
	});

	// Delete the selected form submission
	$('#doaction').on("click", function() {
		
		if($("select[name='urr-dropdown']").val() == "trash") {
		alert("Are you sure to delete this entry? If you do that, it will be removed from the entire website.");
		window.location.reload(true);
		}

	});
   
	// Toggle Swatches - Radio slider
	$("#radios").radiosToSlider();   

	// Feedback Submissions
	$("#urr-form-entries").DataTable();  

	$('.click-to-copy').click(function(event){
		$(this).prev().CopyToClipboard();
		event.preventDefault();
	});  
			
	// This below script is to enable to "CopyToCilpboard" function
	$.fn.CopyToClipboard = function() {
		var textToCopy = false;
		if(this.is('select') || this.is('textarea') || this.is('input')){
			textToCopy = this.val();
		}else {
			textToCopy = this.text();
		}
		CopyToClipboard(textToCopy);
	};

	function CopyToClipboard( val ){
		var hiddenClipboard = $('#_hiddenClipboard_');
		if(!hiddenClipboard.length){
			$('body').append('<textarea style="position:absolute;top: -9999px;" id="_hiddenClipboard_"></textarea>');
			hiddenClipboard = $('#_hiddenClipboard_');
		}
		hiddenClipboard.html(val);
		hiddenClipboard.select();
		document.execCommand('copy');
		document.getSelection().removeAllRanges();
	}

	$(function(){
		$('[data-clipboard-target]').each(function(){
			$(this).click(function() {
				$($(this).data('clipboard-target')).CopyToClipboard();
			});
		});
		$('[data-clipboard-text]').each(function(){
			$(this).click(function(){
				CopyToClipboard($(this).data('clipboard-text'));
			});
		});
	});

});
