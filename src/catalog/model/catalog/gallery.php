<?php 
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ModelCatalogGallery extends Model {
	public function getAlbum($album_id) {		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "albums WHERE `album_id` = '".(int)$album_id."' ORDER BY `sort_order`");
		if (!empty($query->row)) {
			$query->row['album_data'] = json_decode($query->row['album_data'], true);
		}
		return $query->row;
	}
	public function getAlbums(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "albums WHERE `enabled` = '1' ORDER BY `sort_order`");
		foreach ($query->rows as $key => $value) {
			$query->rows[$key]['album_data'] = json_decode($value['album_data'], true);
		}
		return $query->rows;
	}
	public function getAlbumImages($album_id, $page = 1, $limit = 0){
        $this->load->model('tool/image');
        
        $start = ($page - 1) * $limit;
        $limit = $limit + $start;

        $album = $this->getAlbum($album_id);

        $album_photos = array();
        switch ($album['album_type']) {
            case 0: //Category
        		$this->load->model('catalog/category');
        		$this->load->model('catalog/product');
                
                foreach ($album['album_data']['album_categories'] as $category_id) {
                    $products = $this->model_catalog_category->getCategories($category_id);
                    $data = array(
                        'filter_category_id' => $category_id
                    );
                    
                    $products = $this->model_catalog_product->getProducts($data);

                    foreach ($products as $product){
                        if ($product['image']) {
                            $key = md5($product['image']);
                            $album_photos[$key]['image']    = $product['image'];
                            $album_photos[$key]['title']    = $product['name'];
                        }
                        if ($album['album_data']['include_additional_images']) {
                            $images = $this->model_catalog_product->getProductImages($product['product_id']);
                            if (!empty($images)) {
                                foreach ($images as $image) {
                                    $key = md5($image['image']);
                                    $album_photos[$key]['image']    = $image['image'];
                                    $album_photos[$key]['title']    = $product['name'];
                                }
                            }
                        }
                    }
                }
                break;
            case 1: //Directory
                foreach (explode(PHP_EOL, $album['album_data']['album_directory']) as $directory) {
                    if (!empty($directory)) {
                        
                    $data = glob($directory);
                    $data = array_filter($data, 'is_file');
                        if (!empty($data)) {
                            foreach ($data as $value) {
                                $key = md5($value);
                                if ($value{0} == 'i') {
                                    $value = str_replace('image/', '', $value);
                                }
                                $album_photos[$key]['image'] = $value;
                                $album_photos[$key]['title'] = '';
                            }
                        }
                    }
                }
                break;
            case 2: //Custom images
                if (!empty($album['album_data']['gallery_images'])) {
                    foreach ($album['album_data']['gallery_images'] as $gallery_image) {
                        $key = md5($gallery_image['image']);
                        $album_photos[$key]['image'] = $gallery_image['image'];
                        $album_photos[$key]['title'] = $gallery_image['description'][$this->current_language_id];
                    }
                }
            break;
        }
        //Limit photos
        $result = array();
        
        if (($page == 1) && ($limit == 0)) {
            $result = $album_photos;
        }else{
            reset($album_photos);
            for ($counter = 0; $counter < $limit; $counter ++) { 
                if ($counter < $start) {
                    next($album_photos);
                }else{
                    $elem = current($album_photos);
                    if (!empty($elem)) {
                        $result[] = $elem;
                        next($album_photos);
                    }else{
                        break;
                    }
                }
            }
        }
          return $result;
    }
}
?>