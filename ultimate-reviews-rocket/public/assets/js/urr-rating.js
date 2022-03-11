jQuery(document).ready(function() {
	jQuery.fn.rating = function(options) {
		var self = this;
		var settings = jQuery.extend({
		color: "#556b2f",
		max_stars : 5 ,
		clearSelected : function() {
			jQuery('.plugin-rating i').each(function() {
			jQuery(this).addClass('fa-star-o').removeClass('fa-star');
		});
	},
	setRating : function()
		{
		if(self.val())
		{
			var position = parseInt(self.val())-1;
			// alert(position+1);
			for(var i=0;i<=position;i++)
			{
				//jQuery(".plugin-rating i").addClass("fa-star").removeClass("fa-star-o");

			}
		}   
		}

	}, options );
		var ratingHtml ='<div class="plugin-rating rating"><i class="fa fa-star-o" id="1" aria-hidden="true" data-toggle="urr-rate1" title="Poor"></i><i class="fa fa-star-o" id="2" aria-hidden="true" data-toggle="urr-rate2" title="Not Good"></i><i class="fa fa-star-o" id="3" aria-hidden="true" data-toggle="urr-rate3" title="Average"></i><i class="fa fa-star-o" id="4" aria-hidden="true" data-toggle="urr-rate4" title="Good"></i><i class="fa fa-star-o" id="5" aria-hidden="true" data-toggle="urr-rate5" title="Excellent"></i></div>';
	self.filter('input').each(function(){
		jQuery(self).after(ratingHtml);
		jQuery(self).hide();
	});
	
	settings.setRating(); 
	jQuery(document).on('click','.plugin-rating i',function(){
		var position = jQuery(".plugin-rating i").index(this);
		self.val(position+1); 
		settings.setRating();    	
		var current_rating_value = jQuery('#rating_set_value').val();
		var selected_star = position+1;
		if(typeof(selected_star) != 'undefined'){
			if(selected_star < current_rating_value) {
				console.log(selected_star);
				window.location.href = "your-feedback?urr_rating_value="+selected_star;
			} else {
				window.location.href = "social-media-reviews?urr_rating_value="+selected_star;
			}
		}else{
			alert('choose your Threshold Rating');
		}
	});
        

	jQuery(document).on('mouseenter','.plugin-rating i.fa',function(){
		var getId = jQuery(this).attr('id');
		jQuery( "i.fa" ).each(function() {
			if(jQuery(this).attr('id')<=getId)
			{
				
				jQuery('.plugin-rating i#'+jQuery(this).attr('id')).addClass('fa-star').removeClass('fa-star-o');
			}
		});
		
	});

        jQuery(document).on('mouseout','.plugin-rating i',function(){
        	settings.clearSelected();
        	settings.setRating();
        });        
        
        return self;
    };
    
    
    jQuery('#ratingme').rating();
});
