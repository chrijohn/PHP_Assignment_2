<?php
$servername = "oniddb.cws.oregonstate.edu";
$username = "chrijohn-db";
$password = "MOHdCKVzIRh8lL2I";
$dbname = "chrijohn-db";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "CREATE TABLE videoInventory (
id INT AUTO_INCREMENT PRIMARY KEY, 
name VARCHAR(255) NOT NULL,
category VARCHAR (255),
length INT UNSIGNED,
rented INT,
UNIQUE (name)
)";

if ($conn->query($sql) === TRUE) {
} else {
}

if ($_GET['action'] == 'checkin')
{
	$id = $_POST['movieID'];
	
	$sql = "UPDATE videoInventory SET rented = 0 WHERE id = $id";
	
	$conn->query($sql);
	
}
if ($_GET['action'] == 'checkout')
{
	$id = $_POST['movieID'];
	
	$sql = "UPDATE videoInventory SET rented = 1 WHERE id = $id";
	
	$conn->query($sql);
	
}
if ($_GET['action'] == 'deleteall')
{	
	$sql = "DELETE FROM videoInventory ";
	
	if ($conn->query($sql) === true)
	{
	}
	else
	{
	}
}
if ($_GET['action'] == 'deleteMovie')
{
	$id = $_POST['movieID'];
	
	$sql = "DELETE FROM videoInventory " . "WHERE id = $id" ;
	
	if ($conn->query($sql) === true)
	{
	}
	else
	{
	}
}
if ($_GET['action'] == 'addmovie')
{
	
	$stmt = $conn->prepare("INSERT INTO videoInventory (name, category, length, rented) VALUES (?,?,?,?)");
	$stmt->bind_param("ssii", $name, $category, $length, $rented);

	$name = $_POST[title];
	$category = $_POST[category];
	$length = $_POST[length];
	$rented = 0;
	$stmt->execute();
}

echo '<form action = "http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php?action=addmovie" method = "post">
<p> Enter Title: <input type= "text" name ="title" required>
<p> Enter Category: <input type= "text" name ="category">
<p> Enter Length: <input type= "number" name ="length" min="1" max "999">
<p><input type="submit" value= "Enter Movie" required></p></form>';

if ($_GET['action'] == 'filter')
{
	$filter = $_POST['catFilter'];
	$sql = "SELECT id, name, category, length, rented FROM videoInventory WHERE category = '$filter'";
	$table = $conn->query($sql);
	
	echo '<form action = "http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php" method = "post">
	<p><input type="submit" value= "unfilter"></p></form>';
}
else
{
	$sql = "SELECT id, name, category, length, rented FROM videoInventory";
	$table = $conn->query($sql);

	$sql = "SELECT DISTINCT category FROM videoInventory";
	$categories = $conn->query($sql);


	echo '<div><form action = "http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php?action=filter" method = "post">';
	echo 'Select Category Filter: <select name="catFilter">';
	while ($category = $categories->fetch_assoc())
	{
		$catfilter = $category["category"];
		if ($catfilter == "")
		{
		}
		else
		{
		echo "<option value = $catfilter>" . $category["category"] . "</option>";
		}
	}
	echo '</select>
	<p><input type="submit" value= "Filter"></p></form></div>';
}
if ($table->num_rows > 0)
{
	echo "<table border= '2'><tr><th>DELETE</td><th>ID</th><th>NAME</th><th>CATEGORY</th><th>Length</th><th>STATUS<th>CHECK IN/OUT</th></tr>";
	while ($movie = $table->fetch_assoc())
	{
		echo "<tr><td>";
			echo "<form action = 'http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php?action=deleteMovie' method = 'post'>";
			echo "<input type= 'hidden' name= 'movieID' value = '" . $movie["id"] . "'>";
			echo "<input type='submit' value= 'DELETE'></form>";
		echo "</td>";
		echo "<td>" . $movie["id"] . "</td><td>" . $movie["name"] . "</td><td>" . $movie["category"] . "</td><td>" . $movie["length"] . "</td><td>";
		if ($movie["rented"] == 0)
		{
			echo "Available </td><td>";
		}
		else
		{
			echo "Checked Out</td><td>";
		}
		if ($movie["rented"] == 0)
		{
			echo "<form action = 'http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php?action=checkout' method = 'post'>";
			echo "<input type= 'hidden' name= 'movieID' value = '" . $movie["id"] . "'>";
			echo "<input type='submit' value= 'CHECK OUT'></form>";
			echo "</td></tr>";
		}
		else
		{
			echo "<form action = 'http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php?action=checkin' method = 'post'>";
			echo "<input type= 'hidden' name= 'movieID' value = '" . $movie["id"] . "'>";
			echo "<input type='submit' value= 'CHECK IN'></form>";
			echo "</td></tr>";
		}
	}
	echo "</table>";
	echo '<form action = "http://web.engr.oregonstate.edu/~chrijohn/CS290/PHP2/PHP_Assignment2.php?action=deleteall" method = "post">
	<p><input type="submit" value= "Delete All"></p></form>';
}
else
{
	echo "There are no movies in inventory";
}


?>

