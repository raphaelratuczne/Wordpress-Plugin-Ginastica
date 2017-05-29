<?php
/**
 * @package Ginastica
 * @version 1.0
 */
/*
Plugin Name: Ginastica
Plugin URI:
Description: Gerenciador das turmas de Ginastica
Author: Raphael J. Ratuczne
Version: 1.0
Author URI:
*/

// define (PONE_URL, plugins_url('', __FILE__));
// define (PONE_DIR, plugin_dir_path(__FILE__));

// require_once(PONE_DIR . 'admin.php');

// adiciona a opção no menu
add_action('admin_menu', 'r_register_menus');
// acoes de ativacao
register_activation_hook(__FILE__, 'r_install_hook');
// acoes de desativacao
register_deactivation_hook(__FILE__, 'r_uninstall_hook');

function r_register_menus() {
  add_menu_page('Ginastica', 'Ginastica', 'read', 'r_ginastica', 'r_render_page');
}


function r_install_hook() {
  global $wpdb;

  // cria uma nova tabela no banco

  // nome da tabela a ser
  $table_name = $wpdb->prefix . 'ginastica';

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `id_app` INT NOT NULL ,
    `id_user` INT NOT NULL ,
    `created_at` DATETIME NOT NULL ,
    `updated_at` DATETIME NOT NULL ,
    `date` DATE NOT NULL ,
    `company` VARCHAR(160) NOT NULL ,
    `city` VARCHAR(160) NOT NULL ,
    `room` VARCHAR(160) NOT NULL ,
    `potential` INT(3) NOT NULL ,
    `participants` INT(3) NOT NULL ,
    `absence` VARCHAR(160) NOT NULL ,
    PRIMARY KEY (`id`)
  ) $charset_collate";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );


}

function r_uninstall_hook() {
  global $wpdb;

  // nome da tabela a ser excluida
  $table_name = $wpdb->prefix . 'ginastica';

  $sql = "DROP TABLE `$table_name`";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  $wpdb->query($sql);
}

function r_render_page() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'ginastica';

  // verifica qts resgistros tem na pagina
  $sql_c = "SELECT COUNT(*) FROM $table_name";
  $n = $wpdb->get_var($sql_c);

  // se não tiver registros, adiciona alguns
  if ($n < 1) {
    $datas = array( 0 => '2017-03-15',
                    1 => '2017-03-16',
                    2 => '2017-03-17' );

    $empresas = array(  0 => 'Empresa Letra A',
                        1 => 'Empresa Numero 1',
                        2 => 'Outra Empresa' );

    $cidades = array(0 => 'Navegantes', 1 => 'Itajai');

    $salas = array( 0 => 'Secretaria',
                    1 => 'Produção',
                    2 => 'Criação',
                    3 => 'Atendimento' );

  foreach ($datas as $data) {
    foreach ($empresas as $empresa) {
      foreach ($cidades as $cidade) {
          foreach ($salas as $sala) {

            $wpdb->insert(
              $table_name,
              array(
                'id_app' => 1,
                'id_user' => 1,
                'created_at' => $data . ' 15:00:00',
                'updated_at' => $data . ' 15:00:00',
                'date' => $data,
                'company' => $empresa,
                'city' => $cidade,
                'room' => $sala,
                'potential' => 10,
                'participants' => 8,
                'absence' => 'texto texto texto texto texto',
              )
            );

          }
        }
      }
    }
  }

  // seleciona todas as datas
  $list_dates = $wpdb->get_results("SELECT DISTINCT `date` FROM `$table_name` ORDER BY `date` DESC");

  // var_dump($list_datas);

  // verifica se passou alguma data, senao pega a primeira
  $date = $_GET['date'] ? $_GET['date'] : $list_dates[0]->date;

  // lista de links
  $page_list = '';

  // se a data setada for a primeira
  if ($date == $list_dates[0]->date) {
    $page_list .= '<b>Primeira</b> ';
    $page_list .= '<b>Anterior</b> ';
  } else {
    // primeira pagina
    $page_list .= '<a href=" '.$_SERVER['PHP_SELF'].'?page=r_ginastica&date='.$list_dates[0]->date.'" title="Primeira pagina">Primeira</a> ';
    // se nao for a primeira
    foreach ($list_dates as $k => $v) {
      // encontra a data na lista
      if ($date == $v->date) {
        // seta a anterior
        $page_list .= '<a href=" '.$_SERVER['PHP_SELF'].'?page=r_ginastica&date='.$list_dates[$k-1]->date.'">Anterior</a> ';
        break;
      }
    }
  }


  foreach ($list_dates as $d) {
    if ($date == $d->date) {
      $page_list .= '<b>'.date_format(date_create($date), 'd/m/Y').'</b> ';
    } else {
      $page_list .= '<a href=" '.$_SERVER['PHP_SELF'].'?page=r_ginastica&date='.$d->date.'">'.date_format(date_create($d->date), 'd/m/Y').'</a> ';
    }
  }

  if ($date == $list_dates[count($list_dates)-1]->date) {
    $page_list .= '<b>Proxima</b> ';
    $page_list .= '<b>Ultima</b> ';
  } else {
    // se nao for a ultima
    foreach ($list_dates as $k => $v) {
      // encontra a data na lista
      if ($date == $v->date) {
        // seta a proxima
        $page_list .= '<a href=" '.$_SERVER['PHP_SELF'].'?page=r_ginastica&date='.$list_dates[$k+1]->date.'">Proxima</a> ';
        break;
      }
    }
    // ultima pagina
    $page_list .= '<a href=" '.$_SERVER['PHP_SELF'].'?page=r_ginastica&date='.$list_dates[count($list_dates)-1]->date.'">Ultima</a> ';
  }



  $sql = "SELECT * FROM `$table_name` WHERE `date` = '$date' ORDER BY `created_at`";

  $list = $wpdb->get_results($sql);

  ?>

  <div class="wrap">
    <h2>Ginastica</h2>
    <h3>Contagem de participantes</h3>
    <p>teste de plugin</p>

    <?= $page_list ?>
    <table class="widefat">
      <thead>
          <tr>
              <th>Data</th>
              <th>Empresa</th>
              <th>Cidade</th>
              <th>Grupo</th>
              <th>Potencial</th>
              <th>Participantes</th>
              <th>Ausência</th>
          </tr>
      </thead>
      <tfoot>
          <tr>
            <th>Data</th>
            <th>Empresa</th>
            <th>Cidade</th>
            <th>Grupo</th>
            <th>Potencial</th>
            <th>Participantes</th>
            <th>Ausência</th>
          </tr>
      </tfoot>
      <tbody>
        <?php foreach ($list as $l) { ?>
         <tr>
           <th><?= date_format(date_create($l->date), 'd/m/Y') ?></th>
           <th><?= $l->company ?></th>
           <th><?= $l->city ?></th>
           <th><?= $l->room ?></th>
           <th><?= $l->potential ?></th>
           <th><?= $l->participants ?></th>
           <th><?= $l->absence ?></th>
         </tr>
         <?php } ?>
      </tbody>
      </table>

  </div>
  <?php
  //var_dump($list);
}
?>
