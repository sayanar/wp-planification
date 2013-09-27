<?php
// Création du shortcode [planification]
function WP_Planification_Shortcode() {
	
	// Avec récupération des réglages dans la base de données
	global $wpdb, $table_WP_Planification;
	$donnees = $wpdb->get_row("SELECT * FROM $table_WP_Planification WHERE id=1", ARRAY_A);
	
	$titreInfo		= $donnees['titre'];
	$StyleBloc		= $donnees['StyleBloc'];
	$FormatageDate	= $donnees['FormatageDate'];
	$DisplayOrder	= $donnees['DisplayOrder'];
	$Separator		= $donnees['Separateur'];
	$TitreTag		= $donnees['TitreTag'];
	$TotalTag		= $donnees['TotalTag'];
	$TotalTagBloc	= $donnees['TotalTagBloc'];
	$TotalTagDate	= $donnees['TotalTagDate'];
	$TotalTagLink	= $donnees['TotalTagLink'];
	$ClassTag		= $donnees['ClassTag'];
	$ClassTagBloc	= $donnees['ClassTagBloc'];
	$ClassTagDate	= $donnees['ClassTagDate'];
	$ClassTagURL	= $donnees['ClassTagURL'];
	$TitreError		= $donnees['TitreError'];
	$PhraseError	= $donnees['PhraseError'];
	$Limit			= $donnees['LimitColumn'];
	$choixColonne	= trim($donnees['ChoiceColumn']);
	$TargetColumn	= trim($donnees['TargetColumn']);
	$OrderColumn	= trim($donnees['OrderColumn']);
	$OrderBy		= trim($donnees['OrderBy']);

	/* ----------------------------------------------------------------------- */
	/* ------------------------- Code du shortcode --------------------------- */
	/* ----------------------------------------------------------------------- */		
		// Instanciation des variables
		$tableCible = $wpdb->posts; // Récupération de la table de base de donnée à parcourir (ici, "posts" pour celles des pages et articles)
		$titreBloc = $titreInfo; // Titre du bloc d'événements
		$titreBlocErreur = $TitreError; // Titre du bloc d'événements (si aucun événement n'est prévu)
		$phraseBlocErreur = $PhraseError; // Phrase du bloc d'événements (si aucun événement n'est prévu)
		$orderAffichage = $DisplayOrder; // Order d'affichage
		$separateur = $Separator; // Séparateur
		$TitreBlocTag = $TitreTag; // Balise encapsulant le titre
		$capsule = $TotalTag; // Balise encapsulant le widget
		$TagBloc = $TotalTagBloc; // Balise encapsulant le groupe 'Date + Lien'
		$TagDate = $TotalTagDate; // Balise encapsulant la date (<span> par défaut)
		$TagLink = $TotalTagLink; // Balise encapsulant le texte (<a> par défaut)
		$classTag = $ClassTag; // Classe CSS du bloc global
		$classBloc = $ClassTagBloc; // Classe CSS du bloc 'date + lien'
		$classDate = $ClassTagDate; // Classe CSS de la date
		$classURL = $ClassTagURL; // Classe CSS du lien (URL)
		$colonneCible = $TargetColumn; // Colonne de valeur à parcourir (ici, la date de publication "post_date")
		$ordreColonne = $OrderColumn; // Définit la colonne utilisée pour ordonner les résultats
		$ordreAffichage = $OrderBy; // Ordre ascendant des événements (DESC sinon)
		$formatageDate = $FormatageDate; // Formatage de la date
				
		// Limitation du nombre d'affichage
		if(is_numeric($Limit) && $Limit !== "0") {
			$limitation = 'LIMIT '.$Limit;
		} else {
			$limitation = '';
		}
		// Choix du type de contenu à afficher
		if(!empty($choixColonne)) {
			$ChoiceColumn = "AND post_type = '".$choixColonne."'";
		} else {
			$ChoiceColumn = "";
		}
	
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
			$output = "<".$capsule." class='".$classTag."' id='".$classTag."'>";
				if(!empty($titreBloc)) {
					$argsBlocTag = array('class'=>$classBloc, 'id'=>$classBloc);
					$TitleBlock = encapsuleBloc($titreBloc, 'span');
					$output .= encapsuleBloc($TitleBlock, $TitreBlocTag, $argsBlocTag);
				}
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
					$argsDate = array('class'=>$classDate, 'id'=>$classDate."-".$nb);
					$argsBloc = array('class'=>$classBloc, 'id'=>$classBloc."-".$nb);
					if($TagLink == 'a') {
						$args = array('href'=>$URLLien, 'title'=>$attributTitle, 'class'=>$classURL, 'id'=>$classURL."-".$nb);
					} else {
						$args = array('class'=>$classURL, 'id'=>$classURL."-".$nb);
					}					

					// Première étape du formatage (ici : <span>DATE</span> : <a href="URL" title="TITLE">ANCRE</a>)
					if($orderAffichage == __('Date + Lien','WP-Planification')) {
						$phraseBlocformatage = encapsuleBloc($dateInfo,$TagDate,$argsDate).$separateur.encapsuleBloc($ancreLien,$TagLink,$args);
					} else
					if($orderAffichage == __('Lien + Date','WP-Planification')) {
						$phraseBlocformatage = encapsuleBloc($ancreLien,$TagLink,$args).$separateur.encapsuleBloc($dateInfo,$TagDate,$argsDate);
					} else
					if($orderAffichage == __('Lien seul','WP-Planification')) {
						$phraseBlocformatage = encapsuleBloc($ancreLien,$TagLink,$args);
					} else {
						// Affichage par défaut
						$phraseBlocformatage = encapsuleBloc($dateInfo,$TagDate,$argsDate).$separateur.encapsuleBloc($ancreLien,$TagLink,$args);
					}
					// Deuxième étape du formatage (ici, ajout du bloc précédent entre des balises <p>...</p>)
					$phraseBloc = encapsuleBloc($phraseBlocformatage, $TagBloc, $argsBloc);
					
					// Affichage de la phrase formatée finale !
					$output .= $phraseBloc;
				}
			$output .= "</".$capsule.">";
			return $output;
			} // Fin de vérification
		} // Fin de la première étape (affichage du bloc si un résultat est juste pour $dateOK)
		else
		{ // Affichage (optionnel) d'un autre bloc si aucun résultat n'est retourné ($dateOK == 0 dans ce cas)				
			if( (!empty($titreBlocErreur) && empty($phraseBlocErreur) && !empty($limitation)) || (empty($titreBlocErreur) && !empty($phraseBlocErreur) && !empty($limitation)) || (!empty($titreBlocErreur) && !empty($phraseBlocErreur) && !empty($limitation))) {
				$outputError = "<".$capsule." class='".$classTag."' id='".$classTag."'>";
				
				if(!empty($titreBlocErreur)) {
					$argsBlocTag = array('class'=>$classBloc, 'id'=>$classBloc);
					$TitleBlockErreur = encapsuleBloc($titreBlocErreur, 'span');
					$outputError .= encapsuleBloc($TitleBlockErreur, $TitreBlocTag, $argsBlocTag);
				}
				if(!empty($phraseBlocErreur)) {
					$argsBloc = array('class'=>$classBloc, 'id'=>$classBloc."-".$nb);
					$outputError .= encapsuleBloc($phraseBlocErreur, $TagBloc, $argsBloc);
				}
				$outputError .= "</".$capsule.">";
				return $outputError;
			}
		} // Fin de la condition de sauvegarde (si $dateOK == 0)
}
add_shortcode("planification","WP_Planification_Shortcode");
?>