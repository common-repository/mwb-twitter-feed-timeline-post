(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	 var ajaxurl = mwb_twitter_admin_params.ajax_url;
	 $(document).ready(function(){
	 	$('.mwb_twitter_direct_msg_success').hide();
	 	$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 	$(".mwb-popup-modal-wrapper").hide();
	 	$('#mwb_twitter_admin_notices').hide();
	 	$('#mwb_twitter_admin_error_notices').hide();


	 	$(document).on('click','#mwb_twitter_tweet_msg',function(){
	 		var mwb_twitter_message = $('#mwb_twitter_tweet_messages').val();
	 		var mwb_twitter_status_image = $('#mwb_twitter_upload_status_image_upload').val();
	 		$(".mwb_twitter_for_wordpress_loader_wrapper").show();
	 		$.ajax({
	 			url:ajaxurl,
	 			method:'POST',
	 			cache:false,
	 			data:{
	 				action:'mwb_twitter_tweet_message_from_dashboard',
	 				mwb_twitter_send_message: mwb_twitter_message,
	 				mwb_twitter_send_image: mwb_twitter_status_image,
	 				mwb_update_nonce : mwb_twitter_admin_params.mwb_twitter_nonce
	 			},success:function(response){
	 				console.log(response);
	 				if(response == 'success'){
	 					$('#mwb_twitter_admin_error_notices').hide();
	 					$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 					$('#mwb_twitter_admin_notices').show();
	 					$('html, body').animate({ scrollTop: 0 }, 0);
	 					$('#mwb_twitter_tweet_messages').val('');
	 					$('#mwb_twitter_upload_status_image_upload').val('');
	 					$('#mwb_twitter_status_setting_upload_image').hide();
	 				}else{
	 					$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 					$('#mwb_twitter_admin_error_notices').html('<p>'+mwb_twitter_admin_params.mwb_twitter_update_status_error_msg+'</p>');
	 					$('#mwb_twitter_admin_error_notices').show();
	 					$('#mwb_twitter_admin_error_notices').fadeOut(6000);
	 					$('html, body').animate({ scrollTop: 0 }, 0);
	 					$('#mwb_twitter_status_setting_upload_image').hide();
	 				}
	 			}
	 		});
	 	});

	 	$(document).on('click','.mwb_twitter_followers_ping_msg',function(){
	 		var mwb_twitter_follower_id = $(this).attr('data-followers_id');
	 		$("#mwb_twitter_show_modal_"+mwb_twitter_follower_id).show();
	 		$('.mwb_twitter_message_content').focus();
	 	});
	 	$(document).on('click','#mwb_twitter_close_popup',function(){
	 		var mwb_twitter_follower_id = $(this).attr('data-followers_id');
	 		$("#mwb_twitter_show_modal_"+mwb_twitter_follower_id).hide();
	 	});

	 	$(document).on('click','#mwb_twitter_send_messages',function(){
	 		
	 		var mwb_twitter_followers_id = $(this).attr('data-followers_id');
	 		var mwb_twitter_followers_scrrenname = $(this).attr('data-followers_screen_name');
	 		var mwb_twitter_follower_message = $('#mwb_twitter_message_content_'+mwb_twitter_followers_id).val();
	 		$.ajax({
	 			url : ajaxurl,
	 			method: 'POST',
	 			cache : false,
	 			data:{
	 				action : 'mwb_twitter_send_message_to_follower',
	 				mwb_twitter_follower_id : mwb_twitter_followers_id,
	 				mwb_twitter_follower_text : mwb_twitter_follower_message,
	 				mwb_twitter_follower_screen_name : mwb_twitter_followers_scrrenname,
	 				mwb_update_nonce : mwb_twitter_admin_params.mwb_twitter_nonce
	 			},success:function(response){
	 				if(response != 'failed'){
	 					console.log(response);
	 					$("#mwb_twitter_show_modal_"+response).hide();
	 					$("#mwb_twitter_message_content_"+response).hide();
	 					$('.mwb_twitter_direct_msg_success').show();
	 					$('html, body').animate({ scrollTop: 0 }, 0);
	 					$('.mwb_twitter_direct_msg_success').fadeOut(4000);
	 				}
	 				else{
	 					console.log(response);
	 					window.location.reload();
	 				}
	 			}
	 		});
	 	});
	 });


	 $(document).on('click','.mwb_twitter_setting_upload_pro_image',function(){
	 	var imageurl = $("#mwb_twitter_upload_profile_image_upload").val();
	 	tb_show('', 'media-upload.php?TB_iframe=true');
	 	window.send_to_editor = function(html)
	 	{
	 		var imageurl = $(html).attr('href');

	 		if(typeof imageurl == 'undefined')
	 		{
	 			imageurl = $(html).attr('src');
	 		}
	 		var last_index = imageurl.lastIndexOf('/');
	 		var url_last_part = imageurl.substr(last_index+1);
	 		if( url_last_part == '' ){
	 			imageurl = $(html).children("img").attr("src");  
	 		}   
	 		$("#mwb_twitter_upload_profile_image_upload").val(imageurl);
	 		$("#mwb_twitter_other_setting_upload_image").attr("src",imageurl);
	 		$("#mwb_twitter_other_setting_remove_logo").show();
	 		tb_remove();
	 	};
	 	return false;
	 });


	 $(document).on('click','.mwb_twitter_setting_upload_status_image',function(){
	 	var mwbtwitterimageurl = $("#mwb_twitter_upload_status_image_upload").val();
	 	tb_show('', 'media-upload.php?TB_iframe=true');
	 	window.send_to_editor = function(html)
	 	{
	 		var mwbtwitterimageurl = $(html).attr('href');

	 		if(typeof mwbtwitterimageurl == 'undefined')
	 		{
	 			mwbtwitterimageurl = $(html).attr('src');
	 		}
	 		var last_index = mwbtwitterimageurl.lastIndexOf('/');
	 		var url_last_part = mwbtwitterimageurl.substr(last_index+1);
	 		if( url_last_part == '' ){
	 			mwbtwitterimageurl = $(html).children("img").attr("src");  
	 		}   
	 		$("#mwb_twitter_upload_status_image_upload").val(mwbtwitterimageurl);
	 		$("#mwb_twitter_status_setting_upload_image").attr("src",mwbtwitterimageurl);
	 		$("#mwb_twitter_status_setting_remove_logo").show();
	 		tb_remove();
	 	};
	 	return false;
	 });


	 $(document).on('click','.mwb_twitter_setting_upload_pro_banner',function(){
	 	var bannerurl = $("#mwb_twitter_upload_profile_banner_upload").val();

	 	tb_show('', 'media-upload.php?TB_iframe=true');

	 	window.send_to_editor = function(html)
	 	{
	 		var bannerurl = $(html).attr('href');
	 		if(typeof bannerurl == 'undefined')
	 		{
	 			bannerurl = $(html).attr('src');
	 		}
	 		var last_index = bannerurl.lastIndexOf('/');
	 		var url_last_part = bannerurl.substr(last_index+1);
	 		if( url_last_part == '' ){
	 			bannerurl = $(html).children("img").attr("src");  
	 		}   
	 		$("#mwb_twitter_upload_profile_banner_upload").val(bannerurl);
	 		$("#mwb_twitter_banner_setting_upload_image").attr("src",bannerurl);
	 		$("#mwb_twitter_banner_setting_remove_logo").show();
	 		tb_remove();
	 	};
	 	return false;
	 });

	 $(document).on('click','#mwb_twitter_profile_img',function(e){
	 	e.preventDefault();
	 	var mwb_profile_img_url = $('#mwb_twitter_upload_profile_image_upload').val();
	 	var mwb_profile_background_img_url = $('#mwb_twitter_upload_profile_banner_upload').val();
	 	$(".mwb_twitter_for_wordpress_loader_wrapper").show();
	 	$.ajax({
	 		url : ajaxurl,
	 		type : 'POST',
	 		cache:false,
	 		data:{
	 			action : 'mwb_twitter_profile_settings',
	 			mwb_twitter_profile_img : mwb_profile_img_url,
	 			mwb_twitter_profile_banner_img : mwb_profile_background_img_url,
	 			mwb_update_nonce : mwb_twitter_admin_params.mwb_twitter_nonce 
	 		},success : function(response){
	 			console.log(response);
	 			if(response == 'success'){
	 				$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 				$(document).find('#mwb_twitter_profile_notices').addClass('notice-success');
	 				$(document).find('#mwb_twitter_profile_notices').html('<p>'+mwb_twitter_admin_params.mwb_twitter_both_profile_saved_message+'</p>');
	 				$(document).find('#mwb_twitter_profile_notices').show();
	 				$('html body').animate({scrollTop: 0},0);
	 				$('#mwb_twitter_upload_profile_image_upload').val('');
	 				$('#mwb_twitter_upload_profile_banner_upload').val('');
	 				$('#mwb_twitter_other_setting_remove_logo').hide();
	 				$('#mwb_twitter_banner_setting_remove_logo').hide();

	 			}
	 			else if( response == 'profile' ){
	 				$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 				$(document).find('#mwb_twitter_profile_notices').addClass('notice-success');
	 				$(document).find('#mwb_twitter_profile_notices').html('<p>'+mwb_twitter_admin_params.mwb_twitter_profile_image_saved_message+'</p>');
	 				$(document).find('#mwb_twitter_profile_notices').show();
	 				$('html body').animate({scrollTop: 0},0);
	 				$('#mwb_twitter_upload_profile_image_upload').val('');
	 				$('#mwb_twitter_upload_profile_banner_upload').val('');
	 				$('#mwb_twitter_other_setting_remove_logo').hide();
	 				$('#mwb_twitter_banner_setting_remove_logo').hide();

	 			}
	 			else if( response == 'banner' ){
	 				
	 				$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 				$('html body').animate({scrollTop: 0},0);
	 				$(document).find('#mwb_twitter_profile_notices').addClass('notice-success');
	 				$(document).find('#mwb_twitter_profile_notices').html('<p>'+mwb_twitter_admin_params.mwb_twitter_profile_banner_saved_message+'</p>');
	 				$(document).find('#mwb_twitter_profile_notices').show();
	 				$('#mwb_twitter_upload_profile_image_upload').val('');
	 				$('#mwb_twitter_upload_profile_banner_upload').val('');
	 				$('#mwb_twitter_other_setting_remove_logo').hide();
	 				$('#mwb_twitter_banner_setting_remove_logo').hide();

	 			}
	 			else{

	 				$(".mwb_twitter_for_wordpress_loader_wrapper").hide();
	 				$(document).find('#mwb_twitter_profile_notices').addClass('notice-error');
	 				$(document).find('#mwb_twitter_profile_notices').html('<p>'+mwb_twitter_admin_params.mwb_twitter_error_messages+'</p>');
	 				$(document).find('#mwb_twitter_profile_notices').show();
	 				$('html body').animate({scrollTop: 0},0);
	 				$('#mwb_twitter_upload_profile_image_upload').val('');
	 				$('#mwb_twitter_upload_profile_banner_upload').val('');
	 				$('#mwb_twitter_other_setting_remove_logo').hide();
	 				$('#mwb_twitter_banner_setting_remove_logo').hide();
	 			}
	 		}
	 	});
	 });

	 $(document).on('click','#mwb_twitter_save_custom_message',function(){
	 	var MwbTwitterPostId = $(this).attr('data-postid');
	 	var MwbTwitterTextAreaId = '#mwb_twitter_message_for_sharing_'+MwbTwitterPostId;
	 	var MwbTwitterCutomMessage = $(MwbTwitterTextAreaId).val();
	 	$.ajax({
	 		url: ajaxurl,
	 		type: 'POST',
	 		cache: false,
	 		data:{
	 			action: 'mwb_twitter_save_message',
	 			mwb_twitter_post_id: MwbTwitterPostId,
	 			mwb_twitter_post_custom_message: MwbTwitterCutomMessage,
	 			mwb_update_nonce : mwb_twitter_admin_params.mwb_twitter_nonce
	 		},success:function(response){
	 			$('.mwb_twitter_saved_msg').html('<lable>'+mwb_twitter_admin_params.mwb_twitter_saved_message+'</lable>');
	 			$('.mwb_twitter_saved_msg > lable').fadeOut(3000);
	 		}
	 	});
	 });

	 $(document).on('click','.mwb_twitter_direct_message_notify',function(){
	 	var MwbTwitterUserId = $(this).attr('data-twitter_user');
	 	var MwbTwitterUserShow = '#mwb_twitter_zoom_'+MwbTwitterUserId;
	 	$(document).find(MwbTwitterUserShow).show();
	 });

	 $(document).on('click','.mwb_twitter_close_popup',function(){
	 	$('.mwb_twitter_prodile_image').hide();
	 });


	 // License Activation
	jQuery(document).on('click','#mwb_twitter_license_save',function(e){
		e.preventDefault();
		jQuery('.licennse_notification').html('');
		var mwb_license = jQuery('#mwb_twitter_license_key').val();
		
		if( mwb_license == '' )
		{
			jQuery('#mwb_twitter_license_key').css('border','1px solid red');
			return false;
		}
		else
		{
			jQuery('#mwb_twitter_license_key').css('border','none');
		}
		jQuery('.loading_image').show();
		jQuery.ajax({
			url : ajaxurl,
			type:'POST',
			dataType:'json',
			data:{
				action : 'mwb_twitter_register_license',
				mwb_nonce : mwb_twitter_admin_params.mwb_twitter_nonce,
				license_key: mwb_license
			},
			success : function (response) {
				console.log(response);
				if( response.msg == '' ){
					response.msg = 'Something Went Wrong! Please try again';
				}
				jQuery('.loading_image').hide();
				if(response.status == true ){
					jQuery('.licennse_notification').css('color','green');
					jQuery('.licennse_notification').html(response.msg);
					window.location.reload();
				}
				else{
					jQuery('.licennse_notification').html(response.msg);
				}
			}
		});
	});

	})( jQuery );
