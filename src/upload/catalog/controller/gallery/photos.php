<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryPhotos extends controller{
    private $cacher = array();
    private $current_language_id;
    private $current_store_id;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->current_language_id = $this->config->get('config_language_id');
        $this->current_store_id = $this->config->get('config_store_id');
    }
    
    public function index(){
        $this->load->controller('common/seo_gallery');

        if (isset($this->request->get['album_id'])) {
            $album_id = (int)$this->request->get['album_id'];
            if (!empty($album_id)) {
                $this->section_photos($album_id);
            }else{
                $this->response->redirect($this->url->link('gallery/gallery'));
            }
        }else{
            $this->response->redirect($this->url->link('gallery/gallery'));
        }
    }
    private function section_photos($album_id){
        $data['microtime'] = microtime(true);
        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('catalog/gallery');
        $this->load->language('gallery/gallery');
        $data['text_gallery_list']    = $this->language->get('text_gallery_list');
        $data['text_limit']           = $this->language->get('text_limit');
        
        $pre_album = $this->model_catalog_gallery->getAlbum($album_id);
        if (empty($pre_album)) {
            $this->response->redirect($this->url->link('gallery/gallery'));
        }
        $total_cached_images_name = 'gallery_gallery.total.'.md5($album_id.'10'.$this->current_language_id);

        if ($this->config->get('config_gallery_modules_cache_enabled')) {
            $total_cached = $this->cache->get($total_cached_images_name);
            if (!empty($total_cached)) {
                $pre_album['total_images'] = count($total_cached); 
            }else{
                $total_cached = $this->model_catalog_gallery->getAlbumImages($album_id, 1, 0);
                $this->cache->set($total_cached_images_name, $total_cached);
                $pre_album['total_images'] = count($total_cached);
            }
        }else{
            $pre_album['total_images'] = count($this->model_catalog_gallery->getAlbumImages($album_id, 1, 0));
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else { 
            $page = 1;
        } 

        if (isset($this->request->get['limit'])) {
            $limit = intval($this->request->get['limit']);
            if (($limit < 0) || ($limit >= $pre_album['total_images'])) {
                $this->response->redirect($this->url->link('gallery/photos', 'album_id=' . $album_id. '&limit=0'));
            }else{
                $url = '&limit='.(int)$limit;
            }
        } else {
            $limit = $pre_album['album_data']['photos_limit'];
            $this->cacher['limit'] = $limit;
            $url = '&limit='.$limit;
        }        
        $data['limit'] = $limit;
        
        $cached_page_name = "gallery_gallery.$album_id.$page.$limit.$this->current_store_id.$this->current_language_id";
        
        $this->cacher = $this->cache->get($cached_page_name);
        if (!empty($this->cacher) && $this->config->get('config_gallery_modules_cache_enabled')) {
            foreach ($this->cacher as $key => $value) {
                $data[$key] = $value;
            }
            $album = $data['album'];
        }else{

            $album = $pre_album;
            if (empty($album)) {
                $this->response->redirect($this->url->link('gallery/gallery'));
            }

            $this->cacher['limits'] = array();
            if ($album['album_data']['photos_limit'] != 0) {
                $this->cacher['limits'][$album['album_data']['photos_limit']] = array(
                    'text'  => $album['album_data']['photos_limit'],
                    'value' => $album['album_data']['photos_limit'],
                    'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=' . $album['album_data']['photos_limit'])
                );
            }
            $this->cacher['limits'][25] = array(
                'text'  => 25,
                'value' => 25,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=25')
            );
            
            $this->cacher['limits'][50] = array(
                'text'  => 50,
                'value' => 50,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=50')
            );

            $this->cacher['limits'][75] = array(
                'text'  => 75,
                'value' => 75,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=75')
            );
            
            $this->cacher['limits'][100] = array(
                'text'  => 100,
                'value' => 100,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=100')
            );
            $this->cacher['limits'][0] = array(
                'text'  => $this->language->get('text_limit_all'),
                'value' => 0,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=0')
            );

            //Add data
            $album['album_name'] = $album['album_data']['album_name'][$this->current_language_id];
            
            $album['album_link'] = $this->url->link('gallery/gallery', 'album_id='.$album['album_id']);
            
            $cached_images_name = "gallery_photos.$album_id.$page.$limit.$this->current_store_id.$this->current_language_id";
            
            if ($this->config->get('config_gallery_modules_cache_enabled')) {   
                $cached = $this->cache->get($cached_images_name);
                if (!empty($cached)) {
                    $album['images'] = $cached;  
                }else{
                    $cached = $this->model_catalog_gallery->getAlbumImages($album_id, $page, $limit);
                    $this->cache->set($cached_images_name, $cached);
                    $album['images'] = $cached;
                }
            }else{
                $album['images'] = $this->model_catalog_gallery->getAlbumImages($album_id, $page, $limit);
            }
            
            if ($limit == 0) {
                $this->cacher['show_pagination'] = false;
            }else{
                $this->cacher['show_pagination'] = true;
                $pagination = new Pagination();
                $pagination->total = $album['total_images'];
                $pagination->page = $page;
                $pagination->limit = $limit;
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&page={page}'. $url);
                $this->cacher['pagination'] = $pagination->render();
            }
       

            foreach ($album['images'] as $img_key => $image) {
                if (empty($image['image'])) {
                    $image['thumb'] = $this->model_tool_image->resize('no_image.jpg', $album['album_data']['thumb_width'] , $album['album_data']['thumb_height']);
                    $image['popup'] = $this->model_tool_image->resize('no_image.jpg', $album['album_data']['popup_width'] , $album['album_data']['popup_height']);
                }else{
                    $image['thumb'] = $this->model_tool_image->resize($image['image'], $album['album_data']['thumb_width'] , $album['album_data']['thumb_height']);
                    $image['popup'] = $this->model_tool_image->resize($image['image'], $album['album_data']['popup_width'] , $album['album_data']['popup_height']);
                }
                $album['images'][$img_key] = $image;
            }
            
            //bootstrap columns
            $bootstrap_columns = array(1=>12, 2=>6, 3=>4, 4=>3, 5=>2, 6=>1);
            $album['bootstrap_grid'] = implode(' ', array(
                ((int)$album['album_data']['number_of_columns_xs'] !== 0) ? 'col-xs-'.$bootstrap_columns[$album['album_data']['number_of_columns_xs']] : '',
                ((int)$album['album_data']['number_of_columns_sm'] !== 0) ? 'col-sm-'.$bootstrap_columns[$album['album_data']['number_of_columns_sm']] : '',
                ((int)$album['album_data']['number_of_columns_md'] !== 0) ? 'col-md-'.$bootstrap_columns[$album['album_data']['number_of_columns_md']] : '',
                ((int)$album['album_data']['number_of_columns_lg'] !== 0) ? 'col-lg-'.$bootstrap_columns[$album['album_data']['number_of_columns_lg']] : ''
            ));
            
            //Metadata
            $album['album_title'] = (!empty($album['album_data']['album_title'][$this->current_language_id]) ? $album['album_data']['album_title'][$this->current_language_id] : $album['album_name']);
            
            $album['album_h1_title'] = (!empty($album['album_data']['album_h1_title'][$this->current_language_id]) ? $album['album_data']['album_h1_title'][$this->current_language_id] : $album['album_name']);
            $album['album_keywords'] = (!empty($album['album_data']['album_keywords'][$this->current_language_id]) ? $album['album_data']['album_keywords'][$this->current_language_id] : $album['album_name']);
            $album['album_meta_description'] = (!empty($album['album_data']['album_meta_description'][$this->current_language_id]) ? $album['album_data']['album_meta_description'][$this->current_language_id] : $album['album_name']);
            if (!empty($album['album_data']['album_description']) && $album['album_data']['show_album_description']) {
                $album['album_description'] = html_entity_decode($album['album_data']['album_description'][$this->current_language_id], ENT_QUOTES, 'UTF-8');
            }

            switch ($album['album_data']['js_lib_type']) {
                case 0: // ColorBox 
                    if ($this->config->get('config_gallery_include_colorbox')) {
                        $album['scripts'][] = 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/colorbox/colorbox.css';
                    }
                    $album['js_lib_type_text'] = 'colorbox';
                break;
                case 1: // LightBox 
                    if ($this->config->get('config_gallery_include_lightbox')) {
                        $album['scripts'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.min.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/lightbox/lightbox.css';
                    }
                    $album['js_lib_type_text'] = 'lightbox';
                break;
                case 2: // FancyBox
                    if ($this->config->get('config_gallery_include_fancybox')) { 
                        $album['scripts'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/fancybox/jquery.fancybox.css';
                    }
                    $album['js_lib_type_text'] = 'fancybox';
                break;
                case 3: // Magnific PopUp
                    if ($this->config->get('config_gallery_include_magnific_popup')) { 
                        $album['scripts'][] = 'catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/magnific/magnific-popup.css';
                    }
                    $album['js_lib_type_text'] = 'magnific_popup';
                break;
            }
            
            //lazyload
            if ($this->config->get('config_gallery_include_lazyload')) {
                $album['scripts'][] = 'catalog/view/javascript/jquery/jquery.lazyload.min.js';
            }
            if (!isset($album['album_data']['use_lazyload'])) {
                $album['album_data']['use_lazyload'] = false;
            }
            $album['album_data']['lazyload_image'] = $this->model_tool_image->resize('lazyload_image.jpg', $album['album_data']['thumb_width'], $album['album_data']['thumb_height']);
            // $album['album_data']['lazyload_image'] = '#';

            $this->cacher['album'] = $album;
            if ($this->config->get('config_gallery_modules_cache_enabled')) {
                $this->cache->set($cached_page_name, $this->cacher);
            }
            foreach ($this->cacher as $key => $value) {
                $data[$key] = $value;
            }
            
            $album = $data['album'];
        }

        /////////////////////////////////////////////////////
        if (!empty($album['scripts'])) {
            foreach ($album['scripts'] as $key => $script) {
                $this->document->addScript($script);
            }
        }
        if (!empty($album['styles'])) {
            foreach ($album['styles'] as $key => $style) {
                $this->document->addStyle($style);
            }
        }

        
        // ***************************************************************************************************** 
        #назначаем заголовок страницы (обязательно)

        $this->document->setTitle($album['album_title']);
        $this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.manager.css');
        
        $data['heading_title'] = $album['album_h1_title'];

        $bread = $this->config->get('config_gallery_galleries_breadcrumb');
        if (!empty($bread[$this->current_store_id][$this->current_language_id])) {
            $bread = $bread[$this->current_store_id][$this->current_language_id];
        }else{
            $titl = $this->config->get('config_gallery_galleries_title');
            if (!empty($titl[$this->current_store_id][$this->current_language_id])) {
                $titl = $titl[$this->current_store_id][$this->current_language_id];
            }else{
               $titl = $this->language->get('text_gallery_list');
            }
            $bread = $titl;
        }
        
            
        #Добавляем хлебные крошки (обязательно)
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),         
        ); 
        $data['breadcrumbs'][] = array(
            'text'      => $bread,
            'href'      => $this->url->link('gallery/gallery', '', 'SSL'),          
        );
        $data['breadcrumbs'][] = array(
            'text'      => (!empty($album['album_name'])? $album['album_name']: $album['album_data']['album_title'][$this->current_language_id]),
            'href'      => $this->url->link('gallery/photos', 'album_id='.$album_id. (($page == 1)? '' : '&page='.$page ).(($limit == 0)?'':$url), 'SSL'),          
        );

        //Добавляем Last-Modified
        if (isset($album['last_modified']) && $album['last_modified'] != '0000-00-00 00:00:00') {
            $this->response->addHeader('Last-Modified: '.date('r', strtotime($album['last_modified'])));
        }else{
            $this->response->addHeader('Last-Modified: '.date('r'));
        }

 
        # (Не обязательно) Если хотите использовать модули в левой или правой колонке, то:
        $this->document->setDescription((!empty($album['album_data']['album_meta_description'][$this->current_language_id]) ? $album['album_data']['album_meta_description'][$this->current_language_id] : $album['album_name']));
        $this->document->setKeywords((!empty($album['album_data']['album_meta_keywords'][$this->current_language_id]) ? $album['album_data']['album_meta_keywords'][$this->current_language_id] : $album['album_name']));
        
       # (Не обязательно) Если хотите использовать модули в левой или правой колонке, то:
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['microtime'] = microtime(true) - $data['microtime'];
        $data['no_conflict'] = substr(md5(mt_rand(0, 99)), 20);
       
        #рендеринг шаблона (Обязательно)
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/photos.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/gallery/photos.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/gallery/photos.tpl', $data));
        }
        // ***************************************************************************************************** 
    }
}
?>