<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryModules extends Controller {
	private $error = array();
	
	public function index() {
		$data = $this->load->language('gallery/gallery');
		$this->load->language('marketplace/extension');

		$this->load->model('setting/setting');
		$this->load->model('setting/extension');
		$this->load->model('setting/module');
		$this->load->model('gallery/gallery');
		
		$this->document->setTitle($this->language->get('section_modules'));

		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		if (isset($this->request->get['module_id'])) {
			$data['selected_module_id'] = $this->request->get['module_id'];
		}

		$data['galleries'] = $this->model_gallery_gallery->getGalleries();

		if (empty($data['galleries'])) {
			$this->session->data['error_warning'] = $this->language->get('text_error_no_galleries');
			$this->response->redirect($this->url->link('gallery/gallery', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['gallery_module'])) {
				$exists_modules = $this->model_setting_module->getModulesByCode('gallery');
				foreach ($this->request->post['gallery_module'] as $module) {
					if (isset($module['module_id'])) {
						foreach ($exists_modules as $key => $value) {
							if ($value['module_id'] == $module['module_id']) {
								unset($exists_modules[$key]);
							}
						}
						$this->model_setting_module->editModule($module['module_id'], $module);
					}else{
						$this->model_setting_module->addModule('gallery', $module);
					}
				}

				foreach ($exists_modules as $key => $value) {
					$this->model_setting_module->deleteModule($value['module_id']);
				}

				$this->session->data['success'] = $this->language->get('text_success_modules');

				$this->model_gallery_gallery->clearCache();

				$this->response->redirect($this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('section_modules');
		$data['text_edit'] = $this->language->get('section_modules');

		$data['galleries'] = $this->model_gallery_gallery->getGalleries();

		$categories = $this->model_gallery_gallery->getAllCategories();
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
       		'text'      => $this->language->get('section_modules'),
			'href'      => $this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL')
   		);

		$data['action'] = $this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('gallery/modules', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['modules'] = array();

		if (isset($this->request->post['gallery_module'])) {
			$data['modules'] = $this->request->post['gallery_module'];
		} else {
			foreach ($this->model_setting_module->getModulesByCode('gallery') as $module) {
				$data['modules'][(int)$module['module_id']] = json_decode($module['setting'], true);
				$data['modules'][(int)$module['module_id']]['module_id'] = $module['module_id'];
				
				if ($data['modules'][(int)$module['module_id']]['module_type'] == 2) {
					$data['hook_module_id'] = (int)$module['module_id'];
					unset($data['modules'][(int)$module['module_id']]);
				}
			}
		}

		$this->load->model('catalog/product');

		$data['user_token'] = $this->session->data['user_token'];

		foreach ($data['modules'] as $key => $value) {
			$data['modules'][$key]['gallery_list'] = (isset($value['gallery_list']) ? $value['gallery_list'] : array());
			$data['modules'][$key]['photo_gallery_list'] = (isset($value['photo_gallery_list']) ? $value['photo_gallery_list'] : array());
			$data['modules'][$key]['gallery_show_on_categories'] = (isset($value['gallery_show_on_categories']) ? $value['gallery_show_on_categories'] : array());
			$data['modules'][$key]['gallery_show_on_products'] = (isset($value['gallery_show_on_products']) ? $value['gallery_show_on_products'] : array());

			$gallery_show_on_products = array();

			foreach ($data['modules'][$key]['gallery_show_on_products'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$gallery_show_on_products[$product_id] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
			$data['modules'][$key]['gallery_show_on_products'] = $gallery_show_on_products;
		}

		$data['config_gallery_bootstrap_grid'] = $this->config->get('config_gallery_bootstrap_grid');
		if (empty($data['config_gallery_boostrap_grid'])) {
			$data['config_gallery_bootstrap_grid'] = array(
				'xs' => 12,
				'sm' => 6, 
				'md' => 4,
				'lg' => 3
			);
		}
		$data['bootstrap_grid_to_cols'] = [0 => 0, 12 => 1, 6 => 2, 4 => 3, 3 => 4, 2 => 6];

		$data['config_gallery_module_category_layout_id'] 			= $this->config->get('config_gallery_module_category_layout_id');
		$data['config_gallery_module_product_layout_id'] 			= $this->config->get('config_gallery_module_product_layout_id');
		$data['config_gallery_module_hook_layout_id'] 				= $this->config->get('config_gallery_module_hook_layout_id');

		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();

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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['html_image'] = '<img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" />';
		}

		$this->response->setOutput($this->load->view('gallery/modules', $data));
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