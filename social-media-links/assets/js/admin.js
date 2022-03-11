
jQuery(document).ready(function(){


  function iconupload(type,cls) {
    if ( mediaUploader ) {
      mediaUploader.open();

    }

    var mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Select an Image',
      button: {
        text: 'Use This Image'
      },
      multiple: false
    });

    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      cls.find('.icon-image-hidden-input').attr('value', attachment.url);
      cls.find('.icon-image-media').attr('src', attachment.url);
      cls.find(".icon-image-delete-button").show();

    });
    mediaUploader.open();
    cls.find('.icon-image-hidden-input').val(attachment.url).trigger('change');


  }


  jQuery('body').on('click', '.icon-image-upload-button', function() {
    var cls =jQuery(this).parents('table');
    iconupload('image',cls);
  });

  jQuery('body').on('click', '.icon-image-delete-button', function() {

    jQuery(this).parents('table').find('.icon-image-hidden-input').attr('value', '');
    jQuery(this).parents('table').find('.icon-image-media').attr('src', '');
    jQuery(this).parents('table').find(".icon-image-delete-button").hide();

  });

  jQuery( '#wsxsml-remove' ).live('click', function(e) {
    var checkstr =  confirm('Are you sure, you want to remove this Link?');
    e.preventDefault();
    if(checkstr == true){  
      jQuery(this).parents('table').remove();
      jQuery( "#publish" ).trigger( "click" );
    }
  });

  jQuery( '#wsxsml-add').on('click', function(e) {
    e.preventDefault();
    var p = jQuery('table').length;
    var container = jQuery('.clsSocialMediaOptions');
    var item = jQuery('.wsxsml-empty-row').clone();
    var len = ++p;
    item.find('input:radio').attr('name', 'wsxsml_type'+ len );
    item.find('input:radio').parent().attr('for', 'wsxsml-type' + len);
    item.removeClass('wsxsml-empty-row');
    item.appendTo('.clsNewTable').show();

  });


  var p = jQuery('table').length;
  jQuery(".clsOptions").each(function(){
    var len = ++p;
    jQuery(this).find('input:radio').attr('name', 'wsxsml_type'+ len );
    jQuery(this).find('input:radio').parent().attr('for', 'wsxsml-type' + len);

  });

  jQuery(".checked").each(function(){
    jQuery(this).prop('checked', true);
  });



   //jQuery('input[type="radio"]').live('change', function(e) {
    jQuery("body").on( "change", "table input[name^='wsxsml_type']", function () {


    if (jQuery(this).is(':checked')) {
      jQuery(this).parents("table").find(".clshide").css("display", "none");
      var inputValue = jQuery(this).attr("value");

      var targetBox = jQuery(this).parents("table").find("." + inputValue);
      jQuery(targetBox).show();

    }



  });

  jQuery( '#submitdiv' ).live( 'click', '#publish', function( e ) {

    jQuery("input[name^='wsxsml_type']").each(function(){
      if (jQuery(this).is(':checked')) {
        var inputValue = jQuery(this).attr("value");
        if(inputValue == 'clsSMLImage'){
          jQuery(this).parents("table").find(".clsSMLIcon select option[value='']").attr('selected', true);

        } else if(inputValue == 'clsSMLIcon'){
          jQuery(this).parents('table').find('.icon-image-hidden-input').attr('value', '');
          jQuery(this).parents('table').find('.icon-image-media').attr('src', '');
        }
      }
    });

  });

  jQuery("#resources-class").live("change", function(){

    var selectedCountry = jQuery(this).children("option:selected").val();
    jQuery(this).parents("tr").find("span").hide();
    jQuery(this).parent().after('<span><i class="fa fa-'+selectedCountry+'"></i></span>');

  });

});
