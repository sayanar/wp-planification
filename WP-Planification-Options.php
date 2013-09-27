<?php
// Mise à jour des données par défaut
function WP_Planification_update() {
	global $wpdb, $table_WP_Planification; // insérer les variables globales

	$wp_planification_titre			= $_POST['wp_planification_title'];
	$wp_planification_style			= $_POST['wp_planification_style'];
	$wp_planification_date			= $_POST['wp_planification_date'];
	$wp_planification_displayorder	= $_POST['wp_planification_displayorder'];
	$wp_planification_separator		= $_POST['wp_planification_separator'];
	$wp_planification_totaltag		= $_POST['wp_planification_totaltag'];
	$wp_planification_totaltagbloc	= $_POST['wp_planification_totaltagbloc'];
	$wp_planification_totaltagdate	= $_POST['wp_planification_totaltagdate'];
	$wp_planification_totaltaglink	= $_POST['wp_planification_totaltaglink'];
	$wp_planification_classtag		= $_POST['wp_planification_classtag'];
	$wp_planification_classtagbloc	= $_POST['wp_planification_classtagbloc'];
	$wp_planification_classtagdate	= $_POST['wp_planification_classtagdate'];
	$wp_planification_classtagurl	= $_POST['wp_planification_classtagurl'];
	$wp_planification_titreerror	= $_POST['wp_planification_titreerror'];
	$wp_planification_phraseerror	= $_POST['wp_planification_phraseerror'];
	$wp_planification_limit			= $_POST['wp_planification_limit'];
	$wp_planification_choicecolumn	= $_POST['wp_planification_choicecolumn'];
	$wp_planification_targetcolumn	= $_POST['wp_planification_targetcolumn'];
	$wp_planification_ordercolumn	= $_POST['wp_planification_ordercolumn'];
	$wp_planification_orderby		= $_POST['wp_planification_orderby'];
		
	$wp_planification_update = $wpdb->update(
		$table_WP_Planification,
		array(
			"titre" => $wp_planification_titre,
			"StyleBloc" => $wp_planification_style,
			"FormatageDate" => $wp_planification_date,
			"DisplayOrder" => $wp_planification_displayorder,
			"Separateur" => $wp_planification_separator,
			"TotalTag" => $wp_planification_totaltag,
			"TotalTagBloc" => $wp_planification_totaltagbloc,
			"TotalTagDate" => $wp_planification_totaltagdate,
			"TotalTagLink" => $wp_planification_totaltaglink,
			"ClassTag" => $wp_planification_classtag,
			"ClassTagBloc" => $wp_planification_classtagbloc,
			"ClassTagDate" => $wp_planification_classtagdate,
			"ClassTagURL" => $wp_planification_classtagurl,
			"TitreError" => $wp_planification_titreerror,
			"PhraseError" => $wp_planification_phraseerror,
			"LimitColumn" => $wp_planification_limit,
			"ChoiceColumn" => $wp_planification_choicecolumn,
			"TargetColumn" => $wp_planification_targetcolumn,
			"OrderColumn" => $wp_planification_ordercolumn,
			"OrderBy" => $wp_planification_orderby
		), 
		array('id' => 1)
	);
}

