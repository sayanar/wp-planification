<?php
/*
Plugin Name: WP-Planification
Plugin URI: http://blog.internet-formation.fr/2013/07/wp-planification/
Description: Plugin utilisé pour créer un widget personnalisé permettant de récupérer dynamiquement les articles et pages planifiées (futures) dans WordPress. Il est donc possible d'afficher des événements futurs ou des publications à venir en utilisant la planification (widget ou shortcode). (<em>Plugin used to create a custom widget to dynamically retrieve the scheduled posts and pages in WordPress. It is therefore possible to display scheduled events or future publications using WP-Planification (widget or shortcode)</em>).
Author: Mathieu Chartier
Version: 1.0
Author URI: http://blog.internet-formation.fr
*/

// Instanciation des variables globales
global $wpdb, $table_WP_Planification;
$table_WP_Planification = $wpdb->prefix.'planification';

// Gestion des langues
function WP_Planification_Lang() {
   $path = dirname(plugin_basename(__FILE__)).'/lang/';
   load_plugin_textdomain('WP-Planification', NULL, $path);
}
add_action('plugins_loaded', 'WP_Planification_Lang');

// Fonction lancée lors de l'activation ou de la desactivation de l'extension
register_activation_hook( __FILE__, 'WP_Planification_install' );
register_deactivation_hook( __FILE__, 'WP_Planification_desinstall' );

function WP_Planification_install() {	
	global $wpdb, $table_WP_Planification;

	// Création de la table de base
	$sql = "CREATE TABLE $table_WP_Planification (
		id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		titre VARCHAR( 250 ) NOT NULL, 
		StyleBloc VARCHAR( 5 ) NOT NULL,
		FormatageDate VARCHAR( 30 ) NOT NULL,
		DisplayOrder VARCHAR( 30 ) NOT NULL,
		Separateur VARCHAR( 250 ) NOT NULL,
		TitreTag VARCHAR( 30 ) NOT NULL,
		TotalTag VARCHAR( 30 ) NOT NULL,
		TotalTagBloc VARCHAR( 30 ) NOT NULL,
		TotalTagDate VARCHAR( 30 ) NOT NULL,
		TotalTagLink VARCHAR( 30 ) NOT NULL,
		ClassTag VARCHAR( 100 ) NOT NULL,
		ClassTagBloc VARCHAR( 100 ) NOT NULL,
		ClassTagDate VARCHAR( 100 ) NOT NULL,
		ClassTagURL VARCHAR( 100 ) NOT NULL,
		TitreError VARCHAR( 250 ) NOT NULL,
		PhraseError TEXT NOT NULL,
		LimitColumn VARCHAR( 10 ) NOT NULL,
		ChoiceColumn VARCHAR( 50 ) NULL,
		TargetColumn VARCHAR( 30 ) NOT NULL,
		OrderColumn VARCHAR( 30 ) NOT NULL,
		OrderBy VARCHAR( 50 ) NOT NULL
		);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	// Insertion de valeurs par défaut (premier enregistrement)
	$defaut = array(
		"titre" => __('Prochains événements','WP-Planification'),
		"StyleBloc" => "1",
		"FormatageDate" => __('l j F Y','WP-Planification'),
		"DisplayOrder" => __('Date + Lien','WP-Planification'),
		"Separateur" => " : ",
		"TitreTag" => "h2",
		"TotalTag" => "div",
		"TotalTagBloc" => "p",
		"TotalTagDate" => "span",
		"TotalTagLink" => "a",
		"ClassTag" => "WP-Planification",
		"ClassTagBloc" => "WP-Planification-Bloc",
		"ClassTagDate" => "WP-Planification-Date",
		"ClassTagURL" => "WP-Planification-URL",
		"TitreError" => __('Aucun événement','WP-Planification'),
		"PhraseError" => __('Aucun événement pour le moment','WP-Planification'),
		"LimitColumn" => "5",
		"ChoiceColumn" => "post",
		"TargetColumn" => "post_date",
		"OrderColumn" => "post_date",
		"OrderBy" => "ASC"
	);
	$champ = wp_parse_args($instance, $defaut);
	$default = $wpdb->insert($table_WP_Planification, array('titre' => $champ['titre'], 'StyleBloc' => $champ['StyleBloc'], 'FormatageDate' => $champ['FormatageDate'], 'DisplayOrder' => $champ['DisplayOrder'], 'Separateur' => $champ['Separateur'], 'TitreTag' => $champ['TitreTag'], 'TotalTag' => $champ['TotalTag'], 'TotalTagBloc' => $champ['TotalTagBloc'], 'TotalTagDate' => $champ['TotalTagDate'], 'TotalTagLink' => $champ['TotalTagLink'], 'ClassTag' => $champ['ClassTag'], 'ClassTagBloc' => $champ['ClassTagBloc'], 'ClassTagDate' => $champ['ClassTagDate'], 'ClassTagURL' => $champ['ClassTagURL'], 'TitreError' => $champ['TitreError'], 'PhraseError' => $champ['PhraseError'], 'LimitColumn' => $champ['LimitColumn'], 'ChoiceColumn' => $champ['ChoiceColumn'], 'TargetColumn' => $champ['TargetColumn'], 'OrderColumn' => $champ['OrderColumn'], 'OrderBy' => $champ['OrderBy']));
}
function WP_Planification_desinstall() {
	global $wpdb, $table_WP_Planification;
	// Suppression de la table de base
	$wpdb->query("DROP TABLE IF EXISTS $table_WP_Planification");
}

// Ajout d'une page de sous-menu
function WP_Planification_admin() {
	$parent_slug	= 'options-general.php';					// Page dans laquelle est ajoutée le sous-menu
	$page_title		= 'Aide et réglages de WP-Planification';	// Titre interne à la page de réglages
	$menu_title		= 'WP-Planification';						// Titre du sous-menu
	$capability		= 'manage_options';							// Rôle d'administration qui a accès au sous-menu
	$menu_slug		= 'wp-planification';						// Alias (slug) de la page
	$function		= 'WP_Planification_Callback';				// Fonction appelé pour afficher la page de réglages
	add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}
add_action('admin_menu', 'WP_Planification_admin');

// Ajout conditionné d'une feuille de style personnalisée
function WP_Planification_CSS($bool) {
	if($bool == 1) {
		$url = plugins_url('WP_Planification_style.css',__FILE__);
		wp_register_style('WP_Planification_style', $url);
		wp_enqueue_style('WP_Planification_style');
		add_action('wp_enqueue_scripts', 'WP_Planification_CSS');
	}
}

// Fonction pour choisir la balise qui encapsule dynamiquement des blocs (avec H3 par défaut)
function encapsuleBloc($Contenant, $Balises = "h3", $Attributs = array()) {	
	$listeAttributs = ''; // On crée un variable qui liste les attributs (pour stocker la valeur finale)
	
	// on récupère la totalité des couples 'attribut'=>'valeur' d'un tableau
	foreach($Attributs as $cle => $valeur) {
		$listeAttributs .= ' '.$cle.'="'.$valeur.'"';
	}
	
	// on retourne le résultat en récupérant toutes les variables utiles
	$encapsule = "<".$Balises.$listeAttributs.">$Contenant</$Balises>";
	return $encapsule;
}

// Code du shortcode dans le fichier dédié
include('WP-Planification-Options.php');

// Code du widget dans le fichier dédié
include('WP-Planification-Widget.php');

// Code du widget dans le fichier dédié
include('WP-Planification-Shortcode.php');
?>