<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGalleryGalleries extends Controller {

	public 	$_route 		= 'extension/module/gallery';
	public 	$_model 		= 'model_extension_module_gallery';

	private $cacher = [];
	private $current_language_id;
	private $current_store_id;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->current_language_id = $this->config->get('config_language_id');
		$this->current_store_id = $this->config->get('config_store_id');
	}

	public function index() {
		// Initial
		$this->load->model($this->_route);
		$this->language->load($this->_route);
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		// Page Data
		if (isset($this->request->get['gallery_id'])) {
			$gallery_id = (int)$this->request->get['gallery_id'];
			if (!empty($gallery_id)) {
				$this->response->redirect($this->url->link($this->_route . '/gallery', 'gallery_id=' . (int)$gallery_id));
			} else {
				$this->response->redirect($this->url->link($this->_route . '/galleries'));
			}
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/gallery.css');
	
			$desc = $this->config->get('config_gallery_galleries_description');
			$config_gallery_show_description = $this->config->get('config_gallery_show_description');
			if ($config_gallery_show_description[$this->current_store_id] && !empty($desc[$this->current_store_id][$this->current_language_id])) {
				$data['galleries_description'] = html_entity_decode($desc[$this->current_store_id][$this->current_language_id], ENT_QUOTES, 'UTF-8');
			}
	
			$titl = $this->config->get('config_gallery_galleries_title');
			if (!empty($titl[$this->current_store_id][$this->current_language_id])) {
				$data['title'] = $titl[$this->current_store_id][$this->current_language_id];
			} else {
				$data['title'] = $this->language->get('text_gallery_list');
			}
			$this->document->setTitle($data['title']);
	
			$breadchrumb = $this->config->get('config_gallery_galleries_breadcrumb');
			if (!empty($breadchrumb[$this->current_store_id][$this->current_language_id])) {
				$breadchrumb = $breadchrumb[$this->current_store_id][$this->current_language_id];
			} else {
				$breadchrumb = $data['title'];
			}
	
			$h1_titl = $this->config->get('config_gallery_galleries_h1_title');
			if (!empty($h1_titl[$this->current_store_id][$this->current_language_id])) {
				$data['h1_title'] = $h1_titl[$this->current_store_id][$this->current_language_id];
			} else {
				$data['h1_title'] = $data['title'];
			}
	
			$keyword = $this->config->get('config_gallery_galleries_meta_keywords');
			if (!empty($keyword[$this->current_store_id][$this->current_language_id])) {
				$this->document->setKeywords($keyword[$this->current_store_id][$this->current_language_id]);
			}
	
			$meta_desc = $this->config->get('config_gallery_galleries_meta_description');
			if (!empty($meta_desc[$this->current_store_id][$this->current_language_id])) {
				$this->document->setDescription($meta_desc[$this->current_store_id][$this->current_language_id]);
			}
			
			// Bootstrap
			$bootstrap_grid = '';
			if ($this->config->get('config_gallery_bootstrap_grid')[$this->current_store_id]) {
				foreach ($this->config->get('config_gallery_bootstrap_grid')[$this->current_store_id] as $prefix => $bcol) {
					if ((int)$bcol !== 0) {	
						$bootstrap_grid .= ' col-' . $prefix . '-' . $bcol;
					}
				}
			}
			$data['bootstrap_grid'] = $bootstrap_grid;
	
			$galleries = $this->{$this->_model}->getGalleries();
	
			$config_gallery_cover_image_width   = $this->config->get('config_gallery_cover_image_width');
			$config_gallery_cover_image_height  = $this->config->get('config_gallery_cover_image_height');
	
			$data['config_gallery_cover_image_width'] = $config_gallery_cover_image_width[$this->current_store_id];
			$data['config_gallery_cover_image_height'] = $config_gallery_cover_image_height[$this->current_store_id];
	
			foreach ($galleries as $key => $gallery) {
				$config_gallery_show_counter = $this->config->get('config_gallery_show_counter');
	
				if ($config_gallery_show_counter[$this->current_store_id]) {
	
					$cached_images_name = "gallery_photos.".$gallery['gallery_id'].".1.0.$this->current_store_id.$this->current_language_id";
					$cached = $this->cache->get($cached_images_name);
	
					if (!empty($cached)) {
						$galleries[$key]['images'] = $cached;
					} else {
						$cached = $this->{$this->_model}->getGalleryImages($gallery['gallery_id'], 1, 0);
						$this->cache->set($cached_images_name, $cached);
						$galleries[$key]['images'] = $cached;
					}
				}
				//counter
				$config_gallery_show_counter = $this->config->get('config_gallery_show_counter');
				$gallery_name_postfix = ($config_gallery_show_counter[$this->current_store_id] ? ' ('.count($galleries[$key]['images']).')' : '');
	
				$galleries[$key]['gallery_name'] = $galleries[$key]['gallery_data']['gallery_name'][$this->current_language_id].$gallery_name_postfix;
				$galleries[$key]['gallery_link'] = $this->url->link($this->_route . '/gallery', 'gallery_id='.$galleries[$key]['gallery_id']);
	
				if (!empty($galleries[$key]['gallery_data']['cover_image']['image'])) {
					$galleries[$key]['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize($galleries[$key]['gallery_data']['cover_image']['image'], $config_gallery_cover_image_width[$this->current_store_id], $config_gallery_cover_image_height[$this->current_store_id]);
				} else {
					$galleries[$key]['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $config_gallery_cover_image_width[$this->current_store_id], $config_gallery_cover_image_height[$this->current_store_id]);
				}
			}
	
			$data['galleries'] = $galleries;
	
			$data['heading_title'] = $data['title'];
	
			$data['breadcrumbs'] = [];
	
			$data['breadcrumbs'][] = [
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
			];
	
			$data['breadcrumbs'][] = [
				'text'      => $breadchrumb,
				'href'      => $this->url->link($this->_route . '/galleries', '', 'SSL'),
			];

			// Child Controllers
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			// Output
			$this->response->setOutput($this->load->view($this->_route . '/galleries', $data));
		}
	}
}
