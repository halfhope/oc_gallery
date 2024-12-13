<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGallery extends Controller {

	public 	$_route 		= 'extension/module/gallery';
	public 	$_model 		= 'model_extension_module_gallery';

	private $error = [];

	public function index() {
		if (!isset($this->request->get['module_id'])) {
			$this->response->redirect($this->url->link($this->_route . '/gallery', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->response->redirect($this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL'));
		}
	}

	public function install() {
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->_route . '/gallery');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->_route . '/gallery');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->_route . '/settings');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->_route . '/settings');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->_route . '/modules');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->_route . '/modules');

		$this->language->load($this->_route);

		$sql_layout_names = [
			$this->_route . '/galleries' 	=> $this->language->get('text_layout_galleries'),
			$this->_route . '/gallery' 	=> $this->language->get('text_layout_gallery'),
			'error/not_found' 	=> $this->language->get('text_layout_not_found')
		];

		$sql[] = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."gallery` (
			`gallery_id` int(11) NOT NULL AUTO_INCREMENT,
			`gallery_type` int(11) NOT NULL,
			`enabled` int(11) NOT NULL,
			`sort_order` int(11) NOT NULL,
			`last_modified` datetime NOT NULL,
			`gallery_data` text NOT NULL,
			PRIMARY KEY (`gallery_id`)
		) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."gallery_to_store` (
			`gallery_id` int(11) NOT NULL,
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
		$default_store[] = [
			'store_id' => 0
		];
		$stores = array_merge($default_store, $this->model_setting_store->getStores());
		$settings = [];

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			$store_id = $store['store_id'];
			$settings['galleries_seo_name'][$store_id]			= 'gallery';
			$settings['galleries_include_seo_path'][$store_id]	= '1';
			$settings['cover_image_height'][$store_id]			= '228';
			$settings['cover_image_width'][$store_id]			= '228';
			$settings['show_counter'][$store_id]				= '1';
			$settings['show_description'][$store_id]			= '0';
			$settings['bootstrap_grid'][$store_id]				= ['xs' => 12,'sm' => 6, 'md' => 4,'lg' => 3];
			foreach ($languages as $language) {
				$settings['galleries_title'][$store_id][$language['language_id']]				= '';
				$settings['galleries_breadcrumb'][$store_id][$language['language_id']]			= '';
				$settings['galleries_h1_title'][$store_id][$language['language_id']]			= '';
				$settings['galleries_meta_keywords'][$store_id][$language['language_id']]		= '';
				$settings['galleries_meta_description'][$store_id][$language['language_id']]	= '';
				$settings['galleries_description'][$store_id][$language['language_id']]			= '';
			}
			$sql[] = "INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`, `store_id`) VALUES ('" . $this->_route . "/galleries', 'galleries', " . (int) $store['store_id'] . ")";
		}

		$sql_values = [];
		foreach ($settings as $key => $value) {
			$setting = $this->db->escape(json_encode($value));
			$sql_values[] = "(0, 'config_gallery', 'config_gallery_{$key}', '{$setting}', 1)";
			unset($settings[$key]);
		}
		
		$settings['include_colorbox'] = '1';
		$settings['include_fancybox'] = '1';
		$settings['include_magnific_popup'] = '1';
		$settings['include_jstabs'] = '1';
		$settings['include_lightbox'] = '1';
		$settings['include_lazyload'] = '1';
		$settings['modules_cache_enabled'] = '0';

		foreach ($settings as $key => $value) {
			$setting = $this->db->escape(json_encode($value));
			$sql_values[] = "(0, 'config_gallery', 'config_gallery_{$key}', '{$setting}', 0)";
		}

		$sql[] = "INSERT INTO `". DB_PREFIX ."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES " . implode(',', $sql_values) . ';';

		foreach ($sql as $key => $value) {
			$this->db->query($value);
		}

		foreach ($sql_layout_names as $key => $value) {
			$result = $this->db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout_route` WHERE `route` = '".$value."'");
			if (!$result->num_rows) {
				$this->db->query("INSERT INTO `". DB_PREFIX ."layout` (`name`) VALUES ('".$value."')");

				$layout_id = $this->db->getLastId();

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
	}

	public function uninstall() {
		$this->language->load($this->_route);

		$sql_layout_names = [
			$this->_route . '/galleries' => $this->language->get('text_layout_galleries'), 
			$this->_route . '/gallery' => $this->language->get('text_layout_gallery')
		];
		foreach ($sql_layout_names as $key => $value) {
			$result = $this->db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout_route` WHERE `route` = '".$key."'");
			if (isset($result->row['layout_id'])) {
				$layout_id = $result->row['layout_id'];
				$this->db->query("DELETE FROM `". DB_PREFIX . "layout` WHERE `layout_id` = '".$layout_id."'");
				$this->db->query("DELETE FROM `". DB_PREFIX . "layout_route` WHERE `route` = '".$key."'");
			}
		}

		$this->load->model('user/user_group');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', $this->_route . '/gallery');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', $this->_route . '/gallery');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', $this->_route . '/settings');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', $this->_route . '/settings');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', $this->_route . '/modules');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', $this->_route . '/modules');

		$this->load->model('extension/extension');
		$this->model_extension_extension->uninstall('module','gallery');

		$this->load->model('extension/module');
		$this->model_extension_module->deleteModulesByCode('gallery');

		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."url_alias_gallery`";
		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."gallery`";
		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."gallery_to_store`";
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `code` = 'config_gallery'";
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `code` = 'gallery_module'";

		$this->load->model($this->_route);
		$this->{$this->_model}->clearCache();

		foreach ($sql as $key => $value) {
			$query = $this->db->query($value);
		}
	}
}
