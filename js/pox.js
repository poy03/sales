$(document).ready(function(){
	$( ".main-search" ).autocomplete({
		source: 'search-item-all',
		select: function(event, ui){
		window.location='item?s='+ui.item.data;
		}
	});

	$( ".main-search" )
	.focus(function(e){
		$(this).attr("placeholder","Search for Item Name or SKU Code.");
		$(this).animate({
		  width: "400px"
		}, 400);
	})
	.blur(function(e){
		$(this).animate({
		  width: "75px"
		}, 1000);
	});

});