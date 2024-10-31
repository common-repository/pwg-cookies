<?php
class PWGaliciaCookies{
	
	
	const TITULO_PAGINA_COOKIES = "Aviso cookies";
	
	
	public static function clean_text($String){
		$String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
		$String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
		$String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
		$String = str_replace(array('í','ì','î','ï'),"i",$String);
		$String = str_replace(array('é','è','ê','ë'),"e",$String);
		$String = str_replace(array('É','È','Ê','Ë'),"E",$String);
		$String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
		$String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
		$String = str_replace(array('ú','ù','û','ü'),"u",$String);
		$String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
		$String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
		$String = str_replace("ç","c",$String);
		$String = str_replace("Ç","C",$String);
		$String = str_replace("ñ","n",$String);
		$String = str_replace("Ñ","N",$String);
		$String = str_replace("Ý","Y",$String);
		$String = str_replace("ý","y",$String);
		
		$String = str_replace("&aacute;","a",$String);
		$String = str_replace("&Aacute;","A",$String);
		$String = str_replace("&eacute;","e",$String);
		$String = str_replace("&Eacute;","E",$String);
		$String = str_replace("&iacute;","i",$String);
		$String = str_replace("&Iacute;","I",$String);
		$String = str_replace("&oacute;","o",$String);
		$String = str_replace("&Oacute;","O",$String);
		$String = str_replace("&uacute;","u",$String);
		$String = str_replace("&Uacute;","U",$String);
		return $String;
	}
	
	
	/**
	 * Return the id used with the cookie for this blog.
	 * 
	 * @return string
	 */
	public static function get_id_cookies(){
		
		 /*Multisite*/
		//global $current_blog;		
		//$site_name = preg_replace("/[^a-z0-9\.]/", "_", strtolower($current_blog->path));
		////$site_name = $site_name.$current_blog->site_id;
		//$site_name = $site_name.$current_blog->blog_id;
		
		$blog_title = get_bloginfo('name');	
		$blog_title =  PWGaliciaCookies::clean_text($blog_title);
		$site_name = preg_replace("/[^a-z0-9\.]/", "_", strtolower($blog_title));
		$id_cookie =  "pwg_cookie_".$site_name;
		
		return $id_cookie;
	}
	
