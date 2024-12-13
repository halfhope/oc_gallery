<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ModelExtensionModuleGallery extends Model {

	public 	$_route 		= 'extension/module/gallery';

	public function getGalleries() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery ORDER BY `sort_order`");
		foreach ($query->rows as $key => $value) {
			$query->rows[$key]['gallery_data'] = json_decode($value['gallery_data'], true);
		}
		return $query->rows;
	}

	public function getGallery($gallery_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery WHERE `gallery_id` = '".(int) $gallery_id."' ORDER BY `sort_order`");
		$query->rows[0]['gallery_data'] = json_decode($query->rows[0]['gallery_data'], true);
		$gallery = $query->rows[0];

		$seo_query = $this->db->query("SELECT * FROM `". DB_PREFIX . "url_alias_gallery` WHERE `query` = 'gallery_id=" . (int) $gallery['gallery_id'] . "' LIMIT 1");

		if (!empty($seo_query->row)) {
			$gallery['gallery_data']['gallery_seo_url'] = $seo_query->row['keyword'];
		}

		return $gallery;
	}

	public function editGallery($data) {
		$gallery_data = $data['gallery_data']; //save this variable for work with seo
		if (!empty($gallery_data['photos_limit'])) {
			$data['gallery_data']['photos_limit'] = (int)$gallery_data['photos_limit'];
		} else {
			$data['gallery_data']['photos_limit'] = 0;
		}
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->db->escape(json_encode($value));
			} else {
				$data[$key] = $this->db->escape($value);
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX ."gallery` SET
			`gallery_type` = '".$data['gallery_type']."',
			`enabled` = '".$data['enabled']."',
			`sort_order` = '".$data['sort_order']."',
			`last_modified` = '".date('Y-m-d H:i:s')."',
			`gallery_data` = '".$data['gallery_data']."'
		WHERE `gallery_id` = ".(int)$data['gallery_id']);

		// Adding SEO URL
		$gallery_id = (int)$data['gallery_id'];

		$seo_query = $this->db->query("SELECT * FROM `". DB_PREFIX . "url_alias_gallery` WHERE `query` = 'gallery_id=" . (int) $gallery_id . "' LIMIT 1");

		if (empty($seo_query->row)) {
			if ($gallery_data['gallery_seo_url']) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`) VALUES ('gallery_id=" . (int) $gallery_id . "', '" . $this->db->escape($gallery_data['gallery_seo_url']) . "')");
			}
		} else {
			if (empty($gallery_data['gallery_seo_url'])) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_gallery` WHERE `url_alias_id` = '" . $seo_query->row['url_alias_id'] . "'");
			} else {
				$this->db->query("UPDATE `" . DB_PREFIX . "url_alias_gallery` SET `query` = 'gallery_id=" . (int) $gallery_id . "', keyword = '" . $this->db->escape($gallery_data['gallery_seo_url']) . "' WHERE `url_alias_id` = '" . (int) $seo_query->row['url_alias_id'] . "'");
			}
		}

		$this->db->query("DELETE FROM `". DB_PREFIX . "gallery_to_store` WHERE `gallery_id` = " . (int) $gallery_id);

		foreach ($gallery_data['stores'] as $key => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "gallery_to_store` (`gallery_id`, `store_id`) VALUES (" . (int) $gallery_id . ", " . (int) $value . ")");
		}
	}

	public function addGallery($data) {
		$gallery_data = $data['gallery_data']; // Save this variable for work with seo
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->db->escape(json_encode($value));
			} else {
				$data[$key] = $this->db->escape($value);
			}
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX ."gallery`(
			`gallery_type`,
			`enabled`,
			`sort_order`,
			`last_modified`,
			`gallery_data`) VALUES (
			'" . $data['gallery_type'] . "',
			'" . $data['enabled'] . "',
			'" . $data['sort_order'] . "',
			'" . date('Y-m-d H:i:s') . "',
			'" . $data['gallery_data'] . "'
			)");

		$gallery_id = $this->db->getLastId();

		if (!empty($gallery_data['gallery_seo_url'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`) VALUES ('gallery_id=" . (int) $gallery_id . "', '" . $this->db->escape($gallery_data['gallery_seo_url']) . "')");
		}

		foreach ($gallery_data['stores'] as $key => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "gallery_to_store` (`gallery_id`, `store_id`) VALUES (" . (int) $gallery_id . ", " . (int) $value . ")");
		}
	}

	public function copyGallery($aids, $name_postfix) {
		foreach ($aids as $key => $aid) {
			$data = $this->getGallery((int)$aid);
			foreach ($data['gallery_data']['gallery_name'] as $key => $value) {
				$data['gallery_data']['gallery_name'][$key] .= $name_postfix;
			}
			$data['gallery_data']['gallery_seo_url'] = '';

			$this->addGallery($data);
		}
	}

	public function deleteGallery($aids) {
		foreach ($aids as $key => $aid) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_gallery` WHERE `query` = 'gallery_id=" . (int) $aid . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "gallery` WHERE `gallery_id` = '" . (int) $aid . "'");
		}
	}

	public function updateGallerySeoUrl($data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_gallery` WHERE `query` = '" . $this->_route . "'");

		foreach ($data['config_gallery_galleries_seo_name'] as $store_id => $alias) {
			if (!empty($data['config_gallery_galleries_seo_name'][$store_id])) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`, `store_id`) VALUES ('" . $this->_route . "', '" . $this->db->escape($alias) . "', " . (int) $store_id . ")");
			}
		}
	}

	public function getAllCategories() {
		$category_data = $this->cache->get('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));

		if (!$category_data || !is_array($category_data)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'  ORDER BY c.parent_id, c.sort_order, cd.name");

			$category_data = [];
			foreach ($query->rows as $row) {
				$category_data[$row['parent_id']][$row['category_id']] = $row;
			}

			$this->cache->set('category.all.' . $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id'), $category_data);
		}

		return $category_data;
	}

	public function clearCache() {
		$this->cache->delete('gallery_photos');
		$this->cache->delete('gallery_gallery');
		$this->cache->delete('gallery_module');
		$this->cache->delete('seo_pro_gallery');
	}
}
