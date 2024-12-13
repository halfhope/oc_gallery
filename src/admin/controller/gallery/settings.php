<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGallerySettings extends Controller {
	private $error = array();
	public function index() {
		$this->language->load('gallery/index'); 
		$this->document->setTitle($this->language->get('section_settings'));
		
		$this->load->model('gallery/index');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->session->data['success'] = $this->language->get('text_success_settings');
			$this->model_setting_setting->editSetting('config_gallery', $this->request->post);
			
			$this->model_gallery_index->clearCache();
			
			// Add SEO URL
			// Get seo query
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_gallery` WHERE `query` = 'gallery/gallery'");
			
			foreach ($this->request->post['config_gallery_galleries_seo_name'] as $store_id => $alias) {
				if (!empty($this->request->post['config_gallery_galleries_seo_name'][$store_id])) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias_gallery` (`query`, `keyword`, `store_id`) VALUES ('gallery/gallery', '" . $this->db->escape($alias) . "', " . $store_id . ")");
				} 
			}

			$this->redirect($this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('setting/store');
		
		$default_store[] = array(
			'store_id' => '0',
			'name' => $this->language->get('text_default'),
			'url' => HTTP_CATALOG,
			'ssl' => HTTPS_CATALOG
		);
		$this->data['stores'] = array_merge($default_store, $this->model_setting_store->getStores());

		$this->data['heading_title'] = $this->language->get('section_settings');
		$this->data['config_gallery_modules_cache_enabled'] = $this->config->get('config_gallery_modules_cache_enabled');
		$this->data['config_gallery_cover_image_width'] 		= $this->config->get('config_gallery_cover_image_width');
		$this->data['config_gallery_cover_image_height'] 		= $this->config->get('config_gallery_cover_image_height');
		$this->data['config_gallery_show_counter'] 				= $this->config->get('config_gallery_show_counter');
		$this->data['config_gallery_show_description'] 			= $this->config->get('config_gallery_show_description');

		$this->data['config_gallery_include_colorbox'] 			= $this->config->get('config_gallery_include_colorbox');
		$this->data['config_gallery_include_lightbox'] 			= $this->config->get('config_gallery_include_lightbox');
		$this->data['config_gallery_include_fancybox'] 			= $this->config->get('config_gallery_include_fancybox');
		$this->data['config_gallery_include_magnific_popup'] 		= $this->config->get('config_gallery_include_magnific_popup');
		$this->data['config_gallery_include_jstabs'] 			= $this->config->get('config_gallery_include_jstabs');
		$this->data['config_gallery_include_lazyload'] 			= $this->config->get('config_gallery_include_lazyload');
		$this->data['config_gallery_include_bootstrap'] 			= $this->config->get('config_gallery_include_bootstrap');
		$this->data['config_gallery_galleries_seo_name'] 				= $this->config->get('config_gallery_galleries_seo_name');
		$this->data['config_gallery_galleries_include_seo_path'] 		= $this->config->get('config_gallery_galleries_include_seo_path');
		$this->data['config_gallery_module_category_layout_id'] = $this->config->get('config_gallery_module_category_layout_id');
		$this->data['config_gallery_module_product_layout_id'] 	= $this->config->get('config_gallery_module_product_layout_id');
		$this->data['config_gallery_module_hook_layout_id'] 	= $this->config->get('config_gallery_module_hook_layout_id');

		$this->data['config_gallery_galleries_title'] 					= $this->config->get('config_gallery_galleries_title');
		$this->data['config_gallery_galleries_breadcrumb'] 				= $this->config->get('config_gallery_galleries_breadcrumb');
		$this->data['config_gallery_galleries_h1_title'] 				= $this->config->get('config_gallery_galleries_h1_title');
		$this->data['config_gallery_galleries_meta_keywords'] 			= $this->config->get('config_gallery_galleries_meta_keywords');
		$this->data['config_gallery_galleries_meta_description'] 		= $this->config->get('config_gallery_galleries_meta_description');
		$this->data['config_gallery_galleries_description'] 			= $this->config->get('config_gallery_galleries_description');
		
		$this->data['text_yes'] 		= $this->language->get('text_yes');
		$this->data['text_no'] 			= $this->language->get('text_no');
		
		$this->data['entry_gallery_cover_image_dimension'] 	= $this->language->get('entry_gallery_cover_image_dimension');
		$this->data['entry_gallery_show_counter'] 			= $this->language->get('entry_gallery_show_counter');
		$this->data['entry_gallery_show_description'] 		= $this->language->get('entry_gallery_show_description');
		$this->data['entry_galleries_seo_name'] 			= $this->language->get('entry_galleries_seo_name');
		$this->data['entry_galleries_include_seo_path'] 		= $this->language->get('entry_galleries_include_seo_path');
		
		// Layouts 
		$this->data['entry_category_layout'] 	= $this->language->get('entry_category_layout');
		$this->data['entry_product_layout'] 	= $this->language->get('entry_product_layout');
		$this->data['entry_hook_layout'] 	= $this->language->get('entry_hook_layout');
		
		$this->data['entry_gallery_include_colorbox'] 			= $this->language->get('entry_gallery_include_colorbox');
		$this->data['entry_gallery_include_lightbox'] 			= $this->language->get('entry_gallery_include_lightbox');
		$this->data['entry_gallery_include_fancybox'] 			= $this->language->get('entry_gallery_include_fancybox');
		$this->data['entry_gallery_include_magnific_popup'] 	= $this->language->get('entry_gallery_include_magnific_popup');
		$this->data['entry_gallery_include_jstabs'] 			= $this->language->get('entry_gallery_include_jstabs');
		$this->data['entry_gallery_include_lazyload'] 			= $this->language->get('entry_gallery_include_lazyload');
		$this->data['entry_gallery_include_bootstrap'] 			= $this->language->get('entry_gallery_include_bootstrap');
		//meta
		$this->data['entry_title'] 			= $this->language->get('entry_title');
		$this->data['entry_breadcrumb'] 			= $this->language->get('entry_breadcrumb');
		$this->data['entry_h1'] 			= $this->language->get('entry_h1');
		$this->data['entry_meta_keywords'] 	= $this->language->get('entry_meta_keywords');
		$this->data['entry_meta_description'] 	= $this->language->get('entry_meta_description');
		$this->data['entry_description'] 	= $this->language->get('entry_description');
		
		//Set language variables
		$this->data['text_edit'] = $this->language->get('section_settings');

		//Album
		$this->data['text_list_empty'] 	= $this->language->get('text_list_empty');
		$this->data['text_disabled'] 		= $this->language->get('text_disabled');
		$this->data['text_enabled'] 		= $this->language->get('text_enabled');
		$this->data['text_width'] 		= $this->language->get('text_width');
		$this->data['text_height'] 		= $this->language->get('text_height');
		$this->data['arr_enabled'] 		= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));
		
		// Bootstrap 
		$this->data['text_bootstrap'] 						= $this->language->get('text_bootstrap');
		$this->data['entry_number_of_columns_xs'] 			= $this->language->get('entry_number_of_columns_xs');
		$this->data['entry_number_of_columns_xs_help'] 		= $this->language->get('entry_number_of_columns_xs_help');
        $this->data['entry_number_of_columns_sm'] 			= $this->language->get('entry_number_of_columns_sm');
		$this->data['entry_number_of_columns_sm_help'] 		= $this->language->get('entry_number_of_columns_sm_help');
        $this->data['entry_number_of_columns_md'] 			= $this->language->get('entry_number_of_columns_md');
		$this->data['entry_number_of_columns_md_help'] 		= $this->language->get('entry_number_of_columns_md_help');
        $this->data['entry_number_of_columns_lg'] 			= $this->language->get('entry_number_of_columns_lg');
		$this->data['entry_number_of_columns_lg_help'] 		= $this->language->get('entry_number_of_columns_lg_help');
		$this->data['entry_number_of_columns_values'] 		= array($this->language->get('entry_number_of_columns_auto'), 1, 2, 3, 4, 6, 12);
		$this->data['entry_number_of_columns_auto_help'] 		= $this->language->get('entry_number_of_columns_auto_help');
		
		$this->data['config_gallery_number_of_columns_xs'] 	= $this->config->get('config_gallery_number_of_columns_xs');
		$this->data['config_gallery_number_of_columns_sm'] 	= $this->config->get('config_gallery_number_of_columns_sm');
		$this->data['config_gallery_number_of_columns_md'] 	= $this->config->get('config_gallery_number_of_columns_md');
		$this->data['config_gallery_number_of_columns_lg'] 	= $this->config->get('config_gallery_number_of_columns_lg');

		//help variables 
		$this->data['entry_album_seo_name_help'] = $this->language->get('entry_album_seo_name_help');
		$this->data['entry_gallery_photos_limit_help'] = $this->language->get('entry_gallery_photos_limit_help');
		$this->data['entry_directory_help'] = $this->language->get('entry_directory_help');
		$this->data['entry_album_status_help'] = $this->language->get('entry_album_status_help');
		$this->data['entry_enable_full_cache_help'] = $this->language->get('entry_enable_full_cache_help');
		$this->data['entry_galleries_seo_name_help'] = $this->language->get('entry_galleries_seo_name_help');
		$this->data['entry_gallery_include_colorbox_help'] = $this->language->get('entry_gallery_include_colorbox_help');
		$this->data['entry_gallery_include_lightbox_help'] = $this->language->get('entry_gallery_include_lightbox_help');
		$this->data['entry_gallery_include_fancybox_help'] = $this->language->get('entry_gallery_include_fancybox_help');
		$this->data['entry_gallery_include_magnific_popup_help'] = $this->language->get('entry_gallery_include_magnific_popup_help');
		$this->data['entry_gallery_include_jstabs_help'] = $this->language->get('entry_gallery_include_jstabs_help');
		$this->data['entry_gallery_include_lazyload_help'] = $this->language->get('entry_gallery_include_lazyload_help');
		$this->data['entry_gallery_include_bootstrap_help'] = $this->language->get('entry_gallery_include_bootstrap_help');
		$this->data['entry_category_layout_help'] = $this->language->get('entry_category_layout_help');
		$this->data['entry_product_layout_help'] = $this->language->get('entry_product_layout_help');
		$this->data['entry_hook_layout_help'] = $this->language->get('entry_hook_layout_help');
		$this->data['entry_module_name_help'] = $this->language->get('entry_module_name_help');
		$this->data['entry_use_lazyload_help'] = $this->language->get('entry_use_lazyload_help');
		$this->data['entry_show_album_galleries_link_help'] = $this->language->get('entry_show_album_galleries_link_help');
		$this->data['entry_photo_album_list_help'] = $this->language->get('entry_photo_album_list_help');
		$this->data['entry_show_album_link_help'] = $this->language->get('entry_show_album_link_help');
		$this->data['entry_photos_limit_help'] = $this->language->get('entry_photos_limit_help');

		//Buttons
		$this->data['button_text_save'] = $this->language->get('button_text_save');
		$this->data['button_text_cancel'] = $this->language->get('button_text_cancel');

		//Settings
		$this->data['entry_enable_full_cache'] 	= $this->language->get('entry_enable_full_cache');
		$this->data['text_feed'] 				= $this->language->get('text_feed');
		//h2
		$this->data['text_js_library_settings'] = $this->language->get('text_js_library_settings');
		$this->data['text_albums_settings'] = $this->language->get('text_albums_settings');
		$this->data['text_modules_settings'] = $this->language->get('text_modules_settings');
		$this->data['text_general_settings'] = $this->language->get('text_general_settings');

		//Set links
		$this->data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$this->data['text_section_modules'] 	= $this->language->get('section_modules');
		$this->data['text_section_settings'] 	= $this->language->get('section_settings');

		$this->data['link_section_album_list'] 	= $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_modules'] 	= $this->url->link('gallery/modules', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_settings'] 	= $this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
  		$config_admin_language = $this->config->get('config_admin_language'); 
  		foreach ($this->data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$this->data['config_admin_language_id'] = $language['language_id'];
  			}
  		}
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_settings'),
			'href'      => $this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		//Set links
		$this->data['cancel'] = $this->url->link('gallery/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link('gallery/settings', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		//Set settings
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->template = 'gallery/settings.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());

	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'gallery/settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {return true;} else {return false;}
	}
}
?>