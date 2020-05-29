<?php
// ini_set('display_startup_errors',1);
// ini_set('display_errors',1);
// error_reporting(-1);
include('functions.php');
if (!isset($_GET['pass']) || $_GET['pass'] != 'Gebruiker127!') {
    header("Location: /ggd/");
}
$back = '/ggd/orders.php?pass=Gebruiker127!';  
$back = '<a href="' . $back . '" class="waves-effect waves-light btn col s6 offset-s6"><i class="material-icons left">arrow_back</i>Terug</a>';
$year = $_GET['year'];
$orders = getOrders();
$overzichten = getOverzicht($orders, $year);

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
            float: left;
        }
	.scroll_overflow {
		width: 100%;
		overflow-x: scroll;	
	}
	td, th {
		white-space: nowrap;	
	}
	table td, table th {
	    border-left: 1px solid rgba(0, 0, 0, 0.87);;
	    border-right: 1px solid rgba(0, 0, 0, 0.87);;
	}

	table td:first-child, table th:first-child {
	    border-left: none;
	}

	table td:last-child, table th:last-child {
	    border-right: none;
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
    <h1>Jaaroverzicht <?= $year ?></h1>
	<?php echo $back; ?>
		<div class="row">
            <h3>GGD</h3>
		<div class="scroll_overflow">
            <table id="GGD_<?= $year ?>">
                <thead>
                    <tr>
                        <th>Besteldatum</th>
                        <th>Briefpapier</th>
                        <th>Briefpapier ZA</th>
                        <th>Envelop EA4 ZV</th>
                        <th>Envelop EA4 MV</th>
                        <th>Envelop EB4</th>
                        <th>Envelop EA5 ZV</th>
                        <th>Envelop EA5 MV</th>
                        <th>Envelop C4 ZV</th>
                        <th>Envelop C4 MV</th>
                        <th>Envelop C5 ZV</th>
                        <th>Envelop C5 MV</th>
                        <th>Antwoordenvelop Almere</th>
                        <th>Antwoordenvelop Emmeloord</th>
                        <th>Antwoordenvelop Lelystad</th>
                        <th>Visitekaartjes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($overzichten['ggd']); $i++ ) {
                        $keys = array_keys($overzichten['ggd']);
                        echo "<tr>";
                        echo "<td>" . $keys[$i] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_bp'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_bp_zonderadres'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_220x312'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_220x312_metvenster'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_371x262'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_220x156'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_220x156_metvenster'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_229x324'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_229x324_metvenster'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_229x162'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_env_229x162_metvenster'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_antw_almere'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_antw_emmeloord'] . "</td>";
                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_antw_lelystad'] . "</td>";

                        echo "<td>" . $overzichten['ggd'][$keys[$i]]['ggd_vk_front'] . "</td>";
                        echo "</tr>";                  
                    }
                    ?>
                </tbody>
            </table>
		</div>
		<div class="scroll_overflow">
            <h3>JGZ</h3>
            <table id="JGZ_<?= $year ?>">
                <thead>
                    <tr>
                        <th>Besteldatum</th>
                        <th>Briefpapier</th>
                        <th>Briefpapier ZA</th>
                        <th>Envelop EB4</th>
                        <th>Envelop EA5 ZV</th>
                        <th>Envelop EA5 MV</th>
                        <th>Envelop C4 ZV</th>
                        <th>Envelop C4 MV</th>
                        <th>Antwoordenvelop</th>
                        <th>Visitekaartjes</th>
                        <th>Visitekaartjes ZA</th>
                        <th>Meet & Weegkaartje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($overzichten['jgz']); $i++ ) {
                        $keys = array_keys($overzichten['jgz']);
                        echo "<tr>";
                        echo "<td>" . $keys[$i] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_bp'] . "</td>";
                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_bp_zonderadres'] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_env_371x262'] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_env_220x156'] . "</td>";
                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_env_220x156_metvenster'] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_env_229x324'] . "</td>";
                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_env_229x324_metvenster'] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_antw'] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_vk_front'] . "</td>";
                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_vk_zonderadres_front'] . "</td>";

                        echo "<td>" . $overzichten['jgz'][$keys[$i]]['jgz_mw_front'] . "</td>";
                        
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