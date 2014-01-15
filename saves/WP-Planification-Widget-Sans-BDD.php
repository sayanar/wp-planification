<?php
// 4 class pour le widget
class WP_widget_planification extends WP_widget {
	
    function WP_widget_planification() {
		$options = array(
                "classname" => "WP-Planification",
                "description" => "Afficher des publications et événements futures (en cas de planification d'articles ou de pages)"
                );
    	$this->WP_widget("WP-Planification", "WP-Planification", $options);
	}

    function widget($arguments, $instance) {
		extract($arguments);
        $titreInfo		= apply_filters('widget_title', $instance['titre']);
		$StyleBloc		= $instance['StyleBloc'];
		$FormatageDate	= $instance['FormatageDate'];
		$Separator		= $instance['Separator'];
		$TotalTag		= $instance['TotalTag'];
		$TotalTagBloc	= $instance['TotalTagBloc'];
		$TotalTagDate	= $instance['TotalTagDate'];
		$TitreError		= $instance['TitreError'];
		$PhraseError	= $instance['PhraseError'];
		$TargetColumn	= $instance['TargetColumn'];
		$OrderColumn	= $instance['OrderColumn'];
		$OrderBy		= $instance['OrderBy'];
		
		global $wpdb;
    	$table_prefix = $wpdb->prefix;
		
		// Début du widget
		echo $before_widget;
	
		/* ----------------------------------------------------------------------- */
		/* --------------------------- Code du widget ---------------------------- */
		/* ----------------------------------------------------------------------- */
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
			
			// Instanciation des variables
			$tableCible = $wpdb->posts; // Récupération de la table de base de donnée à parcourir (ici, "posts" pour celles des pages et articles)
			$titreBloc = $titreInfo; // Titre du bloc d'événements
			$titreBlocErreur = $TitreError; // Titre du bloc d'événements (si aucun événement n'est prévu)
			$phraseBlocErreur = $PhraseError; // Phrase du bloc d'événements (si aucun événement n'est prévu)
			$separateur = $Separator; // Séparateur
			$capsule = $TotalTag; // Balise encapsulant le widget
			$TagBloc = $TotalTagBloc; // Balise encapsulant le groupe 'Date + Lien'
			$TagDate = $TotalTagDate; // Balise encapsulant la date (<span> par défaut)
			$colonneCible = $TargetColumn; // Colonne de valeur à parcourir (ici, la date de publication "post_date")
			$ordreColonne = $OrderColumn; // Définit la colonne utilisée pour ordonner les résultats
			$ordreAffichage = $OrderBy; // Ordre ascendant des événements (DESC sinon)
			$formatageDate = $FormatageDate; // Formatage de la date
			
			// Requête de récupération des résultats dans la base de données (pour afficher les informations)
			$RequeteSQL = $wpdb->get_results("SELECT * FROM $tableCible WHERE $colonneCible > NOW() ORDER BY $ordreColonne $ordreAffichage");
			
			// Requête de comptage du nombre de résultats justes (pour afficher ou non le bloc d'événements) !
			$dateOK = $wpdb->get_var("SELECT COUNT(*) FROM $tableCible WHERE $colonneCible > NOW()");
			
			// Fonction d'ajout d'une feuille de style CSS (conditionné avec une cse à cocher)
			WP_planification_CSS($StyleBloc);

			// Si au moins un résultat est juste, alors on affiche le bloc d'événements à venir...
			if($dateOK > 0) {				
				echo "<".$capsule." class='WP-Planification'>";
					echo $before_title.$titreBloc.$after_title;
					
					$nb = 0; // Variable unique pour les ID (départ à 0)
					
					// On fait une boucle pour chaque résultat répondant à la requête $RequeteSQL
					// On crée une variable $dateFuture pour récupérer les champs de la base de donnée qui nous intéresse
					foreach ($RequeteSQL as $dateFuture) {
						$nb++; // Incrémentation automatique de l'ID à chaque tour de boucle
						
						// Instanciation des variables $dateFuture
						$dateInfo = mysql2date($formatageDate, $dateFuture->post_date); // Date de l'événement prévisionnel (au format "dimanche 30 juin 2013")
						$URLLien = $dateFuture->guid; // URL de l'article ou de la page de l'événément à venir
						$ancreLien = $dateFuture->post_title; // Récupération du titre de l'article ou de la page
						$attributTitle = $dateFuture->post_title; // Valeur de l'attribut title="" (ici, reprise du titre de l'article ou de la page)					

						// Arguments utilisés pour les blocs
						$argsDate = array(
							'class'=>'WP-Planification-Date',
							'id'=>'WP-Planification-Date-'.$nb
						);
						$argsBloc = array(
							'class'=>'WP-Planification-Bloc',
							'id'=>'WP-Planification-Bloc-'.$nb
						);
						$args = array(
							'href'=>$URLLien,
							'title'=>$attributTitle,
							'class'=>'WP-Planification-URL',
							'id'=>'WP-Planification-URL-'.$nb
						); // Récupération d'un tableau d'attributs HTML

						// Première étape du formatage (ici : <span>DATE</span> : <a href="URL" title="TITLE">ANCRE</a>)
						$phraseBlocformatage = encapsuleBloc($dateInfo,$TagDate,$argsDate).$separateur.encapsuleBloc($ancreLien,'a',$args);
						// Deuxième étape du formatage (ici, ajout du bloc précédent entre des balises <p>...</p>)
						$phraseBloc = encapsuleBloc($phraseBlocformatage, $TagBloc, $argsBloc);
						
						// Affichage de la phrase formatée finale !
						echo $phraseBloc;
					}
				echo "</".$capsule.">";
			} // Fin de la première étape (affichage du bloc si un résultat est juste pour $dateOK)
			else
			{ // Affichage (optionnel) d'un autre bloc si aucun résultat n'est retourné ($dateOK == 0 dans ce cas)				
				echo "<".$capsule." class='WP-Planification'>";
				echo $before_title.$titreBlocErreur.$after_title;
				echo encapsuleBloc($phraseBlocErreur, $TagBloc, $argsBloc);
				echo "</".$capsule.">";
			} // Fin de la condition de sauvegarde (si $dateOK == 0)
		
		/* ----------------------------------------------------------------------- */
		/* ---------------------- Fin du code du widget -------------------------- */
		/* ----------------------------------------------------------------------- */

		// Fin du widget
		echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
		$instance['titre']			= $new_instance['titre'];
		$instance['StyleBloc']		= esc_attr($new_instance['StyleBloc']);
		$instance['FormatageDate']	= esc_attr($new_instance['FormatageDate']);
		$instance['Separator']		= $new_instance['Separator'];
		$instance['TotalTag']		= esc_attr($new_instance['TotalTag']);
		$instance['TotalTagBloc']	= esc_attr($new_instance['TotalTagBloc']);
		$instance['TotalTagDate']	= esc_attr($new_instance['TotalTagDate']);
		$instance['TitreError']		= $new_instance['TitreError'];
		$instance['PhraseError']	= $new_instance['PhraseError'];
		$instance['TargetColumn']	= esc_attr($new_instance['TargetColumn']);
		$instance['OrderColumn']	= esc_attr($new_instance['OrderColumn']);
		$instance['OrderBy']		= esc_attr($new_instance['OrderBy']);
        return $instance;
    }
 
