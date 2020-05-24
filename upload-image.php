<?php
include("includes/init.php");
$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');
$title = 'Upload New Image';
const MAX_FILE_SIZE = 1000000;
$upload_info = $_FILES["image_file"];

if (isset($_POST["submit_upload"])) {
  $valid = true;
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  $basename = basename($upload_info['name']);
  $upload_ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));;

  if ($description == null) {
    $valid = false;
    $message = "Please add a description.";
  } else {
    $message = "";
  }

  if ($valid) {
    if ($upload_info["error"] == UPLOAD_ERR_OK && $upload_info["size"] < MAX_FILE_SIZE) {
      $sql = "INSERT INTO images (description, file_name, file_ext) VALUES (:description, :file_name, :file_ext)";
      $params = array(
        ':description' => $description,
        ':file_name' => $basename,
        ':file_ext' => $upload_ext,
      );
      $result = exec_sql_query($db, $sql, $params);

      if ($result) {
        $message =  "Your outfit has been added. Thank you!";
        $tmp_name = $upload_info["tmp_name"];
        $id = $db->lastInsertId("id");
        $new_file_name = $id . "." . $upload_ext;
        move_uploaded_file($tmp_name, "uploads/images/$new_file_name");
        header("Location: http://localhost:3000/all.php");
        exit();
      } else {
        $message =  "Failed to add image.";
      }
    } else {
      $message =  "Your file is either too big or you did not select one.";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include("includes/head.php"); ?>

<body>

  <?php include("includes/header.php"); ?>

  <h1>Upload Some Inspo!</h1>

  <div class="messages">
    <?php
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>";
    ?>

  </div>

  <div class="form-wrapper">
    <a href="all.php" class="go-back-button">
      &lt; Go Back</a>


    <form id="uploadFile" action="upload-image.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />

      <div class="group_label_input">
        <label for="image_file">Upload File:</label>
        <input id="image_file" type="file" name="image_file">
      </div>

      <div class="group_label_input">
        <label for="image_desc">Description:</label>
        <textarea id="image_desc" name="description"></textarea>
      </div>

      <div class="group_label_input">
        <button name="submit_upload" type="submit" class="add-button wider_button">Upload Image</button>
      </div>
    </form>

  </div>

</body>

</html>
