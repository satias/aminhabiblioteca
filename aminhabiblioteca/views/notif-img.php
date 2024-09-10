<div class="ms-auto d-flex">
  <div class="position-relative my-auto">
    <button class="rounded-circle d-flex my-auto me-4 noti-btn" type="button" id="show-notif-tab">
      <span class="material-symbols-outlined icon-30">
        notifications
      </span>
    </button>
    <div class="position-absolute notif-tab shadow-lg rounded d-none mt-1 p-2" id="notif-tab">
    </div>
  </div>
  <div class="h-100 rounded-circle ident-img-caixa">
    <?php
    if (!empty($_SESSION['user_dados']['photo_url']) || $_SESSION['user_dados']['photo_url'] != null) {
    ?>
      <img src="<?php echo get_link(""); ?>libs/img/img-perfil/<?php echo $_SESSION['user_dados']['photo_url']; ?>" alt="Perfil">
    <?php
    }
    ?>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#show-notif-tab").click(function() {
      $("#notif-tab").toggleClass("d-none");
      $.ajax({
        type: "POST",
        url: "<?php echo get_link(""); ?>views/post_views/post_notificacoes.php",
        dataType: "html",
        success: function(response) {
          $("#notif-tab").html(response);
        },
      });
    });
  });
</script>