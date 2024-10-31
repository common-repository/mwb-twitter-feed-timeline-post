<?php
if( !defined('ABSPATH')){
	exit;
}
if( ! class_exists('WP_List_Table'))
{
	require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}
require_once MWB_TWITTER_PATH.'Api/tmhOAuth.php';
$mwb_twitter_image_url = MWB_TWITTER_URL.'admin/images/Twitter.png';
?>
<div class="mwb_twitter_image">
	<img src="<?php echo $mwb_twitter_image_url; ?>">
</div>
<div class="mwb_twitter_direct_msg_success"><p class="notice notice-success is-dismissible"><?php _e('Message Successfully Send','mwb_twitter_for_wordpress'); ?></p></div>
<?php
if( !class_exists('Mwb_twitter_followres_list')){

	/**
	* Defining a class for listing followers using WP_List_Table
	*/
	class Mwb_twitter_followres_list extends WP_List_Table
	{
		
		public function __construct()
		{
			parent::__construct(array(
				'singular' => 'singular_form',
				'plural' => 'plural_form',
				'ajax' => true,
				'screen' =>null
				));
		}

		public function prepare_items()
		{

			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();
			$data = $this->table_data();
			$this->get_bulk_actions();
			$this->process_bulk_action();
			usort( $data, array( &$this, 'sort_data' ) );
			$perPage = 10;
			$currentPage = $this->get_pagenum();
			$totalItems = count($data);
			$this->set_pagination_args( array(
				'total_items' => $totalItems,
				'per_page'    => $perPage
				) );
			$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
			$this->_column_headers = array($columns, $hidden, $sortable);
			$this->items = $data;
			
		}

		/**
	     * Override the parent columns method. Defines the columns to use in your listing table
	     *
	     * @return Array
	     */
		public function get_columns()
		{
			$columns = array(
				'mwb_twitter_notification'		=> __('Twitter','mwb_twitter_for_wordpress'),
				'id'				=> __('ID','mwb_twitter_for_wordpress'),
				'image'         	=> __('Profile Image','mwb_twitter_for_wordpress'),
				'name'       		=> __('Follower\'s Name','mwb_twitter_for_wordpress'),
				'screen_name' 		=> __('Screen Name','mwb_twitter_for_wordpress'),
				'followers_count'	=> __('Follow Count','mwb_twitter_for_wordpress'),
				'created_date'    	=> __('Follow Creates','mwb_twitter_for_wordpress'),
				'ping_msg'			=> __('Send Message Directly','mwb_twitter_for_wordpress'),
				);
			return $columns;
		}

		/**
	     * Define which columns are hidden
	     *
	     * @return Array
	     */
		public function get_hidden_columns()
		{
			return array();
		}

		/**
	     * Define the sortable columns
	     *
	     * @return Array
	     */
		public function get_sortable_columns()
		{
			return array('name' => array('name', false));
		}


		/**
	     * Get the table data
	     *
	     * @return Array
	     */
		private function table_data()
		{
			$mwb_twitter_followers_data = get_option('mwb_twitter_all_followers_list',false);

			$mwb_twitter_followers_list_table = array();
			if( isset($mwb_twitter_followers_data) && (empty($mwb_twitter_followers_data) || $mwb_twitter_followers_data == '' ))
			{
				$mwb_twitter_consumer_details = get_option('mwb_twitter_consumer_app_details',false);
				if(is_array($mwb_twitter_consumer_details) && !empty($mwb_twitter_consumer_details))
				{
					$mwb_twitter_settings = array(
						'consumer_key' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appid'],
						'consumer_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appseceret'],
						'user_token' => $mwb_twitter_consumer_details['mwb_twitter_consumer_acess_token'],
						'user_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_token_secret'],
						'curl_ssl_verifypeer'   => false
						);
					$mwb_twitter_object = new tmhOAuth($mwb_twitter_settings);
					$postfields = array();
					$mwb_twitter_get_follower_lists = 'https://api.twitter.com/1.1/followers/list.json';
					$mwb_twitter_followers_response = $mwb_twitter_object->request('GET',$mwb_twitter_get_follower_lists,$postfields,true,true);
					$mwb_twitter_followers_response = json_decode($mwb_twitter_followers_response,true);

					if($mwb_twitter_followers_response['return_code'] == 200)
					{
						if(is_array($mwb_twitter_followers_response['users']) && !empty($mwb_twitter_followers_response['users'])){
							$mwb_twitter_followers_data = $mwb_twitter_followers_response['users'];
							update_option('mwb_twitter_all_followers_list',$mwb_twitter_followers_data);
						}
					}
				}
			}

			if(isset($mwb_twitter_followers_data) && is_array($mwb_twitter_followers_data) && !empty($mwb_twitter_followers_data)){
				foreach($mwb_twitter_followers_data as $follow_key => $follow_value){

					$mwb_twitter_followers_list_table[]= array(
						'id'			 => $follow_value['id'],
						'image'      	 => $follow_value['profile_image_url'],
						'name'       	 => $follow_value['name'],
						'screen_name' 	 => $follow_value['screen_name'],
						'followers_count'=> $follow_value['followers_count'],
						'created_date'   => $follow_value['created_at']
						);
				}
			}
			else
			{
				$mwb_twitter_followers_list_table = array();
			}
			return $mwb_twitter_followers_list_table;
		}

		 /**
	     * Allows you to sort the data by the variables set in the $_GET
	     *
	     * @return Mixed
	     */
		 private function sort_data( $a, $b )
		 {
		 	$orderby = 'name';
		 	$order = 'asc';
		 	if(!empty($_GET['orderby']))
		 	{
		 		$orderby = $_GET['orderby'];
		 	}
		 	if(!empty($_GET['order']))
		 	{
		 		$order = $_GET['order'];
		 	}
		 	$result = strcmp( $a[$orderby], $b[$orderby] );
		 	if($order === 'asc')
		 	{
		 		return $result;
		 	}
		 	return $result;
		 }

		/**
	     * Define what data to show on each column of the table
	     *
	     * @param  Array $item        Data
	     * @param  String $column_name - Current column name
	     *
	     * @return Mixed
	     */
		public function column_default( $item, $column_name )
		{
			switch( $column_name ) {
				case 'id':
				case 'name':
				case 'screen_name':
				case 'followers_count':
				case 'created_date':
				return $item[ $column_name ];
				default:
				return; 
			}
		}

		public function column_mwb_twitter_notification($item)
		{

			echo '<span data-twitter_user="'.$item['id'].'" class="mwb_twitter_direct_message_notify"><i class="dashicons dashicons-twitter"></i><span class="mwb_twitter_direct_msg_count"></span></span><div class="mwb_twitter_prodile_image" id="mwb_twitter_zoom_'.$item['id'].'"><div class="mwb_twitter_pro_image_popup"><span class="mwb_twitter_close_popup" id="mwb_twitter_close_'.$item['id'].'">X</span><img src="'.$item['image'].'" class="mwb_twitter_close_popup_img" id="mwb_twitter_user_img_'.$item['id'].'"></div></div>';
		}

		public function column_image($item)
		{
			echo '<img src="'. $item['image'].'" />';
		}
		public function column_ping_msg($item)
		{
			$message = __('Send Messages Directly To Your Followers','mwb_twitter_for_wordpress');
			$mwb_twitter_button = __('Send Message','mwb_twitter_for_wordpress');
			$mwb_twitter_sub_msg = __('Send messges you want directly fron here to your followers','mwb_twitter_for_wordpress');

			echo '<div class="mwb-popup-modal-wrapper" id="mwb_twitter_show_modal_'.$item['id'].'">
			<div class="wmb-popup-modal mwb_twitter_message_pop_up_zoom mwb_duration_popup">
				<span id="mwb_twitter_close_popup" data-followers_id="'.$item['id'].'">X</span>
				<div class="wmb-popup-modal-title">
					<div class="top-title-wrap">
						<p class="not-convinec-para">'.$message.'</p>
						<p class="mwb_visitor_demo">'.$mwb_twitter_sub_msg.'</p>
					</div>
				</div>
				<div class="wmb-popup-modal-modal-body">
					<textarea id="mwb_twitter_message_content_'.$item['id'].'" placeholder="Enter Your Message" class="textarea"></textarea>
					<div class="mwb-twitter-msg-send-button">
						<span class="dashicons dashicons-twitter"></span>
						<input value="'.$mwb_twitter_button.'" name="mwb_twitter_send_messages" type="button" class ="mwb-twitter-popup-button" id="mwb_twitter_send_messages" data-followers_id="'.$item['id'].'" data-followers_screen_name="'.$item['screen_name'].'">
					</div>
				</div>
			</div>
		</div>';
		return sprintf('<input type="button" class="mwb_twitter_followers_ping_msg button action" value="%s" data-followers_id="%s" />', __('Send Message','mwb_twitter_for_wordpress'),$item['id']);
	}

		/**
		 * Returns an associative array containing the bulk action
		 *
		 * @return array
		 */
		public function get_bulk_actions() 
		{	
			$actions['update'] = __('Get New Followers','mwb_twitter_for_wordpress');
			return $actions;
		}

		public function process_bulk_action() 
		{

			$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
			$action = $wp_list_table->current_action();
			if(isset($_POST['mwb_twitter_followers_send_msg'])){
				$mwb_twitter_follower_id = $_POST['mwb_twitter_followers_send_msg'];
			}
			switch ($action) {
				case 'update': $this->mwb_twitter_get_all_followers();
				break;
				default:
				return;
			}
		}

		public function mwb_twitter_get_all_followers()
		{
			$mwb_twitter_consumer_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_consumer_details) && !empty($mwb_twitter_consumer_details))
			{
				$mwb_twitter_settings = array(
					'consumer_key' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_consumer_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);
				$mwb_twitter_object = new tmhOAuth($mwb_twitter_settings);
				$postfields = array();
				$mwb_twitter_get_follower_lists = 'https://api.twitter.com/1.1/followers/list.json';
				$mwb_twitter_followers_response = $mwb_twitter_object->request('GET',$mwb_twitter_get_follower_lists,$postfields,true,true);
				$mwb_twitter_followers_response = json_decode($mwb_twitter_followers_response,true);

				if($mwb_twitter_followers_response['return_code'] == 200){
					
					if(is_array($mwb_twitter_followers_response['users']) && !empty($mwb_twitter_followers_response['users'])){
						$mwb_twitter_followers_data = $mwb_twitter_followers_response['users'];
						update_option('mwb_twitter_all_followers_list',$mwb_twitter_followers_data);
					}
				}
			}
		}
	}
}