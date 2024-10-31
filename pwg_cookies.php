<?php
/*
Plugin Name: Programación Web Galicia - Cookies
Description: Add a message with information about cookies.
Version: 1.2
Author: Javier Méndez Veira
Author URI: http://www.programacionwebgalicia.com
License:
*/

// disable direct access
if (!defined('ABSPATH')) exit;


//Add utility to the plugin:
require_once('source/pwg_cookies.php');


define ('PWG_COOKIES_VERSION', "1.1");

//Configuration names for the plugin:
define('PWG_COOKIES_CAPABILITY', "manage_pwg_cookies" );
define('PWG_COOKIES_DOMAIN','pwg-cookies');

//Definitions for the settings/options (save several configurations).
define('PWG_COOKIES_SETTINGS', "pwg-cookies-settings");
define('AT_COOKIES_PRELOAD', 'pwg-cookies-preload' );
define('AT_COOKIES_ACTIVAR', 'pwg-cookies-activar' );
define('AT_COOKIES_URL_TITULO', 'pwg-cookies-url-aviso-titulo' );
define('AT_COOKIES_URL', 'pwg-cookies-url-aviso' );
define('AT_COOKIES_TEXTO_FOOTER', 'pwg-cookies-texto-footer' );
define('AT_COOKIES_TEXTO_BOTON', 'pwg-cookies-texto-boton' );
define('AT_COOKIES_POSICION', 'pwg-cookies-posicion' );
define('AT_GOOGLE_ANALYTICS_ACTIVAR', 'pwg-google-analytics-activar' );
define('AT_GOOGLE_ANALYTICS_CODIGO', 'pwg-google-analytics-codigo' );

//Asociate the created class with the initialization of the plugin.
add_action('plugins_loaded', array('PWGaliciaCookiesPlugin', 'init'));

//------------>Events ACTIVATION/DESACTIVATION OF THE PLUGIN:
register_activation_hook(__FILE__, array('PWGaliciaCookiesPlugin', 'pwg_cookies_activation'));
register_deactivation_hook(__FILE__, array('PWGaliciaCookiesPlugin', 'pwg_cookies_deactivation'));


/**
 * Register the plugin.
 *
 * Display the administration panel, insert JavaScript etc.
 */
class PWGaliciaCookiesPlugin {


	/**
	 * Init
	 */
	public static function init() {
		$pwg_cookies = new PWGaliciaCookiesPlugin;		
	}
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
			
	
		//------------> GENERAL HOOKS  ---------------------------------
		//Configure language:
		add_action('init', array($this, 'pwg_cookies_init_language'));
	
		//Add scripts:
		add_action( 'wp_enqueue_scripts', array($this, 'pwg_cookies_add_scripts' ));
	
		
		//Check if cookie is accepted and add code to header.
		add_action('wp_head',array($this,'pwg_cookies_add_code_head') );
		
		//Check if cookie is accepted and add code to footer.
		add_action('wp_footer',array($this,'pwg_cookies_add_code_footer') );
						

		//------------> ADMIN HOOKS --------------------------------		
		//Create own capability (not used) :	
		//add_filter( 'option_page_capability_'.PWG_COOKIES_SETTINGS, array($this,'pwg_cookies_add_capability'));
			
		//Register settings:
		add_action( 'admin_init', array($this,'pwg_cookies_register_settings') );
	
