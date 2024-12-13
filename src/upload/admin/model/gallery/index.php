<?php 
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ModelGalleryIndex extends Model {
	/**
	 * Get albums list
	 */
	public function getAlbums(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "album");
		foreach ($query->rows as $key => $value) {
			$query->rows[$key]['album_data'] = json_decode($value['album_data'], true);
		}
		return $query->rows;
	}
	/**
	 * Get album
	 */
	public function getAlbum($album_id) {		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "album WHERE `album_id` = '".(int)$album_id."' ORDER BY `sort_order`");
		$query->rows[0]['album_data'] = json_decode($query->rows[0]['album_data'], true);
		$album = $query->rows[0];

		$seo_query = $this->db->query("SELECT * FROM `". DB_PREFIX . "url_alias_gallery` WHERE `query` = 'album_id=" . $album['album_id'] . "' LIMIT 1");

		if (!empty($seo_query->row)) {
			$album['album_data']['album_seo_url'] = $seo_query->row['keyword'];
		}
		
		// $store_query = $this->db->query("SELECT store_id FROM `". DB_PREFIX . "album_to_store` WHERE `album_id` = " . $album['album_id']);
		// var_dump($album['album_data']['stores']);
		// if ($store_query->num_rows) {
		// 	foreach ($store_query->rows as $key => $value) {
		// 		$album['album_data']['stores'][] = (int)$value;
		// 	}
		// }else{
		// 	$album['album_data']['stores'] = array();
		// }
		return $album;
	}
	/**
	 * Edit album and seo url
	 */
	public function editAlbum($data) {
		// unset($data['stores']);
		$album_data = $data['album_data']; //save this variable for work with seo
		if (!empty($album_data['photos_limit'])) {
			$data['album_data']['photos_limit'] = (int)$album_data['photos_limit'];			
		}else{
			$data['album_data']['photos_limit'] = 0;
		}
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->db->escape(json_encode($value));
			}else{
				$data[$key] = $this->db->escape($value);
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX ."album` SET 
		`album_type` = '".$data['album_type']."',
		`enabled` = '".$data['enabled']."',
		`sort_order` = '".$data['sort_order']."',
		`last_modified` = '".date('Y-m-d H:i:s')."',
		`album_data` = '".$data['album_data']."'
		 WHERE `album_id` = ".(int)$data['album_id']);

		// Adding SEO URL
		// Get album_id
		$album_id = (int)$data['album_id'];

		// Get seo query
		$seo_query = $this->db->query("SELECT * FROM `". DB_PREFIX . "url_alias_gallery` WHERE `query` = 'album_id=".(int)$album_id."' LIMIT 1");

		// (Check) and (update if exists) or (delete if exists and empty)
		if (empty($seo_query->row)) {
			if ($album_data['album_seo_url']) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`) VALUES ('album_id=" . (int)$album_id . "', '" . $this->db->escape($album_data['album_seo_url']) . "')");
			}
		}else{
			if (empty($album_data['album_seo_url'])) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_gallery` WHERE `url_alias_id` = '".$seo_query->row['url_alias_id']."'");
			}else{
				$this->db->query("UPDATE `" . DB_PREFIX . "url_alias_gallery` SET `query` = 'album_id=" . (int)$album_id . "', keyword = '" . $this->db->escape($album_data['album_seo_url']) . "' WHERE `url_alias_id` = '".$seo_query->row['url_alias_id']."'");
			}
		}

		$store_query = $this->db->query("DELETE FROM `". DB_PREFIX . "album_to_store` WHERE `album_id` = " . (int)$album_id);
		
		foreach ($album_data['stores'] as $key => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "album_to_store` (`album_id`, `store_id`) VALUES (" . (int)$album_id . ", " . (int)$value . ")");
		}
	}
	/**
	 * Add album and seo url
	 */
	public function addAlbum($data) {
		$album_data = $data['album_data']; //save this variable for work with seo
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->db->escape(json_encode($value));
			}else{
				$data[$key] = $this->db->escape($value);
			}
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX ."album`(
			`album_type`, 
			`enabled`, 
			`sort_order`, 
			`last_modified`, 
			`album_data`) VALUES (
			'".$data['album_type']."',
			'".$data['enabled']."',
			'".$data['sort_order']."',
			'".date('Y-m-d H:i:s')."',
			'".$data['album_data']."'
			)");
		
		// Adding SEO URL
		if (!empty($album_data['album_seo_url'])) {
			$album_query = $this->db->query("SELECT `album_id` FROM `". DB_PREFIX ."album` ORDER BY `album_id` DESC LIMIT 1");
			$album_id = $album_query->row['album_id'];
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`) VALUES ('album_id=" . (int)$album_id . "', '" . $this->db->escape($album_data['album_seo_url']) . "')");
		}
		
	}
	/**
	 * Copy albums
	 */
	public function copyAlbum($aids, $name_postfix) {
		foreach ($aids as $key => $aid) {
			$data = $this->getAlbum((int)$aid);
			foreach ($data['album_data']['album_name'] as $key => $value) {
				$data['album_data']['album_name'][$key] .= $name_postfix;
			}
			$data['album_data']['album_seo_url'] = '';

			$this->addAlbum($data);
		}
	}
	/**
	 * Delete album from `album`
	 * and delete url alias of album from `url_alias`
	 */
	public function deleteAlbum($aids) {
		foreach ($aids as $key => $aid) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_gallery` WHERE `query` = 'album_id=".(int)$aid."'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "album` WHERE `album_id` = '" . (int)$aid . "'");
		}
	}
	/**
	 * Function from OcStore for OpenCart to get AllCategories
	 */
	public function getAllCategories() {
		$category_data = $this->cache->get('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));

		if (!$category_data || !is_array($category_data)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ORDER BY c.parent_id, c.sort_order, cd.name");

			$category_data = array();
			foreach ($query->rows as $row) {
				$category_data[$row['parent_id']][$row['category_id']] = $row;
			}

			$this->cache->set('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $category_data);
		}

		return $category_data;
	}

	public function clearCache(){
		$this->cache->delete('gallery_photos');		
		$this->cache->delete('gallery_gallery');		
		$this->cache->delete('gallery_module');		
		$this->cache->delete('seo_pro_gallery');
	}
}	
?>