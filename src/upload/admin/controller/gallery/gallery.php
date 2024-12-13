<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryGallery extends Controller {
	private $error = array();

	public function index() {
		$data = $this->load->language('gallery/gallery');
		$this->load->model('gallery/gallery');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
			$this->error['warning'] = $this->language->get('text_success');

			$this->model_gallery_gallery->clearCache();
			
			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		
		$data['galleries'] = $this->model_gallery_gallery->getGalleries();

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['heading_title'] = $this->language->get('section_gallery_list');
		$data['text_list'] = $this->language->get('section_gallery_list');
		
		//stores
		$this->load->model('setting/store');
		$default_store[] = array(
			'store_id' => 0,
			'name' => $this->language->get('text_default'),
			'url' => HTTP_CATALOG,
			'ssl' => HTTPS_CATALOG
		);

		$stores = array_merge($default_store, $this->model_setting_store->getStores());
		foreach ($stores as $key => $store) {
			$data['stores'][(int)$store['store_id']] = $store;
		}

		if (!empty($data['galleries'])) {
			foreach ($data['galleries'] as $key => $value) {
				$value['edit_link'] = $this->url->link('gallery/gallery/edit', 'user_token=' . $this->session->data['user_token'].'&gallery_id='.$value['gallery_id'], 'SSL');
				foreach ($value['gallery_data']['stores'] as $key2 => $value2) {
					if (isset($data['stores'][(int)$value2])) {
						$value['view_link'][$value2] = $data['stores'][$value2]['url'] . 'index.php?route=gallery/gallery&gallery_id='.$value['gallery_id'];
					}
				}
				$data['galleries'][$key] = $value;
			}
		}

		//Set language variables
		$data['arr_enabled'] 		= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));
		$data['gallery_types'] 		= array($this->language->get('gallery_type1'), $this->language->get('gallery_type2'), $this->language->get('gallery_type3'));
		
		//Set links
		$data['text_section_gallery_list'] 	= $this->language->get('section_gallery_list');
		$data['text_section_modules'] 	= $this->language->get('section_modules');
		$data['text_section_settings'] 	= $this->language->get('section_settings');

  		$config_admin_language = $this->config->get('config_admin_language');
  		foreach ($data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$data['config_admin_language_id'] = $language['language_id'];
  			}
  		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_gallery_list'),
			'href'      => $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			$this->error['warning'] = true;
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		//Set links
		$data['add'] = $this->url->link('gallery/gallery/add', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['delete'] = $this->url->link('gallery/gallery/delete', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['copy'] = $this->url->link('gallery/gallery/copy', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['link_section_gallery_list'] 	= $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link('gallery/settings', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['html_image'] = '<img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" />';
		}

		$this->response->setOutput($this->load->view('gallery/gallery_list', $data));
	}

	public function add() {
		$data = $this->load->language('gallery/gallery');
		$this->load->model('gallery/gallery');

		$this->document->addScript('view/javascript/sortable.min.js');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('section_gallery');
		$data['text_edit'] = $this->language->get('section_gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms() && $this->validategallery()) {
			$this->session->data['success'] = $this->language->get('text_success_added');
			$this->model_gallery_gallery->addGallery($this->request->post);

			$this->model_gallery_gallery->clearCache();

			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$this->getForm($data);
	}

	public function edit() {
		$data = $this->load->language('gallery/gallery');
		$this->load->model('gallery/gallery');

		$this->document->addScript('view/javascript/sortable.min.js');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('section_edit_gallery');
		$data['text_edit'] = $this->language->get('section_edit_gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms() && $this->validategallery()) {
			$this->session->data['success'] = $this->language->get('text_success_edited');
			$this->model_gallery_gallery->editGallery($this->request->post);

			$this->model_gallery_gallery->clearCache();

			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$this->getForm($data);
	}

	public function getForm($data) {
		$data = $data;
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');
		$default_store[] = array(
			'store_id' => '0',
			'name' => $this->language->get('text_default'),
			'url' => HTTP_CATALOG,
			'ssl' => HTTPS_CATALOG
		);
		$data['stores'] = array_merge($default_store, $this->model_setting_store->getStores());

		$data['categories'] = $this->getAllCategories($this->model_gallery_gallery->getAllCategories());

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/image');

		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->get['gallery_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$gallery_query = $this->model_gallery_gallery->getGallery((int)$this->request->get['gallery_id']);
		}

		if (isset($this->request->post['gallery_id'])) {
			$data['gallery_id'] = $this->request->post['gallery_id'];
		} elseif (!empty($gallery_query)) {
			$data['gallery_id'] = $gallery_query['gallery_id'];
		} else {
			$data['gallery_id'] = '';
		}

		if (isset($this->request->post['gallery_type'])) {
			$data['gallery_type'] = $this->request->post['gallery_type'];
		} elseif (!empty($gallery_query)) {
			$data['gallery_type'] = $gallery_query['gallery_type'];
		} else {
			$data['gallery_type'] = '2';
		}

		if (isset($this->request->post['enabled'])) {
			$data['enabled'] = $this->request->post['enabled'];
		} elseif (!empty($gallery_query)) {
			$data['enabled'] = $gallery_query['enabled'];
		} else {
			$data['enabled'] = '1';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($gallery_query)) {
			$data['sort_order'] = $gallery_query['sort_order'];
		} else {
			$data['sort_order'] = '0';
		}

		$gallery_names = array();
		foreach ($data['languages'] as $key => $language) {
			$gallery_names[$language['language_id']] = $this->language->get('text_new_gallery');
		}

		if (isset($this->request->post['gallery_data'])) {
			$data['gallery_data'] = $this->request->post['gallery_data'];
		} elseif (!empty($gallery_query)) {
			$data['gallery_data'] = $gallery_query['gallery_data'];
		} else {
			$data['gallery_data'] = array(
				'gallery_name'				=> $gallery_names,
				'gallery_seo_url'			=> '',
				'stores'					=> array(0 => 0),
				'photos_limit'				=> 0,
				'show_limiter'				=> 1,
				'thumb_width'				=> 228,
				'thumb_height'				=> 228,
				'popup_width'				=> 500,
				'popup_height'				=> 500,
				'js_lib_type'				=> 0,
				'use_lazyload' 				=> 0,
				'bootstrap_grid'			=> array(
					'xs' => 12,
					'sm' => 6, 
					'md' => 4,
					'lg' => 3
				),
				'show_gallery_description' 	=> 1,
				'gallery_categories' 		=> array(),
				'include_additional_images' => 0,
				'gallery_directory' 		=> '',
				'gallery_images' 			=> array(),
				'gallery_title' 			=> '',
				'gallery_h1_title' 			=> '',
				'gallery_meta_keywords' 	=> '',
				'gallery_meta_description' 	=> '',
				'gallery_description' 		=> ''
			);
		}

		$data['bootstrap_grid_to_cols'] = array(0 => 0, 12 => 1, 6 => 2, 4 => 3, 3 => 4, 2 => 6);

		if (isset($this->request->post['gallery_data']['stores'])) {
			$data['gallery_data']['stores'] = $this->request->post['gallery_data']['stores'];
		} elseif (!empty($gallery_query)) {
			$data['gallery_data']['stores'] = $gallery_query['gallery_data']['stores'];
		} else {
			$data['gallery_data']['stores'] = array();
		}

		if (isset($this->request->post['gallery_data']['gallery_categories'])) {
			$data['gallery_data']['gallery_categories'] = $this->request->post['gallery_data']['gallery_categories'];
		} elseif (!empty($gallery_query)) {
			if (isset($data['gallery_data']['gallery_categories'])) {
				$data['gallery_data']['gallery_categories'] = $gallery_query['gallery_data']['gallery_categories'];
			} else {
				$data['gallery_data']['gallery_categories'] = array();
			}
		} else {
			$data['gallery_data']['gallery_categories'] = array();
		}

		if (isset($this->request->post['gallery_data']['gallery_images'])) {
			$data['gallery_data']['gallery_images'] = $this->request->post['gallery_data']['gallery_images'];
		} elseif (!empty($gallery_query)) {
			if (isset($data['gallery_data']['gallery_images'])) {
				$data['gallery_data']['gallery_images'] = $gallery_query['gallery_data']['gallery_images'];
			} else {
				$data['gallery_data']['gallery_images'] = array();
			}
		} else {
			$data['gallery_data']['gallery_images'] = array();
		}

		$this->load->model('tool/image');
		if (!empty($gallery_query['gallery_data']['gallery_images'])) {
			$gallery_images = array();
			foreach ($gallery_query['gallery_data']['gallery_images'] as $gallery_image) {
				$id_s[] = $gallery_image['id'];
				if ($gallery_image['image'] && file_exists(DIR_IMAGE . $gallery_image['image'])) {
					$image = $gallery_image['image'];
				} else {
					$image = 'no_image.png';
				}

				$gallery_images[] = array(
					'id'      		=> $gallery_image['id'],
					'description'	=> ((isset($gallery_image['description'])) ? $gallery_image['description'] : ''),
					'image'     	=> $image,
					'thumb'     	=> $this->model_tool_image->resize($image, 100, 100)
				);
			}
			$data['gallery_data']['gallery_images'] = $gallery_images;
			$data['image_row'] = max($id_s)+1;
		} else {
			$data['image_row'] = 0;
		}

		if (empty($data['gallery_data']['cover_image']['image'])) {
			$data['gallery_data']['cover_image']['image'] = 'no_image.png';
			$data['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		} else {
			$data['gallery_data']['cover_image']['thumb'] = $this->model_tool_image->resize($data['gallery_data']['cover_image']['image'], 100, 100);
		}

		$data['arr_js_lib_types'] 	= array(
			'colorbox' => $this->language->get('arr_js_lib_type1'), 
			'lightbox' => $this->language->get('arr_js_lib_type2'), 
			'fancybox' => $this->language->get('arr_js_lib_type3'),
			'magnific_popup' => $this->language->get('arr_js_lib_type4')
		);

		$data['gallery_types'] 		= array($this->language->get('gallery_type1'), $this->language->get('gallery_type2'), $this->language->get('gallery_type3'));
		$data['arr_enabled'] 		= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		//Set links
		$data['text_section_gallery_list'] 	= $this->language->get('section_gallery_list');
		$data['text_section_modules'] 	= $this->language->get('section_modules');
		$data['text_section_settings'] 	= $this->language->get('section_settings');

		$data['link_section_gallery_list'] 	= $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link('gallery/settings', 'user_token=' . $this->session->data['user_token'], 'SSL');

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_gallery_list'),
			'href'      => $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

		   if (!empty($gallery_query)) {
	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('section_edit_gallery'),
				'href'      => $this->url->link('gallery/gallery/edit', 'gallery_id='.$this->request->get['gallery_id']. '&user_token=' . $this->session->data['user_token'], 'SSL')
	   		);
   		} else {
	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('section_gallery'),
				'href'      => $this->url->link('gallery/gallery/add', 'user_token=' . $this->session->data['user_token'], 'SSL')
	   		);
   		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['error_stores'])) {
			$data['error_stores'] = $this->error['error_stores'];
		} else {
			$data['error_stores'] = '';
		}

		if (isset($this->error['error_categories'])) {
			$data['error_categories'] = $this->error['error_categories'];
		} else {
			$data['error_categories'] = '';
		}

		if (isset($this->error['error_thumb_image_dimensions'])) {
			$data['error_thumb_image_dimensions'] = $this->error['error_thumb_image_dimensions'];
		} else {
			$data['error_thumb_image_dimensions'] = '';
		}

		if (isset($this->error['error_image_dimensions'])) {
			$data['error_image_dimensions'] = $this->error['error_image_dimensions'];
		} else {
			$data['error_image_dimensions'] = '';
		}

		//Set links
		$data['cancel'] = $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['save'] = $this->url->link('gallery/gallery/add', 'user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->get['gallery_id'])) {
			$data['edit'] = $this->url->link('gallery/gallery/edit', 'gallery_id='.$this->request->get['gallery_id']. '&user_token=' . $this->session->data['user_token'], 'SSL');
		}

		$data['user_token'] = $this->session->data['user_token'];

		//Set settings
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['html_image'] = '<img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" />';
		}

		$this->response->setOutput($this->load->view('gallery/gallery_form', $data));
	}

	public function delete() {

		$this->language->load('gallery/gallery');
		$this->load->model('gallery/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {

			$this->model_gallery_gallery->deleteGallery($this->request->post['selected']);

			$this->session->data['success'] = $this->language->get('text_success_deleted');

			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		} else {
			$this->error['warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	}
	
	public function copy() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
			$this->language->load('gallery/gallery');

			$this->load->model('gallery/gallery');

			$this->model_gallery_gallery->copyGallery($this->request->post['selected'], $this->language->get('text_copy'));

			$this->session->data['success'] = $this->language->get('text_success_copied');

			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		} else {
			$this->error['warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	}

	private function validatePerms() {
		if (!$this->user->hasPermission('modify', 'gallery/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateGallery() {
		if (!isset($this->request->post['gallery_data']['stores'])) {
			$this->error['error_stores'] = $this->language->get('error_stores');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!isset($this->request->post['gallery_data']['gallery_categories']) && $this->request->post['gallery_type'] == 0) {
			$this->error['error_categories'] = $this->language->get('error_categories');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (empty($this->request->post['gallery_data']['thumb_width']) || empty($this->request->post['gallery_data']['thumb_height'])) {
			$this->error['error_thumb_image_dimensions'] = $this->language->get('error_image_dimensions');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (empty($this->request->post['gallery_data']['popup_width']) || empty($this->request->post['gallery_data']['popup_height'])) {
			$this->error['error_image_dimensions'] = $this->language->get('error_image_dimensions');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
		$output = array();

		if (array_key_exists($parent_id, $categories)) {
			if ($parent_name != '') {
				$parent_name .= $this->language->get('text_separator');
			}

			foreach ($categories[$parent_id] as $category) {
				$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
				);

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

		return $output;
	}
	
	public function loadImages(){
		if (isset($this->request->post['path']) && $this->validatePerms()) {
	
			$files = glob(DIR_IMAGE . $this->request->post['path']);
			$images = array();
			$this->load->model('tool/image');
			$this->load->language('gallery/gallery');
	
			foreach ($files as $key => $file) {
				if ($file !== DIR_IMAGE && is_file($file)) {
					$image['image'] = str_replace(DIR_IMAGE, '', $file);
					$image['thumb'] = $this->model_tool_image->resize($image['image'], 100, 100);
					if (!empty($image['thumb'])) {
						$images[] = $image;
					}
				} else {
					unset($files[$key]);
				}
			}

			if (empty($files)) {
				$result['warning'] = sprintf($this->language->get('warning_image_loader_empty'), $this->request->post['path']);
			} else {
				$result['success'] = sprintf($this->language->get('success_image_loader_loaded'), count($images));
				$result['files'] = $images;

			}
			$this->response->setOutput(json_encode($result));
		}
	}

	public function checkImages(){
		if (isset($this->request->post['path']) && $this->validatePerms()) {
			$files = glob(DIR_IMAGE . $this->request->post['path']);
			$images = array();
			$this->load->model('tool/image');
			$this->load->language('gallery/gallery');

			foreach ($files as $key => $file) {
				if ($file !== DIR_IMAGE && is_file($file)) {
					$images[]['image'] = str_replace(DIR_IMAGE, '', $file);
				} else {
					unset($files[$key]);
				}
			}

			if (empty($files)) {
				$result['warning'] = sprintf($this->language->get('warning_image_checker_empty'), $this->request->post['path']);
			} else {
				$result['success'] = sprintf($this->language->get('success_image_checker_checked'), count($images));
			}
			$this->response->setOutput(json_encode($result));
		}
	}
}