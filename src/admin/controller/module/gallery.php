<?php 
class ControllerModuleGallery extends Controller {

	private $error = array();
	
	public function index() {
		$this->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
	}
		
	public function install(){
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'gallery/album');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'gallery/album');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'gallery/settings');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'gallery/settings');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'gallery/modules');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'gallery/modules');

		$this->language->load('gallery/index');
		
		$sql_layout_names = array(
			'gallery/gallery' 	=> $this->language->get('text_layout_galleries'), 
			'gallery/photos' 	=> $this->language->get('text_layout_photos'), 
			'error/not_found' 	=> $this->language->get('text_layout_not_found')
		);

		$sql[] = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."album` (
		  `album_id` int(11) NOT NULL AUTO_INCREMENT,
		  `album_type` int(11) NOT NULL,
		  `enabled` int(11) NOT NULL,
		  `sort_order` int(11) NOT NULL,
		  `last_modified` datetime NOT NULL,
		  `album_data` text NOT NULL,
		  PRIMARY KEY (`album_id`)
		) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."album_to_store` (
		  `album_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL
		) DEFAULT CHARSET=utf8;";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "url_alias_gallery` (
		  `url_alias_id` int(11) NOT NULL AUTO_INCREMENT,
		  `query` varchar(255) NOT NULL,
		  `keyword` varchar(255) NOT NULL,
		  `store_id` int(11) NOT NULL,
		  PRIMARY KEY (`url_alias_id`),
		  KEY `query` (`query`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		$this->load->model('setting/store');
		$default_store[] = array(
			'store_id' => 0
		);
		$stores = array_merge($default_store, $this->model_setting_store->getStores());
		$settings = array();
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			$settings['config_gallery_galleries_seo_name'][$store['store_id']]			= 'gallery';
			$settings['config_gallery_galleries_include_seo_path'][$store['store_id']]	= '1';
			$settings['config_gallery_cover_image_height'][$store['store_id']]			= '228';
			$settings['config_gallery_cover_image_width'][$store['store_id']]			= '228';
			$settings['config_gallery_show_counter'][$store['store_id']]					= '1';
			$settings['config_gallery_show_description'][$store['store_id']]				= '0';
			$settings['config_gallery_number_of_columns_xs'][$store['store_id']]			= '1';
			$settings['config_gallery_number_of_columns_sm'][$store['store_id']]			= '2';
			$settings['config_gallery_number_of_columns_md'][$store['store_id']]			= '4';
			$settings['config_gallery_number_of_columns_lg'][$store['store_id']]			= '4';
			foreach ($languages as $language) {	
				$settings['config_gallery_galleries_title'][$store['store_id']][$language['language_id']]				= '';
				$settings['config_gallery_galleries_breadcrumb'][$store['store_id']][$language['language_id']]			= '';
				$settings['config_gallery_galleries_h1_title'][$store['store_id']][$language['language_id']]			= '';
				$settings['config_gallery_galleries_meta_keywords'][$store['store_id']][$language['language_id']]		= '';
				$settings['config_gallery_galleries_meta_description'][$store['store_id']][$language['language_id']]	= '';
				$settings['config_gallery_galleries_description'][$store['store_id']][$language['language_id']]			= '';
			}
			$sql[] = "INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`, `store_id`) VALUES ('gallery/gallery', 'gallery', " . $store['store_id'] . ")";		 
		}
		$config_gallery_galleries_seo_name			= $this->db->escape(serialize($settings['config_gallery_galleries_seo_name']));
		$config_gallery_galleries_include_seo_path	= $this->db->escape(serialize($settings['config_gallery_galleries_include_seo_path']));
		$config_gallery_cover_image_height			= $this->db->escape(serialize($settings['config_gallery_cover_image_height']));
		$config_gallery_cover_image_width			= $this->db->escape(serialize($settings['config_gallery_cover_image_width']));
		$config_gallery_show_counter				= $this->db->escape(serialize($settings['config_gallery_show_counter']));
		$config_gallery_show_description			= $this->db->escape(serialize($settings['config_gallery_show_description']));
		$config_gallery_number_of_columns_xs		= $this->db->escape(serialize($settings['config_gallery_number_of_columns_xs']));
		$config_gallery_number_of_columns_sm		= $this->db->escape(serialize($settings['config_gallery_number_of_columns_sm']));
		$config_gallery_number_of_columns_md		= $this->db->escape(serialize($settings['config_gallery_number_of_columns_md']));
		$config_gallery_number_of_columns_lg		= $this->db->escape(serialize($settings['config_gallery_number_of_columns_lg']));
		$config_gallery_galleries_title				= $this->db->escape(serialize($settings['config_gallery_galleries_title']));
		$config_gallery_galleries_breadcrumb		= $this->db->escape(serialize($settings['config_gallery_galleries_breadcrumb']));
		$config_gallery_galleries_h1_title			= $this->db->escape(serialize($settings['config_gallery_galleries_h1_title']));
		$config_gallery_galleries_meta_keywords		= $this->db->escape(serialize($settings['config_gallery_galleries_meta_keywords']));
		$config_gallery_galleries_meta_description	= $this->db->escape(serialize($settings['config_gallery_galleries_meta_description']));
		$config_gallery_galleries_description		= $this->db->escape(serialize($settings['config_gallery_galleries_description']));

		//delete old settings
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `group` = 'gallery_settings'";
		// default settings
		$sql[] = "INSERT INTO `". DB_PREFIX ."setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES
		(0, 'config_gallery', 'config_gallery_include_colorbox', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_fancybox', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_magnific_popup', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_jstabs', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_lightbox', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_magnific_popup', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_lazyload', '1', 0),
		(0, 'config_gallery', 'config_gallery_include_bootstrap', '0', 0),
		(0, 'config_gallery', 'config_gallery_modules_cache_enabled', '0', 0),
		
		(0, 'config_gallery', 'config_gallery_galleries_seo_name', 			'$config_gallery_galleries_seo_name', 1),
		(0, 'config_gallery', 'config_gallery_galleries_include_seo_path', 	'$config_gallery_galleries_include_seo_path', 1),
		(0, 'config_gallery', 'config_gallery_cover_image_height', 			'$config_gallery_cover_image_height', 1),
		(0, 'config_gallery', 'config_gallery_cover_image_width', 			'$config_gallery_cover_image_width', 1),
		(0, 'config_gallery', 'config_gallery_show_counter', 				'$config_gallery_show_counter', 1),
		(0, 'config_gallery', 'config_gallery_show_description', 			'$config_gallery_show_description', 1),	
		(0, 'config_gallery', 'config_gallery_number_of_columns_xs', 		'$config_gallery_number_of_columns_xs', 1),
		(0, 'config_gallery', 'config_gallery_number_of_columns_sm', 		'$config_gallery_number_of_columns_sm', 1),
		(0, 'config_gallery', 'config_gallery_number_of_columns_md', 		'$config_gallery_number_of_columns_md', 1),
		(0, 'config_gallery', 'config_gallery_number_of_columns_lg', 		'$config_gallery_number_of_columns_lg', 1),
		(0, 'config_gallery', 'config_gallery_galleries_title', 			'$config_gallery_galleries_title', 1),
		(0, 'config_gallery', 'config_gallery_galleries_breadcrumb', 		'$config_gallery_galleries_breadcrumb', 1),
		(0, 'config_gallery', 'config_gallery_galleries_h1_title', 			'$config_gallery_galleries_h1_title', 1),
		(0, 'config_gallery', 'config_gallery_galleries_meta_keywords', 	'$config_gallery_galleries_meta_keywords', 1),
		(0, 'config_gallery', 'config_gallery_galleries_meta_description', 	'$config_gallery_galleries_meta_description', 1),
		(0, 'config_gallery', 'config_gallery_galleries_description', 		'$config_gallery_galleries_description', 1);";
		

		foreach ($sql_layout_names as $key => $value) {
			$result = $this->db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout_route` WHERE `route` = '" . $key . "'");
			if (!$result->num_rows) {				
				$this->db->query("INSERT INTO `". DB_PREFIX ."layout` (`name`) VALUES ('".$value."')");
				$result = $this->db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout` WHERE `name` = '".$value."'");
				
				$layout_id = $result->row['layout_id'];
				
				if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
					$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $this->db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
				} else {
					$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
				}
				if ($store_query->num_rows) {
					$store_id = $store_query->row['store_id'];
				} else {
					$store_id = 0;
				}
				
				$this->db->query("INSERT INTO `".DB_PREFIX."layout_route` (`layout_id`, `store_id`, `route`) VALUES ('".$layout_id."','".$store_id."','".$key."')");
			}
		}
		
		// exec sql
		foreach ($sql as $key => $value) {
			$query = $this->db->query($value);
		}
		
		unset($sql);
		$sql = array();
		
		$module_layouts = array(
			'product/category' => 	'config_gallery_module_category_layout_id',
		 	'product/product' => 	'config_gallery_module_product_layout_id', 
		 	'error/not_found' => 	'config_gallery_module_hook_layout_id'
		);

		foreach ($module_layouts as $route => $config) {
			$result = $this->db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout_route` WHERE `route` = '" . $route . "'");
			if ($result->num_rows) {

				$layout_id = $result->row['layout_id'];
				$sql[] = "INSERT INTO `". DB_PREFIX ."setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES
				(0, 'config_gallery', '" . $config . "', '" . $layout_id . "', 0)";

			}
		}

		// exec sql
		foreach ($sql as $key => $value) {
			$query = $this->db->query($value);
		}
		
		
	}
	public function uninstall(){
		$this->language->load('gallery/index');

		$sql_layout_names = array('gallery/gallery' => $this->language->get('text_layout_galleries'), 'gallery/photos' => $this->language->get('text_layout_photos'));
		foreach ($sql_layout_names as $key => $value) {
			$result = $this->db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout_route` WHERE `route` = '" . $key . "'");
			if (isset($result->row['layout_id'])) {
				$layout_id = $result->row['layout_id'];
				$this->db->query("DELETE FROM `". DB_PREFIX . "layout` WHERE `layout_id` = '".$layout_id."'");
				$this->db->query("DELETE FROM `". DB_PREFIX . "layout_route` WHERE `route` = '".$key."'");
			}
		}
		
		$this->load->model('user/user_group');

		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."url_alias_gallery`";
		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."album`";
		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."album_to_store`";
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `group` = 'config_gallery'";
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `group` = 'gallery_module'";

		$this->load->model('gallery/index');
		$this->model_gallery_index->clearCache();

		foreach ($sql as $key => $value) {
			$query = $this->db->query($value);
		}	
	}
}
?>