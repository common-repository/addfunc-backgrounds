// Thanks to: http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/
jQuery(document).ready(function($){
  var aFBgBdyImUp,
      aFBgWinImUp,
      aFBgWalImUp,
      aFBgIL1ImUp,
      aFBgIL2ImUp,
      aFBgIL3ImUp,
      aFBgIL4ImUp,
      aFBgVideoUp,
      aFBgVido2Up,
      aFBgOvrImUp;

  $('#aFBgBdyImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgBdyImUp){aFBgBdyImUp.open();return;} //If the uploader object has already been created, reopen the dialog
    aFBgBdyImUp = wp.media.frames.file_frame = wp.media({ //Extend the wp.media object
      frame: 'select',
      title: 'Choose Body Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgBdyImUp.on('select', function() { //When a file is selected, grab the URL and set it as the text field's value
      aFBgBdyAttach = aFBgBdyImUp.state().get('selection').first().toJSON();
      $('#aFBgBdyIm').val(aFBgBdyAttach.url).change();
    });
    aFBgBdyImUp.open(); //Open the uploader dialog
  });

  $('#aFBgWinImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgWinImUp){aFBgWinImUp.open();return;}
    aFBgWinImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Window Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgWinImUp.on('select', function() {
      aFBgWinAttach = aFBgWinImUp.state().get('selection').first().toJSON();
      $('#aFBgWinIm').val(aFBgWinAttach.url).change();
    });
    aFBgWinImUp.open();
  });

  $('#aFBgWalImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgWalImUp){aFBgWalImUp.open();return;}
    aFBgWalImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Wrapper Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgWalImUp.on('select', function() {
      aFBgWalAttach = aFBgWalImUp.state().get('selection').first().toJSON();
      $('#aFBgWalIm').val(aFBgWalAttach.url).change();
    });
    aFBgWalImUp.open();
  });

  $('#aFBgIL1ImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgIL1ImUp){aFBgIL1ImUp.open();return;}
    aFBgIL1ImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Layer 1 Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgIL1ImUp.on('select', function() {
      aFBgIL1Attach = aFBgIL1ImUp.state().get('selection').first().toJSON();
      $('#aFBgIL1Im').val(aFBgIL1Attach.url).change();
    });
    aFBgIL1ImUp.open();
  });

  $('#aFBgIL2ImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgIL2ImUp){aFBgIL2ImUp.open();return;}
    aFBgIL2ImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Layer 2 Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgIL2ImUp.on('select', function() {
      aFBgIL2Attach = aFBgIL2ImUp.state().get('selection').first().toJSON();
      $('#aFBgIL2Im').val(aFBgIL2Attach.url).change();
    });
    aFBgIL2ImUp.open();
  });

  $('#aFBgIL3ImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgIL3ImUp){aFBgIL3ImUp.open();return;}
    aFBgIL3ImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Layer 3 Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgIL3ImUp.on('select', function() {
      aFBgIL3Attach = aFBgIL3ImUp.state().get('selection').first().toJSON();
      $('#aFBgIL3Im').val(aFBgIL3Attach.url).change();
    });
    aFBgIL3ImUp.open();
  });

  $('#aFBgIL4ImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgIL4ImUp){aFBgIL4ImUp.open();return;}
    aFBgIL4ImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Layer 4 Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgIL4ImUp.on('select', function() {
      aFBgIL4Attach = aFBgIL4ImUp.state().get('selection').first().toJSON();
      $('#aFBgIL4Im').val(aFBgIL4Attach.url).change();
    });
    aFBgIL4ImUp.open();
  });

  $('#aFBgVideoBttn').click(function(e) {
    e.preventDefault();
    if(aFBgVideoUp){aFBgVideoUp.open();return;}
    aFBgVideoUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Video File',
      button:{text:'Choose Video File'},
      multiple: false
    });
    aFBgVideoUp.on('select', function() {
      aFBgVidAttach = aFBgVideoUp.state().get('selection').first().toJSON();
      $('#aFBgVideo').val(aFBgVidAttach.url).change();
    });
    aFBgVideoUp.open();
  });

  $('#aFBgVido2Bttn').click(function(e) {
    e.preventDefault();
    if(aFBgVido2Up){aFBgVido2Up.open();return;}
    aFBgVido2Up = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Video File (Alternate Format)',
      button:{text:'Choose Video File'},
      multiple: false
    });
    aFBgVido2Up.on('select', function() {
      aFBgVid2Attach = aFBgVido2Up.state().get('selection').first().toJSON();
      $('#aFBgVido2').val(aFBgVid2Attach.url).change();
    });
    aFBgVido2Up.open();
  });

  $('#aFBgOvrImBttn').click(function(e) {
    e.preventDefault();
    if(aFBgOvrImUp){aFBgOvrImUp.open();return;}
    aFBgOvrImUp = wp.media.frames.file_frame = wp.media({
      frame: 'select',
      title: 'Choose Overlay Image',
      button:{text:'Choose Image'},
      multiple: false
    });
    aFBgOvrImUp.on('select', function() {
      aFBgOvrAttach = aFBgOvrImUp.state().get('selection').first().toJSON();
      $('#aFBgOvrIm').val(aFBgOvrAttach.url).change();
    });
    aFBgOvrImUp.open();
  });

});
