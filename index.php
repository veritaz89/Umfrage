<?php 
	// Open database
	$db     = new SQLite3('umfrage.db');
	$missingfield = array();
	// Check POST request
	if (empty($_POST))
	{
		$bHasResponded = false;
	}
	else
	{
		var_dump($_POST);
		// Check
		$bHasResponded = true;
		$name = $wohnort = $fahrtdauer = $verkehrsmittel = $datum_nutzung = "";
		if (isset($_POST["name"]))
		{
			$name           = test_input($_POST["name"]);
			if (empty($name))
			{			  
			  $missingfield[] = "name";
			}
		}
		if (isset($_POST["wohnort"]))
			$wohnort        = test_input($_POST["wohnort"]);
			if (empty($wohnort))
			{			  
			  $missingfield[] = "wohnort";
			}
		if (isset($_POST["fahrtdauer"]))			
			$fahrtdauer     = test_input($_POST["fahrtdauer"]);
			if (empty($fahrtdauer))
			{			  
			  $missingfield[] = "fahrtdauer";
			}			
		if (isset($_POST["verkehrsmittel"]))			
			$verkehrsmittel = test_input($_POST["verkehrsmittel"]);
			if (empty($verkehrsmittel))
			{			  
			  $missingfield[] = "verkehrsmittel";
			}						
		if (isset($_POST["datum_nutzung"]))			
			$datum_nutzung  = test_input($_POST["datum_nutzung"]);
			printf('INSERT INTO T_Antworten (Name, Wohnort, Fahrtdauer, Verkehrsmittel, Datum_Nutzung) VALUES (%s, %s, %s, %s, %s);', $_POST["name"], $wohnort, $fahrtdauer, $verkehrsmittel, $datum_nutzung);
			if (count($missingfield) == 0)
				$bIsValid = $db->query(sprintf('INSERT INTO T_Antworten (Name, Wohnort, Fahrtdauer, Verkehrsmittel, Datum_Nutzung) VALUES ("%s", "%s", "%s", "%s", "%s");', $name, $wohnort, $fahrtdauer, $verkehrsmittel, $datum_nutzung));
			else $bIsValid  = false;
	}

	function test_input($data) {
	echo("here");
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;

}

	// Read database

	$result = $db->query('SELECT * FROM T_Antworten');
?>
<!doctype html>
<html>
    <head>
        <title>Umfrage zum Bahn-Streik</title>
                <link href = "style.css" rel = "stylesheet" type = "text/css">
    </head>
    <body>
        <h1>Umfrage zum Bahnstreik</h1>
        <?php
if ($bHasResponded && $bIsValid)
{
?><div id="responsebox" class="valid">Danke für Ihre Antwort!</div><?php }
elseif ($bHasResponded && !$bIsValid)
{?><div id="responsebox" class="invalid">Ihre Antwort war leider nicht gültig!<?php
foreach ($missingfield as $currentfield)
{
	printf("<br/>Fehlendes Feld: <b>%s</b>", $currentfield);
}
?></div><?php
}
        ?>
        <table id="responsetable">
            <thead>
                <tr>
                    <th><label for="name">Name</label></th>
                    <th><label for="ort">Wohnort</label></th>
                    <th><label for="dauer">Fahrtdauer</label></th>
                    <th><label for="verkehrsmittel">Verkehrsmittel</label></th>
					<th><label for="datum_bahn">Letzte Nutzung S-Bahn</label></th>
                </tr>
            <tbody>
<?php 
	while($row = $result->fetchArray()){
		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row['Name'], $row['Wohnort'], $row['Fahrtdauer'], $row['Verkehrsmittel'], $row['Datum_Nutzung'] );
		}
?>			
			</tbody>
        </table>
    </body>
</html>