// Fonction d'affichage de la page d'aide et de réglages de l'extension
function WP_Planification_Callback() {
	global $wpdb, $table_WP_Planification; // insérer les variables globales
	
	// Déclencher la fonction de mise à jour (upload)
	if(isset($_POST['wp_planification_action']) && $_POST['wp_planification_action'] == __('Enregistrer' , 'WP-Planification')) {
		WP_Planification_update();
	}

	/* --------------------------------------------------------------------- */
	/* ------------------------ Affichage de la page ----------------------- */
	/* --------------------------------------------------------------------- */
	echo '<div class="wrap">';
	echo '<div id="icon-options-general" class="icon32"><br /></div>';
	echo '<h2>'; _e('Aide et réglages de WP-Planification.','WP-Planification'); echo '</h2><br/>';
	_e('<strong>WP-Planification</strong> est un plugin permettant d\'afficher des publications futures comme bon vous semble.', 'WP-Planification'); echo '<br/>';
	_e('Le fonctionnement est simple : il suffit de <strong>planifier une date de publication à venir</strong> pour que la page ou l\'article soit affiché par le plugin (widget ou shortcode selon votre choix).', 'WP-Planification');	echo '<br/><br/>';
    _e('Deux options d\'utilisation :','WP-Planification');
	echo '<ol>';
	echo '<li>'; _e('<strong>Widget</strong> entièrement modulable','WP-Planification'); echo '</li>';
	echo '<li>'; _e('<strong>Shortcode [planification]</strong> à ajouter dans les articles ou pages','WP-Planification'); echo '</li>';
	echo '</ol>';
	_e('<em>N.B. : n\'hésitez pas à contacter <a href="http://blog.internet-formation.fr" target="_blank">Mathieu Chartier</a>, le créateur du plugin, pour de plus amples informations.</em>' , 'WP-Planification'); echo '<br/><br/>';

	// Formulaire de configuration du Shortcode
	echo '<h2>'; _e('Paramètres de l\'extension','WP-Planification'); echo '</h2>';

		// Sélection des données dans la base de données		
		$select = $wpdb->get_row("SELECT * FROM $table_WP_Planification WHERE id=1");
?>
        <form method="post" action="">
        <p>
			<label for="wp_planification_title"><strong><?php _e('Titre','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->titre; ?>" name="wp_planification_title" id="wp_planification_title" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <h3><br/><?php _e('Options d\'affichage','WP-Planification'); ?></h3>
        <p>
			<label for="wp_planification_date"><strong><?php _e('Formatage de la date','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->FormatageDate; ?>" name="wp_planification_date" id="wp_planification_date" type="text" style="width:20%;border:1px solid #ccc;" />
            <br/><em><?php _e('Voir documentation PHP sur les dates</em><br/><em>(ex : "l j F Y" pour "mardi 25 juin 2013")','WP-Planification'); ?></em>
        </p>
        <p>
			<label for="wp_planification_displayorder"><strong><?php _e('Ordre et options d\'affichage','WP-Planification'); ?></strong></label><br />
	        <select name="wp_planification_displayorder" id="wp_planification_displayorder" style="width:20%;border:1px solid #ccc;">
            	<option value="Date + Lien" <?php if($select->DisplayOrder == 'Date + Lien') { echo 'selected="selected"'; } ?>><?php _e('Date + Lien','WP-Planification'); ?></option>
                <option value="Lien + Date" <?php if($select->DisplayOrder == 'Lien + Date') { echo 'selected="selected"'; } ?>><?php _e('Lien + Date','WP-Planification'); ?></option>
                <option value="Lien seul" <?php if($select->DisplayOrder == 'Lien seul') { echo 'selected="selected"'; } ?>><?php _e('Lien seul','WP-Planification'); ?></option>
            </select>
        </p>
        <p>
			<label for="wp_planification_separator"><strong><?php _e('Séparateur entre la date et le titre','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->Separateur; ?>" name="wp_planification_separator" id="wp_planification_separator" type="text" style="width:20%;border:1px solid #ccc;" />
            <br/><em><?php _e('Code HTML accepté','WP-Planification'); ?> (<?php echo esc_attr('<br/> par exemple'); ?>)</em>
        </p>
        <p>
			<label for="wp_planification_totaltag"><strong><?php _e('Balises d\'encadrement du conteneur général :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->TotalTag; ?>" name="wp_planification_totaltag" id="wp_planification_totaltag" type="text" style="width:20%;border:1px solid #ccc;" />
            <br/><em><?php _e('(div, section, aside...)','WP-Planification'); ?></em>
        </p>
        <p>
			<label for="wp_planification_totaltagbloc"><strong><?php _e('Balises d\'encadrement du bloc "Date + Titre" :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->TotalTagBloc; ?>" name="wp_planification_totaltagbloc" id="wp_planification_totaltagbloc" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_totaltagdate"><strong><?php _e('Balises d\'encadrement de la date :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->TotalTagDate; ?>" name="wp_planification_totaltagdate" id="wp_planification_totaltagdate" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_totaltaglink"><strong><?php _e('Balises d\'encadrement du texte (lien) :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->TotalTagLink; ?>" name="wp_planification_totaltaglink" id="wp_planification_totaltaglink" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <h3><br/><?php _e('Affichage si aucune publication future n\'existe','WP-Planification'); ?></h3>
        <p>
			<label for="wp_planification_titreerror"><strong><?php _e('Titre affiché si aucun événement futur n\'est prévu :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->TitreError; ?>" name="wp_planification_titreerror" id="wp_planification_titreerror" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_phraseerror"><strong><?php _e('Phrase affichée si aucun événement futur n\'est prévu :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->PhraseError; ?>" name="wp_planification_phraseerror" id="wp_planification_phraseerror" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <h3><br/><?php _e('Options de la base de données','WP-Planification'); ?></h3>
        <p>
			<label for="wp_planification_limit"><strong><?php _e('Nombre de publications affichées : ','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->LimitColumn; ?>" name="wp_planification_limit" id="wp_planification_limit" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_choicecolumn"><strong><?php _e('Type de contenu à publier : ','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->ChoiceColumn; ?>" name="wp_planification_choicecolumn" id="wp_planification_choicecolumn" type="text" style="width:20%;border:1px solid #ccc;" />
            <br/><em><?php _e('Laisser vide pour tout afficher (ou au choix : post, page, attachment...)','WP-Planification'); ?></em>
        </p>
        <p>
			<label for="wp_planification_targetcolumn"><strong><?php _e('Colonne cible : ','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->TargetColumn; ?>" name="wp_planification_targetcolumn" id="wp_planification_targetcolumn" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_ordercolumn"><strong><?php _e('Colonne utilisée pour le classement :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->OrderColumn; ?>" name="wp_planification_ordercolumn" id="wp_planification_ordercolumn" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_orderby"><strong><?php _e('Ordre d\'affichage :','WP-Planification'); ?></strong></label><br />
	        <select name="wp_planification_orderby" id="wp_planification_orderby" style="width:20%;border:1px solid #ccc;border:1px solid #ccc">
            	<option value="ASC" <?php if($select->StyleBloc == "ASC") { echo 'selected="selected"'; } ?>>ASC</option>
                <option value="DESC" <?php if($select->StyleBloc == "DESC") { echo 'selected="selected"'; } ?>>DESC</option>
            </select>
        </p>
        <h2><?php _e('Choix des classes CSS','WP-Planification'); ?></h2>
        <p>
	        <input value="1" <?php if($select->StyleBloc == 1) { echo 'checked="checked"'; } ?> name="wp_planification_style" id="wp_planification_style" type="checkbox" />&nbsp;<label for="wp_planification_style"><strong><?php _e('Ajout de la feuille de style par défaut ?','WP-Planification'); ?></strong></label>
        </p>
        <p>
			<label for="wp_planification_classtag"><strong><?php _e('Classe du bloc global :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->ClassTag; ?>" name="wp_planification_classtag" id="wp_planification_classtag" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_classtagbloc"><strong><?php _e('Classe du bloc "date + lien" :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->ClassTagBloc; ?>" name="wp_planification_classtagbloc" id="wp_planification_classtagbloc" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_classtagdate"><strong><?php _e('Classe de la date :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->ClassTagDate; ?>" name="wp_planification_classtagdate" id="wp_planification_classtagdate" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_planification_classtagurl"><strong><?php _e('Classe du lien (URL) ou du texte :','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $select->ClassTagURL; ?>" name="wp_planification_classtagurl" id="wp_planification_classtagurl" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p class="submit"><input type="submit" name="wp_planification_action" class="button-primary" value="<?php _e('Enregistrer' , 'WP-Planification'); ?>" /></p>
        </form>
<?php
	echo '</div>'; // Fin de la page d'admin
} // Fin de la fonction Callback
?>