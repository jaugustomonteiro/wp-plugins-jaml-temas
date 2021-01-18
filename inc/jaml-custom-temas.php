<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * CUSTOM POST TEMAS
 */

function jaml_custom_post_tema() {
 
    $labels = array(
      'name'               => __( 'Temas' ),
      'singular_name'      => __( 'Tema' ),
      'add_new'            => __( 'Adicionar novo Tema' ),
      'add_new_item'       => __( 'Adicionar novo Tema' ),
      'edit_item'          => __( 'Editar Tema' ),
      'new_item'           => __( 'Novo Tema' ),
      'all_items'          => __( 'Todos Temas' ),
      'view_item'          => __( 'Visualizar Temas' ),
      'search_items'       => __( 'Localizar Temas' )
    );
   
    $args = array(
      'labels'            => $labels,
      'description'       => 'Holds our Temas',
      'public'            => true,
      'menu_position'     => 6,
      'supports'          => array( 'title', 'thumbnail', 'custom-fields' ),
      'has_archive'       => true,
      'show_in_admin_bar' => true,
      'show_in_nav_menus' => true,
      'has_archive'       => true,
      'menu_icon'         => 'dashicons-screenoptions',
    );
   
    register_post_type( 'tema', $args);
  }  
  
  add_action( 'init', 'jaml_custom_post_tema' );

/**
 * SEPARA NOMES DO PALESTRANTES CASO HAJA MAIS QUE UM
 */
function get_theme_nome_palestrantes($dados) {

  $dados_palestrantes = explode('|', get_post_meta(get_the_ID(), $dados, true));

  $temp_nomes = '';


  for($i = 0; $i < count($dados_palestrantes); ++$i) {
    $temp = explode('&', $dados_palestrantes[$i]);
    if( $i > 0) {
      $temp_nomes .= preg_filter('/^/', ' | ', $temp[1]);
    }
    else {
      $temp_nomes .= $temp[1];
    }            
  }          

  return $temp_nomes;
}
 
/**
 * SELECIONAR METADADOS ['IMAGE', 'DATA', 'LINK']
 */
function get_theme_metadados($dados, $type, $postId) {
  if($type === 'text') {
    return get_post_meta($postId, $dados, true);
  }
  else if($type === 'image') {
    if (wp_get_attachment_image_src(get_post_meta($postId, $dados, true), 'full')) {
      return wp_get_attachment_image_src(get_post_meta($postId, $dados, true), 'full')[0];
    }    
  }
  else if($type === 'data') {
    $d = get_post_meta(get_the_ID(), $dados, true);
    return date('d/m',strtotime($d)) . ' às ' . date('H:i',strtotime($d));
  }
  else if($type === 'link') {		
    return get_post_meta($postId, $dados, true) == '' ? get_the_permalink($postId) : get_post_meta($postId, $dados, true);
  }	
}

/**
 * RETORNA A IMAGE DO PALESTRATE
 */
function jamltemas_imagehome_function() {

	date_default_timezone_set('America/Sao_Paulo');

	$now =  date("Y-m-d H:i:s", strtotime('+1 hours', strtotime(date("Y-m-d H:i:s"))));

	$theme = array( 
    'post_type' => 'tema',
		'orderby' => 'meta_value',
		'order'=> 'ASC',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'theme_data_palestra',
				'value'   => date("Y-m-d H:i:s"),
				'compare' => '>',	
				'type'    => 'DATETIME'			
			)
		)			
  );

	$loop = new WP_Query($theme);	

	if($loop->have_posts()) {
        while($loop->have_posts()) {          
			$loop->the_post();
			$theme_imagehome_palestrate = get_theme_metadados('theme_imagehome_palestrante', 'image', get_the_ID());					
			return '<img style="border-radius: 10px" src="' . $theme_imagehome_palestrate . '" alt="">';						
        }        
	}	
	wp_reset_query();    
}

add_shortcode('jamltemas_imagehome', 'jamltemas_imagehome_function');

/**
 * RETORNA O PALESTRATE
 */
