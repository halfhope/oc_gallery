<?php
class ControllerFeedGallery extends Controller {
	public function index() {
		$this->load->controller('common/seo_gallery');

		$output  = '<?xml version="1.0" encoding="UTF-8"?>';
		$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		$this->load->model('gallery/gallery');

		$galleries = $this->model_gallery_gallery->getGalleries();
		
		$output .= '<url>';
		$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('gallery/galleries'))) . '</loc>';
		$output .= '<changefreq>weekly</changefreq>';
		$output .= '<priority>0.7</priority>';
		$output .= '</url>';

		foreach ($galleries as $gallery) {
			$output .= '<url>';
			$output .= '<loc>' . trim(str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('gallery/gallery', 'gallery_id=' . $gallery['gallery_id'])))) . '</loc>';
			
			if (isset($gallery['last_modified']) && $gallery['last_modified'] != '0000-00-00 00:00:00') {
				$output .= '<lastmod>' . date('Y-m-d', strtotime($gallery['last_modified'])) . '</lastmod>';
			}else{
				$output .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
			}
			
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';
		}

		$output .= '</urlset>';

		$this->response->addHeader('Content-Type: application/xml');
		$this->response->setOutput($output);
	}
}
?>