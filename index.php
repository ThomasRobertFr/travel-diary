<?php
/**
 * Copyright (C) 2013 Thomas Robert (http://thomas-robert.fr).
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


include('includes/constantes.php');
include('includes/fonctions.php');

$vacances = get_vacances();

if (!empty($_GET['id'])) {
	$id =  $_GET['id'];
} 
else {
	$last = end($vacances);
	$id = $last['id'];
}

$carnet = get_vacance($id);

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Carnet de vacances</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

		<link href="bootstrap/css/helvetica.css" rel="stylesheet" media="screen">
		<link href="bootstrap/css/sidebar.css" rel="stylesheet" media="screen">
		<link href="bootstrap/css/application.css" rel="stylesheet" media="screen">

		<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" >

		<link href="bootstrap/css/jquery.justifiedgallery.min.css" rel="stylesheet" media="screen">
		<link href="bootstrap/fancybox/jquery.fancybox.css?v=2.1.5" rel="stylesheet" type="text/css" media="screen" />
		<link href="bootstrap/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7"rel="stylesheet" type="text/css" media="screen" />
	</head>
	<body>
	<a class="sr-only" href="#content">Skip navigation</a>

	<!-- NAVBAR -->
	<div class="navbar navbar-inverse navbar-fixed-top" role="banner">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<span class="navbar-brand">Travel diary</span>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				<ul class="nav navbar-nav">
					<?php

					$current_year = 0;

					foreach($vacances as $date => $vacance) {
						$year = (int) date('Y', $date);
						if ($year != $current_year) {

							if ($current_year != 0) {
								echo '</ul></li>';
							}
							echo '<li class="'. ($year == date('Y', $carnet['date_begin']) ? 'active ' : '' ) .'dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$year.' <b class="caret"></b></a>
							<ul class="dropdown-menu">';

							$current_year = $year;
						}
						
						echo '<li class="'. ($vacance['id'] == $carnet['id'] ? 'active ' : '' ) .'"><a href="?id='.$vacance['id'].'">'.$vacance['title'].'</a></li>';
					}

					?>
					</ul>
				</ul>
			</nav>
		</div>
	</div>
	<header class="bs-header" id="header">
		<div class="container">
			<h1><?php echo $carnet['title']; ?></h1>
			<p>From <?php echo date('F j, Y', $carnet['date_begin']); ?> to <?php echo date('F j, Y', $carnet['date_end']); if (!empty($carnet['with'])) { ?> &bullet; With <?php echo $carnet['with']; } ?></p>
			<p><small><?php echo $carnet['location']; ?></small></p>
		</div>
	</header>

	<?php if (!empty($carnet['cover'])) { ?>
	<style>
	#header:after { background: url(<?php echo $carnet['cover']; ?>) no-repeat <?php echo $carnet['cover_align']; ?>; background-size: cover; }
	</style>
	<?php } ?>

	<div class="container bs-docs-container">
		<div class="row">
			<div class="col-md-2">
				<div class="bs-sidebar hidden-print" role="complementary">
					<ul class="nav bs-sidenav">
						<?php

						preg_match_all('#<block title="(.+)">#isU', $carnet['texte'], $matches);

						foreach($matches[1] as $i => $match) {
							echo '<li><a href="#carnet-anchor-'.$i.'">'.$match.'</a></li>';
						}

						?>
					</ul>
				</div>
			</div>
			<div class="col-md-10" role="main" id="main">
				
				<?php

				$i = 0;
				$images_js = array();

				preg_match_all('#<block title="(.+)">.+<text>(.+)</text>.+<images>(.+)</images>.+</block>#misU', $carnet['texte'], $matches);

				for ($i = 0; $i < count($matches[0]); $i++) {

					$images = array();
					preg_match_all('#<img +src="(.+)" +title="(.+)"#isU', $matches[3][$i], $images);



					echo '<div class="clearfix"></div>
					<div id="carnet-anchor-'.$i.'" class="carnet-day">
						<h2>'.$matches[1][$i].'</h2>
						<table style="width:100%">
							<tr>';

							// texte
							$matches[2][$i] = trim($matches[2][$i]);

							if (!empty($matches[2][$i])) {
								$class = count($images[0]) ? 'col-md-6' : 'col-md-12';
								echo '
								<td class="carnet-texte '. $class .'">
									'.$matches[2][$i].'
								</td>';
							}

							// images
							if (count($images[0])) {
								$class = (!empty($matches[2][$i])) ? 'col-md-6' : 'col-md-12';
								echo '<td class="preview '. $class .'"></td>';
							}
							echo '
						</table>
						
						<div class="clearfix"></div>
						<div class="images">
							';

							$images_js[$i] = array();
							for ($j = 0; $j < count($images[0]); $j++) {

								$images_js[$i][] = array(
									'href' => DATA_DIR.'/'.$id.'/images/'.$images[1][$j],
									'title' => $images[2][$j]
									);

								echo '
								<a href="'.DATA_DIR.'/'.$id.'/images/'.$images[1][$j].'" class="thumbnail" rel="gallery-'.$i.'-'.$j.'" data-title="'.$images[2][$j].'" title="'.$images[2][$j].'">
									<img src="'.DATA_DIR.'/'.$id.'/images/thumb/'.$images[1][$j].'" alt="'.$images[2][$j].'" />
								</a>';
							}

						echo '
						</div>
					</div>';
				}

				?>
			</div>

		<script src="bootstrap/js/jquery.min.js"></script>
		<script src="bootstrap/js/jquery.mousewheel-3.0.6.pack.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootstrap/js/jquery.mousewheel-3.0.6.pack.js"></script>
		<script src="bootstrap/js/jquery.justifiedgallery.min.js"></script>

		<script src="bootstrap/fancybox/jquery.fancybox.js?v=2.1.5"></script>
		<script src="bootstrap/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
		<script>
			var images_js = <?php echo json_encode($images_js); ?>
		</script>

		<script src="bootstrap/js/script.js"></script>
		<script src="bootstrap/js/sidebar.js"></script>
	</body>
</html>
