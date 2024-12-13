<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerExtensionModuleGallery extends Controller {
	private $cacher = array();
	private $current_language_id;
	private $current_store_id;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->current_language_id = $this->config->get('config_language_id');
		$this->current_store_id = $this->config->get('config_store_id');
	}

	/*
	* Faster check category_id and product_id
	*/
	public function index($setting) {

		if ($this->config->get("seogallery") != 1) {

			$this->config->set("seogallery", 1);

			if ($this->gallery_flag == 'gallery') {
				$this->load->controller('gallery/gallery');
				$this->response->output();
				exit();
			}

			if ($this->gallery_flag == 'galleries') {
				$this->load->controller('gallery/galleries');
				$this->response->output();
				exit();
			}
		}

		if ($setting['module_type'] == 2) {
			return;
		}
		$data['no_conflict'] = substr(md5(rand(0, 99)), 20);

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('gallery/gallery');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/gallery.css');

		$module_cache_name = md5($setting['name'].$this->current_store_id.$this->current_language_id);
		$this->cacher = $this->cache->get('gallery_module.'.$module_cache_name);
		if (empty($this->cacher) || (!$this->config->get('config_gallery_modules_cache_enabled'))) {
			$this->cacher['galleries_link'] = $this->url->link('gallery/galleries');
			switch ($setting['module_type']) {
				case 0: // Galleries
					$this->cacher['show_header'] = $setting['show_header'][$this->current_language_id];
					if ($this->cacher['show_header']) {
						$this->cacher['heading_title'] = $setting['header'][$this->current_language_id];
					}

					if (!empty($setting['gallery_list'])) {
						$galleries = array();
						foreach ($setting['gallery_list'] as $key => $gallery_id) {
							$pre_gallery = $this->model_gallery_gallery->getGallery($gallery_id);
							if (!empty($pre_gallery)) {
								$galleries[$key] = $pre_gallery;
								//Add data
								if ($setting['show_counter']) {
									$cached_images_name = "gallery_photos.$gallery_id.1.0.$this->current_store_id.$this->current_language_id";
									$cached = $this->cache->get($cached_images_name);
									if (!empty($cached)) {
										$galleries[$key]['images'] = $cached;
									} else {
										$cached_data = $this->model_gallery_gallery->getGalleryImages($gallery_id);
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
								$this->cacher['bootstrap_grid'] = $bootstrap_grid;

								//counter
								$gallery_name_postfix = ($setting['show_counter'] ? ' ('.count($galleries[$key]['images']).')' : '');

								$galleries[$key]['gallery_name'] = $galleries[$key]['gallery_data']['gallery_name'][$this->current_language_id].$gallery_name_postfix;
								$galleries[$key]['gallery_link'] = $this->url->link('gallery/gallery', 'gallery_id='.$galleries[$key]['gallery_id']);
								if (!empty($galleries[$key]['gallery_data']['cover_image']['image'])) {
									$galleries[$key]['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize($galleries[$key]['gallery_data']['cover_image']['image'], $setting['cover_image_width'], $setting['cover_image_height']);
								} else {
									$galleries[$key]['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $setting['cover_image_width'], $setting['cover_image_height']);
								}

								$this->cacher['cover_image_width'] = $setting['cover_image_width'];
								$this->cacher['cover_image_height'] = $setting['cover_image_height'];

								$this->cacher['show_gallery_galleries_link'] = $setting['show_gallery_galleries_link'];
								$this->cacher['gallery_galleries_link_text'] = $setting['gallery_galleries_link_text'][$this->current_language_id];
							}
						}
						$this->cacher['galleries'] = $galleries;
						if ($setting['show_covers']) {
							$this->cacher['template_name'] = 'galleries_grid';
						} else {
							$this->cacher['template_name'] = 'galleries_list';
						}
					} else {
						return;
					}

				break;
				case 1: // Gallery
					$this->cacher['show_header'] = $setting['show_header'][$this->current_language_id];
					if ($this->cacher['show_header']) {
						$this->cacher['heading_title'] = $setting['header'][$this->current_language_id];
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
					$this->cacher['bootstrap_grid'] = $bootstrap_grid;

					if (!empty($setting['photo_gallery_list'])) {
						foreach ($setting['photo_gallery_list'] as $key => $gallery_id) {
							$galleries[$key] = $this->model_gallery_gallery->getGallery($gallery_id);
							if (!empty($galleries[$key])) {
								//Add data
								if (!empty($galleries[$key]['gallery_data']['gallery_description']) && $setting['show_gallery_description']) {
									$galleries[$key]['gallery_description'] = html_entity_decode($galleries[$key]['gallery_data']['gallery_description'][$this->current_language_id], ENT_QUOTES, 'UTF-8');
								}

								$galleries[$key]['gallery_name'] = $galleries[$key]['gallery_data']['gallery_name'][$this->current_language_id];
								$galleries[$key]['gallery_link'] = $this->url->link('gallery/gallery', 'gallery_id='.$galleries[$key]['gallery_id']);
								
								$cached_images_name = "gallery_photos.$gallery_id.1.".$setting['photos_limit'].".$this->current_store_id.$this->current_language_id";
								$cached = $this->cache->get($cached_images_name);
								if (!empty($cached)) {
									$galleries[$key]['images'] = $cached;
								} else {
									$cached = $this->model_gallery_gallery->getGalleryImages($gallery_id, 1, $setting['photos_limit']);
									$this->cache->set($cached_images_name, $cached);
									$galleries[$key]['images'] = $cached;
								}
								$this->cacher['show_gallery_link'] = $setting['show_gallery_link'];
								$this->cacher['gallery_link_text'] = $setting['gallery_link_text'][$this->current_language_id];

								$this->cacher['gallery_thumb_image_width'] = $setting['gallery_thumb_image_width'];
								$this->cacher['gallery_thumb_image_height'] = $setting['gallery_thumb_image_height'];

								$galleries[$key]['js'] = $galleries[$key]['gallery_data']['js_lib_type'];
								$galleries[$key]['key'] = $data['no_conflict'] . $galleries[$key]['js'] . $galleries[$key]['gallery_id'];

								if ($galleries[$key]['gallery_data']['js_lib_type'] == 'colorbox') {
									if ($this->config->get('config_gallery_include_colorbox')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js';
										$this->cacher['styles'][] ='catalog/view/javascript/jquery/colorbox/colorbox.css';
									}
								}
								if ($galleries[$key]['gallery_data']['js_lib_type'] == 'lightbox') {
									if ($this->config->get('config_gallery_include_lightbox')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.min.js';
										$this->cacher['styles'][] ='catalog/view/javascript/jquery/lightbox/lightbox.css';
									}
								}
								if ($galleries[$key]['gallery_data']['js_lib_type'] == 'fancybox') {
									if ($this->config->get('config_gallery_include_fancybox')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js';
										$this->cacher['styles'][] ='catalog/view/javascript/jquery/fancybox/jquery.fancybox.css';
									}
								}
								if ($galleries[$key]['gallery_data']['js_lib_type'] == 'magnific_popup') {
									if ($this->config->get('config_gallery_include_magnific_popup')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js';
										$this->cacher['styles'][] ='catalog/view/javascript/jquery/magnific/magnific-popup.css';
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
								$this->cacher['galleries'] = $galleries;
							}
						}

						if (!isset($this->cacher['galleries']) || empty($this->cacher['galleries'])) {
							return;
						}

						if (count($this->cacher['galleries']) > 1 ) {
							$this->cacher['template_name'] = 'gallery_with_tabs';
						} else {
							$this->cacher['gallery'] = end($this->cacher['galleries']);
							$this->cacher['template_name'] = 'gallery_without_tabs';
						}
					} else {
						return;
					}
				break;
			}
			$this->cache->set('gallery_module.'.$module_cache_name, $this->cacher);
		}

		if (!empty($this->cacher['scripts'])) {
			foreach ($this->cacher['scripts'] as $key => $script) {
				$this->document->addScript($script);
			}
		}
		if (!empty($this->cacher['styles'])) {
			foreach ($this->cacher['styles'] as $key => $style) {
				$this->document->addStyle($style);
			}
		}
		if (isset($this->request->get['gallery_id'])) {
			$data['current_gallery_id'] = (int)$this->request->get['gallery_id'];
		}

		foreach ($this->cacher as $key => $value) {
			$data[$key] = $value;
		}

		return $this->load->view('extension/module/' . $this->cacher['template_name'], $data);
	}
}