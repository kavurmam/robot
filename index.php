<?php
  require_once('../wp-load.php');
  require_once('inc/fonksiyon.php');
  require_once('inc/URLResolver.php');
  global $db;

  // WP version

if ($_GET['go']=="getcontent") {
  $cat = $_POST['cat'];
  $image = $_POST['image'];
  $link = $_POST['link'];
  $getcat = $_GET['kategori'];
  $p = $_GET['p'];
  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $msg = $wpdb->get_row("select concat(title, '<br>', content) as content, id from archive where cat='$cat' and status=0 order by rand()");
  $msg = $msg->content . '<input type="hidden" name="link" value="'.$link.'"/><input type="hidden" name="image" value="'.$image.'"/><input type="hidden" name="content_id" value="'.$msg->id.'"/><input type="hidden" name="cat_id" value="'.$cat.'"/>';
  $msg = $msg . '<br><input type="submit" class="pure-button pure-button-primary" value="gabul et">';
  $msg = '<form action="?go=insertvideo" method="post">'.$msg.'</form>';
  echo $msg;exit();
}

//grabber get baslar
if ($_GET['go']=="grabber") {
  $p= $_GET['p'];
  if ($p=="") {
      $p=1;
  }

  $kategori = $_GET['kategori'];
  if ($kategori == "xxx") {
      $site = "http://xxx.com/movie?sort=published&page=".$p;
  }
  if ($kategori == "yyy") {
      $site = "http://yyy.com/movie?sort=published&page=".$p;
  }
  if ($kategori == "zzz") {
      $site = "http://zzz.com/movie?sort=published&page=".$p;
  }
  if ($kategori == NULL) {
      $site = "http://zzz.com/movie?sort=published&page=".$p;
  }

  $resolver = new URLResolver();
  $url =  $resolver->resolveURL($site)->getURL();
  $html = @file_get_html($url);
}
//grabber get biter

  //video ekler
  if ($_GET['go']=="insertvideo") {
    $content_id = $_POST['content_id'];
    $cat_id = $_POST['cat_id'];
    $image = $_POST['image'];
    $link = $_POST['link'];
    $row = $wpdb->get_row("select * from archive where id='$content_id'");
    	$my_post = array();
    	$my_post['post_title']		= $row->title;
      $my_post['post_content']	= $row->content;
      $my_post['post_category']	= array($cat_id);
    	$my_post['post_status'] 	= 'draft';
      $my_post['post_author']   = 1;

    	if( $id = wp_insert_post( $my_post )){
    		add_post_meta($id, 'url', $_POST['link']);
    		one_cikan($id,$image,$row->title);
    		mysql_query("INSERT INTO kavur_bot SET url='$link'");
        mysql_query("UPDATE archive set status=1 where id='$content_id'");
    		header('Location: index.php?go=grabber');
    	}
  }
  //video ekler ve gider

  //içerik ekler
  if ($_GET['go']=="insert") {
    $title     = $_POST['title'];
    $content     = $_POST['content'];
    $cat     = $_POST['cat'];
    $wpdb->query("insert into archive (title, content, cat,status) values ('$title','$content','$cat',0) ");
    header('Location: ?go=addvideo&status=1');
  }
  //içerik ekler ve gider
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>önemsiz bir gece</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" href="http://purecss.io/css/bootstrap/modal.css">

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="css/layouts/side-menu.css">
    <!--<![endif]-->

    <!--[if lte IE 8]>
      <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-old-ie-min.css">
    <![endif]-->

    <!--[if gt IE 8]><!-->
      <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
    <!--<![endif]-->

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="http://purecss.io/combo/1.18.13?/css/main-grid-old-ie.css&/css/main-old-ie.css&/css/layouts.css&/css/rainbow/baby-blue.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="http://purecss.io/combo/1.18.13?/css/main-grid.css&/css/main.css&/css/layouts.css&/css/rainbow/baby-blue.css">
    <!--<![endif]-->

    <link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet">


    <style>body, h2 {font-family: 'PT Sans Narrow', sans-serif;} #cat {width: 100%;height: 2.25em; border: 1px solid #ccc; background-color: #fff; display: block; margin: .25em 0;} aside {text-align: center;}
    .pure-img-responsive:hover {background: #ddd;opacity: 0.6;cursor: pointer;}</style>
</head>
<body>

<div id="layout">
    <a href="#menu" id="menuLink" class="menu-link">
        <span></span>
    </a>

    <div id="menu">
        <div class="pure-menu">
            <a class="pure-menu-heading" href="#">Robot v1</a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item"><a href="?go=home" class="pure-menu-link">Anasayfa</a></li>
                <li class="pure-menu-item"><a href="?go=grabber&kategori=letfap&p=1" class="pure-menu-link">x-art arşivi</a></li>
                <li class="pure-menu-item"><a href="?go=grabber&kategori=javhihi&p=1" class="pure-menu-link">kore arşivi</a></li>
                <li class="pure-menu-item"><a href="?go=grabber&kategori=jav789&p=1" class="pure-menu-link">asya arşivi</a></li>
                <li class="pure-menu-item"><a href="?go=addvideo" class="pure-menu-link">Video Ekle</a></li>
            </ul>
        </div>
    </div>

    <div id="main">
        <div class="header">
            <?php if ($_GET['go']=="addvideo") {?>
              <h2>en zor benim ama tatlıyım</h2>
            <?php } else if ($_GET['go']=="grabber") {?>
              <h2>her şey ortada ekle abim bişeyler işte.</h2>
            <?php } ?>
        </div>
        <?php if ($_GET['status']=="1") {?>
        <div class="content">
            <p></p>
            <aside><span>ulan ne iyi ettinde ekledin beni. ohh beee</span></aside>
        </div>
        <?php } ?>

        <?php if ($_GET['go']=="addvideo") {?>
        <!-- içerik ekleme başlar-->
        <form class="pure-form pure-form-stacked content" action="?go=insert" method="post">
          <fieldset>
              <div class="pure-g">
                  <div class="pure-u-1 pure-u-md-1">
                      <label for="title">başlık</label>
                      <input id="title" name="title" class="pure-u-1" type="text" required>
                  </div>

                  <div class="pure-u-1 pure-u-md-1">
                      <label for="content">içerik</label>
                      <textarea id="content" name="content" class="pure-input-1" placeholder="salla bişeyler" required></textarea>
                  </div>

                  <div class="pure-u-1 pure-u-md-1">
                      <label for="cat">kategori</label>
                      <?php wp_dropdown_categories( 'show_count=1&hierarchical=1' ); ?>
                  </div>

              </div>

              <label for="terms" class="pure-checkbox">
                  <input id="terms" type="checkbox" checked required> tüm sorumluluk taşaklarımda
              </label>

              <button type="submit" class="pure-button pure-button-primary">bas geç</button>
          </fieldset>
        </form>
        <!-- içerik ekleme biter-->
        <?php } else if ($_GET['go']=="grabber") {?>
        <!-- video listesi başlar-->
        <div class="pure-g">
          <?php
          $i=0;
          if ($kategori == "letfap") {$linkURL = "http://www.xxx.com/"; }
          if ($kategori == "jav789") {$linkURL = "http://www.yyy.com/"; }
          if ($kategori == "javhihi") {$linkURL = "http://www.zzz.com/"; }
          if ($kategori == NULL) {$linkURL = "http://www.xxx.com/"; }

          foreach(@$html->find('.item-thumbnail') as $article) {
              $i++;
              $image= $article->find('img', 0)->src;
              $short_link = $article->find('a', 0)->href;
              $link = $linkURL.$article->find('a', 0)->href;

              $kontrol = $wpdb->get_var("select url from kavur_bot where url like '%$short_link%'");
              $display =1;
              if ($kontrol) {
                  $display = "none";
              }

          ?>
            <div class="pure-u-1 pure-u-sm-1 pure-u-md-1-3 pure-u-lg-1-3 foto_<?php echo $i; ?>" style="display:<?php echo $display; ?>">
                <a href="#myModal<?php echo $i; ?>" role="button" data-toggle="modal">
                  <img class="pure-img-responsive" src="<?php echo $image; ?>" alt="video">
                </a>
            </div>
            <div id="myModal<?php echo $i; ?>" class="modal hide fade pure-u-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-header">
                  <h2 id="myModalLabel">video ekleme şeyisi</h2>
              </div>

              <div class="modal-body" style="overflow:hidden;">
                    <div class="pure-u-1 pure-u-md-1 cat_<?php echo $i; ?>">
                        <label for="cat">kategori</label>
                        <?php wp_dropdown_categories( 'show_count=1&hierarchical=1' ); ?>
                    </div>
                    <div class="pure-u-1 pure-u-md-1 content_<?php echo $i; ?>" style="font-size: 13px; letter-spacing: 0; text-align: center;">

                    </div>
              </div>

              <div class="modal-footer">
                  <button class="pure-button" data-dismiss="modal" aria-hidden="true">vazcay</button>
                  <input type="button" onclick="salla(<?php echo $i; ?>,'<?php echo $image?>','<?php echo $link?>');" class="pure-button pure-button-primary" value="salla">
                </div>
            </div>

            <?php } ?>
        </div>
        <!-- video listesi biter-->
        <?php } ?>
    </div>
</div>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
<script src="js/ui.js"></script>
<script>

function salla(id, image, link) {
    var cat = $( ".cat_"+id+" option:selected" ).val();
    $.ajax({
    type: 'POST',
    url: 'index.php?go=getcontent',
    data: {
        'id'       : id,
        'cat'      : cat,
        'image'    : image,
        'link'     : link
    },
    success: function(msg){
        //$(".foto_"+id).hide("slow");
        $(".content_"+id).html(msg);
    }
});
}
</script>

</body>
</html>
