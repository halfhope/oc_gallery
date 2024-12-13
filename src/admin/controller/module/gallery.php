<?php 
class ControllerModuleGallery extends Controller {

	private $error = array();
	
	public function index() {
		$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
	}
	public function install(){
		$this->language->load('album/index');
		$sql_layout_names = array($this->language->get('text_layout_galleries'), $this->language->get('text_layout_photos'));

		$sql[] = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."albums` (
		  `album_id` int(11) NOT NULL AUTO_INCREMENT,
		  `album_type` int(11) NOT NULL,
		  `enabled` int(11) NOT NULL,
		  `sort_order` int(11) NOT NULL,
		  `album_data` text NOT NULL,
		  PRIMARY KEY (`album_id`)
		) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		// demo data
		$sql[] = "INSERT INTO `". DB_PREFIX ."setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES
		(0, 'gallery_settings', 'config_gallery_include_fancybox', '0', 0),
		(0, 'gallery_settings', 'config_gallery_include_lightbox', '0', 0),
		(0, 'gallery_settings', 'config_gallery_include_colorbox', '0', 0),
		(0, 'gallery_settings', 'config_gallery_include_jstabs', '0', 0),
		(0, 'gallery_settings', 'config_gallery_show_counter', '1', 0),
		(0, 'gallery_settings', 'config_gallery_cover_image_height', '150', 0),
		(0, 'gallery_settings', 'config_gallery_cover_image_width', '200', 0),
		(0, 'gallery_settings', 'config_gallery_modules_cache_enabled', '0', 0);";

		foreach ($sql as $key => $value) {
			$query = $this->db->query($value);
		}

		$sq2 = array();
		foreach ($sql_layout_names as $key => $value) {
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
			
			$this->db->query("INSERT INTO `".DB_PREFIX."layout_route` (`layout_id`, `store_id`, `route`) VALUES ('".$layout_id."','".$store_id."','".$value."')");
		}
		
	}
	public function uninstall(){
		$this->language->load('album/index');
		$sql_layout_names = array($this->language->get('text_layout_galleries'), $this->language->get('text_layout_photos'));
		foreach ($sql_layout_names as $key => $value) {
			$result = $db->query("SELECT `layout_id` FROM `". DB_PREFIX . "layout` WHERE `name` = '".$value."'");
			$layout_id = $result->row['layout_id'];
			$db->query("DELETE FROM `". DB_PREFIX . "layout` WHERE `layout_id` = '".$layout_id."'");
			$db->query("DELETE FROM `". DB_PREFIX . "layout_route` WHERE `layout_id` = '".$layout_id."'");
		}
		$sql[] = "DROP TABLE IF EXISTS `". DB_PREFIX ."albums`";
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `group` = 'gallery_settings'";
		$sql[] = "DELETE FROM `". DB_PREFIX ."setting` WHERE `group` = 'gallery_module'";

		$this->cache->delete('gallery_album_photos');		
		$this->cache->delete('album_photos');		
		$this->cache->delete('album_module');		
				
		foreach ($sql as $key => $value) {
			$query = $this->db->query($value);
		}	
	}
}
?>