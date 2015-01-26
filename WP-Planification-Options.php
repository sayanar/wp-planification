<?php
// Mise à jour des données par défaut
function WP_Planification_update() {
	global $wpdb, $table_WP_Planification; // insérer les variables globales

	$wp_planification_activ				= $_POST['wp_planification_activ'];
	$wp_planification_titre				= $_POST['wp_planification_title'];
	$wp_planification_suite				= $_POST['wp_planification_suite'];
	$wp_planification_totaltagsuite		= $_POST['wp_planification_totaltagsuite'];
	$wp_planification_textsuite			= $_POST['wp_planification_textsuite'];
	$wp_planification_classtagsuite		= $_POST['wp_planification_classtagsuite'];
	$wp_planification_style				= $_POST['wp_planification_style'];
	$wp_planification_date				= $_POST['wp_planification_date'];
	$wp_planification_displaydl			= $_POST['wp_planification_displaydl'];
	$wp_planification_displaycontent	= $_POST['wp_planification_displaycontent'];
	$wp_planification_displayimage		= $_POST['wp_planification_displayimage'];
	$wp_planification_displayorder		= $_POST['wp_planification_displayorder'];
	$wp_planification_separator			= $_POST['wp_planification_separator'];
	$wp_planification_separator2		= $_POST['wp_planification_separator2'];
	$wp_planification_separator3		= $_POST['wp_planification_separator3'];
	$wp_planification_totaltag			= $_POST['wp_planification_totaltag'];
	$wp_planification_totaltagbloc		= $_POST['wp_planification_totaltagbloc'];
	$wp_planification_totaltagdate		= $_POST['wp_planification_totaltagdate'];
	$wp_planification_totaltaglink		= $_POST['wp_planification_totaltaglink'];
	$wp_planification_totaltagcontent	= $_POST['wp_planification_totaltagcontent'];
	$wp_planification_totaltagimage		= $_POST['wp_planification_totaltagimage'];
	$wp_planification_classtag			= $_POST['wp_planification_classtag'];
	$wp_planification_classtagbloc		= $_POST['wp_planification_classtagbloc'];
	$wp_planification_classtagcontent	= $_POST['wp_planification_classtagcontent'];
	$wp_planification_classtagimage		= $_POST['wp_planification_classtagimage'];
	$wp_planification_classtagdate		= $_POST['wp_planification_classtagdate'];
	$wp_planification_classtagurl		= $_POST['wp_planification_classtagurl'];
	$wp_planification_titreerror		= $_POST['wp_planification_titreerror'];
	$wp_planification_phraseerror		= $_POST['wp_planification_phraseerror'];
	$wp_planification_limit				= $_POST['wp_planification_limit'];
	$wp_planification_choicecolumn		= $_POST['wp_planification_choicecolumn'];
	$wp_planification_targetcolumn		= $_POST['wp_planification_targetcolumn'];
	$wp_planification_ordercolumn		= $_POST['wp_planification_ordercolumn'];
	$wp_planification_orderby			= $_POST['wp_planification_orderby'];
	$wp_planification_clear				= $_POST['wp_planification_clear'];
		
	$wp_planification_update = $wpdb->update(
		$table_WP_Planification,
		array(
			"activ" => $wp_planification_activ,
			"titre" => $wp_planification_titre,
			"suite" => $wp_planification_suite,
			"TotalTagSuite" => $wp_planification_totaltagsuite,
			"TextSuite" => $wp_planification_textsuite,
			"ClassTagSuite" => $wp_planification_classtagsuite,
			"StyleBloc" => $wp_planification_style,
			"FormatageDate" => $wp_planification_date,
			"DisplayDL" => $wp_planification_displaydl,
			"DisplayContent" => $wp_planification_displaycontent,
			"DisplayImage" => $wp_planification_displayimage,
			"DisplayOrder" => $wp_planification_displayorder,
			"Separateur" => $wp_planification_separator,
			"SeparateurContenu" => $wp_planification_separator2,
			"SeparateurImage" => $wp_planification_separator3,
			"TotalTag" => $wp_planification_totaltag,
			"TotalTagBloc" => $wp_planification_totaltagbloc,
			"TotalTagDate" => $wp_planification_totaltagdate,
			"TotalTagLink" => $wp_planification_totaltaglink,
			"TotalTagContent" => $wp_planification_totaltagcontent,
			"TotalTagImage" => $wp_planification_totaltagimage,
			"ClassTag" => $wp_planification_classtag,
			"ClassTagBloc" => $wp_planification_classtagbloc,
			"ClassTagContent" => $wp_planification_classtagcontent,
			"ClassTagImage" => $wp_planification_classtagimage,
			"ClassTagDate" => $wp_planification_classtagdate,
			"ClassTagURL" => $wp_planification_classtagurl,
			"TitreError" => $wp_planification_titreerror,
			"PhraseError" => $wp_planification_phraseerror,
			"LimitColumn" => $wp_planification_limit,
			"ChoiceColumn" => $wp_planification_choicecolumn,
			"TargetColumn" => $wp_planification_targetcolumn,
			"OrderColumn" => $wp_planification_ordercolumn,
			"OrderBy" => $wp_planification_orderby,
			"clear" => $wp_planification_clear
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
	_e('Le fonctionnement est simple : il suffit de <strong>planifier une date de publication à venir</strong> pour que la page ou l\'article soit affiché par le plugin (widget ou shortcode selon votre choix).', 'WP-Planification');	echo '<br/>';
	_e('Ajoutez librement la date (ou non), le lien (ou non), un contenu (page ou article) ou un extrait (ou non) ainsi qu\'une image à la Une (ou non)...', 'WP-Planification'); echo '<br/><br/>';
    _e('Deux options d\'utilisation :','WP-Planification');
	echo '<ol>';
	echo '<li>'; _e('<strong>Widget</strong> entièrement modulable','WP-Planification'); echo '</li>';
	echo '<li>'; _e('<strong>Shortcode [planification]</strong> à ajouter dans les articles ou pages','WP-Planification'); echo '</li>';
	echo '</ol>';
	_e('<em>N.B. : n\'hésitez pas à contacter <a href=\"http://blog.internet-formation.fr\" target=\"_blank\">Mathieu Chartier</a>, le créateur du plugin, pour de plus amples informations.</em>' , 'WP-Planification'); echo '<br/><br/>';

		// Sélection des données dans la base de données		
		$select = $wpdb->get_row("SELECT * FROM $table_WP_Planification WHERE id=1");
?>
<script language=javascript>
function montrer(object) {
   if (document.getElementById) document.getElementById(object).style.display = 'block';
}

function cacher(object) {
   if (document.getElementById) document.getElementById(object).style.display = 'none';
}
</script>

        <form method="post" action="">
        <table cols="4" width="100%">
        	<tr valign="top">
            	<td width="25%">
                    <h2><?php _e('Paramètres de l\'extension','WP-Planification'); ?></h2>
                    <p>
                        <label for="wp_planification_activ"><strong><?php _e('Afficher le bloc de planification','WP-Planification'); ?></strong></label>
                        <select name="wp_planification_activ" id="wp_planification_displayactiv" style="width:75%;border:1px solid #ccc;">
                            <option value="1" <?php if($select->activ == true) { echo 'selected="selected"'; } ?>><?php _e('Oui','WP-Planification'); ?></option>
                            <option value="0" <?php if($select->activ == false) { echo 'selected="selected"'; } ?>><?php _e('Non','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_title"><strong><?php _e('Titre du bloc (shortcode)','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->titre; ?>" name="wp_planification_title" id="wp_planification_title" type="text" style="width:75%;border:1px solid #ccc;" />
                        <br/><em><?php _e('Laisser vide pour masquer le titre...','WP-Planification'); ?></em>
                    </p>
                    <h3><br/><?php _e('Options d\'affichage','WP-Planification'); ?></h3>
                    <p>
                        <label for="wp_planification_date"><strong><?php _e('Formatage de la date','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->FormatageDate; ?>" name="wp_planification_date" id="wp_planification_date" type="text" style="width:75%;border:1px solid #ccc;" />
                        <br/><em><?php _e('<a href="http://php.net/manual/fr/function.date.php" target="_blank">Voir documentation PHP sur les dates</a></em><br/><em>(ex : "l j F Y" pour "mardi 25 juin 2013")','WP-Planification'); ?></em>
                    </p>
                    <p>
                        <label for="wp_planification_displayorder"><strong><?php _e('Ordre d\'affichage des blocs','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_displayorder" id="wp_planification_displayorder" style="width:75%;border:1px solid #ccc;">
                            <option value="DL" <?php if($select->DisplayOrder == 'DL') { echo 'selected="selected"'; } ?>><?php _e('Date-Titre seul','WP-Planification'); ?></option>
                            <option value="C" <?php if($select->DisplayOrder == 'C') { echo 'selected="selected"'; } ?>><?php _e('Extrait-Article seul','WP-Planification'); ?></option>
                            <option value="I" <?php if($select->DisplayOrder == 'I') { echo 'selected="selected"'; } ?>><?php _e('Image seule','WP-Planification'); ?></option>
                            <option value="DL + C" <?php if($select->DisplayOrder == 'DL + C') { echo 'selected="selected"'; } ?>><?php _e('Date-Titre => Extrait-Article','WP-Planification'); ?></option>
                            <option value="C + DL" <?php if($select->DisplayOrder == 'C + DL') { echo 'selected="selected"'; } ?>><?php _e('Extrait-Article => Date-Titre','WP-Planification'); ?></option>
                            <option value="DL + I" <?php if($select->DisplayOrder == 'DL + I') { echo 'selected="selected"'; } ?>><?php _e('Date-Titre => Image','WP-Planification'); ?></option>
                            <option value="I + DL" <?php if($select->DisplayOrder == 'I + DL') { echo 'selected="selected"'; } ?>><?php _e('Image => Date-Titre','WP-Planification'); ?></option>
                            <option value="I + C" <?php if($select->DisplayOrder == 'I + C') { echo 'selected="selected"'; } ?>><?php _e('Image => Extrait-Article','WP-Planification'); ?></option>
                            <option value="C + I" <?php if($select->DisplayOrder == 'C + I') { echo 'selected="selected"'; } ?>><?php _e('Extrait-Article => Image','WP-Planification'); ?></option>
                            <option value="DL + I + C" <?php if($select->DisplayOrder == 'DL + I + C') { echo 'selected="selected"'; } ?>><?php _e('Date-Titre => Image => Extrait-Article','WP-Planification'); ?></option>
                            <option value="DL + C + I" <?php if($select->DisplayOrder == 'DL + C + I') { echo 'selected="selected"'; } ?>><?php _e('Date-Titre => Extrait-Article => Image','WP-Planification'); ?></option>
                            <option value="C + DL + I" <?php if($select->DisplayOrder == 'C + DL + I') { echo 'selected="selected"'; } ?>><?php _e('Extrait-Article => Date-Titre => Image','WP-Planification'); ?></option>
                            <option value="C + I + DL" <?php if($select->DisplayOrder == 'C + I + DL') { echo 'selected="selected"'; } ?>><?php _e('Extrait-Article => Image => Date-Titre','WP-Planification'); ?></option>
                            <option value="I + DL + C" <?php if($select->DisplayOrder == 'I + DL + C') { echo 'selected="selected"'; } ?>><?php _e('Image => Date-Titre => Extrait-Article','WP-Planification'); ?></option>
                            <option value="I + C + DL" <?php if($select->DisplayOrder == 'I + C + DL') { echo 'selected="selected"'; } ?>><?php _e('Image => Extrait-Article => Date-Titre','WP-Planification'); ?></option>
                            <option value="Aucun" <?php if($select->DisplayOrder == 'Aucun') { echo 'selected="selected"'; } ?>><?php _e('Ne rien afficher...','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_displaydl"><strong><?php _e('Affichage de la date et/ou du lien ?','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_displaydl" id="wp_planification_displaydl" style="width:75%;border:1px solid #ccc;">
                            <option value="Date + Lien" <?php if($select->DisplayDL == 'Date + Lien') { echo 'selected="selected"'; } ?>><?php _e('Date + Lien','WP-Planification'); ?></option>
                            <option value="Lien + Date" <?php if($select->DisplayDL == 'Lien + Date') { echo 'selected="selected"'; } ?>><?php _e('Lien + Date','WP-Planification'); ?></option>
                            <option value="Date seule" <?php if($select->DisplayDL == 'Date seule') { echo 'selected="selected"'; } ?>><?php _e('Date seule','WP-Planification'); ?></option>
                            <option value="Lien seul" <?php if($select->DisplayDL == 'Lien seul') { echo 'selected="selected"'; } ?>><?php _e('Lien seul','WP-Planification'); ?></option>
                       		<option value="Aucun" <?php if($select->DisplayDL == 'Aucun') { echo 'selected="selected"'; } ?>><?php _e('Aucun des deux','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_displaycontent"><strong><?php _e('Affichage de l\'extrait ou de l\'article ?','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_displaycontent" id="wp_planification_displaycontent" style="width:75%;border:1px solid #ccc;">
                            <option value="Extrait" <?php if($select->DisplayContent == 'Extrait') { echo 'selected="selected"'; } ?>><?php _e('Extrait','WP-Planification'); ?></option>
                            <option value="Article" <?php if($select->DisplayContent == 'Article') { echo 'selected="selected"'; } ?>><?php _e('Article complet','WP-Planification'); ?></option>
                            <option value="Aucun" <?php if($select->DisplayContent == 'Aucun') { echo 'selected="selected"'; } ?>><?php _e('Aucun','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_suite"><strong><?php _e('Afficher un lien "Lire la suite..."','WP-Planification'); ?></strong></label>
                        <select name="wp_planification_suite" id="wp_planification_displaysuite" style="width:75%;border:1px solid #ccc;">
                            <option value="1" onclick="montrer('blockReadMore');" <?php if($select->suite == true) { echo 'selected="selected"'; } ?>><?php _e('Oui','WP-Planification'); ?></option>
                            <option value="0" onclick="cacher('blockReadMore');" <?php if($select->suite == false) { echo 'selected="selected"'; } ?>><?php _e('Non','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p id="blockReadMore" <?php if($select->suite == true) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
                        <label for="wp_planification_textsuite"><strong><?php _e('Texte du "Lire la suite..." (si actif)','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->TextSuite; ?>" name="wp_planification_textsuite" id="wp_planification_textsuite" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_displayimage"><strong><?php _e('Ajout de l\'image à la Une ?','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_displayimage" id="wp_planification_displayimage" style="width:75%;border:1px solid #ccc;">
                            <option value="Image" <?php if($select->DisplayImage == 'Image') { echo 'selected="selected"'; } ?>><?php _e('Oui','WP-Planification'); ?></option>
                            <option value="Aucune" <?php if($select->DisplayImage == 'Aucune') { echo 'selected="selected"'; } ?>><?php _e('Non','WP-Planification'); ?></option>
                        </select>
                        <br/><em><?php _e('Activer les images à la Une pour afficher la vignette','WP-Planification'); ?></em>
                        <br/><em><?php _e('N.B. : add_theme_support(\'post-thumbnails\'); dans functions.php pour activer l\'option','WP-Planification'); ?></em>
                    </p>
				</td>
                <td width="25%">
                	<h2><?php _e('Mise en page HTML','WP-Planification'); ?></h2>
                    <p>
                        <label for="wp_planification_separator"><strong><?php _e('Séparateur entre la date et le titre *','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->Separateur; ?>" name="wp_planification_separator" id="wp_planification_separator" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_separator2"><strong><?php _e('Séparateur pour l\'article ou l\'extrait *','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->SeparateurContenu; ?>" name="wp_planification_separator2" id="wp_planification_separator2" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_separator3"><strong><?php _e('Séparateur pour l\'image à la Une *','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->SeparateurImage; ?>" name="wp_planification_separator3" id="wp_planification_separator3" type="text" style="width:75%;border:1px solid #ccc;" />
                        <br/>
                    </p>
                    <p><em><?php _e('* Code HTML accepté','WP-Planification'); ?> (<?php echo esc_attr('<br/> par exemple'); ?>)</em></p>
                    <p>
                        <label for="wp_planification_totaltag"><strong><?php _e('Balises d\'encadrement du conteneur général','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltag" id="wp_planification_totaltag" style="width:75%;border:1px solid #ccc;">
                            <?php
								$totaltag = array('div', 'p', 'section','article','aside','header','footer','nav','ul','blockquote');
								for($i = 0; $i < count($totaltag); $i++) {
									$selected = '';
									if($select->TotalTag == $totaltag[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltag[$i].'" '.$selected.'>'.__($totaltag[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_totaltagbloc"><strong><?php _e('Balises d\'encadrement du bloc "Date + Titre"','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltagbloc" id="wp_planification_totaltagbloc" style="width:75%;border:1px solid #ccc;">
                            <?php
								$totaltagbloc = array('div', 'p', 'h1','h2','h3','h4','h5','h6','span','strong','em','header','footer','li','cite','sup','big','small');
								for($i = 0; $i < count($totaltagbloc); $i++) {
									$selected = '';
									if($select->TotalTagBloc == $totaltagbloc[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltagbloc[$i].'" '.$selected.'>'.__($totaltagbloc[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_totaltagdate"><strong><?php _e('Balises d\'encadrement de la date','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltagdate" id="wp_planification_totaltagdate" style="width:75%;border:1px solid #ccc;">
                            <?php
								$totaltagdate = array('div', 'p', 'h1','h2','h3','h4','h5','h6','span','strong','em','header','footer','li','cite','sup','big','small');
								for($i = 0; $i < count($totaltagdate); $i++) {
									$selected = '';
									if($select->TotalTagDate == $totaltagdate[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltagdate[$i].'" '.$selected.'>'.__($totaltagdate[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_totaltaglink"><strong><?php _e('Balises d\'encadrement du texte (lien)','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltaglink" id="wp_planification_totaltaglink" style="width:75%;border:1px solid #ccc;">
                        	<?php
								$totaltaglink = array('a','span','div', 'p', 'h1','h2','h3','h4','h5','h6','strong','em','header','footer','li','cite','sup','big','small');
								for($i = 0; $i < count($totaltaglink); $i++) {
									$selected = '';
									if($select->TotalTagLink == $totaltaglink[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltaglink[$i].'" '.$selected.'>'.__($totaltaglink[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                        <br/><em><?php _e('Remplacer "a" pour rendre le lien inactif...','WP-Planification'); ?></em>
                    </p>
                    <p>
                        <label for="wp_planification_totaltagcontent"><strong><?php _e('Balises d\'encadrement du contenu ou de l\'extrait','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltagcontent" id="wp_planification_totaltagcontent" style="width:75%;border:1px solid #ccc;">
                        	<?php
								$totaltagcontent = array('div', 'p', 'article','section','span','strong','em','header','footer','cite','big','small');
								for($i = 0; $i < count($totaltagcontent); $i++) {
									$selected = '';
									if($select->TotalTagContent == $totaltagcontent[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltagcontent[$i].'" '.$selected.'>'.__($totaltagcontent[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_totaltagsuite"><strong><?php _e('Balises d\'encadrement du "Lire la suite..."','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltagsuite" id="wp_planification_totaltagsuite" style="width:75%;border:1px solid #ccc;">
                        	<?php
								$totaltagsuite = array('div', 'p', 'span','strong','em','header','footer','cite','sup','big','small');
								for($i = 0; $i < count($totaltagsuite); $i++) {
									$selected = '';
									if($select->TotalTagSuite == $totaltagsuite[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltagsuite[$i].'" '.$selected.'>'.__($totaltagsuite[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_totaltagimage"><strong><?php _e('Balises d\'encadrement de l\'image à la Une','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_totaltagimage" id="wp_planification_totaltagimage" style="width:75%;border:1px solid #ccc;">
                        	<?php
								$totaltagimage = array('div', 'p', 'aside','section','span','header','footer');
								for($i = 0; $i < count($totaltagimage); $i++) {
									$selected = '';
									if($select->TotalTagImage == $totaltagimage[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$totaltagimage[$i].'" '.$selected.'>'.__($totaltagimage[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_clear"><strong><?php _e('Faire un "clear" après le bloc ?','WP-Planification'); ?></strong></label>
                        <select name="wp_planification_clear" id="wp_planification_displayclear" style="width:75%;border:1px solid #ccc;">
                            <option value="1" <?php if($select->clear == true) { echo 'selected="selected"'; } ?>><?php _e('Oui','WP-Planification'); ?></option>
                            <option value="0" <?php if($select->clear == false) { echo 'selected="selected"'; } ?>><?php _e('Non','WP-Planification'); ?></option>
                        </select>
                    </p>
        		</td>
                <td width="25%">
                    <h2><?php _e('Autres options','WP-Planification'); ?></h2>
                    <h3><?php _e('Options de la base de données','WP-Planification'); ?></h3>
                    <p>
                        <label for="wp_planification_limit"><strong><?php _e('Nombre de posts affichés par défaut','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->LimitColumn; ?>" name="wp_planification_limit" id="wp_planification_limit" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_choicecolumn"><strong><?php _e('Type de contenu à publier','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_choicecolumn" id="wp_planification_choicecolumn" style="width:75%;border:1px solid #ccc;border:1px solid #ccc">
                            <option value="post" <?php if($select->ChoiceColumn == "post") { echo 'selected="selected"'; } ?>><?php _e('post','WP-Planification'); ?></option>
                            <option value="page" <?php if($select->ChoiceColumn == "page") { echo 'selected="selected"'; } ?>><?php _e('page','WP-Planification'); ?></option>
                            <option value="" <?php if($select->ChoiceColumn == "") { echo 'selected="selected"'; } ?>><?php _e('Les deux','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_targetcolumn"><strong><?php _e('Colonne cible','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_targetcolumn" id="wp_planification_targetcolumn" style="width:75%;border:1px solid #ccc;border:1px solid #ccc">
                            <option value="post_date" <?php if($select->TargetColumn == "post_date") { echo 'selected="selected"'; } ?>><?php _e('post_date','WP-Planification'); ?></option>
                            <option value="post_date_gmt" <?php if($select->TargetColumn == "post_date_gmt") { echo 'selected="selected"'; } ?>><?php _e('post_date_gmt','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_ordercolumn"><strong><?php _e('Colonne utilisée pour le classement','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_ordercolumn" id="wp_planification_ordercolumn" style="width:75%;border:1px solid #ccc;">
                        	<?php
								$ordercolumn = array('ID','post_date','post_date_gmt','post_type','post_content','post_title','post_category','post_exceprt','post_name','post_modified','post_modified_gmt', 'guid');
								for($i = 0; $i < count($ordercolumn); $i++) {
									$selected = '';
									if($select->OrderColumn == $ordercolumn[$i]) {
										$selected = 'selected="selected"';
									}
	                            	echo '<option value="'.$ordercolumn[$i].'" '.$selected.'>'.__($ordercolumn[$i],"WP-Planification")."</option>\n";
								}
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_orderby"><strong><?php _e('Ordre d\'affichage','WP-Planification'); ?></strong></label><br />
                        <select name="wp_planification_orderby" id="wp_planification_orderby" style="width:75%;border:1px solid #ccc;border:1px solid #ccc">
                            <option value="ASC" <?php if($select->OrderBy == "ASC") { echo 'selected="selected"'; } ?>>ASC</option>
                            <option value="DESC" <?php if($select->OrderBy == "DESC") { echo 'selected="selected"'; } ?>>DESC</option>
                        </select>
                    </p>
                    <h3><br/><?php _e('Affichage si aucune publication future n\'existe','WP-Planification'); ?></h3>
                    <p>
                        <label for="wp_planification_titreerror"><strong><?php _e('Titre affiché si aucune planification *','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->TitreError; ?>" name="wp_planification_titreerror" id="wp_planification_titreerror" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_phraseerror"><strong><?php _e('Phrase affichée si aucune planification *','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->PhraseError; ?>" name="wp_planification_phraseerror" id="wp_planification_phraseerror" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p><em><?php _e('* Laisser les deux champs vides pour masquer le bloc quand aucune planification n\'existe','WP-Planification'); ?></em></p>
        		</td>
                <td width="25%">
                    <h2><?php _e('Choix des classes CSS','WP-Planification'); ?></h2>
                    <p>
                        <label for="wp_planification_style"><strong><?php _e('Ajout de la feuille de style par défaut ?','WP-Planification'); ?></strong></label>
                        <select name="wp_planification_style" id="wp_planification_style" style="width:75%;border:1px solid #ccc;">
                            <option value="Aucune" <?php if($select->StyleBloc == 'Aucune') { echo 'selected="selected"'; } ?>><?php _e('Aucune feuille','WP-Planification'); ?></option>
                            <option value="Vide" <?php if($select->StyleBloc == 'Vide') { echo 'selected="selected"'; } ?>><?php _e('Feuille vide personnalisable (style-empty.css)','WP-Planification'); ?></option>
                            <option value="Sobre" <?php if($select->StyleBloc == 'Sobre') { echo 'selected="selected"'; } ?>><?php _e('Style sobre','WP-Planification'); ?></option>
                            <option value="Black" <?php if($select->StyleBloc == 'Black') { echo 'selected="selected"'; } ?>><?php _e('Dominante en noir','WP-Planification'); ?></option>
                            <option value="Grey" <?php if($select->StyleBloc == 'Grey') { echo 'selected="selected"'; } ?>><?php _e('Dominante en gris','WP-Planification'); ?></option>
                            <option value="Blue" <?php if($select->StyleBloc == 'Blue') { echo 'selected="selected"'; } ?>><?php _e('Dominante en bleu','WP-Planification'); ?></option>
                            <option value="Red" <?php if($select->StyleBloc == 'Red') { echo 'selected="selected"'; } ?>><?php _e('Dominante en rouge','WP-Planification'); ?></option>
                            <option value="Green" <?php if($select->StyleBloc == 'Green') { echo 'selected="selected"'; } ?>><?php _e('Dominante en vert','WP-Planification'); ?></option>
                            <option value="BlackNB" <?php if($select->StyleBloc == 'BlackNB') { echo 'selected="selected"'; } ?>><?php _e('Dominante en noir sans bordures','WP-Planification'); ?></option>
                            <option value="GreyNB" <?php if($select->StyleBloc == 'GreyNB') { echo 'selected="selected"'; } ?>><?php _e('Dominante en gris sans bordures','WP-Planification'); ?></option>
                            <option value="BlueNB" <?php if($select->StyleBloc == 'BlueNB') { echo 'selected="selected"'; } ?>><?php _e('Dominante en bleu sans bordures','WP-Planification'); ?></option>
                            <option value="RedNB" <?php if($select->StyleBloc == 'RedNB') { echo 'selected="selected"'; } ?>><?php _e('Dominante en rouge sans bordures','WP-Planification'); ?></option>
                            <option value="GreenNB" <?php if($select->StyleBloc == 'GreenNB') { echo 'selected="selected"'; } ?>><?php _e('Dominante en vert sans bordures','WP-Planification'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="wp_planification_classtag"><strong><?php _e('Classe du bloc global','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTag; ?>" name="wp_planification_classtag" id="wp_planification_classtag" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_classtagbloc"><strong><?php _e('Classe du bloc "date + lien..."','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTagBloc; ?>" name="wp_planification_classtagbloc" id="wp_planification_classtagbloc" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_classtagdate"><strong><?php _e('Classe de la date','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTagDate; ?>" name="wp_planification_classtagdate" id="wp_planification_classtagdate" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_classtagurl"><strong><?php _e('Classe du lien (URL) ou du texte','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTagURL; ?>" name="wp_planification_classtagurl" id="wp_planification_classtagurl" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_classtagcontent"><strong><?php _e('Classe du bloc de contenu (extrait ou article)','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTagContent; ?>" name="wp_planification_classtagcontent" id="wp_planification_classtagcontent" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_classtagsuite"><strong><?php _e('Classe du bloc "Lire la suite..."','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTagSuite; ?>" name="wp_planification_classtagsuite" id="wp_planification_classtagsuite" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
                    <p>
                        <label for="wp_planification_classtagimage"><strong><?php _e('Classe de l\'image à la Une','WP-Planification'); ?></strong></label><br />
                        <input value="<?php echo $select->ClassTagImage; ?>" name="wp_planification_classtagimage" id="wp_planification_classtagimage" type="text" style="width:75%;border:1px solid #ccc;" />
                    </p>
            	</td>
        	</tr>
        </table>
        <p class="submit"><input type="submit" name="wp_planification_action" class="button-primary" value="<?php _e('Enregistrer' , 'WP-Planification'); ?>" /></p>
        </form>
<?php
	echo '</div>'; // Fin de la page d'admin
} // Fin de la fonction Callback
?>