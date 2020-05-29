<?php
include('functions.php');
if (!isset($_GET['pass']) || $_GET['pass'] != 'Gebruiker127!') {
    header("Location: /ggd/");
}
$back = '/ggd/?pass=Gebruiker127!';  
$back = '<a href="' . $back . '" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons left">arrow_back</i>Terug</a>';
$jaaroverzicht2018 = '/ggd/jaaroverzicht.php?pass=Gebruiker127!&year=2018';
$jaaroverzicht2018 = '<a href="' . $jaaroverzicht2018 . '" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons left">history</i>Jaaroverzicht 2018</a>';
$jaaroverzicht2019 = '/ggd/jaaroverzicht.php?pass=Gebruiker127!&year=2019';
$jaaroverzicht2019 = '<a href="' . $jaaroverzicht2019 . '" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons left">history</i>Jaaroverzicht 2019</a>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GGD Online Bestellen - Orders</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style type="text/css">
        
        table caption {
            margin-bottom: 1rem;
        }
        table caption button {
            margin-top: 5px;
            margin-bottom: 20px;
            float: left;
        }
	.scroll_overflow {
		width: 100%;
		overflow-x: scroll;	
	}
	th {
		white-space: nowrap;	
	}
    </style>
    <script src="save.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.13/js/tableexport.js"></script>

    <script>
    $( document ).ready(function() {
        $("table").tableExport({
            formats: ["csv"],
            fileName: $(this).attr('id')
        });
    });
    </script>
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
    <h1>Alle orders</h1>
	<?php echo $back; ?>
    <?php echo $jaaroverzicht2018; ?>
    <?php echo $jaaroverzicht2019; ?>
		<div class="row">
		<div class="scroll_overflow">
            <table style="table-layout:fixed;" id="all_orders">
                <thead>
                    <tr>
                        <th style="width: 75px;">Naam</th>
                        <th style="width: 20px;">Aantal</th>
                        <th style="width: 50px;">Adres</th>
                        <th style="width: 30px;">Inkoopnr.</th>
                        <th style="width: 50px;">Info</th>
                        <th style="width: 30px;">
                        <?php
                        if (isset($_GET['datum']) && $_GET['datum'] == "asc") {
                            $dat_a = "/ggd/orders.php?pass=" . $_GET['pass'] . "&datum=desc";
                            $arrow = "&uarr;";  
                        } else {
                            $dat_a = "/ggd/orders.php?pass=" . $_GET['pass'] . "&datum=asc";
                            $arrow = "&darr;";              
                        }
                        ?>
                        <a href="<?= $dat_a ?>">Datum <?= $arrow ?></a>
                        </th>
                        <th style="width: 25px;">Herhaal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

            if (isset($_GET['datum']) && $_GET['datum'] == "asc") {
            $orders = getOrders();  
            } else {
            $orders = array_reverse(getOrders());               
            }
                    foreach ($orders as $order) {
                        $orderd = unserialize(base64_decode($order['data']));
                        $fields = implode(", ",$orderd['fields']);;
                        echo "<tr>";
                        echo "<td>" . $orderd['naam'] . "</td>";
                        echo "<td>" . $orderd['aantal'] . "</td>";
                        echo "<td>" . $orderd['adres'] . "</td>";
                        echo "<td>" . $orderd['inkoopnummer'] . "</td>";
                        echo "<td>" . $fields . "</td>";
                        if ($order['time'] != '0000-00-00 00:00:00') {
                            echo "<td>" . $order['time'] . "</td>";
                        } else {
                            echo "<td></td>";
                        }
                        echo "<td><a style='color:black' href='" . getProductUrl($orderd['naam'], $orderd['fields'], $orderd['adres'], $orderd['aantal']) . "'><i class='material-icons center'>repeat</i></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
		</div>
        </div>
    </div>
</body>
</html>