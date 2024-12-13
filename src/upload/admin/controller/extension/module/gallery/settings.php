<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGallerySettings extends Controller {

	public 	$_route 		= 'extension/module/gallery';
	public 	$_model 		= 'model_extension_module_gallery';
	private $_version 		= '1.5';

	private $error = [];

	public function index() {
		// Initial
		$this->load->model($this->_route);
		$data = $this->language->load($this->_route);

		$this->document->setTitle($this->language->get('section_settings'));
		$data['heading_title'] = $this->language->get('section_settings');
		$data['version'] = $this->_version;

		$this->document->addStyle('view/javascript/summernote/summernote.css');
		$this->document->addScript('view/javascript/summernote/summernote.min.js');

		$this->document->addStyle('view/javascript/jquery/switcher/switcher.css');
		$this->document->addScript('view/javascript/jquery/switcher/jquery.switcher.min.js');
		
		// POST request handler
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('config_gallery', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_settings');

			$this->{$this->_model}->clearCache();
			$this->{$this->_model}->updateGallerySeoUrl($this->request->post);

			$this->response->redirect($this->url->link($this->_route . '/settings', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// Session messages
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

		// BreadCrumbs
		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		];

		$data['breadcrumbs'][] = [
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
		];

		$data['breadcrumbs'][] = [
			'text'      => $this->language->get('section_settings'),
			'href'      => $this->url->link($this->_route . '/settings', 'token=' . $this->session->data['token'], 'SSL')
		];

		// Page Data
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$config_admin_language = $this->config->get('config_admin_language');
		foreach ($data['languages'] as $key => $language) {
			if ($config_admin_language == $language['code']) {
				$data['config_admin_language_id'] = $language['language_id'];
			}
		}

		$this->load->model('setting/store');
		$default_store[] = [
			'store_id' => '0',
			'name' => $this->language->get('text_default'),
			'url' => HTTP_CATALOG,
			'ssl' => HTTPS_CATALOG
		];
		$this->load->model('setting/store');
		$data['stores'] = array_merge($default_store, $this->model_setting_store->getStores());

		$settings = [
			'cover_image_width',
			'cover_image_height',
			'show_counter',
			'show_description',

			'include_colorbox',
			'include_lightbox',
			'include_fancybox',
			'include_magnific_popup',
			'include_jstabs',
			'include_lazyload',
			'galleries_seo_name',
			'galleries_include_seo_path',
			'module_category_layout_id',
			'module_product_layout_id',
			'module_hook_layout_id',

			'galleries_title',
			'galleries_breadcrumb',
			'galleries_h1_title',
			'galleries_meta_keywords',
			'galleries_meta_description',
			'galleries_description',

			'bootstrap_grid'
		];
		$prefix = 'config_gallery_';
		foreach ($settings as $value) {
			$data[$prefix . $value] = $this->config->get($prefix . $value);
		}

		if (empty($data[$prefix . 'bootstrap_grid'])) {
			foreach ($data['stores'] as $store) {
				$data[$prefix . 'bootstrap_grid'][$store['store_id']] = [
					'xs' => 12,
					'sm' => 6, 
					'md' => 4,
					'lg' => 3
				];
			}
		}
		$data['bootstrap_grid_to_cols'] = [0 => 0, 12 => 1, 6 => 2, 4 => 3, 3 => 4, 2 => 6];

		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['html_image'] = '<img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" />';
		}

		// Language
		$data['text_edit'] = $this->language->get('section_settings');

		$data['text_section_galleries']= $this->language->get('section_galleries');
		$data['text_section_modules'] = $this->language->get('section_modules');
		$data['text_section_settings'] = $this->language->get('section_settings');

		// Links
		$data['link_section_galleries'] 	= $this->url->link($this->_route . '/gallery', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_modules'] 	= $this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_settings'] 	= $this->url->link($this->_route . '/settings', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link($this->_route . '/gallery', 'token=' . $this->session->data['token'], 'SSL');
		$data['action'] = $this->url->link($this->_route . '/settings', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['feed'] = str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link($this->_route . '/feed', [],'SSL'));
		
		// Child Controllers 
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		// Output
		$this->response->setOutput($this->load->view($this->_route . '/settings', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->_route . '/settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {return true;} else {return false;}
	}
}
