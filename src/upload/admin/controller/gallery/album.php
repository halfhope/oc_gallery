<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryAlbum extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('gallery/index'); 
		$this->load->model('gallery/index');
		
		$this->load->model('setting/store');
		
		$this->document->setTitle($this->language->get('heading_title'));
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
				$this->error['warning'] = $this->language->get('text_success');
				
				$this->model_gallery_index->clearCache();

				$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
			}
		$data['albums'] = $this->model_gallery_index->getAlbums();

		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['heading_title'] = $this->language->get('section_album_list');
		$data['text_list'] = $this->language->get('section_album_list');
		
		//stores
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

		if (!empty($data['albums'])) {
			foreach ($data['albums'] as $key => $value) {
				$value['edit_link'] = $this->url->link('gallery/album/edit', 'token=' . $this->session->data['token'].'&album_id='.$value['album_id'], 'SSL');
				foreach ($value['album_data']['stores'] as $key2 => $value2) {
					if (isset($data['stores'][(int)$value2])) {
						$value['view_link'][$value2] = $data['stores'][$value2]['url'] . 'index.php?route=gallery/photos&album_id='.$value['album_id'];
					}
				}
				$data['albums'][$key] = $value;
			}
		}
		//Set language variables
		
		//Album
		$data['text_confirm'] 		= $this->language->get('text_confirm');
		$data['text_list_empty'] 	= $this->language->get('text_list_empty');
		$data['arr_enabled'] 		= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));
		
		$data['album_types'] 			= array($this->language->get('album_type1'), $this->language->get('album_type2'), $this->language->get('album_type3'));

		//Buttons
		$data['button_text_save'] = $this->language->get('button_text_save');
		$data['button_text_insert'] = $this->language->get('button_text_insert');
		$data['button_text_copy'] = $this->language->get('button_text_copy');
		$data['button_text_edit'] = $this->language->get('button_text_edit');
		$data['button_text_view'] = $this->language->get('button_text_view');
		$data['button_text_delete'] = $this->language->get('button_text_delete');
		$data['button_text_cancel'] = $this->language->get('button_text_cancel');
		
		//Table
		$data['column_name'] = $this->language->get('column_name');
		$data['column_album_type'] = $this->language->get('column_album_type');
		$data['column_enabled'] = $this->language->get('column_enabled');
		$data['column_store'] = $this->language->get('column_store');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['text_default'] = $this->language->get('text_default');

		//Set links
		$data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$data['text_section_modules'] 	= $this->language->get('section_modules');
		$data['text_section_settings'] 	= $this->language->get('section_settings');

		$data['link_section_album_list'] 	= $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
  		$config_admin_language = $this->config->get('config_admin_language'); 
  		foreach ($data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$data['config_admin_language_id'] = $language['language_id'];
  			}
  		}
  		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_album_list'),
			'href'      => $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		//Set links
		$data['add'] = $this->url->link('gallery/album/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] = $this->url->link('gallery/album/delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['copy'] = $this->url->link('gallery/album/copy', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			$this->error['warning'] = true;
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('gallery/album_list.tpl', $data));
	}
	public function add() {
		$this->language->load('gallery/index'); 

		$this->document->addScript('view/javascript/sortable.min.js');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('gallery/index');
		$data['heading_title'] = $this->language->get('section_album');
		$data['text_edit'] = $this->language->get('section_album');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms() && $this->validateAlbum()) {
				$this->session->data['success'] = $this->language->get('text_success_added');
				$this->model_gallery_index->addAlbum($this->request->post);				
				
				$this->model_gallery_index->clearCache();

				$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
			}	
		$this->getForm($data);
	}
	public function edit() {
		$this->language->load('gallery/index'); 

		$this->document->addScript('view/javascript/sortable.min.js');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('section_edit_album');
		$data['text_edit'] = $this->language->get('section_edit_album');

		$this->load->model('gallery/index');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms() && $this->validateAlbum()) {
				$this->session->data['success'] = $this->language->get('text_success_edited');
				$this->model_gallery_index->editAlbum($this->request->post);
				
				$this->model_gallery_index->clearCache();

				$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
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

		$data['categories'] = $this->getAllCategories($this->model_gallery_index->getAllCategories());

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/image');

		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->get['album_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$album_query = $this->model_gallery_index->getAlbum((int)$this->request->get['album_id']);
		}

		if (isset($this->request->post['album_id'])) {
			$data['album_id'] = $this->request->post['album_id'];
		} elseif (!empty($album_query)) {
			$data['album_id'] = $album_query['album_id'];
		} else {
			$data['album_id'] = '';
		}

		if (isset($this->request->post['album_type'])) {
			$data['album_type'] = $this->request->post['album_type'];
		} elseif (!empty($album_query)) {
			$data['album_type'] = $album_query['album_type'];
		} else {
			$data['album_type'] = '0';
		}
		
		if (isset($this->request->post['enabled'])) {
			$data['enabled'] = $this->request->post['enabled'];
		} elseif (!empty($album_query)) {
			$data['enabled'] = $album_query['enabled'];
		} else {
			$data['enabled'] = '1';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($album_query)) {
			$data['sort_order'] = $album_query['sort_order'];
		} else {
			$data['sort_order'] = '0';
		}
		
		$album_names = array();
		foreach ($data['languages'] as $key => $language) {
			$album_names[$language['language_id']] = $this->language->get('text_new_album');
		}

		if (isset($this->request->post['album_data'])) {
			$data['album_data'] = $this->request->post['album_data'];
		} elseif (!empty($album_query)) {
			$data['album_data'] = $album_query['album_data'];
		} else {
			$data['album_data'] = array(
				'album_name'				=> $album_names,
				'album_seo_url'				=> '',
				'stores'					=> array(0 => 0),
				'photos_limit'				=> 0,
				'show_limiter'				=> 1,
				'thumb_width'				=> 228,
				'thumb_height'				=> 228,
				'popup_width'				=> 500,
				'popup_height'				=> 500,
				'js_lib_type'				=> 0,
				'number_of_columns_xs'		=> 1,
				'number_of_columns_sm'		=> 2,
				'number_of_columns_md'		=> 4,
				'number_of_columns_lg'		=> 4,
				'use_lazyload' 				=> 0,
				'show_album_description' 	=> 1,
				'album_categories' 			=> array(),
				'include_additional_images' => 0,
				'album_directory' 			=> '',
				'gallery_images' 			=> array(),
				'album_title' 				=> '',
				'album_h1_title' 			=> '',
				'album_meta_keywords' 		=> '',
				'album_meta_description' 	=> '',
				'album_description' 		=> '',
			);
		}
		
		if (isset($this->request->post['album_data']['stores'])) {
			$data['album_data']['stores'] = $this->request->post['album_data']['stores'];
		} elseif (!empty($album_query)) {
			$data['album_data']['stores'] = $album_query['album_data']['stores'];
		} else {
			$data['album_data']['stores'] = array();
		}

		if (isset($this->request->post['album_data']['album_categories'])) {
			$data['album_data']['album_categories'] = $this->request->post['album_data']['album_categories'];
		} elseif (!empty($album_query)) {
			if (isset($data['album_data']['album_categories'])) {
				$data['album_data']['album_categories'] = $album_query['album_data']['album_categories'];
			}else{
				$data['album_data']['album_categories'] = array();	
			}
		} else {
			$data['album_data']['album_categories'] = array();
		}
		if (isset($this->request->post['album_data']['gallery_images'])) {
			$data['album_data']['gallery_images'] = $this->request->post['album_data']['gallery_images'];
		} elseif (!empty($album_query)) {
			if (isset($data['album_data']['gallery_images'])) {
				$data['album_data']['gallery_images'] = $album_query['album_data']['gallery_images'];
			}else{
				$data['album_data']['gallery_images'] = array();	
			}
		} else {
			$data['album_data']['gallery_images'] = array();
		}

		$this->load->model('tool/image');
		if (!empty($album_query['album_data']['gallery_images'])) {
			$gallery_images = array();
			foreach ($album_query['album_data']['gallery_images'] as $gallery_image) {
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
			$data['album_data']['gallery_images'] = $gallery_images;
			$data['image_row'] = max($id_s)+1;
		}else{
			$data['image_row'] = 0;
		}
		if (empty($data['album_data']['cover_image']['image'])) {
			$data['album_data']['cover_image']['image'] = 'no_image.png';
			$data['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}else{
			$data['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize($data['album_data']['cover_image']['image'], 100, 100);
		}

		//Album
		$data['arr_js_lib_types'] 	= array($this->language->get('arr_js_lib_type1'), $this->language->get('arr_js_lib_type2'), $this->language->get('arr_js_lib_type3'),$this->language->get('arr_js_lib_type4'));
		$data['album_types'] 			= array($this->language->get('album_type1'), $this->language->get('album_type2'), $this->language->get('album_type3'));
		$data['arr_enabled'] 		= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));
		
		//help variables 
		$data['entry_album_seo_name_help'] = $this->language->get('entry_album_seo_name_help');
		$data['entry_gallery_photos_limit_help'] = $this->language->get('entry_gallery_photos_limit_help');
		$data['entry_directory_help'] = $this->language->get('entry_directory_help');
		$data['entry_album_status_help'] = $this->language->get('entry_album_status_help');
		$data['entry_enable_full_cache_help'] = $this->language->get('entry_enable_full_cache_help');
		$data['entry_galleries_seo_name_help'] = $this->language->get('entry_galleries_seo_name_help');
		$data['entry_gallery_include_colorbox_help'] = $this->language->get('entry_gallery_include_colorbox_help');
		$data['entry_gallery_include_lightbox_help'] = $this->language->get('entry_gallery_include_lightbox_help');
		$data['entry_gallery_include_fancybox_help'] = $this->language->get('entry_gallery_include_fancybox_help');
		$data['entry_gallery_include_jstabs_help'] = $this->language->get('entry_gallery_include_jstabs_help');
		$data['entry_gallery_include_lazyload_help'] = $this->language->get('entry_gallery_include_lazyload_help');
		$data['entry_module_name_help'] = $this->language->get('entry_module_name_help');
		$data['entry_use_lazyload_help'] = $this->language->get('entry_use_lazyload_help');
		$data['entry_show_album_galleries_link_help'] = $this->language->get('entry_show_album_galleries_link_help');
		$data['entry_photo_album_list_help'] = $this->language->get('entry_photo_album_list_help');
		$data['entry_show_album_link_help'] = $this->language->get('entry_show_album_link_help');
		$data['entry_photos_limit_help'] = $this->language->get('entry_photos_limit_help');
		
		$data['entry_album_name'] 	= $this->language->get('entry_album_name');
		$data['entry_album_seo_name'] = $this->language->get('entry_album_seo_name');
		$data['entry_album_type'] 	= $this->language->get('entry_album_type');
		$data['entry_store'] 	= $this->language->get('entry_store');
		$data['entry_gallery_photos_limit'] 	= $this->language->get('entry_gallery_photos_limit');
		$data['entry_gallery_show_limiter'] 	= $this->language->get('entry_gallery_show_limiter');
		$data['entry_thumb_size'] 	= $this->language->get('entry_thumb_size');
		$data['entry_popup_size'] 	= $this->language->get('entry_popup_size');
		$data['entry_category_list'] 	= $this->language->get('entry_category_list');
		$data['entry_include_additional_images'] 	= $this->language->get('entry_include_additional_images');
		$data['entry_directory'] 		= $this->language->get('entry_directory');
		$data['entry_galery_images'] 	= $this->language->get('entry_galery_images');
		$data['entry_cover_image'] 	= $this->language->get('entry_cover_image');
		$data['entry_show_gallery_album_description'] 	= $this->language->get('entry_show_gallery_album_description');
		$data['entry_use_lazyload'] 	= $this->language->get('entry_use_lazyload');
		
		//bootstrap
		$data['text_bootstrap'] 					= $this->language->get('text_bootstrap');
		$data['entry_number_of_columns_xs'] 		= $this->language->get('entry_number_of_columns_xs');
		$data['entry_number_of_columns_xs_help'] 	= $this->language->get('entry_number_of_columns_xs_help');
		$data['entry_number_of_columns_sm'] 		= $this->language->get('entry_number_of_columns_sm');
		$data['entry_number_of_columns_sm_help'] 	= $this->language->get('entry_number_of_columns_sm_help');
		$data['entry_number_of_columns_md'] 		= $this->language->get('entry_number_of_columns_md');
		$data['entry_number_of_columns_md_help'] 	= $this->language->get('entry_number_of_columns_md_help');
		$data['entry_number_of_columns_lg'] 		= $this->language->get('entry_number_of_columns_lg');
		$data['entry_number_of_columns_lg_help'] 	= $this->language->get('entry_number_of_columns_lg_help');
		$data['entry_number_of_columns_values'] 	= array($this->language->get('entry_number_of_columns_auto'), 1, 2, 3, 4, 6, 12);
		$data['entry_number_of_columns_auto_help'] 	= $this->language->get('entry_number_of_columns_auto_help');
		
		$data['entry_title'] 	= $this->language->get('entry_title');
		$data['entry_h1'] 	= $this->language->get('entry_h1');
		$data['entry_description'] 	= $this->language->get('entry_description');
		$data['entry_meta_keywords'] 	= $this->language->get('entry_meta_keywords');
		$data['entry_meta_description'] 	= $this->language->get('entry_meta_description');
		$data['entry_album_status'] 	= $this->language->get('entry_album_status');
		$data['entry_sort_order'] 	= $this->language->get('entry_sort_order');
		$data['entry_js_library'] = $this->language->get('entry_js_library');

		// h2
		$data['text_album_page_settings'] = $this->language->get('text_album_page_settings');
		$data['text_album_data'] = $this->language->get('text_album_data');
		
		$data['text_image_manager'] 	= $this->language->get('text_image_manager');
		$data['text_select_all'] 	= $this->language->get('text_select_all');
		$data['text_unselect_all'] 	= $this->language->get('text_unselect_all');
		$data['text_browse'] 	= $this->language->get('text_browse');
		$data['text_clear'] 	= $this->language->get('text_clear');
		$data['text_default'] 	= $this->language->get('text_default');
		$data['text_image_manager'] 	= $this->language->get('text_image_manager');
		$data['text_placeholder_description'] 	= $this->language->get('text_placeholder_description');
		$data['text_enabled'] 	= $this->language->get('text_enabled');
		$data['text_disabled'] 	= $this->language->get('text_disabled');
		$data['text_error_check_form'] 	= $this->language->get('text_error_check_form');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		//Tabs
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');

		//Buttons
		$data['button_text_save'] = $this->language->get('button_text_save');
		$data['button_text_cancel'] = $this->language->get('button_text_cancel');
		$data['button_text_add_image'] = $this->language->get('button_text_add_image');
		$data['button_text_remove'] = $this->language->get('button_text_remove');
		
		$data['button_text_load_images'] = $this->language->get('button_text_load_images');
		$data['button_text_check_images'] = $this->language->get('button_text_check_images');

		//Set links
		$data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$data['text_section_modules'] 	= $this->language->get('section_modules');
		$data['text_section_settings'] 	= $this->language->get('section_settings');
		
		$data['text_load_images'] = $this->language->get('text_load_images');
		$data['text_check_images'] = $this->language->get('text_check_images');
		$data['text_load_images_help'] = $this->language->get('text_load_images_help');
		$data['text_check_images_help'] = $this->language->get('text_check_images_help');

		$data['link_section_album_list'] 	= $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_album_list'),
			'href'      => $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		if (!empty($album_query)) {
	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('section_edit_album'),
				'href'      => $this->url->link('gallery/album/edit', 'album_id='.$this->request->get['album_id']. '&token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => ' :: '
	   		);
   		}else{
	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('section_album'),
				'href'      => $this->url->link('gallery/album/add', 'token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => ' :: '
	   		);
   		}

		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		//Set links
		$data['cancel'] = $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL');
		$data['save'] = $this->url->link('gallery/album/add', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->get['album_id'])) {
			$data['edit'] = $this->url->link('gallery/album/edit', 'album_id='.$this->request->get['album_id']. '&token=' . $this->session->data['token'], 'SSL');
		}
		
		$data['token'] = $this->session->data['token'];

		//Set settings
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('gallery/album_form.tpl', $data));
	}
	public function delete() {

		$this->language->load('gallery/index'); 
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
			
			$this->load->model('gallery/index');

			$this->model_gallery_index->deleteAlbum($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_success_deleted');

			$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
		}else{
			$this->error['warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function copy() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
			$this->language->load('gallery/index'); 
			
			$this->load->model('gallery/index');

			$this->model_gallery_index->copyAlbum($this->request->post['selected'], $this->language->get('text_copy'));
			
			$this->session->data['success'] = $this->language->get('text_success_copied');

			$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
		}else{
			$this->error['warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	private function validatePerms() {
		if (!$this->user->hasPermission('modify', 'gallery/album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	private function validateAlbum() {
		if (!isset($this->request->post['album_data']['stores'])) {
			$this->error['error_stores'] = $this->language->get('error_stores');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!isset($this->request->post['album_data']['album_categories']) && $this->request->post['album_type'] == 0) {
			$this->error['error_categories'] = $this->language->get('error_categories');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (empty($this->request->post['album_data']['thumb_width']) || empty($this->request->post['album_data']['thumb_height'])) {
			$this->error['error_thumb_image_dimensions'] = $this->language->get('error_image_dimensions');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (empty($this->request->post['album_data']['popup_width']) || empty($this->request->post['album_data']['popup_height'])) {
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
			$files = glob($this->request->post['path']);
			$images = array();
			$this->load->model('tool/image');
			$this->load->language('gallery/index');
			foreach ($files as $key => $file) {
				if ($file !== DIR_IMAGE && is_file($file)) {
					$image['image'] = str_replace(DIR_IMAGE, '', $file);
					$image['thumb'] = $this->model_tool_image->resize($image['image'], 100, 100);
					if (!empty($image['thumb'])) {
						$images[] = $image;
					}
				}else{
					unset($files[$key]);
				}
			}

			if (empty($files)) {
				$result['warning'] = sprintf($this->language->get('warning_image_loader_empty'), $this->request->post['path']);
			}else{
				$result['success'] = sprintf($this->language->get('success_image_loader_loaded'), count($images));
				$result['files'] = $images;

			}
			$this->response->setOutput(json_encode($result));
		}
	}
	public function checkImages(){
		if (isset($this->request->post['path']) && $this->validatePerms()) {	
			$files = glob($this->request->post['path']);
			$images = array();
			$this->load->model('tool/image');
			$this->load->language('gallery/index');
			foreach ($files as $key => $file) {
				if ($file !== DIR_IMAGE && is_file($file)) {
					$images[]['image'] = str_replace(DIR_IMAGE, '', $file);
				}else{
					unset($files[$key]);
				}
			}

			if (empty($files)) {
				$result['warning'] = sprintf($this->language->get('warning_image_checker_empty'), $this->request->post['path']);
			}else{
				$result['success'] = sprintf($this->language->get('success_image_checker_checked'), count($images));
			}
			$this->response->setOutput(json_encode($result));
		}
	}
}
?>
