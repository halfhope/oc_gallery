<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGalleryModules extends Controller {
	
	public 	$_route 		= 'extension/module/gallery';
	public 	$_model 		= 'model_extension_module_gallery';
	private $_version 		= '1.5';

	private $error = [];

	public function index() {
		// Initial
		$this->load->model($this->_route);
		$data = $this->load->language($this->_route);

		$this->load->model('extension/module');

		$this->document->setTitle($this->language->get('section_modules'));
		$data['heading_title'] = $this->language->get('section_modules');
		$data['version'] = $this->_version;

		$this->document->addStyle('view/javascript/jquery/switcher/switcher.css');
		$this->document->addScript('view/javascript/jquery/switcher/jquery.switcher.min.js');

		// POST request handler 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$exists_modules = $this->model_extension_module->getModulesByCode('gallery');

			if (isset($this->request->post['gallery_module'])) {
				foreach ($this->request->post['gallery_module'] as $module) {
					if (isset($module['module_id'])) {
						foreach ($exists_modules as $key => $value) {
							if ($value['module_id'] == $module['module_id']) {
								unset($exists_modules[$key]);
							}
						}
						$this->model_extension_module->editModule($module['module_id'], $module);
					} else {
						$this->model_extension_module->addModule('gallery', $module);
					}
				}
			}
			$this->{$this->_model}->clearCache();

			foreach ($exists_modules as $key => $value) {
				$this->model_extension_module->deleteModule($value['module_id']);
			}

			$this->session->data['success'] = $this->language->get('text_success_modules');

			$this->response->redirect($this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'], 'SSL'));
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
			'href'      => $this->url->link($this->_route, 'token=' . $this->session->data['token'], 'SSL')
		];

		$data['breadcrumbs'][] = [
			'text'      => $this->language->get('section_modules'),
			'href'      => $this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'], 'SSL')
		];

		// Page Data
		$categories = $this->{$this->_model}->getAllCategories();
		$data['categories'] = $this->getAllCategories($categories);

		if (isset($this->request->get['module_id'])) {
			$data['selected_module_id'] = $this->request->get['module_id'];
		}

		$data['galleries'] = $this->{$this->_model}->getGalleries();
		if (empty($data['galleries'])) {
			$this->session->data['error_warning'] = $this->language->get('text_error_no_galleries');
			$this->response->redirect($this->url->link($this->_route . '/gallery', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['modules'] = [];
		if (isset($this->request->post['gallery_module'])) {
			$data['modules'] = $this->request->post['gallery_module'];
		} else {
			foreach ($this->model_extension_module->getModulesByCode('gallery') as $module) {
				$data['modules'][(int)$module['module_id']] = json_decode($module['setting'], true);
				$data['modules'][(int)$module['module_id']]['module_id'] = $module['module_id'];
			}
		}

		$this->load->model('catalog/product');
		foreach ($data['modules'] as $key => $value) {
			$data['modules'][$key]['gallery_list'] = (isset($value['gallery_list']) ? $value['gallery_list'] : []);
			$data['modules'][$key]['photo_gallery_list'] = (isset($value['photo_gallery_list']) ? $value['photo_gallery_list'] : []);
			$data['modules'][$key]['gallery_show_on_categories'] = (isset($value['gallery_show_on_categories']) ? $value['gallery_show_on_categories'] : []);
			$data['modules'][$key]['gallery_show_on_products'] = (isset($value['gallery_show_on_products']) ? $value['gallery_show_on_products'] : []);

			$gallery_show_on_products = [];

			foreach ($data['modules'][$key]['gallery_show_on_products'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if ($product_info) {
					$gallery_show_on_products[$product_id] = [
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					];
				}
			}

			$data['modules'][$key]['gallery_show_on_products'] = $gallery_show_on_products;
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$config_admin_language = $this->config->get('config_admin_language');
  		foreach ($data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$data['config_admin_language_id'] = $language['language_id'];
  			}
  		}

		$data['config_gallery_bootstrap_grid'] = $this->config->get('config_gallery_bootstrap_grid');
		if (empty($data['config_gallery_boostrap_grid'])) {
			$data['config_gallery_bootstrap_grid'] = [
				'xs' => 12,
				'sm' => 6, 
				'md' => 4,
				'lg' => 3
			];
		}
		$data['bootstrap_grid_to_cols'] = [0 => 0, 12 => 1, 6 => 2, 4 => 3, 3 => 4, 2 => 6];

		$data['config_gallery_module_category_layout_id'] = $this->config->get('config_gallery_module_category_layout_id');
		$data['config_gallery_module_product_layout_id'] = $this->config->get('config_gallery_module_product_layout_id');

		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();

		// Language
		$data['module_types'] = [
			$this->language->get('module_type1'), 
			$this->language->get('module_type2')
		];

		$data['text_section_galleries'] = $this->language->get('section_galleries');
		$data['text_section_modules'] = $this->language->get('section_modules');
		$data['text_section_settings'] = $this->language->get('section_settings');

		$data['text_edit'] = $this->language->get('section_modules');
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));

		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['html_image'] = '<img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" />';
		}

		// Links
		$data['link_section_galleries'] = $this->url->link($this->_route . '/gallery', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_modules'] = $this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'], 'SSL');
		$data['link_section_settings'] = $this->url->link($this->_route . '/settings', 'token=' . $this->session->data['token'], 'SSL');

		$data['action'] = $this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link($this->_route . '/modules', 'token=' . $this->session->data['token'], 'SSL');

		// Child Controllers
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Output
		$this->response->setOutput($this->load->view($this->_route . '/modules', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', $this->_route . '/modules')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
		$output = [];

		if (array_key_exists($parent_id, $categories)) {
			if ($parent_name != '') {
				$parent_name .= $this->language->get('text_separator');
			}

			foreach ($categories[$parent_id] as $category) {
				$output[$category['category_id']] = [
					'category_id' => $category['category_id'],
					'name'        => addslashes($parent_name . $category['name'])
				];

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

		return $output;
	}
}
