<?php
require("mail/PHPMailerAutoload.php");

function printProduct($url, $img, $title, $fields = array()) {
	$fields = base64_encode(serialize($fields));
	echo '
	<div class="col s6">
		<div class="card horizontal">
			<div class="card-image">
				<img src="img/' .  $img . '.jpg">
			</div>
			<div class="card-stacked">
				<div class="card-content">
					<p>' . $title . '</p>
				</div>
				<div class="card-action">
				<a href="' . $url . '&product=' . $img . '&fields=' . $fields . '">Selecteer</a>
				</div>
			</div>
		</div>
	</div>
	';
}

function getProductUrl($img, $fields = array(), $adres, $aantal) {
	$fields = base64_encode(serialize($fields));
	$bedrijf = substr($img, 0, 3);
	$url = "/ggd/?pass=Gebruiker127!&bedrijf=" . $bedrijf;
	return $url . '&product=' . $img . '&fields=' . $fields . "&aantal=" . $aantal . "&adres=" . $adres;
}

function getDB() {
	$dbhost = "localhost";
	$dbuser = "ggdjgz";
	$dbpassword = "code1234";
	$dbname = "ggdjgz";
	$db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpassword);
	return $db;
}

function getOrders() {
	$db = getDB();
	$sql = "SELECT * FROM orders";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	return array_map('reset', $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
}

function getOverzicht($orders, $year) {
	$overzicht = [];
	$overzicht['ggd'] = [];
	$overzicht['jgz'] = [];
	foreach ($orders as $order) {
        $orderd = unserialize(base64_decode($order['data']));
	$aantal = str_replace(".","",$orderd['aantal']);
        $date = date('Y-m-d',strtotime($order['time']));
	if ($date == '-0001-11-30') {
		$date = 'Onbekende datum (voor 2018-08-27)';	
	}
        if (date('Y', strtotime($order['time'])) == $year || ($year == '2018' && $date == 'Onbekende datum (voor 2018-08-27)')) {
        	$organisation = substr($orderd['naam'], 0, 3);
	        if (!array_key_exists($date, $overzicht[$organisation])) {
	        	$overzicht[$organisation][$date] = [];
	        	$overzicht[$organisation][$date][$orderd['naam']] = $aantal;
	        } else {
	        	if (!array_key_exists($orderd['naam'], $overzicht[$organisation][$date])) {
	        		$overzicht[$organisation][$date][$orderd['naam']] = $aantal;
	        	} else {
	        		$aantal0 = intval($overzicht[$organisation][$date][$orderd['naam']]);
		        	$aantal1 = intval($aantal);
		        	$sum = $aantal0 + $aantal1;
		        	$overzicht[$organisation][$date][$orderd['naam']] = $sum;	        	}
	        }
        }        
    }
    return $overzicht;
}

function addToDb($data) {
	$db = getDB();
	$sql = "INSERT INTO orders (data) VALUES (:data)";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(":data", $data);
	$stmt->execute();
}

function addToCart($product) {
	$keys = array_keys($product);
	$name = $product[$keys[0]];
	$fields = array();
	for ($i = 1; $i < count($product) - 3; $i++) {
		$fields[$keys[$i]] = $product[$keys[$i]];
	}
	$aantal = $product['Aantal'];
	$adres = $product['Afleveradres'];

	$inkoopnummer = $product['Inkoopnummer'];
	$product = array(
		'naam'=>$name,
		'aantal'=>$aantal,
		'adres'=>$adres,
		'inkoopnummer'=>$inkoopnummer,
		'fields'=>$fields
	);
	$_SESSION['cart'][] = $product;
	alert('Product Toegevoegd Aan Winkelwagen');
}

function removeFromCart($product) {
	$product = unserialize(base64_decode($product));
	for ($i = 0; $i < $_SESSION['cart']; $i++) {
		if ($product == $_SESSION['cart'][$i]) {
			unset($_SESSION['cart'][$i]);
			alert('Product Verwijderd Van Winkelwagen');
			header("Location: /ggd/?pass=code&cart=");
			break;
		}
	}
}

function printCart($url) {
	foreach ($_SESSION['cart'] as $product) {
		$remove = base64_encode(serialize($product));
		echo '
		<div class="col s12">
			<div class="card horizontal">
				<div class="card-image" style="max-width: 20%;">
					<img src="img/' .  $product['naam'] . '.jpg">
				</div>
				<div class="card-stacked">
					<div class="card-content">
						<p>
						Naam: ' . $product['naam'] . '<br>
						Aantal: ' . $product['aantal'] . '<br>
						Adres: ' . $product['adres'] . '<br>
						Inkoopnummer: ' . $product['inkoopnummer'] . '<br>';
		if (count($product['fields']) > 1) {
			echo "<br>";
			$keys = array_keys($product['fields']);
			for ($i = 0; $i < count($product['fields']); $i++) {
				echo $keys[$i] . ": " . $product['fields'][$keys[$i]] . '<br>';
			}
		}
		echo '
						</p>
					</div>
					<div class="card-action">
						<a href="' . $url . '&remove=' . $remove . '">Verwijder Uit Winkelwagen</a>
					</div>
				</div>
			</div>
		</div>
		';
	}
}

function alert($msg) {
	echo "<script>alert('" . $msg . "')</script>";
}

function completeOrder() {
	sendMail();
	session_destroy();
	alert('Bestelling Geplaatst!');
	// header('Location: /ggd/');
}


function sendMail() {
	$mail = new PHPMailer;

	$body = '';

	foreach ($_SESSION['cart'] as $product) {
		addToDb(base64_encode(serialize($product)));
		$keys = array_keys($product['fields']);
		$naam = $product['naam'];
		$aantal = $product['aantal'];
		$adres = $product['adres'];
		$inkoopnummer = $product['inkoopnummer'];
		$body = $body . 
		'Naam: ' . $naam . '<br>' .
		'Aantal: ' . $aantal . '<br>' .
		'Adres: ' . $adres . '<br>' .
		'Inkoopnummer: ' . $inkoopnummer . '<br>';
		if (count($keys)) {
			$body = $body . "<br>";
		}
		for ($i = 0; $i < count($product['fields']); $i++) {
			$body = $body . $keys[$i] . ': ' . $product['fields'][$keys[$i]] . "<br>";
		}
		$body = $body . "-------------------------------" . "<br>";
	}

	try {
	    //Server settings
	    $mail->SMTPDebug = 0;
	    $mail->isSMTP();
	    $mail->Host = 'smtp.alphamegahosting.com';
	    $mail->SMTPAuth = true;
	    $mail->Username = 'ggdjgz@teewes.nl';
	    $mail->Password = 'code1234';
	    $mail->SMTPSecure = 'tls';
	    $mail->Port = 587;

	    //Recipients
	    $mail->setFrom('ggdjgz@teewes.nl', 'GGD & JGZ Online Bestellen');
	    $mail->addAddress('inkoop@ggdflevoland.nl', 'GGD Flevoland Inkoop'); // inkoop@ggdflevoland.nl
	    $mail->addReplyTo('ggdjgz@teewes.nl', 'GGD & JGZ Online Bestellen');
	    $mail->addCC('o.zitman@teewes.nl');

	    //Content
	    $mail->isHTML(true);
	    $mail->Subject = 'Order GGD & JGZ online';
	    $mail->Body    = $body;

	    $mail->send();
	} catch (Exception $e) {
	    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}

?>