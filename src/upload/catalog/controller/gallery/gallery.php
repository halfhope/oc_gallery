<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryGallery extends controller{
	private $cacher = array();
	private $current_language_id;
	private $current_store_id;

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->current_language_id = $this->config->get('config_language_id');
		$this->current_store_id = $this->config->get('config_store_id');
	}

	public function index(){

		if (isset($this->request->get['gallery_id'])) {
			$gallery_id = (int)$this->request->get['gallery_id'];
			if (!empty($gallery_id)) {
				$this->section_photos($gallery_id);
			} else {
				$this->response->redirect($this->url->link('gallery/galleries'));
			}
		} else {
			$this->response->redirect($this->url->link('gallery/galleries'));
		}
	}

	private function section_photos($gallery_id) {

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('gallery/gallery');
		$this->load->language('gallery/gallery');
		
		$data['no_conflict'] = substr(md5(mt_rand(0, 99)), 20);

		$data['text_gallery_list']    = $this->language->get('text_gallery_list');
		$data['text_limit']           = $this->language->get('text_limit');

		$pre_gallery = $this->model_gallery_gallery->getGallery($gallery_id);
		if (empty($pre_gallery)) {
			$this->response->redirect($this->url->link('gallery/galleries'));
		}
		$total_cached_images_name = 'gallery_gallery.total.'.md5($gallery_id.'10'.$this->current_language_id);

		if ($this->config->get('config_gallery_modules_cache_enabled')) {
			$total_cached = $this->cache->get($total_cached_images_name);
			if (!empty($total_cached)) {
				$pre_gallery['total_images'] = count($total_cached);
			} else {
				$total_cached = $this->model_gallery_gallery->getGalleryImages($gallery_id, 1, 0);
				$this->cache->set($total_cached_images_name, $total_cached);
				$pre_gallery['total_images'] = count($total_cached);
			}
		} else {
			$pre_gallery['total_images'] = count($this->model_gallery_gallery->getGalleryImages($gallery_id, 1, 0));
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = intval($this->request->get['limit']);
			if (($limit < 0) || ($limit >= $pre_gallery['total_images'])) {
				$this->response->redirect($this->url->link('gallery/gallery', 'gallery_id=' . $gallery_id. '&limit=0'));
			} else {
				$url = '&limit='.(int)$limit;
			}
		} else {
			$limit = $pre_gallery['gallery_data']['photos_limit'];
			$this->cacher['limit'] = $limit;
			$url = '&limit='.$limit;
		}
		$data['limit'] = $limit;

		$cached_page_name = "gallery_gallery.$gallery_id.$page.$limit.$this->current_store_id.$this->current_language_id";

		$this->cacher = $this->cache->get($cached_page_name);
		if (!empty($this->cacher) && $this->config->get('config_gallery_modules_cache_enabled')) {
			foreach ($this->cacher as $key => $value) {
				$data[$key] = $value;
			}
			$gallery = $data['gallery'];
		} else {

			$gallery = $pre_gallery;
			if (empty($gallery)) {
				$this->response->redirect($this->url->link('gallery/galleries'));
			}

			$this->cacher['limits'] = array();
			if ($gallery['gallery_data']['photos_limit'] != 0) {
				$this->cacher['limits'][$gallery['gallery_data']['photos_limit']] = array(
					'text'  => $gallery['gallery_data']['photos_limit'],
					'value' => $gallery['gallery_data']['photos_limit'],
					'href'  => $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=' . $gallery['gallery_data']['photos_limit'])
				);
			}
			$this->cacher['limits'][25] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=25')
			);

			$this->cacher['limits'][50] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=50')
			);

			$this->cacher['limits'][75] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=75')
			);

			$this->cacher['limits'][100] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=100')
			);
			$this->cacher['limits'][0] = array(
				'text'  => $this->language->get('text_limit_all'),
				'value' => 0,
				'href'  => $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&limit=0')
			);

			//Add data
			$gallery['key'] = $gallery['gallery_data']['gallery_name'][$this->current_language_id];
			$gallery['gallery_name'] = $gallery['gallery_data']['gallery_name'][$this->current_language_id];

			$gallery['gallery_link'] = $this->url->link('gallery/galleries', 'gallery_id='.$gallery['gallery_id']);

			$cached_images_name = "gallery_photos.$gallery_id.$page.$limit.$this->current_store_id.$this->current_language_id";

			if ($this->config->get('config_gallery_modules_cache_enabled')) {
				$cached = $this->cache->get($cached_images_name);
				if (!empty($cached)) {
					$gallery['images'] = $cached;
				} else {
					$cached = $this->model_gallery_gallery->getGalleryImages($gallery_id, $page, $limit);
					$this->cache->set($cached_images_name, $cached);
					$gallery['images'] = $cached;
				}
			} else {
				$gallery['images'] = $this->model_gallery_gallery->getGalleryImages($gallery_id, $page, $limit);
			}

			if ($limit == 0) {
				$this->cacher['show_pagination'] = false;
			} else {
				$this->cacher['show_pagination'] = true;
				$pagination = new Pagination();
				$pagination->total = $gallery['total_images'];
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('gallery/gallery', 'gallery_id=' . $this->request->get['gallery_id']. '&page={page}'. $url);
				$this->cacher['pagination'] = $pagination->render();
			}


			foreach ($gallery['images'] as $img_key => $image) {
				if (empty($image['image'])) {
					$image['thumb'] = $this->model_tool_image->resize('no_image.jpg', $gallery['gallery_data']['thumb_width'] , $gallery['gallery_data']['thumb_height']);
					$image['popup'] = $this->model_tool_image->resize('no_image.jpg', $gallery['gallery_data']['popup_width'] , $gallery['gallery_data']['popup_height']);
				} else {
					$image['thumb'] = $this->model_tool_image->resize($image['image'], $gallery['gallery_data']['thumb_width'] , $gallery['gallery_data']['thumb_height']);
					$image['popup'] = $this->model_tool_image->resize($image['image'], $gallery['gallery_data']['popup_width'] , $gallery['gallery_data']['popup_height']);
				}
				$gallery['images'][$img_key] = $image;
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
			$gallery['bootstrap_grid'] = $bootstrap_grid;
			
			//Metadata
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
					$gallery['scripts'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.min.js';
					$gallery['styles'][] ='catalog/view/javascript/jquery/lightbox/lightbox.css';
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

			//lazyload
			if ($this->config->get('config_gallery_include_lazyload')) {
				$gallery['scripts'][] = 'catalog/view/javascript/jquery/jquery.lazyload.min.js';
			}
			if (!isset($gallery['gallery_data']['use_lazyload'])) {
				$gallery['gallery_data']['use_lazyload'] = false;
			}
			$gallery['gallery_data']['lazyload_image'] = $this->model_tool_image->resize('lazyload_image.jpg', $gallery['gallery_data']['thumb_width'], $gallery['gallery_data']['thumb_height']);

			$this->cacher['gallery'] = $gallery;
			if ($this->config->get('config_gallery_modules_cache_enabled')) {
				$this->cache->set($cached_page_name, $this->cacher);
			}
			foreach ($this->cacher as $key => $value) {
				$data[$key] = $value;
			}

			$gallery = $data['gallery'];
		}

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

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
		);
		$data['breadcrumbs'][] = array(
			'text'      => $breadchrumbs,
			'href'      => $this->url->link('gallery/galleries', '', 'SSL'),
		);
		$data['breadcrumbs'][] = array(
			'text'      => (!empty($gallery['gallery_name'])? $gallery['gallery_name']: $gallery['gallery_data']['gallery_title'][$this->current_language_id]),
			'href'      => $this->url->link('gallery/gallery', 'gallery_id='.$gallery_id. (($page == 1)? '' : '&page='.$page ).(($limit == 0)?'':$url), 'SSL'),
		);

		if (isset($gallery['last_modified']) && $gallery['last_modified'] != '0000-00-00 00:00:00') {
			$this->response->addHeader('Last-Modified: '.date('r', strtotime($gallery['last_modified'])));
		} else {
			$this->response->addHeader('Last-Modified: '.date('r'));
		}

		$this->document->setDescription((!empty($gallery['gallery_data']['gallery_meta_description'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_meta_description'][$this->current_language_id] : $gallery['gallery_name']));
		$this->document->setKeywords((!empty($gallery['gallery_data']['gallery_meta_keywords'][$this->current_language_id]) ? $gallery['gallery_data']['gallery_meta_keywords'][$this->current_language_id] : $gallery['gallery_name']));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('gallery/gallery', $data));
	}
}
?>