
$('#container_noticias').html('<h5 style="margin: 15px; font-weight: bold;">CANAL DE NOVEDADES</h5><div id="noticiasContainer"></div>');


// PARA MOSTRAR TODAS LAS NOTICIAS DEL BLOG
var loaded = false;

function showNews() {
  if(loaded) return;

  $.ajax({
      url: 'http://queserademi.com/noticias/wp-json/wp/v2/posts',
      method: 'GET',
      success: function(result) {
          var title, content, src, i, posts = '';
          for (i = 0; i < result.length; i++) {
              title = result[i].title.rendered;
              content = result[i].excerpt.rendered;
              src = result[i].link;
              posts += '<a href="' + src + '" class="list-group-item list-group-item-action">';
              posts += '<h4><strong>' + title + '</strong></h4>';
              posts += content;
              posts += '</a>';
          }
          $('#noticiasContainer').append('<div class="list-group">' + posts + '</div>');
      },
      error: function(xhr, textStatus, errorThrown) {
          console.log(xhr, textStatus, errorThrown);
          $('#noticiasContainer').append('<h2>No hay noticias!</h2>');
      }
  });

  loaded = true;
}

showNews();



// PARA MOSTRAR NOTICIAS ESPECIFICAS A LA PROFESION

//conexion a http://diy-qsdm.rhcloud.com/
/*function getNoticias(id) {
    console.log("http://diy-qsdm.rhcloud.com/service/noticias/byProfesion/" + id);
    $.ajax({
        url: "http://diy-qsdm.rhcloud.com/service/noticias/byProfesion/" + id,
        success: function(result) {
            console.log(result);
            return result;
        },
        error: function(xhr, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
}

<?php if (isset($profesion)) { ?> 
    var id_profesion = <?php echo $id_profesion; ?>;
    var noticias = getNoticias(id_profesion); 
    $('#noticiasContainer').append('<h4 class="principal nombre"><?php echo mb_strtoupper($profesion,"UTF-8" ); ?></h4>');
    if (!noticias || noticias.length === 0) {
        $('#noticiasContainer').append('<p class="descripcion" id="desc1">No hay noticias sobre esta profesion!</p>');
        $('#noticiasContainer').append('<div class="col-md-8 col-md-offset-2"><a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: #d5001e; color: #d5001e;">Colabora!</a></div>');
    } else {
        $('#noticiasContainer').append('<ul class="noticias">');
        for (var i = 0; i < noticias.length; i++) {
            $('#noticiasContainer').append('<li class="noticia">' + noticias[i] + '</li>');
        }
        $('#noticiasContainer').append('</ul>');
    }
<?php } ?>
<?php if (isset($profesion_dos)) { ?>
    var id_profesion_dos = <?php echo $id_profesion; ?>;
    var noticias_dos = getNoticias(id_profesion_dos); 
    $('#noticiasContainer').append('<hr>');
    $('#noticiasContainer').append('<h4 class="secundaria nombre"><?php echo mb_strtoupper($profesion_dos,"UTF-8" ); ?></h4>');
    if (!noticias_dos || noticias_dos.length === 0) {
        $('#noticiasContainer').append('<p class="descripcion">No hay noticias sobre esta profesion!</p>');
        $('#noticiasContainer').append('<div class="col-md-8 col-md-offset-2"><a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a></div>');
    } else {
        $('#noticiasContainer').append('<ul class="noticias">');
        for (var i = 0; i < noticias_dos.length; i++) {
            $('#noticiasContainer').append('<li class="noticia">' + noticias_dos[i] + '</li>');
        }
        $('#noticiasContainer').append('</ul>');
    }
<?php } ?>*/





