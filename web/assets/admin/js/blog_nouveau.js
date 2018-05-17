/***********************************************************************************/
/******************************* AJOUTER IMAGE *************************************/
/***********************************************************************************/
var o = 
{
  message : 'Déposer une image ici'
};

var numImage = 0;

function declancherUploadImage(e)
{
  e.preventDefault();
  $('#file').click();
}

function ajouterImage(e)
{
  if ( $('#file').val() != '' )
  {
    numImage = numImage + 1;
    $('.dropzone').append("<div id='drop-file-"+numImage+"' class='drop-file file-remove'><div class='col-xs-12 text-center drop-file-text'><div class='progress'><div class='progress-bar' id='progress-bar-"+numImage+"' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: 0%;'><span id='text-progress-"+numImage+"'></span></div></div></div></div>");
    e.preventDefault();
    var form = $('form')[1];
    var formData = new FormData(form);

    urlScript = "/blog/add.image";
    $.ajax(
    {
      url : urlScript,
      type : "POST",
        data    : formData, // our data object
        processData: false,
        contentType: false,
        xhr: function()
        {
          //upload Progress
          var xhr = $.ajaxSettings.xhr();
          if (xhr.upload) 
          {
            xhr.upload.addEventListener('progress', function(event) 
            {
              var percent = 0;
              var position = event.loaded || event.position;
              var total = event.total;
              if (event.lengthComputable) 
              {
                percent = Math.ceil(position / total * 100);
              }
              //update progressbar
              $("#progress-bar-"+numImage).css("width", + percent +"%");
              $("#text-progress-"+numImage).text( percent +" %");
            }, true);
          }
          return xhr;
        },
        success: function(data, text, jqxhr)
        {
          var resultat = JSON.parse(jqxhr.responseText);
          if ( resultat.success )
          {
            $("#drop-file-"+numImage).empty();
            $('#drop-file-'+numImage).append("<div onclick='deleteImage(event, this)' class='delete-img text-center'><p class='drop-file-text'><span class='glyphicon glyphicon-remove-circle'></span></p></div>"+resultat.image);

            $("* .dropzone .file-remove:not(#drop-file-"+numImage+")").children('.delete-img').click();
            $("#input-image").val(resultat.url);
          }
        }
      });
  }
  $('#file').val('');
}


function deleteImage(event, btn)
{
  event.preventDefault();
  var datas = {'url' : $(btn).next().attr('src')};

  urlScript = "/blog/remove.image";
  $.ajax(
  {
    url : urlScript,
    type : "POST",
    data    : datas,
    success: function(data, text, jqxhr)
    {
      var resultat = JSON.parse(jqxhr.responseText);
      if ( resultat.success )
      {
        $(btn).parents(".drop-file").fadeOut(300, function() { $(this).remove(); });
        $("#input-image").val();
      }
    }
  });
}
/***********************************************************************************/
/******************************* AJOUTER IMAGE *************************************/
/***********************************************************************************/