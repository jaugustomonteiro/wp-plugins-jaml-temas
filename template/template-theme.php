<?php
/**
 * Este template é para mostrar os temas
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php get_header(); ?>

<style>
	.jaml-theme-container {
		margin-top: 3rem;
		box-sizing: border-box;
	}

	.jaml-theme-texts {
		color: #392466
	}

	.jaml-theme-title {
		font-weight: bold;
		font-size: calc(1.375rem + 1.5vw) !important;
		margin-bottom: 3rem;
	}

	.jaml-theme-badge {           
		background-color: #ff5973; 
		color: #ffffff;
		font-size: 1.5rem;          
		width: 15rem;
		padding: 1rem 3rem;
		border-radius: 10rem;            
		display: inline-block;   
		text-align: center;         
	}

	.jaml-theme-thumbnail {
		width: 10rem;
		height: 10rem;
		background: url('img/thumbnail.jpg');
		border-radius: 50%;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
	}

	.jaml-theme-palestrante {
		font-weight: bold;
		font-size: calc(1.2rem + 1.2vw) !important;
		
	}

	.jaml-theme-minicurriculum {
		font-weight: 400;
		font-size: calc(.5rem + 1.2vw) !important;            
	}

	.jaml-theme-list {
		list-style: none;
		margin: 3rem 0;
		padding: 0;
	}

	.jaml-theme-list li {
		display: flex;
		align-items: center;  
		font-size: 1.7rem; 
		font-weight: 500;
		color: #392466;    
		padding: .5rem 0;     
	}

	.jaml-theme-list img {
		width: 3rem;
		height: 3rem;
		margin-right: 1rem;            
	}

	.jaml-theme-list img svg {
		width: 3rem;
		height: 3rem;
		margin-right: 1rem;
		fill: #ff3352;
	}

	.jaml-theme-footer {
		background-color: #7e3a77;
		color: #ffffff;	
		padding: 2rem 0;	
	}

	.jaml-theme-button {           
		background-color: #ff5973 !important; 
		color: #ffffff;
		font-size: 1.5rem;		
		padding: 1rem 3rem;
		border-radius: 10rem;            
		display: inline-block;   
		text-align: center;   
		margin: 1rem 0;   
		opacity: .7;		  
	}
	
	.jaml-theme-button:focus {
  		outline: none;
  		box-shadow: none;
	}

	.jaml-theme-button:hover, .jaml-theme-button:active {           
		background-color: #ff5973 !important; 
		color: #ffffff;
		opacity: 1;		
	}

	.jaml-topicos-list {
		margin: 1rem 1.5rem;
	}

	.jaml-topicos-list li {
		font-size: 1.3rem;
		margin-bottom: .5rem;
		color: #392466;
		font-weight: 500
	}

</style>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
	
	function get_singletheme_data() {

		$singletheme_meses = array(
			'01' => 'Janeiro',
			'02' => 'Fevereiro',
			'03' => 'Março',
			'04' => 'Abril',
			'04' => 'Maio',
			'06' => 'Junho',
			'07' => 'Julho',
			'08' => 'Agosto',
			'09' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro'
		);

		$singletheme_semana = array(
			0 => 'Domingo',
			1 => 'Segunda',
			2 => 'Terça',
			3 => 'Quarta',
			4 => 'Quinta',
			5 => 'Sexta',
			6 => 'Sábado',
		);

		$dia = date('d',strtotime(get_post_meta(get_the_ID(), 'theme_data_palestra', true)));
		$mes = date('m',strtotime(get_post_meta(get_the_ID(), 'theme_data_palestra', true)));
		$semana = date('w',strtotime(get_post_meta(get_the_ID(), 'theme_data_palestra', true)));	
		
		return $dia . ' de ' . $singletheme_meses[$mes] . ', ' . $singletheme_semana[$semana];
	}

	function get_singletheme_hora() {

		$hora = date('H',strtotime(get_post_meta(get_the_ID(), 'theme_data_palestra', true)));
		$minutos = date('i',strtotime(get_post_meta(get_the_ID(), 'theme_data_palestra', true)));

		return $hora . 'h:' . $minutos;
	}


	function getTopicos() {

	}

	$singletheme_image_banner_palestrante = get_the_post_thumbnail_url() != null ? get_the_post_thumbnail_url() : 'https://mulheresprogressistas.com.br/wp-content/uploads/2020/07/fmc-banner-urnas.png';
	$singletheme_dados_palestrantes = explode('|', get_post_meta(get_the_ID(), 'theme_dados_palestrantes', true));
	$singletheme_titulo_palestra = get_theme_metadados('theme_titulo_palestra', 'text', get_the_ID());			
	$singletheme_data_palestra =  get_theme_metadados('theme_data_palestra', 'data', get_the_ID());
	$singletheme_link_palestra = get_theme_metadados('theme_link_palestra', 'link', get_the_ID());
	$singletheme_live_palestra = get_theme_metadados('theme_live_palestra', 'link', get_the_ID());	
	$singletheme_topicos_palestra = explode('|', get_post_meta(get_the_ID(), 'theme_topicos_palestra', true));
	$singletheme_tipo_evento = explode('&', get_post_meta(get_the_ID(), 'theme_tipo_evento', true));
	

?>


<div style='
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(25,25,25, .5)), to(rgba(25,25,25, .4))), url(<?=$singletheme_image_banner_palestrante?>);
	background: linear-gradient(to bottom, rgba(25,25,25, .5) 0%, rgba(25,25,25, .4) 100%), url(<?=$singletheme_image_banner_palestrante?>); 
	background-position: center;    
	background-repeat: no-repeat; 
	background-attachment: fixed; 
	background-size: cover; 
	height: 50vh; 
	min-height: 50vh; 
	padding-bottom: 0;'>
</div>

<div class="container jaml-theme-container">
	
	<h1 class="jaml-theme-texts jaml-theme-title"><?=$singletheme_titulo_palestra?></h1>

	<?php if(array_key_exists(2, $singletheme_topicos_palestra)): ?>
		
		<?php $sigletheme_topicos = explode(';', $singletheme_topicos_palestra[2]); ?>
		<strong class="jaml-theme-badge"><?=$singletheme_topicos_palestra[0]?><?php echo count($sigletheme_topicos) > 1 ? 's' : ''; ?></strong>
		<ul class="jaml-topicos-list" style="list-style: decimal">
			<?php foreach ($sigletheme_topicos as &$value): ?>			
				<li><?=$value?></li>			
			<?php endforeach;?>
		</ul>

	<?php endif?>

	<br />

	<strong class="jaml-theme-badge">Palestrante<?php echo count($singletheme_dados_palestrantes) > 1 ? 's' : ''; ?></strong>
	<?php foreach ($singletheme_dados_palestrantes as &$value): $dados = explode('&', $value); ?>	
	<div class="row my-5 justify-content-md-center">
		<div class="col-md-3">
		  <div style="
			width: 10rem;
			height: 10rem;
			background: url('<?=$dados[0]?>');
			border-radius: 50%;
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
		  ">
		</div>
		</div>
		<div class="col-md-9 d-flex justify-content-center flex-column jaml-theme-texts">
		  <h3><strong><?=$dados[1]?></strong></h3>
		  <p style="font-size: 1.2rem; font-weight: 500"><?=$dados[2]?></p>
		</div>            
	</div>
	<?php endforeach; ?>

	<strong class="jaml-theme-badge">Detalhes</strong>
	
	<ul class="jaml-theme-list">
		<li><img src="https://mulheresprogressistas.com.br/wp-content/uploads/2020/07/live-pink.svg" alt="lives" /><strong><?=$singletheme_live_palestra?></strong></li>
		<li><img src="https://mulheresprogressistas.com.br/wp-content/uploads/2020/07/calendar-pink.svg" alt="lives" /><strong><?=get_singletheme_data()?></strong></li>
		<li><img src="https://mulheresprogressistas.com.br/wp-content/uploads/2020/07/times-pink.svg" alt="lives" /><strong><?=get_singletheme_hora()?></strong></li>
	</ul>
</div>

<?php endwhile; ?>
<?php endif; ?>

<div class="jaml-theme-footer text-center">
	<?php if(count($singletheme_tipo_evento) > 0): ?>
		<?php foreach ($singletheme_tipo_evento as &$value): ?>
			<h1><?=$value?></h1>		
		<?php endforeach;?>
	<?php endif;?>
	<h1>Faça sua incrição</h1>
	<button class="jaml-theme-button jaml-btn-theme">Quero fazer minha inscrição</button>
<div> 


<?php get_footer(); ?>