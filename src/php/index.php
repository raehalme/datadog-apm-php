<?

$conn = new mysqli("mariadb", "root", "secret", "db_name");

$conn->query("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT, name VARCHAR(8), PRIMARY KEY (id))");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$stmt = $conn->prepare("INSERT INTO users(name) VALUES(?)");
	$stmt->bind_param("s", $_POST["name"]);
	$stmt->execute();
	header("Location: index.php");
	die();
}
else if (!empty($_GET["delete"])) {
	$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	header("Location: index.php");
	die();
}
else {
	$result = $conn->query("SELECT id, name FROM users");
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo $row['name'];
			echo "<form method=\"get\"><input type=\"hidden\" name=\"id\" value=\"" . $row["id"] . "\"/><input type=\"submit\" name=\"delete\" value=\"Delete\" /></form>";
			echo "<br>";
		}
	}
	else {
		echo "<p>0 results</p>";
	}
}

echo "<br><form method=\"post\">";
echo "Name: <input name=\"name\" />";
echo "<input type=\"submit\" value=\"Add\" />";
echo "</form>";

$conn->close();
