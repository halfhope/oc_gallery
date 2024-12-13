<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryGallery extends controller{
    private $cacher = array();
    private $current_language_id;

    public function index(){
        $this->getChild('common/seo_gallery');
        
        if (isset($this->request->get['album_id'])) {
            $album_id = (int)$this->request->get['album_id'];
            if (!empty($album_id)) {
                $this->redirect($this->url->link('gallery/gallery', 'album_id='.$album_id));
            }else{
                $this->redirect($this->url->link('gallery/gallery'));
            }
        }else{
            $this->section_galleries();
        }
    }
    private function section_galleries(){
        $this->data['microtime'] = microtime(true);
        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('catalog/gallery');
        $this->language->load('product/gallery');
        $this->current_language_id = $this->config->get('config_language_id');
        
        $desc = $this->config->get('config_galleries_description');
        if ($this->config->get('config_gallery_show_description') && !empty($desc[$this->current_language_id])) {
            $this->data['galleries_description'] = html_entity_decode($desc[$this->current_language_id], ENT_QUOTES, 'UTF-8');
        }

        $titl = $this->config->get('config_galleries_title');
        if (!empty($titl[$this->current_language_id])) {
            $this->data['title'] = $titl[$this->current_language_id];
        }else{
            $this->data['title'] = $this->language->get('text_gallery_list');
        }
        $this->document->setTitle($this->data['title']);

        $h1_titl = $this->config->get('config_galleries_h1_title');
        if (!empty($h1_titl[$this->current_language_id])) {
            $this->data['h1_title'] = $h1_titl[$this->current_language_id];
        }else{
            $this->data['h1_title'] = $this->data['title'];
        }

        $kwd = $this->config->get('config_galleries_meta_keywords');
        if (!empty($kwd[$this->current_language_id])) {
            $this->document->setKeywords($kwd[$this->current_language_id]);
        }

        $meta_desc = $this->config->get('config_galleries_meta_description');
        if (!empty($meta_desc[$this->current_language_id])) {
            $this->document->setDescription($meta_desc[$this->current_language_id]);
        }

        $albums = $this->model_catalog_gallery->getAlbums();
        
        foreach ($albums as $key => $album) {
            if ($this->config->get('config_gallery_show_counter')) {
                $cached = $this->cache->get('album_photos.'.md5($album['album_id'].'0'));
                if (!empty($cached)) {
                    $albums[$key]['images'] = $cached;  
                }else{
                    $cached = $this->model_catalog_gallery->getAlbumImages($album['album_id'], 1, 0);
                    $this->cache->set('album_photos.'.md5($album['album_id'].'0'), $cached);
                    $albums[$key]['images'] = $cached;
                }
            }
            //counter
            $album_name_postfix = ($this->config->get('config_gallery_show_counter') ? ' ('.count($albums[$key]['images']).')' : '');

            $albums[$key]['album_name'] = $albums[$key]['album_data']['album_name'][$this->current_language_id].$album_name_postfix;
            $albums[$key]['album_link'] = $this->url->link('gallery/photos', 'album_id='.$albums[$key]['album_id']);
            if (!empty($albums[$key]['album_data']['cover_image']['image'])) {
                $albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize($albums[$key]['album_data']['cover_image']['image'], $this->config->get('config_gallery_cover_image_width'), $this->config->get('config_gallery_cover_image_height'));
            }else{
                $albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_gallery_cover_image_width'), $this->config->get('config_gallery_cover_image_height'));
            }
        }
        $this->data['config_gallery_cover_image_width'] = $this->config->get('config_gallery_cover_image_width');
        $this->data['config_gallery_cover_image_height'] = $this->config->get('config_gallery_cover_image_height');

        $this->data['albums'] = $albums;

        // ***************************************************************************************************** 
        #назначаем заголовок страницы (обязательно)

        $this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.manager.css');
        
        $this->data['heading_title'] = $this->data['title'];
        
        #Добавляем хлебные крошки (обязательно)
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),         
            'separator' => false
        ); 
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->data['title'],
            'href'      => $this->url->link('gallery/gallery', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        
        # стандартный код загрузки файла шаблона (обязательно)
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/gallery.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/gallery/gallery.tpl';
        } else {
            $this->template = 'default/template/gallery/gallery.tpl';
        }
 
        # (Не обязательно) Если хотите использовать модули в левой или правой колонке, то:
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->data['microtime'] = microtime(true) - $this->data['microtime'];

        #рендеринг шаблона (Обязательно)
        $this->response->setOutput($this->render());
        // ***************************************************************************************************** 
    }

}
?>