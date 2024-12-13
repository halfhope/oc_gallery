<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGallerySettings extends Controller {
	private $error = array();

	public function index() {
		$data = $this->load->language('gallery/gallery');
		$this->load->model('gallery/gallery');

		$this->document->setTitle($this->language->get('section_settings'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->session->data['success'] = $this->language->get('text_success_settings');
			$this->model_setting_setting->editSetting('config_gallery', $this->request->post);

			$this->model_gallery_gallery->clearCache();
			$this->model_gallery_gallery->updateGallerySeoUrl($this->request->post);

			$this->response->redirect($this->url->link('gallery/settings', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

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

		$data['heading_title'] = $this->language->get('section_settings');
		$data['config_gallery_modules_cache_enabled'] = $this->config->get('config_gallery_modules_cache_enabled');
		$data['config_gallery_cover_image_width'] 		= $this->config->get('config_gallery_cover_image_width');
		$data['config_gallery_cover_image_height'] 		= $this->config->get('config_gallery_cover_image_height');
		$data['config_gallery_show_counter'] 				= $this->config->get('config_gallery_show_counter');
		$data['config_gallery_show_description'] 			= $this->config->get('config_gallery_show_description');

		$data['config_gallery_include_colorbox'] 			= $this->config->get('config_gallery_include_colorbox');
		$data['config_gallery_include_lightbox'] 			= $this->config->get('config_gallery_include_lightbox');
		$data['config_gallery_include_fancybox'] 			= $this->config->get('config_gallery_include_fancybox');
		$data['config_gallery_include_magnific_popup'] 		= $this->config->get('config_gallery_include_magnific_popup');
		$data['config_gallery_include_jstabs'] 			= $this->config->get('config_gallery_include_jstabs');
		$data['config_gallery_include_lazyload'] 			= $this->config->get('config_gallery_include_lazyload');
		$data['config_gallery_galleries_seo_name'] 				= $this->config->get('config_gallery_galleries_seo_name');
		$data['config_gallery_galleries_include_seo_path'] 		= $this->config->get('config_gallery_galleries_include_seo_path');
		$data['config_gallery_module_category_layout_id'] = $this->config->get('config_gallery_module_category_layout_id');
		$data['config_gallery_module_product_layout_id'] 	= $this->config->get('config_gallery_module_product_layout_id');
		$data['config_gallery_module_hook_layout_id'] 	= $this->config->get('config_gallery_module_hook_layout_id');

		$data['config_gallery_galleries_title'] 					= $this->config->get('config_gallery_galleries_title');
		$data['config_gallery_galleries_breadcrumb'] 				= $this->config->get('config_gallery_galleries_breadcrumb');
		$data['config_gallery_galleries_h1_title'] 				= $this->config->get('config_gallery_galleries_h1_title');
		$data['config_gallery_galleries_meta_keywords'] 			= $this->config->get('config_gallery_galleries_meta_keywords');
		$data['config_gallery_galleries_meta_description'] 		= $this->config->get('config_gallery_galleries_meta_description');
		$data['config_gallery_galleries_description'] 			= $this->config->get('config_gallery_galleries_description');

		$data['text_yes'] 		= $this->language->get('text_yes');
		$data['text_no'] 			= $this->language->get('text_no');

		//Set language variables
		$data['text_edit'] 		= $this->language->get('section_settings');
		$data['arr_enabled'] 	= array($this->language->get('text_disabled'), $this->language->get('text_enabled'));

		// bootstrap grid control
		if (isset($this->request->post['config_gallery_bootstrap_grid'])) {
			$data['config_gallery_bootstrap_grid'] = $this->request->post['config_gallery_bootstrap_grid'];
		} elseif (!empty($this->config->get('config_gallery_bootstrap_grid'))) {
			$data['config_gallery_bootstrap_grid'] = $this->config->get('config_gallery_bootstrap_grid');
		} else {
			foreach ($data['stores'] as $store) {
				$data['config_gallery_bootstrap_grid'][$store['store_id']] = array(
					'xs' => 12,
					'sm' => 6, 
					'md' => 4,
					'lg' => 3
				);
			}
		}

		$data['bootstrap_grid_to_cols'] = [0 => 0, 12 => 1, 6 => 2, 4 => 3, 3 => 4, 2 => 6];

		//Set links
		$data['text_section_gallery_list'] 	= $this->language->get('section_gallery_list');
		$data['text_section_modules'] 	= $this->language->get('section_modules');
		$data['text_section_settings'] 	= $this->language->get('section_settings');

		$data['link_section_gallery_list'] 	= $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link('gallery/settings', 'user_token=' . $this->session->data['user_token'], 'SSL');

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
       		'text'      => $this->language->get('section_settings'),
			'href'      => $this->url->link('gallery/settings', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

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

		$data['cancel'] = $this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['action'] = $this->url->link('gallery/settings', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['html_image'] = '<img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" />';
		}

		$this->response->setOutput($this->load->view('gallery/settings', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'gallery/settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {return true;} else {return false;}
	}
}