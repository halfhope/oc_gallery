<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryGallery extends controller{
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
       $this->getchild('common/seo_gallery');
        
        if (isset($this->request->get['album_id'])) {
            $album_id = (int)$this->request->get['album_id'];
            if (!empty($album_id)) {
                $this->response->redirect($this->url->link('gallery/gallery', 'album_id='.$album_id));
            }else{
                $this->response->redirect($this->url->link('gallery/gallery'));
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
        $this->language->load('gallery/gallery');
        
        $desc = $this->config->get('config_gallery_galleries_description');
        $config_gallery_show_description = $this->config->get('config_gallery_show_description');
        if ($config_gallery_show_description[$this->current_store_id] && !empty($desc[$this->current_store_id][$this->current_language_id])) {
            $this->data['galleries_description'] = html_entity_decode($desc[$this->current_store_id][$this->current_language_id], ENT_QUOTES, 'UTF-8');
        }

        $titl = $this->config->get('config_gallery_galleries_title');
        if (!empty($titl[$this->current_store_id][$this->current_language_id])) {
            $this->data['title'] = $titl[$this->current_store_id][$this->current_language_id];
        }else{
            $this->data['title'] = $this->language->get('text_gallery_list');
        }
        $this->document->setTitle($this->data['title']);
        
        $bread = $this->config->get('config_gallery_galleries_breadcrumb');
        if (!empty($bread[$this->current_store_id][$this->current_language_id])) {
            $bread = $bread[$this->current_store_id][$this->current_language_id];
        }else{
            $bread = $this->data['title'];
        }

        $h1_titl = $this->config->get('config_gallery_galleries_h1_title');
        if (!empty($h1_titl[$this->current_store_id][$this->current_language_id])) {
            $this->data['h1_title'] = $h1_titl[$this->current_store_id][$this->current_language_id];
        }else{
            $this->data['h1_title'] = $this->data['title'];
        }

        $kwd = $this->config->get('config_gallery_galleries_meta_keywords');
        if (!empty($kwd[$this->current_store_id][$this->current_language_id])) {
            $this->document->setKeywords($kwd[$this->current_store_id][$this->current_language_id]);
        }

        $meta_desc = $this->config->get('config_gallery_galleries_meta_description');
        if (!empty($meta_desc[$this->current_store_id][$this->current_language_id])) {
            $this->document->setDescription($meta_desc[$this->current_store_id][$this->current_language_id]);
        }
        
        //bootstrap columns
        $bootstrap_columns = array(1=>12, 2=>6, 3=>4, 4=>3, 5=>2, 6=>1);
        $config_gallery_number_of_columns_xs = $this->config->get('config_gallery_number_of_columns_xs');
        $config_gallery_number_of_columns_sm = $this->config->get('config_gallery_number_of_columns_sm');
        $config_gallery_number_of_columns_md = $this->config->get('config_gallery_number_of_columns_md');
        $config_gallery_number_of_columns_lg = $this->config->get('config_gallery_number_of_columns_lg');

        $this->data['bootstrap_grid'] = implode(' ', array(
            ($config_gallery_number_of_columns_xs[$this->current_store_id] !== 0) ? 'col-xs-'.$bootstrap_columns[$config_gallery_number_of_columns_xs[$this->current_store_id]] : '',
            ($config_gallery_number_of_columns_sm[$this->current_store_id] !== 0) ? 'col-sm-'.$bootstrap_columns[$config_gallery_number_of_columns_sm[$this->current_store_id]] : '',
            ($config_gallery_number_of_columns_md[$this->current_store_id] !== 0) ? 'col-md-'.$bootstrap_columns[$config_gallery_number_of_columns_md[$this->current_store_id]] : '',
            ($config_gallery_number_of_columns_lg[$this->current_store_id] !== 0) ? 'col-lg-'.$bootstrap_columns[$config_gallery_number_of_columns_lg[$this->current_store_id]] : ''
        ));
       
        $albums = $this->model_catalog_gallery->getAlbums();
        
        $config_gallery_cover_image_width   = $this->config->get('config_gallery_cover_image_width');
        $config_gallery_cover_image_height  = $this->config->get('config_gallery_cover_image_height');

        $this->data['config_gallery_cover_image_width'] = $config_gallery_cover_image_width[$this->current_store_id];
        $this->data['config_gallery_cover_image_height'] = $config_gallery_cover_image_height[$this->current_store_id];
        
        foreach ($albums as $key => $album) {
            $config_gallery_show_counter = $this->config->get('config_gallery_show_counter');
            if ($config_gallery_show_counter[$this->current_store_id]) {
                $cached_images_name = "gallery_photos.".$album['album_id'].".1.0.$this->current_store_id.$this->current_language_id";
                $cached = $this->cache->get($cached_images_name);
                if (!empty($cached)) {
                    $albums[$key]['images'] = $cached;  
                }else{
                    $cached = $this->model_catalog_gallery->getAlbumImages($album['album_id'], 1, 0);
                    $this->cache->set($cached_images_name, $cached);
                    $albums[$key]['images'] = $cached;
                }
            }
            //counter
            $config_gallery_show_counter = $this->config->get('config_gallery_show_counter'); 
            $album_name_postfix = ($config_gallery_show_counter[$this->current_store_id] ? ' ('.count($albums[$key]['images']).')' : '');

            $albums[$key]['album_name'] = $albums[$key]['album_data']['album_name'][$this->current_language_id].$album_name_postfix;
            $albums[$key]['album_link'] = $this->url->link('gallery/photos', 'album_id='.$albums[$key]['album_id']);
            if (!empty($albums[$key]['album_data']['cover_image']['image'])) {
                $albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize($albums[$key]['album_data']['cover_image']['image'], $config_gallery_cover_image_width[$this->current_store_id], $config_gallery_cover_image_height[$this->current_store_id]);
            }else{
                $albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $config_gallery_cover_image_width[$this->current_store_id], $config_gallery_cover_image_height[$this->current_store_id]);
            }
        }

        $this->data['albums'] = $albums;

        // ***************************************************************************************************** 
        #назначаем заголовок страницы (обязательно)

        $this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.manager.css');
        
        $config_gallery_include_bootstrap = $this->config->get('config_gallery_include_bootstrap');
        if ($config_gallery_include_bootstrap) {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.bootstrap.grid.css');
            
        }
        
        $this->data['heading_title'] = $this->data['title'];
        
        #Добавляем хлебные крошки (обязательно)
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'), 
            'separator' => false        
        ); 
        $this->data['breadcrumbs'][] = array(
            'text'      => $bread,
            'href'      => $this->url->link('gallery/gallery', '', 'SSL'), 
            'separator' => $this->language->get('text_separator')
        );

        $this->data['microtime'] = microtime(true) - $this->data['microtime'];
        $this->data['no_conflict'] = substr(md5(rand(0, 99)), 20);

        # (Не обязательно) Если хотите использовать модули в левой или правой колонке, то:
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );
        
        # стандартный код загрузки файла шаблона (обязательно)
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/gallery.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/gallery/gallery.tpl';
        } else {
            $this->template = 'default/template/gallery/gallery.tpl';
        }

        #рендеринг шаблона (Обязательно)
        $this->response->setOutput($this->render());
        // ***************************************************************************************************** 
    }

}
?>