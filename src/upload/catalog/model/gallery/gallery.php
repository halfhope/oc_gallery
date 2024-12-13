<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ModelGalleryGallery extends Model {
	private $current_language_id;
	private $current_store_id;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->current_language_id = $this->config->get('config_language_id');
		$this->current_store_id = $this->config->get('config_store_id');
	}

	public function getGallery($gallery_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery WHERE `gallery_id` = ".(int)$gallery_id);
		if (!empty($query->row)) {
			$query->row['gallery_data'] = json_decode($query->row['gallery_data'], true);
		}
		return $query->row;
	}

	public function getGalleries() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery a LEFT JOIN " . DB_PREFIX . "gallery_to_store a2c ON a.gallery_id = a2c.gallery_id WHERE `enabled` = '1' AND a2c.store_id = " . $this->current_store_id . " ORDER BY `sort_order`");
		foreach ($query->rows as $key => $value) {
			$query->rows[$key]['gallery_data'] = json_decode($value['gallery_data'], true);
		}
		return $query->rows;
	}

	public function getGalleryImages($gallery_id, $page = 1, $limit = 0) {
		$this->load->model('tool/image');

		$start = ($page - 1) * $limit;
		$limit = $limit + $start;

		$gallery = $this->getgallery($gallery_id);

		$gallery_photos = array();
		switch ($gallery['gallery_type']) {
			case 0: //Category
				$this->load->model('catalog/category');
				$this->load->model('catalog/product');

				foreach ($gallery['gallery_data']['gallery_categories'] as $category_id) {
					$products = $this->model_catalog_category->getCategories($category_id);
					$data = array(
						'filter_category_id' => $category_id
					);

					$products = $this->model_catalog_product->getProducts($data);

					foreach ($products as $product){
						if ($product['image']) {
							$key = md5($product['image']);
							$gallery_photos[$key]['image']    = $product['image'];
							$gallery_photos[$key]['title']    = $product['name'];
						}
						if ($gallery['gallery_data']['include_additional_images']) {
							$images = $this->model_catalog_product->getProductImages($product['product_id']);
							if (!empty($images)) {
								foreach ($images as $image) {
									$key = md5($image['image']);
									$gallery_photos[$key]['image']    = $image['image'];
									$gallery_photos[$key]['title']    = $product['name'];
								}
							}
						}
					}
				}
				break;
			case 1: //Directory
				foreach (explode(PHP_EOL, $gallery['gallery_data']['gallery_directory']) as $directory) {
					if (!empty($directory)) {

					$data = glob($directory);
					$data = array_filter($data, 'is_file');
						if (!empty($data)) {
							foreach ($data as $value) {
								$key = md5($value);
								$value = str_replace(DIR_IMAGE, '', $value);
								if ($value{0} == 'i') {
									$value = str_replace('image/', '', $value);
								}
								$gallery_photos[$key]['image'] = $value;
								$gallery_photos[$key]['title'] = '';
							}
						}
					}
				}
				break;
			case 2: //Custom images
				reset($gallery_photos);
				if (!empty($gallery['gallery_data']['gallery_images'])) {
					foreach ($gallery['gallery_data']['gallery_images'] as $gallery_image) {
						$key = md5($gallery_image['image'].$gallery_image['id']);
						$gallery_photos[$key]['image'] = $gallery_image['image'];
						$gallery_photos[$key]['title'] = $gallery_image['description'][$this->current_language_id];
					}
				}
			break;
		}
		//Limit photos
		$result = array();

		if (($page == 1) && ($limit == 0)) {
			$result = $gallery_photos;
		}else{
			reset($gallery_photos);
			for ($counter = 0; $counter < $limit; $counter ++) {
				if ($counter < $start) {
					next($gallery_photos);
				}else{
					$elem = current($gallery_photos);
					if (!empty($elem)) {
						$result[] = $elem;
						next($gallery_photos);
					}else{
						break;
					}
				}
			}
		}
		  return $result;
	}
}