function jamltemas_palestrantes_function() {

	$theme = array( 
    'post_type' => 'tema',
		'orderby' => 'meta_value',
		'order'=> 'ASC',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'theme_data_palestra',
				'value'   => date( "Y-m-d H:i:s" ),
				'compare' => '>=',	
				'type'    => 'DATETIME'			
			)
		)			
  );
	
	date_default_timezone_set('America/Sao_Paulo');

	$loop = new WP_Query($theme);	

	if($loop->have_posts()) {
		
		while($loop->have_posts()) {
          
			$loop->the_post();
			$theme_imagehome_palestrate = get_theme_metadados('theme_imagehome_palestrate', 'image', get_the_ID());
			$theme_data_palestra =  get_theme_metadados('theme_data_palestra', 'data', get_the_ID());
			$theme_titulo_palestra = get_theme_metadados('theme_titulo_palestra', 'text', get_the_ID());
			$theme_dados_palestrantes = explode('|', get_post_meta(get_the_ID(), 'theme_dados_palestrantes', true));

				
			$html = '';			
			$html .= '<h3><strong>' . $theme_titulo_palestra . '</strong></h3>';
			$html .= '<hr class="mb-3" style="border: 2px solid #ff3352; width: 25%" />';
			foreach ($theme_dados_palestrantes as &$value) {
				$dados = explode('&', $value);
				$html .= '<h4 style="margin-bottom: -.1rem"><strong>' . $dados[1] . '</strong></h4>';
				$html .= '<p class="text-white">' . $dados[2] . '</p>';
			}		
			$html .= '<h3><strong>' . $theme_data_palestra . '</strong></h3>';									
		}		
		return $html;
	}	
	wp_reset_query();    
}

add_shortcode('jamltemas_palestrantes', 'jamltemas_palestrantes_function');


/**
 * LISTA TODOS OS TEMAS
 */
function jamltemas_function($attr) {
	
	$args = shortcode_atts( array(     
		'limit' => '-1',
	), $attr );

	$theme = array( 
        'post_type' => 'tema',
		'orderby' => 'meta_value',
		'order'=> 'ASC',
		'posts_per_page' => $args['limit'],
		'meta_query' => array(
			array(
				'key' => 'theme_data_palestra',
				'value'   => date( "Y-m-d H:i:s" ),
				'compare' => '>=',	
				'type'    => 'DATETIME'			
			)
		)			
  );

  date_default_timezone_set('America/Sao_Paulo');

  $loop = new WP_Query( $theme );

  $html = '';

  $html = '
    <style>
    .jaml-row-flex{display:flex;flex-wrap:wrap}.jaml-row-flex [class*=col-]{margin-bottom:30px}.jaml-card-content{height:100%;position:relative}.jaml-card-palestrantes{position:absolute;top:10px;right:10px;background-color:#392466;opacity:.7;color:#fff;padding:2px 10px;border-radius:100px}.jaml-card-title{color:#392466!important;font-weight:700}.jaml-card-btn{background-color:#ff5973;transition:all .5s;color:#fff;opacity:.7;border-radius:100px}.jaml-card-btn:hover{background-color:#ff5973;opacity:1;color:#fff}.jaml-card-badge{background-color:#392466;color:#fff} 
    </style>  
  ';

  $html .= '<div class="row jaml-row-flex">';
  
  if($loop->have_posts()) {
  
    while($loop->have_posts()) {
          
      $loop->the_post();
      $theme_image_card_palestrante = get_theme_metadados('theme_imagecard_palestrante', 'image', get_the_ID());
      $theme_nomes_palestrantes = get_theme_nome_palestrantes('theme_dados_palestrantes');		
      $theme_titulo_palestra = get_theme_metadados('theme_titulo_palestra', 'text', get_the_ID());			
      $theme_data_palestra =  get_theme_metadados('theme_data_palestra', 'data', get_the_ID());
      $theme_link_palestra = get_theme_metadados('theme_link_palestra', 'link', get_the_ID());

      
      $html .= '<div class="col-lg-4">';
      $html .= '<div class="card mb-2 jaml-card-content">';
      $html .= '<img class="card-img-top" src="'. $theme_image_card_palestrante .'" alt="Card image cap">';
      $html .= '<div class="jaml-card-palestrantes">' . $theme_nomes_palestrantes . '</div>';
      $html .= '<div class="card-body text-center">';
      $html .= '<h5 class="card-title jaml-card-title">' . $theme_titulo_palestra . '</h5>';													
      $html .= '</div>';
      $html .= '<div class="text-center">';
      $html .= '<h3><span class="badge jaml-card-badge">' . $theme_data_palestra . '</span></h3>';								
      $html .= '<a href="' . $theme_link_palestra . '" class="btn jaml-card-btn m-3">Inscrever-se</a>';
      $html .= '</div>';
      $html .= '</div>';
      $html .= '</div>';
      
    }        
  }
  else {
    $html .= '<script>
            jQuery(document).ready(function( $ ){
              $(".jaml-hide-section").hide();
              //alert(123);
            });
          </script>';
  }
  
  $html .= '</div>';

  wp_reset_query();

  return $html;
}

add_shortcode('jamltemas', 'jamltemas_function');


/**
 * ADICIONA TEMPLATE PARA TEMAS
 */

add_filter( 'single_template', 'custom_post_type_theme' );
function custom_post_type_theme($single_template) {
    global $post;
    if ($post->post_type == 'tema' ) {
        $single_template = dirname( __FILE__, 2) . '/template/template-theme.php';
    }
    return $single_template;  
}