	/**
	 * Crea una página básica de cookies
	 *
	 * @param unknown $titulo_pagina
	 * @param unknown $descripcion_pagina
	 * @param unknown $template
	 *
	 * @return int Identificador de la página.
	 */
	public static function crear_pagina_cookies(){
		global $wpdb;
	
		$titulo_pagina =  self::TITULO_PAGINA_COOKIES;
		$contenido_pagina = '
	<div class="row-avisos">
		<h4>¿Qué son las Cookies?</h4>
		<p> Una cookie es un archivo o dispositivo que se descarga en el equipo terminal de un usuario con la finalidad de almacenar datos que podrán ser actualizados y recuperados por la entidad responsable de su instalación. Es decir, es un fichero que se descarga en su ordenador al acceder a determinadas páginas web. Las cookies permiten a una página web, entre otras cosas, almacenar y recuperar información sobre los hábitos de navegación de un usuario o de su equipo y, dependiendo de la información que contengan y de la forma en que utilice su equipo, pueden utilizarse para reconocer al usuario. </p>
	</div>
	<div class="row-avisos">
		<h4>¿Qué tipo de cookies utiliza esta página web?</h4>
		<p> La presente página web está utilizando cookies que permiten facilitar al usuario el uso y la navegación a través de la misma, garantizar el acceso a determinados servicios, así como mejorar la configuración funcional de la web. </p>
		<p> Concretamente, esta página web utiliza las siguientes cookies: </p>
		<p> <b>Cookies propias</b>: que se envían al equipo terminal del usuario desde un equipo o dominio gestionado por el propio editor y desde el que se presta el servicio solicitado al usuario. </p>
		<p> <b>Cookies de terceros</b>: que se envían al equipo terminal del usuario desde un equipo o dominio que no es gestionado por el editor, sino por otra entidad que trata los datos obtenidos a través de las cookies. </p>
		<p> <b>Cookies de sesión</b>: diseñadas para recabar y almacenar datos mientras el usuario accede a la web. </p>
		<p> <b>Cookies persistentes</b>: diseñadas para recabar y almacenar datos durante un período determinado de tiempo. </p>
		<p> <b>Cookies técnicas</b>: utilizadas para permitir al usuario la navegación a través de la página web, plataforma o aplicación. </p>
		<p> <b>Cookies de análisis</b>: estas cookies se utilizan para llevar a cabo un análisis de los comportamientos y acciones de los usuarios de la página web o plataforma así como para la elaboración de perfiles de navegación con el fin de realizar mejoras técnicas de funcionamiento y de servicio. </p>
	</div>
	<div class="row-avisos">
		<h4>Resumen de Cookies</h4>
	</div>
	<div class="row-avisos">
		<div class="table-responsive">
		  <table class="table table-bordered">
		   	<thead>
			<tr>
				<th>Tipo</th>
				<th>Caducidad</th>
				<th>Finalidad</th>
				<th>Propias/Terceros </th>
			</tr>
			</thead>
			<tbody>
				<tr>
					<td>Técnica</td>
					<td>De sesión</td>
					<td>Información de sesión para permitir la funcionalidad del sitio web o plataforma.</td>
					<td>Propia</td>
				</tr>
				<tr>
					
					<td>Analítica</td>
					<td>Persistente</td>
					<td>Para determinar nuevas sesiones / visitas.</td>
					<td>Tercero (Google analytics)</td>
				</tr>
				<tr>
	
					<td>Analítica</td>
					<td>Persistente</td>
					<td>Para determinar como el usuario llegó a su sitio.</td>
					<td>Tercero (Google analytics)</td>
				</tr>
				<tr>
	
					<td>Analítica</td>
					<td>Persistente</td>
					<td>Para distinguir a los usuarios y sesiones.</td>
					<td>Tercero (Google analytics)</td>
				</tr>
				<tr>
					
					<td>Técnica</td>
					<td>De sesión</td>
					<td>Complementa el funcionamiento de _utmb.</td>
					<td>Tercero (Google analytics)</td>
				</tr>
	
				<tr>
					
					<td>Técnica</td>
					<td>Persistente</td>
					<td>Contiene datos sobre preferencias de visualización.</td>
					<td>Tercero (Youtube)</td>
				</tr>
				<tr>
					
					<td>Analítica</td>
					<td>De sesión</td>
					<td>Contiene un identificador único para permitir el control de visitas a videos de Youtube.</td>
					<td>Tercero (Youtube)</td>
				</tr>
				<tr>
					
					<td>Analítica</td>
					<td>Persistente</td>
					<td>Contiene un identificador único para permitir el control de visitas a videos de Youtube.</td>
					<td>Tercero (Youtube)</td>
				</tr>
				<tr>
					
					<td>Técnica</td>
					<td>Persistente</td>
					<td>Contiene la preferencia de idioma del usuario.</td>
					<td>Propia</td>
				</tr>
			</tbody>
		  </table>
		</div>
	</div>
	
 	<div class="row-avisos">
		<h4>¿Cómo gestionar la configuración de las cookies?</h4>
		<p> En el presente apartado se lleva a cabo una breve exposición de cómo consultar y llevar a cabo la configuración del sistema de cookies en relación a los navegadores más comunes o más utilizados por los usuarios. </p>
		<p> En este sentido, prácticamente todos los navegadores permiten al usuario obtener información general sobre las cookies instaladas en una página web, concretamente verificar la existencia de las mismas, su duración o el sistema de eliminación. En este sentido, a título informativo, se facilitan una serie de enlaces relacionados:  </p>
		<p>
			<span class="glyphicon glyphicon-hand-right"></span>
			<a class="blueBold" href="https://support.google.com/chrome/answer/95647?hl=es">
			Chrome			</a>
		</p>
		<p>
			<span class="glyphicon glyphicon-hand-right"></span>
			<a class="blueBold" href="https://support.mozilla.org/es/kb/cookies-informacion-que-los-sitios-web-guardan-en-">Mozilla Firefox</a>
		</p>
		<p>
			<span class="glyphicon glyphicon-hand-right"></span>
			<a class="blueBold" href="http://windows.microsoft.com/es-es/windows7/how-to-manage-cookies-in-internet-explorer-9">Internet Explorer</a>
		</p>
		<p> Por otra parte, el usuario podrá permitir, bloquear o eliminar las cookies instaladas en su equipo modificando la configuración de su navegador conforme a las instrucciones indicadas en el mismo. A título informativo se indica que habitualmente la configuración de las cookies se suele llevar a cabo en el menú "Preferencias" o "Herramientas" de cada navegador, en cualquier caso, siempre podrá acudir a la ayuda de su navegador para solucionar o solventar las dudas que se le generen al respecto. </p>
		<p> Conviene tener en cuenta que, si se impide la instalación de todas las cookies, el contenido de la web así como determinadas funcionalidades y servicios facilitados por la misma pueden verse afectados. </p>
	</div>
	
	';
	
		//Obtenemos las páginas por su nombre por defecto:
		$the_page = get_page_by_title($titulo_pagina );
		if ( ! $the_page ) {
			// Creamos el objeto post:
			$_p = array();
			$_p['post_title'] = $titulo_pagina;
			$_p['post_content'] = $contenido_pagina;
			$_p['post_status'] = 'publish';
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // categoría por defecto
	
			// Hacemos el insert del post:
			$the_page_id = wp_insert_post( $_p );
	
			//Actualizamos el template:
			//update_post_meta($the_page_id, '_wp_page_template', $template);
	
		}
		else {
			// Si ya se ha activado con anterioridad, existe la página, solo
			// le cambiamos el estado a publicado.
			$the_page_id = $the_page->ID;
			$the_page->post_status = 'publish';
			$the_page_id = wp_update_post( $the_page );
		}
	
		//return $the_page_id;
	}
}