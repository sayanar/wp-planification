<?php
// 4 class pour le widget
class WP_widget_planification extends WP_widget {
	
    function WP_widget_planification() {
		$options = array(
                "classname" => __('WP-Planification','WP-Planification'),
                "description" => __('Afficher des publications et événements futurs (en cas de planification d\'articles ou de pages)','WP-Planification')
        );
    	$this->WP_widget("WP-Planification", "WP-Planification", $options);
	}

    function widget($arguments, $instance) {
		extract($arguments);
        // Avec récupération des réglages dans la base de données
		global $wpdb, $table_WP_Planification;
		$donnees = $wpdb->get_row("SELECT * FROM $table_WP_Planification WHERE id=1", ARRAY_A);
		
		$titreBloc			= apply_filters('widget_title', $instance['titre']); // titre récupéré via le widget
		$nb					= apply_filters('widget_nb', $instance['nb']); // nombre récupéré via le widget
		$BlocOK				= $donnees['activ'];
		$suite				= $donnees['suite']; // Active ou non le "lire la suite..."
		$TagSuite			= $donnees['TotalTagSuite']; // Balise du bloc "Lire la suite..."
		$TextSuite			= $donnees['TextSuite']; // Texte du "Lire la suite..."
		$classSuite			= $donnees['ClassTagSuite']; // class du bloc "Lire la suite..."
		$StyleBloc			= $donnees['StyleBloc'];
		$formatageDate		= $donnees['FormatageDate'];
		$orderDL			= $donnees['DisplayDL']; // Ordre d'affichage (lien et date)
		$orderContent		= $donnees['DisplayContent']; // Ordre d'affichage (Article ou Extrait)
		$orderImage			= $donnees['DisplayImage']; // affichage ou non (image à la Une)
		$orderAffichage		= $donnees['DisplayOrder']; // Ordre d'affichage
		$separateur			= $donnees['Separateur'];
		$separateur2		= $donnees['SeparateurContenu'];
		$separateur3		= $donnees['SeparateurImage'];
		$capsule			= $donnees['TotalTag'];
		$TagBloc			= $donnees['TotalTagBloc']; // Balise encapsulant l'ensemble du bloc
		$TagDate			= $donnees['TotalTagDate']; // Balise encapsulant la date
		$TagLink			= $donnees['TotalTagLink']; // Balise encapsulant le lien
		$TagContent			= $donnees['TotalTagContent']; // Balise encapsulant le contenu
		$TagImage			= $donnees['TotalTagImage']; // Balise encapsulant l'image à la Une
		$classTag			= $donnees['ClassTag']; // Classe CSS du bloc global
		$classBloc			= $donnees['ClassTagBloc']; // Classe CSS du bloc 'date + lien'
		$classContent		= $donnees['ClassTagContent']; // Classe CSS du bloc de contenu
		$classImage			= $donnees['ClassTagImage']; // Classe CSS de l'image
		$classDate			= $donnees['ClassTagDate']; // Classe CSS de la date
		$classURL			= $donnees['ClassTagURL']; // Classe CSS du lien (URL)
		$titreBlocErreur	= $donnees['TitreError']; // Titre du bloc d'événements (si aucun événement n'est prévu)
		$phraseBlocErreur	= $donnees['PhraseError']; // Phrase du bloc d'événements (si aucun événement n'est prévu)
		$colonneCible		= trim($donnees['TargetColumn']); // Colonne de valeur à parcourir (ici, la date de publication "post_date")
		$ordreColonne		= trim($donnees['OrderColumn']); // Définit la colonne utilisée pour ordonner les résultats
		$ordreAffichage		= trim($donnees['OrderBy']); // Ordre ascendant des événements (DESC sinon)
		$Limit				= $donnees['LimitColumn'];
		$choixColonne		= trim($donnees['ChoiceColumn']);
		$clear				= $donnees['clear']; // Ajout du clear ou non
		
		// Début du widget
		echo $before_widget;
	
		/* ----------------------------------------------------------------------- */
		/* --------------------------- Code du widget ---------------------------- */
		/* ----------------------------------------------------------------------- */			
			// Instanciation des variables
			$tableCible = $wpdb->posts; // Récupération de la table de base de donnée à parcourir (ici, "posts" pour celles des pages et articles)
			$tableMeta = $wpdb->postmeta; // Récupération des métas pour l'image à la Une
			
			// Limitation du nombre d'affichage
			if(isset($nb) && !empty($nb) && $nb !== "0") {
				$limitation = "LIMIT ".$nb;
			} else {
				if(is_numeric($Limit) && $Limit !== "0") {
					$limitation = 'LIMIT '.$Limit;
				} else {
					$limitation = '';
				}
			}
			// Choix du type de contenu à afficher
			if(!empty($choixColonne)) {
				$ChoiceColumn = "AND post_type = '".$choixColonne."'";
			} else {
				$ChoiceColumn = "";
			}
			
		// Si on active le bloc (sécurité...)
		if($BlocOK == true) {
			
			// Requête de récupération des résultats dans la base de données (pour afficher les informations)
			$RequeteSQL = $wpdb->get_results("SELECT * FROM $tableCible WHERE $colonneCible > NOW() AND post_status = 'future' $ChoiceColumn ORDER BY $ordreColonne $ordreAffichage $limitation");
			
			// Requête de comptage du nombre de résultats justes (pour afficher ou non le bloc d'événements) !
			$dateOK = $wpdb->get_var("SELECT COUNT(*) FROM $tableCible WHERE $colonneCible > NOW() AND post_status = 'future' $ChoiceColumn");

			// Fonction d'ajout d'une feuille de style CSS (conditionné avec une cse à cocher)
			WP_planification_CSS($StyleBloc);

			// Si au moins un résultat est juste, alors on affiche le bloc d'événements à venir...
			if($dateOK > 0 && !empty($limitation)) {
				// On vérifie d'abord qu'un titre est écrit et que la limitation n'est pas vide ou à zéro...
				if(!empty($limitation)) {
	
					echo "<".$capsule." class='".$classTag."' id='".$classTag."'>";
					// Affichage du titre s'il n'est pas vide
					if(!empty($titreBloc)) {
						echo $before_title.'<span>'.$titreBloc.'</span>'.$after_title;
					}
					$nb = 0; // Variable unique pour les ID (départ à 0)
					
					// On fait une boucle pour chaque résultat répondant à la requête $RequeteSQL
					// On crée une variable $dateFuture pour récupérer les champs de la base de donnée qui nous intéresse
					foreach ($RequeteSQL as $dateFuture) {
						$nb++; // Incrémentation automatique de l'ID à chaque tour de boucle
						/*
						// Requête de récupération des résultats dans la base de données (pour afficher les informations)						
						$ImageOK = $wpdb->get_results("SELECT * FROM $tableCible AS p INNER JOIN $tableMeta AS m1 ON (m1.post_id = '".$dateFuture->ID."' AND m1.meta_value = p.ID AND m1.meta_key = '_thumbnail_id' AND p.post_type = 'attachment')");
						foreach($ImageOK as $img) {
							$imageThumb = '<img src="'.$img->guid.'" alt="'.$img->post_title.'" />'; // Image à la Une
						} */
						$imageThumb = get_the_post_thumbnail($dateFuture->ID,'thumbnail');

						// Instanciation des variables $dateFuture
						$dateInfo = mysql2date($formatageDate, $dateFuture->post_date); // Date de l'événement prévisionnel (au format "dimanche 30 juin 2013")
						$URLLien = get_permalink($dateFuture->ID); // URL de l'article ou de la page de l'événément à venir
						$content = $dateFuture->post_content; // Contenu de l'article
						$excerpt = $dateFuture->post_excerpt; // Contenu de l'extrait
						$ancreLien = $dateFuture->post_title; // Récupération du titre de l'article ou de la page
						$attributTitle = $dateFuture->post_title; // Valeur de l'attribut title="" (ici, reprise du titre de l'article ou de la page)

						// Arguments utilisés pour les blocs
						$argsDate = array('class'=>$classDate, 'id'=>$classDate."-".$nb);
						$argsBloc = array('class'=>$classBloc, 'id'=>$classBloc."-".$nb);
						$argsContent = array('class'=>$classContent, 'id'=>$classContent."-".$nb);
						$argsSuite = array('href'=>$URLLien,'title'=>$attributTitle);
						$argsSuitea = array('class'=>$classSuite,'id'=>$classSuite."-".$nb);
						$argsImage = array('class'=>$classImage, 'id'=>$classImage."-".$nb);
						if($TagLink == 'a') {
							$args = array('href'=>$URLLien, 'title'=>$attributTitle, 'class'=>$classURL, 'id'=>$classURL."-".$nb);
						} else {
							$args = array('class'=>$classURL, 'id'=>$classURL."-".$nb);
						}
						
						// Première étape du formatage (ici : <span>DATE</span> : <a href="URL" title="TITLE">ANCRE</a>)
						if($orderDL == 'Date + Lien') {
							$DL = encapsuleBloc($dateInfo,$TagDate,$argsDate).$separateur.encapsuleBloc($ancreLien,$TagLink,$args);
							$DL = encapsuleBloc($DL, $TagBloc, $argsBloc);	
						} else
						if($orderDL == 'Lien + Date') {
							$DL = encapsuleBloc($ancreLien,$TagLink,$args).$separateur.encapsuleBloc($dateInfo,$TagDate,$argsDate);
							$DL = encapsuleBloc($DL, $TagBloc, $argsBloc);	
						} else
						if($orderDL == 'Lien seul') {
							$DL = encapsuleBloc($ancreLien,$TagLink,$args);
							$DL = encapsuleBloc($DL, $TagBloc, $argsBloc);	
						} else
						if($orderDL == 'Date seule') {
							$DL = encapsuleBloc($dateInfo,$TagDate,$argsDate);
							$DL = encapsuleBloc($DL, $TagBloc, $argsBloc);	
						} else {
							$DL = "";
						}

						// Deuxième étape du formatage (contenu ou extrait)
						if($orderContent == 'Extrait') {
							$C = encapsuleBloc($excerpt,$TagContent,$argsContent);
						} else
						if($orderContent == 'Article') {
							$C = encapsuleBloc($content,$TagContent,$argsContent);
						} else {
							$C = "";
						}
						
						// Troisième étape du formatage (image à la Une ou non)
						if($orderImage == 'Image') {
							$I = encapsuleBloc($imageThumb,$TagImage,$argsImage);
						} else {
							$I = "";
						}
						
						// Ordre d'affichage des éléments (N.B. : DL = Date + Lien)
							// Si DL actif seul
							if($orderAffichage == 'DL') {
								$phraseBloc = $DL;
							}
							// Si Contenu actif seul
							else if($orderAffichage == 'C') {
								$phraseBloc = $C;
							}
							// Si Image active seule
							else if($orderAffichage == 'I') {
								$phraseBloc = $I;
							}
							// Si DL + Contenu sont actifs (sans image)
							else if($orderAffichage == 'DL + C') {
								$phraseBloc = $DL.$separateur2.$C;
							}
							else if($orderAffichage == 'C + DL') {
								$phraseBloc = $C.$separateur2.$DL;
							}
							// Si DL + Image sont actifs (sans contenu)
							else if($orderAffichage == 'DL + I') {
								$phraseBloc = $DL.$separateur3.$I;
							}
							else if($orderAffichage == 'I + DL') {
								$phraseBloc = $I.$separateur3.$DL;
							}
							// Si Contenu + Image sont actifs (sans DL)
							else if($orderAffichage == 'C + I') {
								$phraseBloc = $C.$separateur3.$I;
							}
							else if($orderAffichage == 'I + C') {
								$phraseBloc = $I.$separateur3.$C;
							}
							// Si tout est activé
							else if($orderAffichage == 'DL + C + I') {
								$phraseBloc = $DL.$separateur2.$C.$separateur3.$I;
							}
							else if($orderAffichage == 'DL + I + C') {
								$phraseBloc = $DL.$separateur3.$I.$separateur2.$C;
							}
							else if($orderAffichage == 'C + DL + I') {
								$phraseBloc = $C.$separateur2.$DL.$separateur2.$I;
							}
							else if($orderAffichage == 'C + I + DL') {
								$phraseBloc = $C.$separateur2.$I.$separateur2.$DL;
							}
							else if($orderAffichage == 'I + C + DL') {
								$phraseBloc = $I.$separateur3.$C.$separateur2.$DL;
							}
							else if($orderAffichage == 'I + DL + C') {
								$phraseBloc = $I.$separateur3.$DL.$separateur2.$C;
							} else {
								$phraseBloc = "";
							}

						// Site le "Lire la suite..." est activé
						if($suite == true) {
							$ReadMore = encapsuleBloc($TextSuite,'a',$argsSuite);
							$phraseBloc .= encapsuleBloc($ReadMore,$TagSuite,$argsSuitea);
						}
						
						// Affichage de la phrase formatée finale !
						echo $phraseBloc;
						
						// Affichage du Clear si nécessaire
						if($clear == true) {
							echo '<div class="clearBlock"></div>';
						}
					}
				echo "</".$capsule.">";
				} // Fin de la vérification
			} // Fin de la première étape (affichage du bloc si un résultat est juste pour $dateOK)
			else
			{ // Affichage (optionnel) d'un autre bloc si aucun résultat n'est retourné ($dateOK == 0 dans ce cas)				
				if( (!empty($titreBlocErreur) && empty($phraseBlocErreur) && !empty($limitation)) || (empty($titreBlocErreur) && !empty($phraseBlocErreur) && !empty($limitation)) || (!empty($titreBlocErreur) && !empty($phraseBlocErreur) && !empty($limitation))) {			
					echo "<".$capsule." class='".$classTag."' id='".$classTag."'>";
					// Affichage du titre s'il n'est pas vide
					if(!empty($titreBlocErreur)) {
						echo $before_title.'<span>'.$titreBlocErreur.'</span>'.$after_title;
					}
					if(!empty($phraseBlocErreur)) {
						$argsBloc = array('class'=>$classBloc, 'id'=>$classBloc."-".$nb);
						echo encapsuleBloc($phraseBlocErreur, $TagBloc, $argsBloc);
					}
					echo "</".$capsule.">";
				}
			} // Fin de la condition de sauvegarde (si $dateOK == 0)
		
		} // Fin du if($blocOK == true)
		/* ----------------------------------------------------------------------- */
		/* ---------------------- Fin du code du widget -------------------------- */
		/* ----------------------------------------------------------------------- */

		// Fin du widget
		echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
		$instance['activ']			= $new_instance['activ'];
		$instance['titre']			= $new_instance['titre'];
		$instance['nb']				= $new_instance['nb'];
		$instance['suite']			= $new_instance['suite'];
		$instance['TotalTagSuite']	= $new_instance['TotalTagSuite'];
		$instance['TextSuite']		= $new_instance['TextSuite'];
		$instance['ClassTagSuite']	= $new_instance['ClassTagSuite'];
		$instance['StyleBloc']		= esc_attr($new_instance['StyleBloc']);
		$instance['FormatageDate']	= esc_attr($new_instance['FormatageDate']);
		$instance['DisplayDL']		= esc_attr($new_instance['DisplayDL']);
		$instance['DisplayContent']	= esc_attr($new_instance['DisplayContent']);
		$instance['DisplayImage']	= esc_attr($new_instance['DisplayImage']);
		$instance['DisplayOrder']	= esc_attr($new_instance['DisplayOrder']);
		$instance['Separateur']		= $new_instance['Separateur'];
		$instance['SeparateurContenu']= $new_instance['SeparateurContenu'];
		$instance['SeparateurImage']= $new_instance['SeparateurImage'];
		$instance['TotalTag']		= esc_attr($new_instance['TotalTag']);
		$instance['TotalTagBloc']	= esc_attr($new_instance['TotalTagBloc']);
		$instance['TotalTagDate']	= esc_attr($new_instance['TotalTagDate']);
		$instance['TotalTagLink']	= esc_attr($new_instance['TotalTagLink']);
		$instance['TotalTagContent']= esc_attr($new_instance['TotalTagContent']);
		$instance['TotalTagImage']	= esc_attr($new_instance['TotalTagImage']);
		$instance['ClassTag']		= esc_attr($new_instance['ClassTag']);
		$instance['ClassTagBloc']	= esc_attr($new_instance['ClassTagBloc']);
		$instance['ClassTagDate']	= esc_attr($new_instance['ClassTagDate']);
		$instance['ClassTagURL']	= esc_attr($new_instance['ClassTagURL']);
		$instance['ClassTagContent']= esc_attr($new_instance['ClassTagContent']);
		$instance['ClassTagImage']	= esc_attr($new_instance['ClassTagImage']);
		$instance['TitreError']		= $new_instance['TitreError'];
		$instance['PhraseError']	= $new_instance['PhraseError'];
		$instance['LimitColumn']	= esc_attr($new_instance['LimitColumn']);
		$instance['ChoiceColumn']	= esc_attr($new_instance['ChoiceColumn']);
		$instance['TargetColumn']	= esc_attr($new_instance['TargetColumn']);
		$instance['OrderColumn']	= esc_attr($new_instance['OrderColumn']);
		$instance['OrderBy']		= esc_attr($new_instance['OrderBy']);
		$instance['clear']			= esc_attr($new_instance['clear']);
        return $instance;
    }
 
    function form($instance) {
		$defaut = array(
			"titre" => __('Prochains événements', 'WP-Planification'),
			"nb" => $Limit
		);
	    $instance = wp_parse_args($instance, $defaut);
?>
        <p><em><?php _e('Consultez la page de réglages pour tout paramétrer en détail...', 'WP-Planification'); ?></em></p>
        <p>
			<label for="<?php echo $this->get_field_id('titre'); ?>"><strong><?php _e('Titre', 'WP-Planification'); ?></strong></label><br />
	        <input value="<?php echo $instance['titre']; ?>" name="<?php echo $this->get_field_name('titre'); ?>" id="<?php echo $this->get_field_id('titre'); ?>" type="text" style="width:100%;" /><br/><br/>
            <label for="<?php echo $this->get_field_id('nb'); ?>"><strong><?php _e('Nombre de posts (optionnel)', 'WP-Planification'); ?></strong></label><br />
            <input value="<?php echo $instance['nb']; ?>" name="<?php echo $this->get_field_name('nb'); ?>" id="<?php echo $this->get_field_id('nb'); ?>" type="text" style="width:100%;" />
        </p>
<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("WP_widget_planification");'));
?>