(function($) {
  $(document).ready(function() {

    $('*[id*="Co"]').live('change',function(){
      $(this).parents('section').find('.preview').css('background-color',$(this).val());
    });

    $('*[id*="Gr"]').live('change',function(){
      if ($(this).parents('section').find('*[id*="Im"]').val() == ''){
        if($(this).val() != ''){
          $(this).parents('section').find('.preview').css('background-image',$(this).val());
        }
        else {
          $(this).parents('section').find('.preview').css('background-image','');
        }
      }
    });

    $('*[id*="Im"]').live('change',function(){
      if ($(this).val() != ''){
        imval = "url(" + $(this).val() + ")";
        $(this).parents('section').find('.preview').css('background-image',imval);
      }
      else if($(this).parents('section').find('*[id*="Gr"]').val() != ''){
        grval = $(this).parents('section').find('*[id*="Gr"]').val();
        $(this).parents('section').find('.preview').css('background-image',grval);
      }
      else {
        $(this).parents('section').find('.preview').css('background-image','');
      }
    });

    $('*[id*="Fx"]').live('change',function(){
      if (this.checked){
        $(this).parents('section').find('.preview').css('background-attachment','fixed');
      }
      else {
        $(this).parents('section').find('.preview').css('background-attachment','');
      }
    });

    $('*[id*="Fl"]').live('change',function() {
      var sel = $(this).children("option:selected").val();
      if(sel == 'contain'){
        fill = 'contain';
      }
      else if(sel == 'actual'){
        fill = 'auto';
      }
      else if(sel == 'stretch'){
        fill = '100% 100%';
      }
      else{
        fill = 'cover';
      }
      $(this).parents('section').find('.preview').css('background-size',fill);
    });

    $('*[id*="Rp"]').live('change',function() {
      var sel = $(this).children("option:selected").val();
      if(sel == 'r'){
        repeat = 'repeat';
      }
      else if(sel == 'x'){
        repeat = 'repeat-x';
      }
      else if(sel == 'y'){
        repeat = 'repeat-y';
      }
      else{
        repeat = 'no-repeat';
      }
      $(this).parents('section').find('.preview').css('background-repeat',repeat);
    });

    $('*[id*="Op"]').live('change',function(){
      $(this).parents('section').find('.preview').css('opacity',$(this).val()).change();
    });

    $('*[id*="XY"]').live('change',function() {
      var sel = $(this).val();
      if(sel === 'lt' || sel === 'lm' || sel === 'lb'){
        xp = "0%";
      }
      else if(sel === 'rt' || sel === 'rm' || sel === 'rb'){
        xp = "100%";
      }
      else{
        xp = "50%";
      }
      if(sel === 'lt' || sel === 'ct' || sel === 'rt'){
        yp = "0%";
      }
      else if(sel === 'lb' || sel === 'cb' || sel === 'rb'){
        yp = "100%";
      }
      else{
        yp = "50%";
      }
      xpyp = xp + " " + yp;
      $(this).parents('section').find('.preview').css('background-position',xpyp);
    });

    $('.aFclearbttn').click(function(e){
      e.preventDefault();
      var labl = $(this).parents('label');
      var isnxt = $(labl).next();
      if($(isnxt).is("input[type=text]") || $(isnxt).is("input[type=number]") || $(isnxt).is("textarea")){
        var inpt = $(labl).next();
      }
      else {
        var inpt = $(labl).next().find("input[type=text],input[type=number],textarea");
      }
      $(inpt).val("").change();
    })

    $('.aFswatches li span').click(function(){
      var txtarea = $(this).parents('.aFswatches').prev('*[id*="Gr"]');
      var grad = $(this).css('background-image');
      $(txtarea).val(grad);
      $(txtarea).change();
    })

	});
})(jQuery);
