<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGallery extends Controller {

	public 	$_route 		= 'extension/module/gallery';
	public 	$_model 		= 'model_extension_module_gallery';

	private $current_language_id;
	private $current_store_id;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->current_language_id = $this->config->get('config_language_id');
		$this->current_store_id = $this->config->get('config_store_id');
	}

	public function index($setting) {
		// Initial
		$this->load->model($this->_route);
		$this->load->model('tool/image');
		$this->load->model('catalog/product');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/gallery.css');

		// Check SEO
		if ($this->config->get("seogallery") != 1) {

			$this->config->set("seogallery", 1);

			if ($this->gallery_flag == 'gallery') {
				$this->load->controller($this->_route);
				$this->response->output();
				exit();
			}

			if ($this->gallery_flag == 'galleries') {
				$this->load->controller($this->_route . '/galleries');
				$this->response->output();
				exit();
			}
		}

		// Page Data
		$data['no_conflict'] = substr(md5(rand(0, 99)), 20);

		$data['galleries_link'] = $this->url->link($this->_route . '/galleries');
		switch ((int) $setting['module_type']) {
			case 0: // Galleries
				$data['show_header'] = $setting['show_header'][$this->current_language_id];
				if ($data['show_header']) {
					$data['heading_title'] = $setting['header'][$this->current_language_id];
				}

				if (!empty($setting['gallery_list'])) {
					$galleries = [];
					foreach ($setting['gallery_list'] as $key => $gallery_id) {
						$pre_gallery = $this->{$this->_model}->getGallery($gallery_id);
						if (!empty($pre_gallery)) {
							$galleries[$key] = $pre_gallery;
							//Add data
							if ($setting['show_counter']) {
								$cached_images_name = "gallery_photos.$gallery_id.1.0.$this->current_store_id.$this->current_language_id";
								$cached = $this->cache->get($cached_images_name);
								if (!empty($cached)) {
									$galleries[$key]['images'] = $cached;
								} else {
									$cached_data = $this->{$this->_model}->getGalleryImages($gallery_id);
									$this->cache->set($cached_images_name, $cached_data);
									$galleries[$key]['images'] = $cached_data;
								}
							}
							
							// Bootstrap
							$bootstrap_grid = '';
							if ($setting['bootstrap_grid']) {
								foreach ($setting['bootstrap_grid'] as $prefix => $bcol) {
									if ((int)$bcol !== 0) {	
										$bootstrap_grid .= ' col-' . $prefix . '-' . $bcol;
									}
								}
							}
							$data['bootstrap_grid'] = $bootstrap_grid;

							//counter
							$gallery_name_postfix = ($setting['show_counter'] ? ' ('.count($galleries[$key]['images']).')' : '');

							$galleries[$key]['gallery_name'] = $galleries[$key]['gallery_data']['gallery_name'][$this->current_language_id].$gallery_name_postfix;
							$galleries[$key]['gallery_link'] = $this->url->link($this->_route . '/gallery', 'gallery_id='.$galleries[$key]['gallery_id']);
							if (!empty($galleries[$key]['gallery_data']['cover_image']['image'])) {
								$galleries[$key]['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize($galleries[$key]['gallery_data']['cover_image']['image'], $setting['cover_image_width'], $setting['cover_image_height']);
							} else {
								$galleries[$key]['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $setting['cover_image_width'], $setting['cover_image_height']);
							}

							$data['cover_image_width'] = $setting['cover_image_width'];
							$data['cover_image_height'] = $setting['cover_image_height'];

							$data['show_gallery_galleries_link'] = $setting['show_gallery_galleries_link'];
							$data['gallery_galleries_link_text'] = $setting['gallery_galleries_link_text'][$this->current_language_id];
						}
					}
					$data['galleries'] = $galleries;
					if ($setting['show_covers']) {
						$data['template_name'] = 'module_galleries_grid';
					} else {
						$data['template_name'] = 'module_galleries_list';
					}
				} else {
					return;
				}

			break;
			case 1: // Gallery
				$data['show_header'] = $setting['show_header'][$this->current_language_id];
				if ($data['show_header']) {
					$data['heading_title'] = $setting['header'][$this->current_language_id];
				}

				// Bootstrap
				$bootstrap_grid = '';
				if ($setting['bootstrap_grid']) {
					foreach ($setting['bootstrap_grid'] as $prefix => $bcol) {
						if ((int)$bcol !== 0) {	
							$bootstrap_grid .= ' col-' . $prefix . '-' . $bcol;
						}
					}
				}
				$data['bootstrap_grid'] = $bootstrap_grid;

				if (!empty($setting['photo_gallery_list'])) {
					foreach ($setting['photo_gallery_list'] as $key => $gallery_id) {
						$galleries[$key] = $this->{$this->_model}->getGallery($gallery_id);
						if (!empty($galleries[$key])) {
							//Add data
							if (!empty($galleries[$key]['gallery_data']['gallery_description']) && $setting['show_gallery_description']) {
								$galleries[$key]['gallery_description'] = html_entity_decode($galleries[$key]['gallery_data']['gallery_description'][$this->current_language_id], ENT_QUOTES, 'UTF-8');
							}

							$galleries[$key]['gallery_name'] = $galleries[$key]['gallery_data']['gallery_name'][$this->current_language_id];
							$galleries[$key]['gallery_link'] = $this->url->link($this->_route . '/gallery', 'gallery_id='.$galleries[$key]['gallery_id']);
							
							$cached_images_name = "gallery_photos.$gallery_id.1.".$setting['photos_limit'].".$this->current_store_id.$this->current_language_id";
							$cached = $this->cache->get($cached_images_name);
							if (!empty($cached)) {
								$galleries[$key]['images'] = $cached;
							} else {
								$cached = $this->{$this->_model}->getGalleryImages($gallery_id, 1, $setting['photos_limit']);
								$this->cache->set($cached_images_name, $cached);
								$galleries[$key]['images'] = $cached;
							}
							$data['show_gallery_link'] = $setting['show_gallery_link'];
							$data['gallery_link_text'] = $setting['gallery_link_text'][$this->current_language_id];

							$data['gallery_thumb_image_width'] = $setting['gallery_thumb_image_width'];
							$data['gallery_thumb_image_height'] = $setting['gallery_thumb_image_height'];

							$galleries[$key]['js'] = $galleries[$key]['gallery_data']['js_lib_type'];
							$galleries[$key]['key'] = $data['no_conflict'] . $galleries[$key]['js'] . $galleries[$key]['gallery_id'];

							if ($galleries[$key]['gallery_data']['js_lib_type'] == 'colorbox') {
								if ($this->config->get('config_gallery_include_colorbox')) {
									$data['scripts'][] = 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js';
									$data['styles'][] ='catalog/view/javascript/jquery/colorbox/colorbox.css';
								}
							}
							if ($galleries[$key]['gallery_data']['js_lib_type'] == 'lightbox') {
								if ($this->config->get('config_gallery_include_lightbox')) {
									$data['scripts'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.min.js';
									$data['styles'][] ='catalog/view/javascript/jquery/lightbox/lightbox.css';
								}
							}
							if ($galleries[$key]['gallery_data']['js_lib_type'] == 'fancybox') {
								if ($this->config->get('config_gallery_include_fancybox')) {
									$data['scripts'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js';
									$data['styles'][] ='catalog/view/javascript/jquery/fancybox/jquery.fancybox.css';
								}
							}
							if ($galleries[$key]['gallery_data']['js_lib_type'] == 'magnific_popup') {
								if ($this->config->get('config_gallery_include_magnific_popup')) {
									$data['scripts'][] = 'catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js';
									$data['styles'][] ='catalog/view/javascript/jquery/magnific/magnific-popup.css';
								}
							}

							foreach ($galleries[$key]['images'] as $img_key => $image) {
								if (empty($image['image'])) {
									$image['thumb'] = $this->model_tool_image->resize('no_image.jpg', $setting['gallery_thumb_image_width'] , $setting['gallery_thumb_image_height']);
									$image['popup'] = $this->model_tool_image->resize('no_image.jpg', $setting['gallery_popup_image_width'] , $setting['gallery_popup_image_height']);
								} else {
									$image['thumb'] = $this->model_tool_image->resize($image['image'], $setting['gallery_thumb_image_width'] , $setting['gallery_thumb_image_height']);
									$image['popup'] = $this->model_tool_image->resize($image['image'], $setting['gallery_popup_image_width'] , $setting['gallery_popup_image_height']);
								}
								$galleries[$key]['images'][$img_key] = $image;
							}
							$data['galleries'] = $galleries;
						}
					}

					if (!isset($data['galleries']) || empty($data['galleries'])) {
						return;
					}

					if (count($data['galleries']) > 1 ) {
						$data['template_name'] = 'module_gallery_with_tabs';
					} else {
						$data['gallery'] = end($data['galleries']);
						$data['template_name'] = 'module_gallery_without_tabs';
					}
				} else {
					return;
				}
			break;
		}

		if (!empty($data['scripts'])) {
			foreach ($data['scripts'] as $key => $script) {
				$this->document->addScript($script);
			}
		}
		if (!empty($data['styles'])) {
			foreach ($data['styles'] as $key => $style) {
				$this->document->addStyle($style);
			}
		}
		if (isset($this->request->get['gallery_id'])) {
			$data['current_gallery_id'] = (int)$this->request->get['gallery_id'];
		}

		foreach ($data as $key => $value) {
			$data[$key] = $value;
		}

		return $this->load->view($this->_route . '/' . $data['template_name'], $data);
	}
}
