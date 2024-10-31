(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 // $('p').powerTip({ manual: true });
	 $(document).ready(function(){

	 	$('.mwb_twitter_tooltip').hide();
	 	// $(document).on('mouseup',".entry-content p",function(e){
	 	// 	var mwb_twitter_text_selection = window.getSelection().toString();
	 	// 	var mwb_twitter_selected_text = mwb_twitter_text_selection.toString();
	 	// 	if(mwb_twitter_selected_text != ''){

	 	// 		$('.entry-content').append('<div class="mwb_twitter_tooltip"><i class=""></i><a class="dashicons dashicons-twitter" href="https://twitter.com/intent/tweet?text='+mwb_twitter_selected_text+'"></a></div>');
	 	// 		var mwb_twitter_selected_text_x_pos = e.clientX;
	 	// 		var mwb_twitter_selected_text_y_pos = e.clientY;

	 	// 		mwb_twitter_selected_text_placeTooltip(mwb_twitter_selected_text_x_pos, mwb_twitter_selected_text_y_pos);
	 	// 		$('.mwb_twitter_tooltip').show();
	 	// 	}
	 	// 	else
	 	// 	{
	 	// 		$('.entry-content').find('.mwb_twitter_tooltip').remove();
	 	// 	}

	 	// });
	 	$('.entry-content').quoteShare({
	 		background: '#000',
	 		twitterColor : '#55acee'
	 	});
	 	$('.woocommerce-product-details__short-description').quoteShare({
	 		background: '#000',
	 		twitterColor : '#55acee'
	 	});

	 	// $(document).on('click','body',function(en){
	 	// 	var mwb_twitter_container = $(".entry-content p");
	 	// 	if (!mwb_twitter_container.is(en.target) && mwb_twitter_container.has(en.target).length === 0) 
	 	// 	{
	 	// 		$('.mwb_twitter_tooltip').hide();
	 	// 	}
	 	// });

	 });

	 // function mwb_twitter_selected_text_placeTooltip(x_pos, y_pos) {
	 // 	$(".mwb_twitter_tooltip").css({
	 // 		top: y_pos + 'px',
	 // 		left: x_pos + 'px',
	 // 		position: 'fixed'
	 // 	});
	 // }
	 $('.mwb-twitter-follow-button').on('click',function(){
	 	window.open(this.href,'_blank');
	 	return false;
	 });

	 $('.mwb-twitter-share-button').on('click',function(){
	 	window.open(this.href,'_blank');
	 	return false;
	 });

	})( jQuery );
