<?php

function get_timestamp_from_txt($txt, $format = 'd-m-Y') {
	
	if ($date = DateTime::createFromFormat('!d-m-Y', $txt)) {
		return $date->getTimestamp();
	}

	return false;
}

function get_vacances() {
	$vacances = array();

	$dirs = glob(DATA_DIR.'/*');

	// pour chaque dossier
	foreach($dirs as $dir) {
		$id = preg_replace('#^'.DATA_DIR.'/#isU', '', $dir);

		if ($data = get_vacance($id, false)) {
			// sauvegarde des données
			$vacances[$data['date_begin']] = $data;
		}
	}

	ksort($vacances);

	return $vacances;
}

function get_vacance($id, $load_texte = true) {
	
	$dir = DATA_DIR.'/'.$id;
	
	if (is_file($dir.'/text.html') && is_file($dir.'/infos.json') && $data = json_decode(file_get_contents($dir.'/infos.json'), true))
	{
		// si on a des infos valides
		if (isset($data['title']) && isset($data['date_begin']) && isset($data['date_end']) && isset($data['with']) && isset($data['location'])) {
			
			// données
			$data['id'] = $id;
			$data['date_begin'] = get_timestamp_from_txt($data['date_begin'], 'd-m-Y');
			$data['date_end']   = get_timestamp_from_txt($data['date_end'], 'd-m-Y');

			// image de couverture pas définie, recherche auto sinon rien (défaut)
			if (!isset($data['cover'])) {
				$data['cover'] = is_file($dir.'/cover.jpg') ? $dir.'/cover.jpg' : '';
			}

			// alignement cover
			if (!isset($data['cover_align'])) {
				$data['cover_align'] = DEFAULT_COVER_ALIGN;
			}
			
			// texte
			if ($load_texte) {
				$data['texte'] = file_get_contents($dir.'/text.html');

				// espaces insécables auto
				$data['texte'] = preg_replace('# +([!:;»\?])#U', '&nbsp;\1', $data['texte']);
				$data['texte'] = preg_replace('#(«]) +#U', '\1&nbsp;', $data['texte']);
			}
		}
		return $data;
	}

	return false;
}
