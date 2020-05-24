<?php
include("includes/init.php");
$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');
$title = 'Outfits';
$tag_title = filter_input(INPUT_GET, "tag_title", FILTER_SANITIZE_STRING);
$delete_image_id = filter_input(INPUT_POST, "delete_image_id", FILTER_VALIDATE_INT);


if ($tag_title == null) {
  $title = 'Outfits';
} else {
  $title = $tag_title;
}

if ($delete_image_id != null) {
  $sql = "SELECT * FROM images where id = :img_id";
  $params = array(
    ":img_id" => $delete_image_id
  );
  $result = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  $outfit = $result[0];
  $file_path = 'uploads/images/' . $outfit['id'] . "." . $outfit['file_ext'];

  if (unlink($file_path)) {
    $sql3 = "DELETE FROM images WHERE id = :img_id";
    $params = array(
      ":img_id" => $delete_image_id
    );
    $sql4 = "DELETE FROM image_tags WHERE image_id = :img_id";
    $db->beginTransaction();
    exec_sql_query($db, $sql3, $params);
    exec_sql_query($db, $sql4, $params);
    $db->commit();
    header("Location: http://localhost:3000/all.php");
    exit();
  } else {
    $message = "Delete failed.";
  }
}


if (isset($_GET['search'])) {
  $do_search = TRUE;
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  $do_search = FALSE;
  $search = NULL;
}


function print_record($record)
{
  echo "<a href='detail-view.php?" . http_build_query(
    array(
      'outfit_id' => $record['id']
    )
  ) . "'><img src=\"uploads/images/" . $record['id'] . "." . $record['file_ext'] . "\" alt=\"" . $record['description'] .  "\" class='image' ></a> \n";
};

?>
<!DOCTYPE html>
<html lang="en">

<?php include("includes/head.php"); ?>

<body>
  <?php include("includes/header.php"); ?>

  <div class="wrapper">

    <?php include("includes/search.php"); ?>

    <a href="upload-image.php" class="add-button">+</a>
  </div>

  <h1 class="all-images-title"><?php echo  "#" . $title ?></h1>


  <div class="all-images-wrapper">

    <?php
    $sql;

    if ($do_search) {
      $sql = "SELECT DISTINCT images.id, images.description, images.file_name, images.file_ext FROM image_tags INNER JOIN images ON images.id = image_tags.image_id LEFT OUTER JOIN tags ON tags.id = image_tags.tag_id WHERE tags.title LIKE" . "'%' || :search || '%'";
      $params = array(
        ':search' => $search
      );
    } else if ($tag_title != null) {
      //ids of outfits with the tag
      $sql = "SELECT images.id, images.description, images.file_name, images.file_ext FROM image_tags INNER JOIN images ON images.id = image_tags.image_id LEFT OUTER JOIN tags ON tags.id = image_tags.tag_id WHERE tags.title = :title";
      $params = array(
        ':title' => $tag_title
      );
    } else {
      $sql = "SELECT * FROM images";
    }
    $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);

    if (count($records) > 0) {
      foreach ($records as $record) {
        print_record($record);
      }
    } else {
      echo '<p>No outfits uploaded yet. Try uploading an image!</p>';
    } ?>


  </div>

</body>

</html>
