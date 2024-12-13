<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryModules extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/module');

		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));
		if (isset($this->request->get['module_id'])) {
			$data['selected_module_id'] = $this->request->get['module_id'];
		}
		$this->load->language('gallery/index');

		$this->document->setTitle($this->language->get('section_modules'));
		$this->load->model('setting/setting');
		$this->load->model('gallery/index');
		$data['albums'] = $this->model_gallery_index->getAlbums();
		
		if (empty($data['albums'])) {
			$this->session->data['error_warning'] = $this->language->get('text_error_no_albums');
			$this->response->redirect($this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->load->model('extension/module');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['gallery_module'])) {
				$exists_modules = $this->model_extension_module->getModulesByCode('gallery');
				foreach ($this->request->post['gallery_module'] as $module) {
					if (isset($module['module_id'])) {
						foreach ($exists_modules as $key => $value) {
							if ($value['module_id'] == $module['module_id']) {
								unset($exists_modules[$key]);
							}
						}
						$this->model_extension_module->editModule($module['module_id'], $module);
					}else{
						$this->model_extension_module->addModule('gallery', $module);
					}
				}

				foreach ($exists_modules as $key => $value) {
					$this->model_extension_module->deleteModule($value['module_id']);	
				}

				$this->session->data['success'] = $this->language->get('text_success_modules');
				
				$this->model_gallery_index->clearCache();

				$this->response->redirect($this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('section_modules');
		$data['text_edit'] = $this->language->get('section_modules');
		
		$data['albums'] = $this->model_gallery_index->getAlbums();
		
		$categories = $this->model_gallery_index->getAllCategories();
		$data['categories'] = $this->getAllCategories($categories);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$config_admin_language = $this->config->get('config_admin_language'); 
  		foreach ($data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$data['config_admin_language_id'] = $language['language_id'];
  			}
  		}

		$data['module_types'] = array($this->language->get('module_type1'), $this->language->get('module_type2'));
  		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_new_module_name'] = $this->language->get('text_new_module_name');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_show_album_text'] = $this->language->get('text_show_album_text');
		$data['text_show_galleries_text'] = $this->language->get('text_show_galleries_text');
		$data['text_seo_hook'] = $this->language->get('text_seo_hook');
		
		$data['entry_module_name'] = $this->language->get('entry_module_name');
		$data['entry_module_header'] = $this->language->get('entry_module_header');
		$data['entry_show_module_header'] = $this->language->get('entry_show_module_header');
		$data['entry_module_type'] = $this->language->get('entry_module_type');
		$data['entry_album_list'] = $this->language->get('entry_album_list');
		$data['entry_photo_album_list'] = $this->language->get('entry_photo_album_list');
		$data['entry_photos_limit'] = $this->language->get('entry_photos_limit');
		$data['entry_show_covers'] = $this->language->get('entry_show_covers');
		$data['entry_cover_size'] = $this->language->get('entry_cover_size');
		$data['entry_show_counter'] = $this->language->get('entry_show_counter');
		$data['entry_show_album_link'] = $this->language->get('entry_show_album_link');
		$data['entry_show_album_text'] = $this->language->get('entry_show_album_text');
		$data['entry_show_album_galleries_link'] = $this->language->get('entry_show_album_galleries_link');
		$data['entry_show_album_description'] = $this->language->get('entry_show_album_description');
		$data['entry_thumb_size_mod'] = $this->language->get('entry_thumb_size_mod');
		$data['entry_popup_size_mod'] = $this->language->get('entry_popup_size_mod');
		$data['entry_status'] = $this->language->get('entry_status');
				
		$data['button_text_save'] = $this->language->get('button_save');
		$data['button_text_cancel'] = $this->language->get('button_cancel');
		$data['button_text_add_module'] = $this->language->get('button_text_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		
		//Set links
		$data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$data['text_section_modules'] 	= $this->language->get('section_modules');
		$data['text_section_settings'] 	= $this->language->get('section_settings');

		$data['link_section_album_list'] 	= $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
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
				
  		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
       		'text'      => $this->language->get('section_modules'),
			'href'      => $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL');

		$data['modules'] = array();

		if (isset($this->request->post['gallery_module'])) {
			$data['modules'] = $this->request->post['gallery_module'];
		} else { 
			foreach ($this->model_extension_module->getModulesByCode('gallery') as $module) {
				$data['modules'][(int)$module['module_id']] = unserialize($module['setting']);
				$data['modules'][(int)$module['module_id']]['module_id'] = $module['module_id'];
				if ($data['modules'][(int)$module['module_id']]['module_type'] == 2) {
					$data['hook_module_id'] = (int)$module['module_id'];
					unset($data['modules'][(int)$module['module_id']]);
				}
			}			
		}

		$this->load->model('catalog/product');
		
		$data['token'] = $this->session->data['token'];

		foreach ($data['modules'] as $key => $value) {
			$data['modules'][$key]['album_list'] = (isset($value['album_list']) ? $value['album_list'] : array());
			$data['modules'][$key]['photo_album_list'] = (isset($value['photo_album_list']) ? $value['photo_album_list'] : array());
			$data['modules'][$key]['album_show_on_categories'] = (isset($value['album_show_on_categories']) ? $value['album_show_on_categories'] : array());
			$data['modules'][$key]['album_show_on_products'] = (isset($value['album_show_on_products']) ? $value['album_show_on_products'] : array());
			
			$album_show_on_products = array();

			foreach ($data['modules'][$key]['album_show_on_products'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					$album_show_on_products[$product_id] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
			$data['modules'][$key]['album_show_on_products'] = $album_show_on_products;
		}

		$data['config_gallery_module_category_layout_id'] 			= $this->config->get('config_gallery_module_category_layout_id');
		$data['config_gallery_module_product_layout_id'] 				= $this->config->get('config_gallery_module_product_layout_id');
		$data['config_gallery_module_hook_layout_id'] 				= $this->config->get('config_gallery_module_hook_layout_id');
				
		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('gallery/modules.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'gallery/modules')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
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
					'name'        => addslashes($parent_name . $category['name'])
				);

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

		return $output;
	}
}