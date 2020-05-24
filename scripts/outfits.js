$(document).ready(function () {

  $("#edit_tag").on("click", function () {
    if ($("#editTagForm").is(":hidden")) {
      ;
      $("#editTagForm").removeClass("hidden");
      return;
    }
  });

  /*From campuswire posts, by hl685, woa4 */
  document.getElementById("delete_image").onclick = function () {
    document.getElementById("popup").classList.remove("hidden")
  }
  document.getElementById("yes").onclick = function () {
    document.getElementById("popup").classList.add("hidden")
    //delete item

  }
  document.getElementById("no").onclick = function () {
    document.getElementById("popup").classList.add("hidden")
  }

});
