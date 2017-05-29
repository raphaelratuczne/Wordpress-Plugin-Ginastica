<?php
/*
Plugin Name: O meu Plugin
Plugin URI: http://exemplo.org/o-meu-plugin
Description: Um plugin de teste didático
Version: 1.0
Author: Aluno da Escola WordPress
Author URI: http://exemplo.org
License: GPLv2
*/

/*
 *      Copyright 2012 Aluno da Escola WordPress <email@exemplo.org>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
?>

<?php

// Se quisermos ativar o modo de depuração de erros
// definimos com o valor true a seguinte constante
define( 'WP_DEBUG', true );

// Vamos carregar o ambiente do WordPress a partir
//de um único local, o wp-load.php
require( '../wp-load.php' );

// Ja temos o WordPress carregado, já é possível usar
// as suas potencialidades e testar os nossos códigos
// ou apenas incluir um script standalone.

?>

<?php include( 'script-standalone.php' ); ?>


<?php

// Incluir um ficheiro que se encontre na mesma
// diretoria do plugin.

include( plugin_dir_path( __FILE__ ) . 'ficheiro-a-incluir.php' );

// Fazer o display de uma imagem localizada
// na diretoria do plugin.

$imagem = '<img src="'.plugins_url( 'imagem.png', __FILE__ ).'" alt="" />';

// Criar um link para a homepage
$link = '<a href="'.home_url().'" title="Home">Homepage</a>';

?>

<?php

// Registamos a função para correr na ativação do plugin
register_activation_hook( __FILE__, 'ewp_install_hook' );

function ewp_install_hook() {
  // Vamos testar a versão do PHP e do WordPress
  // caso as versões sejam antigas, desativamos
  // o nosso plugin.
  if ( version_compare( PHP_VERSION, '5.2.1', '<' )
    or version_compare( get_bloginfo( 'version' ), '3.3', '<' ) ) {
      deactivate_plugins( basename( __FILE__ ) );
  }

  // Vamos criar um opção para ser guardada na base-de-dados
  // e incluir um valor por defeito.
  add_option( 'ewp_opcao', 'valor_por_defeito' );

}
?>

<?php

add_action( 'init', 'ewp_funcao_a_chamar' );

function ewp_funcao_a_chamar() {
  // Corre um código qualquer em PHP no init
}

?>
