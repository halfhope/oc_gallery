<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/

class ControllerExtensionModuleGallerySeoGallery extends Controller {

	public 	$_route 		= 'extension/module/gallery';

	private $cache_data = null;
	private $current_store_id = 0;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->current_store_id = $this->config->get('config_store_id');
		$cache_name = 'seo_pro_gallery.' . $this->current_store_id;
		$this->cache_data = $this->cache->get($cache_name);
		if (!$this->cache_data) {
			$query            = $this->db->query("SELECT LOWER(`keyword`) as 'keyword', `query` FROM " . DB_PREFIX . "url_alias_gallery WHERE store_id = " . $this->current_store_id);
			$this->cache_data = [];
			foreach ($query->rows as $row) {
				$this->cache_data['keywords'][$row['keyword']] = $row['query'];
				$this->cache_data['queries'][$row['query']]    = $row['keyword'];
			}

			$query            = $this->db->query("SELECT LOWER(`keyword`) as 'keyword', `query` FROM " . DB_PREFIX . "url_alias_gallery WHERE query LIKE '%gallery_id=%'");
			foreach ($query->rows as $row) {
				$this->cache_data['keywords'][$row['keyword']] = $row['query'];
				$this->cache_data['queries'][$row['query']]    = $row['keyword'];
			}

			$this->cache->set($cache_name, $this->cache_data);
		}
	}

	public function index() {
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		} else {
			return;
		}

		if (isset($_GET['_route_']))
			$this->request->get['_route_'] = $_GET['_route_'];
		if (isset($_GET['route']))
			$this->request->get['route'] = $_GET['route'];

		if (isset($this->request->get['gallery_id'])) {
			$this->request->get['route'] = $this->_route . '/gallery';
			if (isset($this->request->get['_route_'])) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
			}

			$this->validate();
			if (isset($this->request->get['_route_'])) {
				$this->request->get['_route_'] = $_route_;
			}
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
			return $this->gallery_flag = 'gallery';
		}

		if (isset($this->request->get['_route_'])) {

			// SEO CMS multilanguage compatibility fix
			$this->langmark_prefix = $this->registry->get('langmark_prefix');
			if ($this->langmark_prefix) {
				$this->langmark_prefix = $this->registry->get('langmark_prefix');
				$route = str_replace($this->langmark_prefix, '', $this->request->get['_route_']);
			} else {
				$route = $this->request->get['_route_'];
			}

			$parts = explode('/', trim(utf8_strtolower($route), '/'));
			list($last_part) = explode('.', array_pop($parts));
			array_push($parts, $last_part);
			
			foreach ($parts as $part) {
				$rows = [];
				foreach ($parts as $keyword) {
					if (isset($this->cache_data['keywords'][$keyword])) {
						$rows[] = [
							'keyword' => $keyword, 
							'query' => $this->cache_data['keywords'][$keyword]
						];
					}
				}
	
				if (count($rows) == sizeof($parts)) {
					$queries = [];
					foreach ($rows as $row) {
						$queries[utf8_strtolower($row['keyword'])] = $row['query'];
					}

					reset($parts);
					foreach ($parts as $part) {
						$url = explode('=', $queries[$part], 2);

						if (isset($url[0]) && $url[0] == 'gallery_id') {
							$this->request->get['gallery_id'] = $url[1];
							$this->gallery_flag = 'gallery';
						} elseif (isset($url[0]) && $url[0] == $this->_route . '/galleries') {
							$this->gallery_flag = 'galleries';
							if (!isset($this->request->get[$this->_route . '/galleries'])) {
								$this->request->get['route'] = $this->_route . '/galleries';
							} else {
								$this->request->get['route'] = $this->_route . '/galleries';
							}
						} elseif (isset($url[0]) && $url[0] == 'route') {
							$this->request->get['route'] = $url[1];
						} elseif (count($url) > 1) {
							$this->request->get[$url[0]] = $url[1];
						}
					}
				}

			}

			if (isset($this->request->get['gallery_id'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = $this->_route . '/gallery';
			} elseif (isset($this->request->get[$this->_route . '/galleries'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = $this->_route . '/galleries';
			} else {
				if (isset($queries[$parts[0]])) {
					$this->request->get['route'] = $queries[$parts[0]];
				}
			}

			$_route_ =$this->request->get['_route_'];
			unset($this->request->get['_route_']);
			$this->validate();
			$this->request->get['_route_'] = $_route_;

			if (isset($this->request->get['route'])) {
				$this->request->get['_route_'] = $this->request->get['route'];
			}
			return $this->gallery_flag;
		}
	}
	
	public function rewrite($link) {
		if (!$this->config->get('config_seo_url'))
			return $link;
		$seo_url = '';

		$component = parse_url(str_replace('&amp;', '&', $link));
		$data      = [];

		if (isset($component['query'])) {
			parse_str($component['query'], $data);
			if (!isset($data['route'])) {
				return $link;
			}
			$route = $data['route'];

			unset($data['route']);

			$postfix = false;

			if ($route == $this->_route . '/gallery') {
				if (isset($data['gallery_id'])) {
					$tmp              = $data;
					$data             = [];
					$data['gallery_id'] = $tmp['gallery_id'];
					if (isset($tmp['limit'])) {
						$data['limit'] = $tmp['limit'];
					}
					if (isset($tmp['page'])) {
						$data['page'] = $tmp['page'];
					}
					$postfix = true;
				}
			} elseif ($route == $this->_route . '/galleries') {

			} else {
				return $link;
			}

			if ($component['scheme'] == 'https') {
				$link = $this->config->get('config_ssl');
			} else {
				$link = $this->config->get('config_url');
			}
			$link .= 'index.php?route=' . $route;
			if (count($data)) {
				$link .= '&amp;' . urldecode(http_build_query($data, '', '&amp;'));
			}

			$queries = [];
			foreach ($data as $key => $value) {
				switch ($key) {
					case 'gallery_id':
						$config_gallery_galleries_include_seo_path = $this->config->get('config_gallery_galleries_include_seo_path');
						if ($config_gallery_galleries_include_seo_path[$this->current_store_id]) {
							$queries[] = $this->_route . '/galleries';
						}
						$queries[] = $key . '=' . $value;
						unset($data[$key]);
						$postfix = true;
						break;
					default:
						break;
				}
			}
			if (empty($queries)) {
				$queries[] = $route;
			}

			$rows = [];
			foreach ($queries as $query) {
				if (isset($this->cache_data['queries'][$query])) {
					$rows[] = [
						'query' => $query,
						'keyword' => $this->cache_data['queries'][$query]
					];
				}
			}

			if (count($rows) == count($queries)) {
				$aliases = [];
				foreach ($rows as $row) {
					$aliases[$row['query']] = $row['keyword'];
				}
				foreach ($queries as $query) {
					$seo_url .= '/' . rawurlencode($aliases[$query]);
				}
			}

			if ($seo_url == '')
				return $link;
			if ($seo_url) {
				unset($data['route']);
				$query = '';
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . $key . '=' . $value;
					}
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}

				$devider = $this->config->get('config_seo_url_postfix');
				if (empty($devider) || !$postfix) {
					$devider = "/";
				}


				$link = $component['scheme'] . '://' . $component['host'] . (isset($component['port']) ? ':' . $component['port'] : '') . str_replace('/index.php', '', $component['path']) . $seo_url . $devider . $query;
				return $link;
			} else {
				return $link;
			}
		} else {
			return $link;
		}
	}

	private function validate() {
		if (empty($this->request->get['route']) || $this->request->get['route'] == 'error/not_found') {
			return;
		}
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$url = str_replace('&amp;', '&', $this->config->get('config_ssl') . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(['route']), 'SSL'));
		} else {
			$url = str_replace('&amp;', '&', $this->config->get('config_url') . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(['route']), 'NONSSL'));
		}
		if (rawurldecode($url) != rawurldecode($seo)) {
			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
			$this->response->redirect($seo);
		}
	}

	private function getQueryString($exclude = []) {
		if (!is_array($exclude)) {
			$exclude = [];
		}
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}
}