		//Set menu options:
		add_action ( 'admin_menu', array($this, 'pwg_cookies_config_admin_menu') ); // menu setup
		
	}
	
	/**
	 * Plugin activation.
	 */
	static function pwg_cookies_activation() {	
		//Create own cookies page (not used):
		//PWGaliciaCookies::crear_pagina_cookies();
		
		//Note: languages are not loaded.
		//Check options of the plugin, if no exist we created it:
		
		//Get Url cookie page:
		$url_cookies = get_option(AT_COOKIES_URL);
		if(!isset($url_cookies) || empty($url_cookies)){
			$url_cookies = get_site_url()."/aviso-cookies";
			update_option(AT_COOKIES_URL, $url_cookies);
		}
			
		//Message of cookies:
		$texto_cookies = get_option(AT_COOKIES_TEXTO_FOOTER);
		if(!isset($texto_cookies) || empty($texto_cookies)){
			$texto_cookies = "Utilizamos cookies propias y de terceros para mejorar nuestros productos y servicios mediante el análisis de sus hábitos de navegación. Al aceptar el presente aviso entendemos que das tu consentimiento a nuestra [[Link_1]].";
			update_option(AT_COOKIES_TEXTO_FOOTER, $texto_cookies);
		}
		
		
		//Title link to cookies page
		$titulo_enlace = get_option(AT_COOKIES_URL_TITULO);
		if(!isset($titulo_enlace) || empty($titulo_enlace))
		{
			$titulo_enlace = "Aviso de cookies";
			update_option( AT_COOKIES_URL_TITULO, $titulo_enlace );
		}
			
		//Title button accept cookies:
		$aceptar = get_option(AT_COOKIES_TEXTO_BOTON);
		if(!isset($aceptar) || empty($aceptar))
		{
			$aceptar = "Aceptar";
			update_option( AT_COOKIES_TEXTO_BOTON, $aceptar );
		}
	}
	
	/**
	 * Deactivation plugin.
	 */
	static function pwg_cookies_deactivation() {
		update_option(AT_COOKIES_URL, NULL);
		update_option(AT_COOKIES_TEXTO_FOOTER, NULL);
		update_option( AT_COOKIES_URL_TITULO, NULL );
		update_option( AT_COOKIES_TEXTO_BOTON, NULL );
	}
	
	
	/**
	 * Define capability to save options of the plugin.
	 * @param unknown $capability
	 * @return string
	 */
	function pwg_cookies_add_capability( $capability ) {
		return PWG_COOKIES_CAPABILITY;
	}
	
	
	/**
	 * Initialize language files.
	 */
	function pwg_cookies_init_language(){
		load_plugin_textdomain(PWG_COOKIES_DOMAIN, false, 'pwg-cookies/languages');
	}
	
	
	/**
	 * Configure menu
	 */
	function pwg_cookies_config_admin_menu() {				
		// This page will be under "Settings"
		add_options_page(
			"Configurar Cookies",
			"Cookies",
			"manage_options", //PWG_COOKIES_CAPABILITY,
			"admin-pwg-cookies",
			array( $this, 'pwg_cookies_render_page_admin' )
		);
		
	}
	
	
	
	/**
	 * Proper way to enqueue scripts and styles
	 */
	function pwg_cookies_add_scripts() {
	
		$url_css_cookies = plugins_url( 'assets/style.css' , __FILE__ );
		wp_enqueue_style( 'style-pwg-cookies', $url_css_cookies, array(), PWG_COOKIES_VERSION, false );
	
		//$js_cookies = dirname(__FILE__).'/assets/cookies.js';
		$url_js_cookies = plugins_url( 'assets/cookies.js' , __FILE__ );
		wp_enqueue_script( 'script-pwg-cookies', $url_js_cookies, array(), PWG_COOKIES_VERSION, true );
	}
	
	
	/**
	 * Register all options
	 */
	function pwg_cookies_register_settings() {
		//Saved in table wp_options:
	
		//Cookies:
		register_setting( PWG_COOKIES_SETTINGS, AT_COOKIES_ACTIVAR );
		register_setting( PWG_COOKIES_SETTINGS, AT_COOKIES_PRELOAD );
		register_setting( PWG_COOKIES_SETTINGS, AT_COOKIES_URL );
		register_setting( PWG_COOKIES_SETTINGS, AT_COOKIES_URL_TITULO );
		register_setting( PWG_COOKIES_SETTINGS, AT_COOKIES_TEXTO_FOOTER );
		register_setting( PWG_COOKIES_SETTINGS, AT_COOKIES_TEXTO_BOTON );
	
		//Google Anlytics:
		register_setting( PWG_COOKIES_SETTINGS, AT_GOOGLE_ANALYTICS_ACTIVAR );
		register_setting( PWG_COOKIES_SETTINGS, AT_GOOGLE_ANALYTICS_CODIGO );
	}
	
	
	
	function pwg_cookies_add_code_head(){		
		$id_cookie = PWGaliciaCookies::get_id_cookies();
		$activate = false;
		
		//Exist cookie user acceptation or the admin set preload cookies to 1:
		if(isset($_COOKIE[$id_cookie]) || get_option(AT_COOKIES_PRELOAD)==1){
						
			//Key Google Analytics:
			$clave_google_analytics = get_option(AT_GOOGLE_ANALYTICS_CODIGO);
			
			if(isset($_SERVER['SERVER_NAME']))
				$dominio_sitio = $_SERVER['SERVER_NAME'];
			
			//Check Activation of google analytics:
			if(get_option(AT_GOOGLE_ANALYTICS_ACTIVAR)==1){
				$activate = true;
				?>
				<!-- Google Analytics -->
				<script>
				  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
				  ga('create', '<?php echo $clave_google_analytics;?>', '<?php echo $dominio_sitio;?>');
				  ga('send', 'pageview');
				</script>
				<?php 
			}
		}
		
		if(!$activate){
			?>
			<!-- Google Analytics is blocked until cookie acceptation [PWGalicia Plugin] -->
			<?php
		}
	}
	
	/**
	 * Add code to footer for accepting cookies.
	 */
	function pwg_cookies_add_code_footer()
	{
	
	
		//If the function is actived:
		if(get_option(AT_COOKIES_ACTIVAR)==1){
			
			//Create page of cookies (not used):
			//PWGaliciaCookies::crear_pagina_cookies();
		

			//Url cookies
			$url_cookies = get_option(AT_COOKIES_URL);
			if(!isset($url_cookies) || empty($url_cookies))
				$url_cookies = get_site_url()."/aviso-cookies";
			
			
			//Message cookies:
			$texto_cookies = get_option(AT_COOKIES_TEXTO_FOOTER);
			if(!isset($texto_cookies) || empty($texto_cookies)){
				$texto_cookies = __("And third-party use cookies to improve our products and services by analyzing their browsing habits. By accepting this notice mean that you consent to our [[Link_1]].",PWG_COOKIES_DOMAIN);
				//update_option( AT_COOKIES_TEXTO_FOOTER, $texto_cookies );
			}
			
			//Title link to cookie page:			
			$titulo_enlace = get_option(AT_COOKIES_URL_TITULO);
			if(!isset($titulo_enlace) || empty($titulo_enlace))
			{
				$titulo_enlace = __("Cookies Policy",PWG_COOKIES_DOMAIN);
				//update_option( AT_COOKIES_URL_TITULO, $titulo_enlace );
			}
						
			//Link cookies:
			$link = '<a id="link_avisocookies" href="'.$url_cookies.'" target="_blank" >'.$titulo_enlace.'</a>';
			
			//Title accept cookies:
			$aceptar = get_option(AT_COOKIES_TEXTO_BOTON);
			if(!isset($aceptar) || empty($aceptar))
			{
				$aceptar = __("Accept",PWG_COOKIES_DOMAIN);
				//update_option( AT_COOKIES_TEXTO_BOTON, $aceptar );
			}

			//Replace the tag with de url link:
			$etiqueta_link = "[[Link_1]]";
			$texto_cookies = str_replace($etiqueta_link, $link, $texto_cookies);
			
			//Get Id Cookie
			$id_cookie = PWGaliciaCookies::get_id_cookies();
			
			$output= '
			<form id="form-cookies" action=""  method="post" >
				<div class="divBottom" id="Cookies" style="display:none">
					<div class="row">
						<input type="hidden" id="hd_pwg_cookie_name" name="hd_pwg_cookie_name" value="'.$id_cookie.'">
						<div class="col-md-1 col-sm-1 col-xs-12 icon"></div>
						<div class="col-md-10 col-sm-10 col-xs-12 text">'.$texto_cookies.'</div>
						<div id="btnAcceptCookies" class="col-md-1 col-sm-1 col-xs-12 button" >'.$aceptar.'</div>
					</div>
				</div>
			</form>
			';
		
			echo $output;
		}
	}
	
	

	/**
	 * Create administration page of cookies.
	 */
	function pwg_cookies_render_page_admin(){
			
	
		//List of pages to assign to notice cookies page.
		$args = array(
				"post_parent" => 0,
				'post_status' => 'publish',
		);
		$pages_cookies = get_pages($args);
	
		?>
			<div class="wrap">
				<h2><?php echo __("Cookie settings",PWG_COOKIES_DOMAIN);?>.</h2>
				<br/>
								
				    <form method="post" role="form" action="options.php">
					    <?php settings_fields( PWG_COOKIES_SETTINGS ); ?>
					    <?php do_settings_sections( PWG_COOKIES_SETTINGS ); ?>
							
							
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php echo __("Enable cookies Notice",PWG_COOKIES_DOMAIN);?></th>
								<td>									
									<input type="checkbox"  							   					
				   					id="<?php echo AT_COOKIES_ACTIVAR;?>"
				    				name="<?php echo AT_COOKIES_ACTIVAR;?>" 
				    				value="1"					    				
				    				<?php if(get_option(AT_COOKIES_ACTIVAR)==1){ echo 'checked="checked"';} ?>>
								</td>
							</tr>   
							<tr valign="top">
								<th scope="row"><?php echo __("Preload cookies regardless of whether the notice is accepted",PWG_COOKIES_DOMAIN);?></th>
								<td>									
									<input type="checkbox"  							   					
				   					id="<?php echo AT_COOKIES_PRELOAD;?>"
				    				name="<?php echo AT_COOKIES_PRELOAD;?>" 
				    				value="1"					    				
				    				<?php if(get_option(AT_COOKIES_PRELOAD)==1){ echo 'checked="checked"';} ?>>
								</td>
							</tr> 
							<tr valign="top">
								<th scope="row"><?php echo __("Text warning cookies",PWG_COOKIES_DOMAIN);?></th>
								<td>
									<textarea rows="4" 
										class="large-text code" 
										id="<?php echo AT_COOKIES_TEXTO_FOOTER;?>" 
										name="<?php echo AT_COOKIES_TEXTO_FOOTER;?>"
										placeholder="<?php echo __("Enter the message text from cookies",PWG_COOKIES_DOMAIN);?>" 
										><?php echo get_option(AT_COOKIES_TEXTO_FOOTER); ?></textarea>
									 <p class="description">    
								   		<p>
								   		<?php echo __("Positions the text [[Link_1]] where you want the link to the page display ad cookies",PWG_COOKIES_DOMAIN);?>
								   		</p>       
								   </div>  
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row"><?php echo __("Link title to page warning cookies",PWG_COOKIES_DOMAIN);?></th>
								<td>									
									<input type="text" 
							    	class="regular-text"
							    	id="<?php echo AT_COOKIES_URL_TITULO;?>"
							    	name="<?php echo AT_COOKIES_URL_TITULO;?>"
							    	value="<?php echo get_option(AT_COOKIES_URL_TITULO); ?>"
							    	placeholder="<?php echo __("Enter the text of the link to the cookies warning",PWG_COOKIES_DOMAIN);?>">
								</td>
							</tr>  
							
							<tr valign="top">
								<th scope="row"><?php echo __("Page url warning cookies",PWG_COOKIES_DOMAIN);?></th>
								<td>									
								  	<select 
								    	id="<?php echo AT_COOKIES_URL;?>"
								    	name="<?php echo AT_COOKIES_URL;?>"
								    	class="regular-text">
									    <?php 
									    	$selected_page = get_option(AT_COOKIES_URL);
									    											    
										    foreach($pages_cookies as $page){
												$selected = false;
												if($selected_page == $page->guid)
													$selected = true;
												
												?>
												<option value="<?php echo $page->guid;?>"
													<?php echo $selected?'selected="true"':"";?>
												>
													<?php echo $page->post_title;?>
												</option>
												<?php
											}										    
									    ?>	
								    </select>	
								</td>
							</tr>  
							
							<tr valign="top">
								<th scope="row"><?php echo __("Text button cookie acceptance notice",PWG_COOKIES_DOMAIN);?>			</th>
								<td>									
									 <input type="text" 
								    	class="regular-text"
								    	id="<?php echo AT_COOKIES_TEXTO_BOTON;?>"
								    	name="<?php echo AT_COOKIES_TEXTO_BOTON;?>"
								    	value="<?php echo get_option(AT_COOKIES_TEXTO_BOTON); ?>"
								    	placeholder="<?php echo __("Enter the text of the button to accept cookies Announcement",PWG_COOKIES_DOMAIN);?>">
								</td>
							</tr>  
							
							<tr valign="top">
								<th scope="row"><?php echo __("Enable Google Analytics",PWG_COOKIES_DOMAIN);?>	</th>
								<td>									
									<input type="checkbox"  							   					
				   					id="<?php echo AT_GOOGLE_ANALYTICS_ACTIVAR;?>"
				    				name="<?php echo AT_GOOGLE_ANALYTICS_ACTIVAR;?>" 
				    				value="1"					    				
				    				<?php if(get_option(AT_GOOGLE_ANALYTICS_ACTIVAR)==1){ echo 'checked="checked"';} ?>>
								</td>
							</tr> 
							
							<tr valign="top">
								<th scope="row"><?php echo __("Google Analytics Code",PWG_COOKIES_DOMAIN);?></th>
								<td>									
									 <input type="text" 
								    	class="regular-text"
								    	id="<?php echo AT_GOOGLE_ANALYTICS_CODIGO;?>"
								    	name="<?php echo AT_GOOGLE_ANALYTICS_CODIGO;?>"
								    	value="<?php echo get_option(AT_GOOGLE_ANALYTICS_CODIGO); ?>"
								    	placeholder="<?php echo __("Enter your keywords in google analytics",PWG_COOKIES_DOMAIN);?>">
								</td>
							</tr>  
							 
						</table>
						
			
						<?php submit_button(); ?>
					</form>
		
			
			</div>
			<?php 
		}
}



/*
 $texto_cookies = Yii::t('avisos', 'cookies_alert_texto');
		$link ='<a href="javascript:;" id="link_avisocookies" >'.Yii::t('avisos', 'cookies_titulo').'</a>';
		$aceptar  = Yii::t('avisos', 'cookies_bt_aceptar');
		
		echo '
			<div class="divBottom" id="Cookies" style="display:none">
				<div class="row">								
					<div class="col-md-1 col-sm-1 col-xs-12 icon"></div>
					<div class="col-md-10 col-sm-10 col-xs-12 text">'.$texto_cookies.' '.$link.'.</div>
					<div id="btnAcceptCookies" class="col-md-1 col-sm-1 col-xs-12 button" >'.$aceptar.'</div>					
				</div>
			</div>
		'; 
  
 isset($_COOKIE[self::NAME_COOKIE]);
 */


