<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGalleryGallery extends Controller {

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

	public function index(){
		// Initial
		$this->load->model($this->_route);
		$this->load->language($this->_route);
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		// Page Data
		if (!isset($this->request->get['gallery_id'])) {
			$this->response->redirect($this->url->link($this->_route . '/galleries'));
		} else {
			$gallery_id = (int)$this->request->get['gallery_id'];
			$gallery = $this->{$this->_model}->getGallery($gallery_id);
			if (empty($gallery_id) || empty($gallery)) {
				$this->response->redirect($this->url->link($this->_route . '/galleries'));
			} else {
				$data['no_conflict'] = substr(md5(mt_rand(0, 99)), 20);

				$data['text_gallery_list']    = $this->language->get('text_gallery_list');
				$data['text_limit']           = $this->language->get('text_limit');

				if (empty($gallery)) {
					$this->response->redirect($this->url->link($this->_route . '/galleries'));
				}

				$gallery['images'] = $this->{$this->_model}->getGalleryImages($gallery_id, 1, 0);
				$gallery['total_images'] = count($gallery['images']);

				if (isset($this->request->get['page'])) {
					$page = (int)$this->request->get['page'];
				} else {
					$page = 1;
				}

				if (isset($this->request->get['limit'])) {
					$limit = intval($this->request->get['limit']);
					if (($limit < 0) || ($limit >= $gallery['total_images'])) {
						$this->response->redirect($this->url->link($this->_route . '/gallery', 'gallery_id=' . $gallery_id. '&limit=0'));
					} else {
						$url = '&limit='.(int)$limit;
					}
				} else {
					$limit = $gallery['gallery_data']['photos_limit'];
					$data['limit'] = $limit;
					$url = '&limit='.$limit;
				}
				$data['limit'] = $limit;

				$data['limits'] = [];

				if (!$gallery['gallery_data']['photos_limit']) {
					$data['limits'][$gallery['gallery_data']['photos_limit']] = [
						'text'  => $gallery['gallery_data']['photos_limit'],
						'value' => $gallery['gallery_data']['photos_limit'],
						'href'  => $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=' . $gallery['gallery_data']['photos_limit'])
					];
				}

				$data['limits'][25] = [
					'text'  => 25,
					'value' => 25,
					'href'  => $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=25')
				];
	
				$data['limits'][50] = [
					'text'  => 50,
					'value' => 50,
					'href'  => $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=50')
				];
	
				$data['limits'][75] = [
					'text'  => 75,
					'value' => 75,
					'href'  => $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=75')
				];
	
				$data['limits'][100] = [
					'text'  => 100,
					'value' => 100,
					'href'  => $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=100')
				];

				$data['limits'][0] = [
					'text'  => $this->language->get('text_limit_all'),
					'value' => 0,
					'href'  => $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=0')
				];

				$gallery['key'] = $gallery['gallery_data']['gallery_name'][$this->current_language_id];
				$gallery['gallery_name'] = $gallery['gallery_data']['gallery_name'][$this->current_language_id];
	
				$gallery['gallery_link'] = $this->url->link($this->_route . '/gallery', 'gallery_id='.$gallery['gallery_id']);
	
				if ($limit == 0) {
					$data['show_pagination'] = false;
				} else {
					$data['show_pagination'] = true;
					$pagination = new Pagination();
					$pagination->total = $gallery['total_images'];
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = $this->language->get('text_pagination');
					$pagination->url = $this->url->link($this->_route . '/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&page={page}'. $url);
					$data['pagination'] = $pagination->render();
				}

				foreach ($gallery['images'] as $img_key => $image) {
					if (empty($image['image'])) {
						$image['thumb'] = $this->model_tool_image->resize('no_image.png', $gallery['gallery_data']['thumb_width'] , $gallery['gallery_data']['thumb_height']);
						$image['popup'] = $this->model_tool_image->resize('no_image.png', $gallery['gallery_data']['popup_width'] , $gallery['gallery_data']['popup_height']);
					} else {
						$image['thumb'] = $this->model_tool_image->resize($image['image'], $gallery['gallery_data']['thumb_width'] , $gallery['gallery_data']['thumb_height']);
						$image['popup'] = $this->model_tool_image->resize($image['image'], $gallery['gallery_data']['popup_width'] , $gallery['gallery_data']['popup_height']);
					}
					$gallery['images'][$img_key] = $image;
				}

				// Bootstrap
				$bootstrap_grid = '';
				if (isset($gallery['gallery_data']['bootstrap_grid'])) {
					foreach ($gallery['gallery_data']['bootstrap_grid'] as $prefix => $bcol) {
						if ((int)$bcol !== 0) {
							$bootstrap_grid .= ' col-' . $prefix . '-' . $bcol;
						}
					}
				}
				$gallery['bootstrap_grid'] = $bootstrap_grid;
				
				// Metadata
				$gallery['gallery_title'] = (!empty($gallery['gallery_data']['gallery_title'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_title'][$this->current_language_id] : $gallery['gallery_name']);
				
				$gallery['gallery_h1_title'] = (!empty($gallery['gallery_data']['gallery_h1_title'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_h1_title'][$this->current_language_id] : $gallery['gallery_name']);
				$gallery['gallery_keywords'] = (!empty($gallery['gallery_data']['gallery_keywords'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_keywords'][$this->current_language_id] : $gallery['gallery_name']);
				$gallery['gallery_meta_description'] = (!empty($gallery['gallery_data']['gallery_meta_description'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_meta_description'][$this->current_language_id] : $gallery['gallery_name']);
				if (!empty($gallery['gallery_data']['gallery_description']) && $gallery['gallery_data']['show_gallery_description']) {
					$gallery['gallery_description'] = html_entity_decode($gallery['gallery_data']['gallery_description'][$this->current_language_id], ENT_QUOTES, 'UTF-8');
				}
				
				$gallery['js'] = $gallery['gallery_data']['js_lib_type'];
				$gallery['key'] = $data['no_conflict'] . $gallery['js'] . $gallery['gallery_id'];
	
				if ($gallery['gallery_data']['js_lib_type'] == 'colorbox') {
					if ($this->config->get('config_gallery_include_colorbox')) {
						$gallery['scripts'][] = 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js';
						$gallery['styles'][] ='catalog/view/javascript/jquery/colorbox/colorbox.css';
					}
				}
				if ($gallery['gallery_data']['js_lib_type'] == 'lightbox') {
					if ($this->config->get('config_gallery_include_lightbox')) {
						$gallery['scripts'][] = 'catalog/view/javascript/jquery/lightbox/js/lightbox.js';
						$gallery['styles'][] ='catalog/view/javascript/jquery/lightbox/css/lightbox.css';
					}
				}
				if ($gallery['gallery_data']['js_lib_type'] == 'fancybox') {
					if ($this->config->get('config_gallery_include_fancybox')) {
						$gallery['scripts'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js';
						$gallery['styles'][] ='catalog/view/javascript/jquery/fancybox/jquery.fancybox.css';
					}
				}
				if ($gallery['gallery_data']['js_lib_type'] == 'magnific_popup') {
					if ($this->config->get('config_gallery_include_magnific_popup')) {
						$gallery['scripts'][] = 'catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js';
						$gallery['styles'][] ='catalog/view/javascript/jquery/magnific/magnific-popup.css';
					}
				}

				// LazyLoad
				if ($this->config->get('config_gallery_include_lazyload')) {
					$gallery['scripts'][] = 'catalog/view/javascript/jquery/jquery.lazyload.min.js';
				}
				if (!isset($gallery['gallery_data']['use_lazyload'])) {
					$gallery['gallery_data']['use_lazyload'] = false;
				}
				$gallery['gallery_data']['lazyload_image'] = $this->model_tool_image->resize('no_image.png', $gallery['gallery_data']['thumb_width'], $gallery['gallery_data']['thumb_height']);
	
				$data['gallery'] = $gallery;
		
				if (!empty($gallery['scripts'])) {
					foreach ($gallery['scripts'] as $key => $script) {
						$this->document->addScript($script);
					}
				}
				if (!empty($gallery['styles'])) {
					foreach ($gallery['styles'] as $key => $style) {
						$this->document->addStyle($style);
					}
				}
				
				$this->document->setTitle($gallery['gallery_title']);
				$this->document->addStyle('catalog/view/theme/default/stylesheet/gallery.css');
		
				$data['heading_title'] = $gallery['gallery_h1_title'];
		
				$breadchrumbs = $this->config->get('config_gallery_galleries_breadcrumb');
				if (!empty($breadchrumbs[$this->current_store_id][$this->current_language_id])) {
					$breadchrumbs = $breadchrumbs[$this->current_store_id][$this->current_language_id];
				} else {
					$title = $this->config->get('config_gallery_galleries_title');
					if (!empty($title[$this->current_store_id][$this->current_language_id])) {
						$title = $title[$this->current_store_id][$this->current_language_id];
					} else {
						$title = $this->language->get('text_gallery_list');
					}
					$breadchrumbs = $title;
				}
		
				$data['breadcrumbs'] = [];
				
				$data['breadcrumbs'][] = [
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
				];
				
				$data['breadcrumbs'][] = [
					'text'      => $breadchrumbs,
					'href'      => $this->url->link($this->_route . '/galleries', '', 'SSL'),
				];
				
				$data['breadcrumbs'][] = [
					'text'      => (!empty($gallery['gallery_name'])? $gallery['gallery_name']: $gallery['gallery_data']['gallery_title'][$this->current_language_id]),
					'href'      => $this->url->link($this->_route . '/gallery', 'gallery_id='.$gallery_id. (($page == 1)? '' : '&page='.$page ).(($limit == 0)?'':$url), 'SSL'),
				];
		
				if (isset($gallery['last_modified']) && $gallery['last_modified'] != '0000-00-00 00:00:00') {
					$this->response->addHeader('Last-Modified: '.date('r', strtotime($gallery['last_modified'])));
				} else {
					$this->response->addHeader('Last-Modified: '.date('r'));
				}
		
				$this->document->setDescription((!empty($gallery['gallery_data']['gallery_meta_description'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_meta_description'][$this->current_language_id] : $gallery['gallery_name']));
				$this->document->setKeywords((!empty($gallery['gallery_data']['gallery_meta_keywords'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_meta_keywords'][$this->current_language_id] : $gallery['gallery_name']));
				
				// Child Controllers
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');

				// Output
				$this->response->setOutput($this->load->view($this->_route . '/gallery', $data));
			}
		}
	}
}
