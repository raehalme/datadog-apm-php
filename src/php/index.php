<?
use DDTrace\SpanData;

function insertUser($conn) {
	$stmt = $conn->prepare("INSERT INTO users(name) VALUES(?)");
	$stmt->bind_param("s", $_POST["name"]);
	$stmt->execute();
}

function deleteUser($conn) {
	$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
}

function listUsers($conn) {
	$result = $conn->query("SELECT id, name FROM users");
	if ($result->num_rows == 0) {
		echo "<p>0 results</p>";
	}
	else {
		while($row = $result->fetch_assoc()) {
			echo $row['name'];
			echo "<form method=\"get\"><input type=\"hidden\" name=\"id\" value=\"" . $row["id"] . "\"/><input type=\"submit\" name=\"delete\" value=\"Delete\" /></form>";
			echo "<br>";
		}
	}

	echo "<br><form method=\"post\">";
	echo "Name: <input name=\"name\" />";
	echo "<input type=\"submit\" value=\"Add\" />";
	echo "</form>";
}

// Datadog APM can instrument automatically known frameworks etc.
// Manual instrumentation is also possible by calling trace_function
// or trace_method on the function or method. Use the function definition
// to customize the span data.
// https://docs.datadoghq.com/tracing/setup_overview/custom_instrumentation/php/?tab=tracingfunctioncalls
\DDTrace\trace_function('insertUser', function(SpanData $span, $args, $retval) {});
\DDTrace\trace_function('deleteUser', function(SpanData $span, $args, $retval) {});
\DDTrace\trace_function('listUsers', function(SpanData $span, $args, $retval) {});

$conn = new mysqli("mariadb", "root", "secret", "db_name");

$conn->query("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT, name VARCHAR(8), PRIMARY KEY (id))");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	insertUser($conn);
	header("Location: index.php");
}
else if (!empty($_GET["delete"])) {
	deleteUser($conn);
	header("Location: index.php");
}
else {
	listUsers($conn);
}

$conn->close();
