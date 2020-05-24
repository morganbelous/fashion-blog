<?php
include("includes/init.php");
$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');
$title = 'Tags';
$tags_list = exec_sql_query($db, "SELECT title FROM tags", NULL)->fetchAll(PDO::FETCH_COLUMN);

function print_tag($record)
{

  echo "<a href='all.php?" . http_build_query(
    array(
      'tag_title' => $record['title']
    )
  ) . "' class=\"tag\">" . $record["title"] . "</a>\n";
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/head.php"); ?>

<body>

  <?php include("includes/header.php"); ?>

  <h1>All Hashtags</h1>

  <div class="messages">
    <?php
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>";
    ?>
  </div>

  <div class="all-tags-wrapper">
    <?php
    $sql = "SELECT DISTINCT id, title FROM tags";
    $records = exec_sql_query($db, $sql);

    if (count($records) > 0) {
      foreach ($records as $record) {
        print_tag($record);
      }
    } else {
      echo '<p><strong>No hashtags added.></p>';
    }
    ?>
  </div>


</body>

</html>
