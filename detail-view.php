<?php
include("includes/init.php");
$outfit_id = filter_input(INPUT_GET, "outfit_id", FILTER_VALIDATE_INT);
$title = 'Details';
$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');
?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/head.php"); ?>

<body>
  <?php include("includes/header.php"); ?>

  <div class="details-wrapper">
    <a href="all.php" class="go-back-button">
      &lt; Go Back</a>

    <?php
    $sql = "SELECT * FROM images WHERE id = $outfit_id";


    $records = exec_sql_query($db, $sql);
    if (count($records) > 0) {
      foreach ($records as $record) {
        echo "<img src=\"uploads/images/" . $record['id'] . "." . $record['file_ext'] . "\" alt=\"" . $record['description'] .  "\" class='details-image'> \n";


    ?>

        <!--Popup from component library, by hl685, woa4 -->
        <div id="popup" class="hidden">
          <p>Are you sure you want to delete this image?</p>
          <div class="conf-buttons-wrapper">

            <form id="delete-image" action="all.php" method="post">
              <input type="hidden" name="delete_image_id" value=<?php echo $outfit_id ?> />
              <button id="yes" name="yes" class="confirmation-button">Delete</button>

            </form>

            <button id="no" class="confirmation-button">Cancel</button>
          </div>
        </div>


        <div class="icons-wrapper">

          <div>
            <button id="edit_tag" name="edit_tag" type="submit" class="edit-tag-button bigger-button">...</button>
          </div>

          <div class="icon2">
            <button id="delete_image" class="delete-image-button bigger-button"><img src="site-images/trash-icon.png" alt="trash can icon"></button>

          </div>
        </div>
        <div class="descp-and-tags">
      <?php
        if ($outfit_id == 1) {
          //Source: https://www.pinterest.com/pin/849280442207158137/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/849280442207158137\">Source</a></cite>";
        } else if ($outfit_id == 2) {
          //Source: https://www.pinterest.com/pin/500040364874793606
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/500040364874793606\">Source</a></cite>";
        } else if ($outfit_id == 3) {
          //Source: https://www.pinterest.com/pin/669136457121603845/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/669136457121603845\">Source</a></cite>";
        } else if ($outfit_id == 4) {
          //Source: https://www.pinterest.com/pin/121456521187963354/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/121456521187963354\">Source</a></cite>";
        } else if ($outfit_id == 5) {
          //Source: https://www.pinterest.com/pin/294704369367264748
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/294704369367264748\">Source</a></cite>";
        } else if ($outfit_id == 6) {
          //Source: https://www.pinterest.com/pin/666040232380998677/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/666040232380998677\">Source</a></cite>";
        } else if ($outfit_id == 7) {
          //Source: https://www.pinterest.com/pin/699676492093731425/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/699676492093731425\">Source</a></cite>";
        } else if ($outfit_id == 8) {
          //Source: https://www.pinterest.com/pin/549017010815805585/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/549017010815805585\">Source</a></cite>";
        } else if ($outfit_id == 9) {
          //Source: https://www.pinterest.com/pin/793548396830584784/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/793548396830584784\">Source</a></cite>";
        } else if ($outfit_id == 10) {
          //Source: https://www.pinterest.com/pin/754775218780790529/
          echo "<cite class=\"cite\"><a href=\"https://www.pinterest.com/pin/754775218780790529\">Source</a></cite>";
        }



        echo "<h1>" . htmlspecialchars($record['description']) . "</h1> \n";
      }
    } else {
      echo '<p><strong>No images uploaded yet.</strong></p>';
    }






    $all_tags = exec_sql_query($db, "SELECT title FROM tags", NULL)->fetchAll(PDO::FETCH_COLUMN);

    if (isset($_POST["edit_tag_for_image"])) {
      $valid_tag = true;
      $message = "";
      $tag_name = filter_input(INPUT_POST, 'tag_for_image', FILTER_SANITIZE_STRING);
      $action = filter_input(INPUT_POST, 'edit', FILTER_SANITIZE_STRING);

      //all tags for this image
      $sqlTags = "SELECT tags.title FROM image_tags INNER JOIN images ON images.id = image_tags.image_id LEFT OUTER JOIN tags ON tags.id = image_tags.tag_id WHERE images.id = :outfit_id";
      $params = array(
        ':outfit_id' => $outfit_id,
      );
      $tags_for_image = exec_sql_query($db, $sqlTags, $params)->fetchAll(PDO::FETCH_COLUMN);

      //errors
      if ($action != 'add_tag' && $action != 'delete_tag') {
        $messagee = "Invalid choice.";
        $valid_tag = false;
      } else if ($tag_name == null) {
        $message = "Please enter a hashtag.";
        $valid_tag = false;
      } else if (preg_match('/\s/', $tag_name)) {
        $valid_tag = false;
        $message = "Please enter only one tag with no whitespace.";
      } else if ($action == 'delete_tag' && !in_array($tag_name, $tags_for_image)) {
        $valid_tag = false;
        $message = "That tag is not associated with this image.";
      }


      //if adding a tag
      if ($valid_tag && $action == 'add_tag') {
        $tag_id;

        //if tag is new
        if (!in_array($tag_name, $all_tags)) {
          //insert into tags table
          $sql = "INSERT INTO tags (title) VALUES (:title)";
          $params = array(
            ':title' => $tag_name,
          );
          $result = exec_sql_query($db, $sql, $params);
        }

        //get id of the tag
        $sql = "SELECT id FROM tags where title = :tag_name";
        $params = array(
          ':tag_name' => $tag_name,
        );
        $arr = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_COLUMN);
        $tag_id = $arr[0];

        //tag/image association
        $sql2 = "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
        $params = array(
          ':image_id' => $outfit_id,
          ':tag_id' => $tag_id
        );
        $final_result = exec_sql_query($db, $sql2, $params);
        if ($final_result) {
          $message = "Congrats! The tag has been added.";
        };
        //if deleting a tag
      } else if ($valid_tag && $action == 'delete_tag') {

        //get id of the tag
        $sql = "SELECT id FROM tags where title = :tag_name";
        $params = array(
          ':tag_name' => $tag_name,
        );
        $arr = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_COLUMN);
        $tag_id = $arr[0];

        //remove image/tag association
        $delsql = "DELETE FROM image_tags WHERE image_id = :outfit_id AND tag_id = :tag_id";
        $params = array(
          ':outfit_id' => $outfit_id,
          ':tag_id' => $tag_id,
        );
        $result = exec_sql_query($db, $delsql, $params);
        if ($result) {
          $message = "The tag has been deleted.";
        };
      }
    }

      ?>

      <div class="messages" id="messages">
        <?php
        echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>";
        ?>

      </div>

      <form id="editTagForm" action="detail-view.php?outfit_id=<?php echo $outfit_id ?>" method="post" class="hidden">
        <div class="edit-tag-form-wrapper">


          <div class="radio-wrapper">
            <input type="radio" id="add_tag" name="edit" value="add_tag" checked="checked">
            <label for="add">Add</label>
            <input type="radio" id="delete_tag" name="edit" value="delete_tag">
            <label for="delete_tag">Delete</label>
          </div>


          <div class="input-wrapper">
            <div class="group_label_input">
              <label for="tag_for_image">Edit a Hashtag:</label>
              <input id="tag_for_image" name="tag_for_image" />
            </div>

            <div class="group_label_input">
              <button name="edit_tag_for_image" type="submit" class="edit-tag-button">&gt;</button>
            </div>

          </div>

        </div>
      </form>

        </div>

        <?php


        $sql2 = "SELECT tags.title FROM image_tags INNER JOIN images ON images.id = image_tags.image_id LEFT OUTER JOIN tags ON tags.id = image_tags.tag_id WHERE images.id = :outfit_id";
        $params = array(
          ':outfit_id' => $outfit_id,
        );
        $tag_records = exec_sql_query($db, $sql2, $params);
        if (count($tag_records) > 0) {
          echo "<p class=\"image-tags\">";
          foreach ($tag_records as $tag) {
            echo "#" . htmlspecialchars($tag['title']) . " ";
          }
          echo "</p>";
        }

        ?>

  </div>

</body>

</html>