    function form($instance) {
		/* Avec récupération des réglages dans la base de données
		global $wpdb, $table_WP_Planification;
		$instance = $wpdb->get_row("SELECT * FROM $table_WP_Planification WHERE id=1", ARRAY_A);
		*/
		// Sans récupération de la base de données
	    $defaut = array(
			"titre" => __('Prochains événements', 'WP-Planification'),
			"StyleBloc" => __('1', 'WP-Planification'),
			"FormatageDate" => __('l j F Y', 'WP-Planification'),
			"Separator" => __(' : ', 'WP-Planification'),
			"TotalTag" => __('div', 'WP-Planification'),
			"TotalTagBloc" => __('p', 'WP-Planification'),
			"TotalTagDate" => __('span', 'WP-Planification'),
			"TitreError" => __('Aucun événement', 'WP-Planification'),
			"PhraseError" => __('Aucun événement pour le moment', 'WP-Planification'),
			"TargetColumn" => __('post_date', 'WP-Planification'),
			"OrderColumn" => __('post_date', 'WP-Planification'),
			"OrderBy" => __('ASC', 'WP-Planification')
		);
	    $instance = wp_parse_args($instance, $defaut);
?>
        <p>
			<label for="<?php echo $this->get_field_id('titre'); ?>"><strong><?php _e('Titre', 'WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['titre']; ?>" name="<?php echo $this->get_field_name('titre'); ?>" id="<?php echo $this->get_field_id('titre'); ?>" type="text" style="width:100%;" />
        </p>
        <p>
	        <input value="1" <?php checked('1',$instance['StyleBloc']); ?> name="<?php echo $this->get_field_name('StyleBloc'); ?>" id="<?php echo $this->get_field_id('StyleBloc'); ?>" type="checkbox" />&nbsp;<label for="<?php echo $this->get_field_id('StyleBloc'); ?>"><strong><?php _e('Style CSS défini ?','WP-Planification'); ?></strong></label>
        </p>
        <h2><?php _e('Options d\'affichage','WP-Planification'); ?></h2>
        <div>
			<label for="<?php echo $this->get_field_id('FormatageDate'); ?>"><strong><?php _e('Formatage de la date','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['FormatageDate']; ?>" name="<?php echo $this->get_field_name('FormatageDate'); ?>" id="<?php echo $this->get_field_id('FormatageDate'); ?>" type="text" style="width:100%;" />
            <p>
            	<em><?php _e('Voir documentation PHP sur les dates','WP-Planification'); ?></em><br/>
            	<em><?php _e('(ex : "l j F Y" pour "mardi 25 juin 2013")','WP-Planification'); ?></em>
            </p>
        </div>
        <div>
			<label for="<?php echo $this->get_field_id('Separator'); ?>"><strong><?php _e('Séparateur','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['Separator']; ?>" name="<?php echo $this->get_field_name('Separator'); ?>" id="<?php echo $this->get_field_id('Separator'); ?>" type="text" style="width:100%;" />
            <p><em><?php _e('Code HTML accepté','WP-Planification'); ?> (<?php echo esc_attr('<br/>...'); ?>)</p>
        </div>
        <div>
			<label for="<?php echo $this->get_field_id('TotalTag'); ?>"><strong><?php _e('Balises d\'encadrement du bloc','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['TotalTag']; ?>" name="<?php echo $this->get_field_name('TotalTag'); ?>" id="<?php echo $this->get_field_id('TotalTag'); ?>" type="text" style="width:100%;" />
            <p><em><?php _e('(div, section, aside...)','WP-Planification'); ?></em></p>
        </div>
        <div>
			<label for="<?php echo $this->get_field_id('TotalTagBloc'); ?>"><strong><?php _e('Balises du bloc "Date + Lien"','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['TotalTagBloc']; ?>" name="<?php echo $this->get_field_name('TotalTagBloc'); ?>" id="<?php echo $this->get_field_id('TotalTagBloc'); ?>" type="text" style="width:100%;" />
        </div>
        <div>
			<label for="<?php echo $this->get_field_id('TotalTagDate'); ?>"><strong><?php _e('Balises d\'encadrement de la date','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['TotalTagDate']; ?>" name="<?php echo $this->get_field_name('TotalTagDate'); ?>" id="<?php echo $this->get_field_id('TotalTagDate'); ?>" type="text" style="width:100%;" />
        </div>
        <h2><?php _e('Si aucun événément','WP-Planification'); ?></h2>
        <p>
			<label for="<?php echo $this->get_field_id('TitreError'); ?>"><strong><?php _e('Titre si aucun événement','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['TitreError']; ?>" name="<?php echo $this->get_field_name('TitreError'); ?>" id="<?php echo $this->get_field_id('TitreError'); ?>" type="text" style="width:100%;" />
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('PhraseError'); ?>"><strong><?php _e('Phrase si aucun événement','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['PhraseError']; ?>" name="<?php echo $this->get_field_name('PhraseError'); ?>" id="<?php echo $this->get_field_id('PhraseError'); ?>" type="text" style="width:100%;" />
        </p>
        <h2><?php _e('Options BDD','WP-Planification'); ?></h2>
        <p>
			<label for="<?php echo $this->get_field_id('TargetColumn'); ?>"><strong><?php _e('Colonne cible','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['TargetColumn']; ?>" name="<?php echo $this->get_field_name('TargetColumn'); ?>" id="<?php echo $this->get_field_id('TargetColumn'); ?>" type="text" style="width:100%;" />
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('OrderColumn'); ?>"><strong><?php _e('Colonne de classement','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['OrderColumn']; ?>" name="<?php echo $this->get_field_name('OrderColumn'); ?>" id="<?php echo $this->get_field_id('OrderColumn'); ?>" type="text" style="width:100%;" />
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('OrderBy'); ?>"><strong><?php _e('Ordre d\affichage','WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['OrderBy']; ?>" name="<?php echo $this->get_field_name('OrderBy'); ?>" id="<?php echo $this->get_field_id('OrderBy'); ?>" type="text" style="width:100%;" />
        </p>
<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("WP_widget_planification");'));
?>