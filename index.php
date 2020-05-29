<?php
session_start();
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$back = substr($url, 0, strrpos( $url, '&'));  
$back = '<a style="margin-left: 10.75px;margin-bottom: 20px;" href="#" onclick="window.history.back()" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons left">arrow_back</i>Terug</a>';
$bestel = '<a style="margin-right: 10.75px;margin-bottom: 20px; float: right;" href="' . $url . '&order=" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons right">arrow_forward</i>Plaats order</a>';
$winkelwagen = '<a style="margin-right: 10.75px;margin-bottom: 12px; float: right;" href="' . $url . '&cart=" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons right">arrow_forward</i>Naar winkelwagen</a>';
include('functions.php');

if (isset($_POST['product'])) {
	addToCart($_POST);
}
if (isset($_GET['remove'])) {
	removeFromCart($_GET['remove']);
}

if (isset($_GET['order'])) {
	completeOrder();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>GGD Online Bestellen</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<nav style="margin-bottom: 30px;">
		<div class="nav-wrapper black">
			<div class="container">
				<a href="/ggd/" class="brand-logo">Teewes</a>
				<?php if (isset($_GET['pass']) && $_GET['pass'] == 'Gebruiker127!') : ?>
				<ul class="right">
					<li><a href="/ggd/orders.php?pass=<?=$_GET['pass']?>"><i class="material-icons left">history</i>Orders</a></li>
					<li><a href="/ggd/?pass=<?=$_GET['pass']?>&cart"><i class="material-icons left">shopping_cart</i>Winkelwagen</a></li>
				</ul>
				<?php endif; ?>
			</div>
		</div>
	</nav>
	<div class="container">
		<?php if (isset($_GET['cart'])) : ?>
		<?= $back ?>
		<?php endif;?>
		<?php if (isset($_GET['cart']) && empty($_SESSION['cart'])) : ?>
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<p>Winkelwagen Is Leeg</p>
					</div>
				</div>
			</div>
		</div>
		
		<?php elseif (isset($_GET['cart']) && !empty($_SESSION['cart'])) : ?>
		<?= $bestel ?>
		<div class="row products">
		<?php printCart($url); ?>
		</div>
		<?= $bestel ?>
		<?php elseif (!isset($_GET['pass']) || $_GET['pass'] != 'Gebruiker127!') : ?>
		<div class="row">
			<h4>Vul uw wachtwoord in om te beginnen</h4>
			<form class="col s12" method="GET" action="">
				<div class="card" style="padding: 1rem;">
					<div class="input-field">
						<input id="pass" name="pass" type="text">
						<label for="pass">Wachtwoord</label>
					</div>
					<button class="btn waves-effect waves-light" type="submit">Verder
						<i class="material-icons right">arrow_forward</i>
					</button>
				</div>
			</form>
		</div>
		<?php elseif (!isset($_GET['bedrijf']) && $_GET['pass'] == 'Gebruiker127!') : ?>
		<?= $winkelwagen ?>
		<div class="row" id="bedrijf">
			<h4>Kies uw bedrijf</h4>
			<div class="col s6">
				<div class="card">
					<div class="card-image">
						<img src="img/jgz.svg" style="padding: 3rem;">
					</div>
					<div class="card-action">
						<a href="<?php echo $url . "&bedrijf=JGZ"?>" id="bestel" data-id="JGZ">Bestel Voor JGZ</a>
					</div>
				</div>
			</div>
			<div class="col s6">
				<div class="card">
					<div class="card-image">
						<img src="img/ggd.png" style="padding: 3rem;">
					</div>
					<div class="card-action">
						<a href="<?php echo $url . "&bedrijf=GGD"?>">Bestel Voor GGD</a>
					</div>
				</div>
			</div>
		</div>
		<?php elseif (!isset($_GET['product']) && $_GET['bedrijf'] == 'GGD') :?>
		<?= $back ?>
		<div class="row products">
			<h4>Kies uw product</h4>
			<h4>Briefpapier</h4>
			<?php
			printProduct($url, 'ggd_bp', 'Briefpapier');
			printProduct($url, 'ggd_bp_zonderadres', 'Briefpapier zonder adres');
			?>
		</div>
		<div class="row products">
			<h4>Envelop EA5 220x156</h4>
			<?php
			printProduct($url, 'ggd_env_220x156', 'Envelop EA5 220x156 Port Betaald');
			printProduct($url, 'ggd_env_220x156_metvenster', 'Envelop EA5 220x156 met venster Port Betaald');
			?>
		</div>
		<div class="row products">
			<h4>Envelop C4 229x324</h4>
			<?php
			printProduct($url, 'ggd_env_229x324', 'Envelop C4 229x324 Port Betaald');
			printProduct($url, 'ggd_env_229x324_metvenster', 'Envelop C4 229x324 met venster Port Betaald');
			?>
		</div>
		<div class="row products">
			<h4>Envelop C5 229x162</h4>
			<?php
			printProduct($url, 'ggd_env_229x162', 'Envelop C5 229x162 Port Betaald');
			printProduct($url, 'ggd_env_229x162_metvenster', 'Envelop C5 229x162 met venster Port Betaald');
			?>
		</div>
		<div class="row products">
			<h4>Envelop EA4 220x312</h4>
			<?php
			printProduct($url, 'ggd_env_220x312', 'Envelop EA4 220x312 Port Betaald');
			printProduct($url, 'ggd_env_220x312_metvenster', 'Envelop EA4 220x312 met venster Port Betaald');
			?>
		</div>
		<div class="row products">
			<h4>Envelop EB4 371x262 Monsterenvelop</h4>
			<?php
			printProduct($url, 'ggd_env_371x262', 'Envelop EB4 371x262 Monsterenvelop Port Betaald');
			?>
		</div>
		<div class="row products">
			<h4>Antwoordenvelop</h4>
			<?php
			printProduct($url, 'ggd_antw_almere', 'Antwoordenvelop Almere');
			printProduct($url, 'ggd_antw_emmeloord', 'Antwoordenvelop Emmeloord');
			printProduct($url, 'ggd_antw_lelystad', 'Antwoordenvelop Lelystad');
			?>
		</div>
		<div class="row products">
			<h4>Visitekaartjes</h4>
			<?php
			printProduct($url, 'ggd_vk_front', 'Visitekaartje <br><span style="font-size: 12px;">U ontvangt z.s.m. een proef.</span>', array(
				"Volledige Naam"=>"Laurine den Houting",
				""=>"Regionaal Meldpunt OGGz",
				"Functie"=>"Zorgcoordinator Vangnet & Advies",
				"Telefoonnummer"=>"088 00 29 915",
				"Email"=>"I.denhouting@ggdflevoland.nl",
				"Mobiele Nummer"=>"06 230 366 77",
				"Adres"=>"Boomgaardweg 4 1326 AC Almere"));
			?>
		</div>
		<?php elseif (!isset($_GET['product']) && $_GET['bedrijf'] == 'JGZ') :?>
		<?= $back ?>
		<div class="row products">
			<h4>Kies uw product</h4>
			<h4>Briefpapier</h4>
			<?php
			printProduct($url, 'jgz_bp_metadres', 'Briefpapier met adres');
			printProduct($url, 'jgz_bp_zonderadres', 'Briefpapier zonder adres');
			?>
		</div>
		<div class="row products">
			<h4>Envelop EA5 220x156</h4>
			<?php
			printProduct($url, 'jgz_env_220x156', 'Envelop EA5 220x156');
			printProduct($url, 'jgz_env_220x156_metvenster', 'Envelop EA5 220x156 met venster');
			?>
		</div>	
		<div class="row products">
			<h4>Envelop C4 229x324</h4>
			<?php
			printProduct($url, 'jgz_env_229x324', 'Envelop C4 229x324');
			printProduct($url, 'jgz_env_229x324_metvenster', 'Envelop C4 229x324 met venster');
			?>
		</div>
		<div class="row products">
			<h4>Envelop 230x350</h4>
			<?php
			printProduct($url, 'jgz_env_230x350', 'Envelop 230x350');
			?>		
		</div>
		<div class="row products">
			<h4>Envelop EB4 371x262 Monsterenvelop</h4>
			<?php
			printProduct($url, 'jgz_env_371x262', 'Envelop EB4 371x262 Monsterenvelop');
			?>
		</div>
		<div class="row products">
			<h4>Antwoordenvelop</h4>
			<?php
			printProduct($url, 'jgz_antw', 'Antwoordenvelop');
			?>
		</div>	
		<div class="row products">
			<h4>Visitekaartjes</h4>
			<?php
			printProduct($url, 'jgz_vk_front', 'Visitekaartje <br><span style="font-size: 12px;">U ontvangt z.s.m. een proef.</span>', array(
				"Volledige Naam"=>"Jan Herweijer",
				"Functie"=>"Directeur",
				"Mobiele Nummer"=>"06 - 22 92 52 35",
				"Extra Nummer"=>"06 - 22 92 52 35",
				"Email"=>"jherweijer@jgzalmere.nl"));
			?>
			<?php
			printProduct($url, 'jgz_vk_zonderadres_front', 'Visitekaartje Zonder Adres<br><span style="font-size: 12px;">U ontvangt z.s.m. een proef.</span>', array(
				"Volledige Naam"=>"Jan Herweijer",
				"Functie"=>"Directeur",
				"Mobiele Nummer"=>"06 - 22 92 52 35",
				"Extra Nummer"=>"06 - 22 92 52 35",
				"Email"=>"jherweijer@jgzalmere.nl"));
			?>
		</div>
		<div class="row products">
			<h4>Meet & Weegkaartje</h4>
			<?php
			printProduct($url, 'jgz_mw_front', 'Meet & Weegkaartje');
			?>
		</div>
		<?php else :?>
		<?= $back ?>
		<div class="row">
			<div class="col s6">
				<div class="card">
					<div class="card-image">
						<img src="img/<?php echo $_GET['product']?>.jpg">
					</div>
					<?php if (strpos($_GET['product'], 'jgz_vk') !== false || strpos($_GET['product'], 'jgz_mw') !== false) : ?>
					<div class="card-image">
						<img src="img/<?php echo substr($_GET['product'], 0, -5) . "back"?>.jpg">
					</div>
					<?php elseif (strpos($_GET['product'], 'ggd_vk') !== false) : ?>
					<div class="card-image">
						<img src="img/<?php echo substr($_GET['product'], 0, -5) . "back"?>.jpg">
					</div>
					<?php endif;?>
				</div>
			</div>
			<form action="/ggd/?pass=<?php echo $_GET['pass'] ?>" method="post">
				<div class="col s6">
					<div class="card-panel">
						<input type="hidden" name="product" value="<?php echo $_GET['product']?>">
						<?php if (isset($_GET['fields'])) : ?>
						<?php
						$fields = unserialize(base64_decode($_GET['fields']));
						$keys = array_keys($fields);
						?>
						<?php for($i = 0; $i < count($fields); $i++) :?>
						<?php if ($fields[$keys[0]] == "Jan Herweijer") : ?>
							<div class="input-field">
								<input placeholder="<?=$fields[$keys[$i]]?>" name="<?=$keys[$i]?>" type="text">
								<label for="<?=$keys[$i]?>"><?=$keys[$i]?></label>
							</div>
						<?php else : ?>
							<div class="input-field">
								<input value="<?=$fields[$keys[$i]]?>" name="<?=$keys[$i]?>" type="text">
								<label for="<?=$keys[$i]?>"><?=$keys[$i]?></label>
							</div>
						<?php endif; ?>
						<?php endfor; ?>
						<?php endif; ?>
						<div class="input-field">
							<?php if (isset($_GET['aantal'])) : ?>
							<input value="<?= $_GET['aantal'] ?>" name="Aantal" type="number" required>
							<?php else : ?>
							<input name="Aantal" type="number" required>
							<?php endif ?>
							<?php
							$type = '';							?>
							<label for="Aantal">Aantal <?=$type?></label>
						</div>
						<div class="input-field">			
							<label>Kies Afleveradres</label>
							<?php if (isset($_GET['adres'])) : ?>
							<input value="<?= $_GET['adres'] ?>" type="text" name="Afleveradres" list="Afleveradres">
							<?php else : ?>
							<input type="text" name="Afleveradres" list="Afleveradres">
							<?php endif ?>
							<datalist id="Afleveradres">
								<option value="GGD Flevoland - Noorderwagenstraat 2 - 8223 AM  Lelystad">GGD Flevoland - Noorderwagenstraat 2 - 8223 AM  Lelystad</option>
								<option value="GGD Flevoland - Boomgaardweg 4 - 1326 AC  Almere">GGD Flevoland - Boomgaardweg 4 - 1326 AC  Almere</option>
							</datalist>
							</div>
							<div class="input-field">
							<input placeholder="Inkoopnummer" name="Inkoopnummer" type="text" required>
							<label for="Inkoopnummer">Inkoopnummer</label>
						</div>
						<p>Levertijd: Maximaal 8 werkdagen na bestelling.</p>
					</div>
				</div>
				<div class="col s12">
					<div class="card" style="padding: 1rem; text-align: center;">
						<button class="btn waves-effect waves-light" type="submit">Toevoegen Aan Bestelling
							<i class="material-icons right">add_shopping_cart</i>
						</button>
					</div>
				</div>
			</form>
		</div>
		<?php endif;?>
	</div>
</body>
</html>