<?php
/*
    M E T A B O X E S
    =================
*/

/*
    Metabox for Posts and Other Content Types
*/
add_action('add_meta_boxes','aFBgPostMetaBox');
function aFBgPostMetaBox(){
  $args = array(
   'public'   => true
  );
  $post_types = get_post_types($args);
  add_meta_box('aFBgMetaBox','Background','aFBgPMB',$post_types,'side','low');
}
function aFBgPMB($post){
  $values = get_post_custom($post->ID);
  $aFBg_select = isset($values['aFBg_select']) ? esc_attr($values['aFBg_select'][0]) : '';
  wp_nonce_field('aFBg_nonce','aFBg_mb_nonce');
  $args = array(
  	'posts_per_page'   => -1,
	  'offset'           => 0,
  	'orderby'          => 'title',
  	'order'            => 'ASC',
  	'post_type'=>'background',
  	'post_status'      => 'publish'
  );
  $aFBgEntries = get_posts($args); ?>
    <div>
<?php if(!empty($aFBgEntries) && !is_wp_error($aFBgEntries)){ ?>
      <label for="aFBg_select" title="Choose one of the Backgrounds you created.">Choose a Background: </label>
      <select id="aFBg_select" name="aFBg_select">
<?php   foreach($aFBgEntries as $aFBgEntry){
          if($aFBgEntry->ID==$aFBg_select)
          {
            $selected = ' selected="selected"';
          }else{
            $selected = '';
          } ?>
        <option value="<?php echo $aFBgEntry->ID; ?>"<?php echo $selected; ?>><?php echo $aFBgEntry->post_title; ?></option>
<?php   } ?>
        <option value=""<?php if(empty($aFBg_select)){ echo ' selected="selected"';} ?>>None</option>
      </select>
      <?php
      }
      else{ ?>
        <label for="aFBg_select" title="Create a Background and select it here.">Background: </label>
        <select id="aFBg_select" name="aFBg_select">
          <option value=""<?php if(empty($aFBg_select)){ echo ' selected="selected"';} ?>>None</option>
        </select>
<?php } ?>
    </div>
<?php }
add_action('save_post', 'aFBgPst_save');
function aFBgPst_save( $post_id ){
  if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)return;
  if(!isset($_POST['aFBg_mb_nonce'])||!wp_verify_nonce($_POST['aFBg_mb_nonce'],'aFBg_nonce'))return;
  if(!current_user_can('edit_post')) return;
  if(isset($_POST['aFBg_select']))
    update_post_meta($post_id,'aFBg_select',$_POST['aFBg_select']);
}

/*
    Metabox for Backgrounds
*/
add_action('add_meta_boxes','aFBgMetaBox');
function aFBgMetaBox(){
  $post_types = array('background');
  add_meta_box('aFBgMetaBox','Background Settings','aFBgBMB',$post_types,'normal','high');
}
function aFBgBMB($post)
{
  global $aFBgVarList;
  $aFBgVals = get_post_custom($post->ID);
  foreach ($aFBgVarList as $opt => $value){
    $value = "aFBg".$value;
    $$value = isset($aFBgVals[$value]) ? $aFBgVals[$value][0] :'';
  }
  wp_nonce_field('aFBgNonce','aFBgMBNonce');
  function aFBgSetPreview($color=NULL,$grad=NULL,$img=NULL,$fill=NULL,$repeat=NULL,$opac=NULL,$xy=NULL,$fixed=NULL){
    $css = '';
    if($grad){
      $css = $css     .'background-image: '.$grad.'; ';
    }
    if($color){
      $css = $css     .'background-color: '.$color.'; ';
    }
    if($opac){
      $css = $css     .'opacity: '.$opac.'; ';
    }
    if($img){
      $css = $css     .'background-image: url('.$img.'); ';
    }
    if($fill === 'contain'){
      $css = $css     .'background-size: contain; ';
    }
    elseif($fill === 'actual'){
      $css = $css     .'background-size: auto; ';
    }
    elseif($fill === 'stretch'){
      $css = $css     .'background-size: 100% 100%; ';
    }
    else{
      $css = $css     .'background-size: cover; ';
    }
    if($repeat === 'r'){
      $css = $css     .'background-repeat: repeat; ';
    }
    elseif($repeat === 'x'){
      $css = $css     .'background-repeat: repeat-x; ';
    }
    elseif($repeat === 'y'){
      $css = $css     .'background-repeat: repeat-y; ';
    }
    else{
      $css = $css     .'background-repeat: no-repeat; ';
    }
    $xp = "";
    $yp = "";
    if($xy === 'lt' || $xy === 'lm' || $xy === 'lb'){
      $xp = "0%";
    }
    elseif($xy === 'rt' || $xy === 'rm' || $xy === 'rb'){
      $xp = "100%";
    }
    else{
      $xp = "50%";
    }
    if($xy === 'lt' || $xy === 'ct' || $xy === 'rt'){
      $yp = "0%";
    }
    elseif($xy === 'lb' || $xy === 'cb' || $xy === 'rb'){
      $yp = "100%";
    }
    else{
      $yp = "50%";
    }
    $css = $css       .'background-position: '.$xp.' '.$yp.'; ';
    if($fixed){
      $css = $css     .'background-attachment: fixed;';
    }
    else{
      $css = $css     .'background-attachment: auto;';
    }
    echo 'style="'.$css.'"';
  }
  function aFBgSwatches() { ?>
    <ul class="aFswatches">
      <li><span style="background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%);"></span></li>
      <li><span style="background: linear-gradient(0deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 66%);"></span></li>
      <li><span style="background: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 66%);"></span></li>
      <li><span style="background: radial-gradient(rgba(102,25,0,0) 10%, rgba(102,25,0,0.05) 30%, rgba(102,25,0,0.15) 50%, rgba(102,25,0,0.5) 100%);"></span></li>
      <li><span style="background: linear-gradient(45deg, #f7797d, #FBD786, #C6FFDD)"></span></li>
      <li><span style="background: linear-gradient(300deg, #3E5151, #DECBA4);"></span></li>
      <li><span style="background: linear-gradient(to bottom, #0f2027, #203a43, #2c5364);"></span></li>
      <li><span style="background: linear-gradient(to right, rgba(0,26,40,1) 0%, rgba(101,55,76,1) 46%, rgba(255,79,79,1) 100%);"></span></li>
      <li><span style="background: linear-gradient(217deg, rgba(255,0,0,1), rgba(255,0,0,0) 70.71%), linear-gradient(127deg, rgba(0,255,0,1), rgba(0,255,0,0) 70.71%), linear-gradient(336deg, rgba(0,0,255,1), rgba(0,0,255,0) 70.71%);"></span></li>
      <li><span style="background: repeating-linear-gradient(-45deg, transparent, transparent 1%, black 1%, black 2%);"></span></li>
    </ul>
<?php } ?>
    <h3 class="wp-ui-text-highlight">General Settings
    </h3>
    <br>
    <label for="aFBgKills" class="labelcss">Kill Background(s) on:</label>
    <input id="aFBgKills" type="text" class="full-text" size="36" name="aFBgKills" value="<?php echo $aFBgKills; ?>" />
    <span class="description"></span> <span class="dashicons dashicons-info" title="Add CSS selectors, seperated by commas, to remove their backgrounds."></span>
    <br>
    <label for="aFBgPrllx" class="labelcss">Parallax:</label>
    <input id="aFBgPrllx" class="aFswitch aFprllxpndr" type="checkbox" name="aFBgPrllx" value="1" <?php checked($aFBgPrllx,'1'); ?> />
    <label class="aFswitch wp-core-ui" for="aFBgPrllx">
      <span class="aFswitch" style="display:none;">
        <b data-default="1" class="wp-ui-highlight">OFF</b>
        <b data-default="0" class="wp-ui-notification">ON</b>
      </span>
    </label>
    <section id="body-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgBdyCo,$aFBgBdyGr,$aFBgBdyIm,$aFBgBdyFl,$aFBgBdyRp,'',$aFBgBdyXY,$aFBgBdyFx); ?>></div>
      <input id="aFBgBdyOn" class="aFtoggle-indicator aFexpandr" style="display:none;" type="checkbox" name="aFBgBdyOn" value="1" <?php checked($aFBgBdyOn,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Body
        <label for="aFBgBdyOn">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgBdyImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgBdyIm" type="text" class="aFsrcfield full-text" size="36" name="aFBgBdyIm" value="<?php echo $aFBgBdyIm; ?>" /><br>
          <input id="aFBgBdyImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title="Choose/upload an image to be used as a background. Applies to the &lt;body&gt; element.."></span>
        </div>
        <br>
        <label for="aFBgBdyFl" class="labelcss">Fill:</label>
        <select id="aFBgBdyFl" name="aFBgBdyFl">
          <option value=""<?php         echo ($aFBgBdyFl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgBdyFl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgBdyFl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgBdyFl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgBdyRp" class="labelcss">Repeat:</label>
        <select id="aFBgBdyRp" name="aFBgBdyRp">
          <option value=""<?php         echo ($aFBgBdyRp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgBdyRp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgBdyRp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgBdyRp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgBdyFx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
        <div class="fieldcss">
          <input id="aFBgBdyFx" class="aFswitch" type="checkbox" name="aFBgBdyFx" value="1" <?php checked($aFBgBdyFx,'1'); ?> />
          <label class="aFswitch wp-core-ui" for="aFBgBdyFx">
            <span class="aFswitch" style="display:none;">
              <b data-default="1" class="wp-ui-highlight">SCROLL</b>
              <b data-default="0" class="wp-ui-notification">FIXED</b>
            </span>
          </label>
        </div>
        <br>
        <label for="aFBgBdyGr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgBdyGr" class="large-text" name="aFBgBdyGr" rows="5"><?php echo $aFBgBdyGr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgBdyCo" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgBdyCo" type="text" class="full-text aFcolorpikr" size="36" name="aFBgBdyCo" value="<?php echo $aFBgBdyCo; ?>" />
        <br>
        <br>
        <label for="aFBgBdyXY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYLT" value="lt" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'lt'); ?> />
          <label for="aFBgBdyXYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYCT" value="ct" <?php if(isset(  $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'ct'); ?> />
          <label for="aFBgBdyXYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYRT" value="rt" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'rt'); ?> />
          <label for="aFBgBdyXYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYLC" value="lm" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'lm'); ?> />
          <label for="aFBgBdyXYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYCC" value="cm" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'cm'); ?> />
          <label for="aFBgBdyXYCC">Center Center </label>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYRC" value="rm" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'rm'); ?> />
          <label for="aFBgBdyXYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYLB" value="lb" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'lb'); ?> />
          <label for="aFBgBdyXYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYCB" value="cb" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'cb'); ?> />
          <label for="aFBgBdyXYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgBdyXY" id="aFBgBdyXYRB" value="rb" <?php if(isset( $aFBgVals['aFBgBdyXY']))checked($aFBgVals['aFBgBdyXY'][0],'rb'); ?> />
          <label for="aFBgBdyXYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgBdyHL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgBdyHL" class="large-text" style="resize:both;" name="aFBgBdyHL" rows="5"><?php echo $aFBgBdyHL; ?></textarea>
          <br>
        </div>
        <br>
        <label for="aFBgClass" class="labelcss">Add CSS Class:</label>
        <input id="aFBgClass" type="text" class="full-text" style="margin-bottom: 16px;" size="36" name="aFBgClass" value="<?php echo $aFBgClass; ?>" />
        <span class="description"></span> <span class="dashicons dashicons-info" title="Add CSS classes to the body element. Seperate multiple classes by space."></span>
        <br>
      </div>
    </section>
    <section id="video-settings" class="wp-core-ui">
      <input id="aFBgVidOn" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgVidOn" value="1" <?php checked($aFBgVidOn,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Video
        <label for="aFBgVidOn">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <div class="aFprllxcntrx">
          <label for="aFBgVidSn" class="labelcss">On Scroll, Stick to<span class="aFhideTxt"> Sreen</span>:</label>
          <div class="fieldcss">
            <input id="aFBgVidSn" class="aFswitch" type="checkbox" name="aFBgVidSn" value="1" <?php checked($aFBgVidSn,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgVidSn">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">PAGE</b>
                <b data-default="0" class="wp-ui-notification">SCREEN</b>
              </span>
            </label>
            <span class="dashicons dashicons-info" title="On scroll, stick this layer to the screen."></span>
          </div>
        </div>
        <div class="aFprllxpnds">
          <label for="aFBgVid_Z" class="labelcss">Z Position:</label>
          <div class="fieldcss">
            <input id="aFBgVid_Z" type="number" class="small-text" size="3" name="aFBgVid_Z" value="<?php echo $aFBgVid_Z; ?>" />
            <span class="description">Default for this layer: 1 </span> <span class="dashicons dashicons-info" title="For parallax effect. Numerical value only."></span>
          </div>
        </div>
        <label for="aFBgVideoBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgVideo" type="text" class="full-text" size="36" name="aFBgVideo" value="<?php echo $aFBgVideo; ?>" />
          <input id="aFBgVideoBttn" type="button" class="button" value="Choose Video" /><br> <span class="dashicons dashicons-info" title="Choose/upload video to be used as a background. Displays behind the video, hence it will be partially or entirely hidden."></span>
        </div>
        <br>
        <label for="aFBgVidAlBttn" class="labelcss">Alternate Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgVidAl" type="text" class="full-text" size="36" name="aFBgVidAl" value="<?php echo $aFBgVidAl; ?>" />
          <input id="aFBgVidAlBttn" type="button" class="button" value="Choose Video" /><br>
          <span class="description">Choose/upload video in a different format.</span>
        </div>
        <br>
        <label for="aFBgVidFl" class="labelcss">Fill:</label>
        <select id="aFBgVidFl" name="aFBgVidFl">
          <option value=""<?php         echo ($aFBgVidFl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgVidFl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgVidFl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgVidFl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgVidGr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgVidGr" class="large-text" name="aFBgVidGr" rows="5"><?php echo $aFBgVidGr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgVidCo" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgVidCo" type="text" class="full-text aFcolorpikr" size="36" name="aFBgVidCo" value="<?php echo $aFBgVidCo; ?>" />
        <br>
        <label for="aFBgVidOp" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgVidOp" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgVidOp" value="<?php echo $aFBgVidOp; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgVidXY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYLT" value="lt" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'lt'); ?> />
          <label for="aFBgVidXYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYCT" value="ct" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'ct'); ?> />
          <label for="aFBgVidXYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYRT" value="rt" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'rt'); ?> />
          <label for="aFBgVidXYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYLC" value="lm" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'lm'); ?> />
          <label for="aFBgVidXYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYCC" value="cm" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'cm'); ?> />
          <label for="aFBgVidXYCC">Center Center </label>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYRC" value="rm" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'rm'); ?> />
          <label for="aFBgVidXYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYLB" value="lb" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'lb'); ?> />
          <label for="aFBgVidXYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYCB" value="cb" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'cb'); ?> />
          <label for="aFBgVidXYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgVidXY" id="aFBgVidXYRB" value="rb" <?php if(isset( $aFBgVals['aFBgVidXY']))checked($aFBgVals['aFBgVidXY'][0],'rb'); ?> />
          <label for="aFBgVidXYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgVidHL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgVidHL" class="large-text" style="resize:both;" name="aFBgVidHL" rows="5"><?php echo $aFBgVidHL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="image-1-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgIL1Co,$aFBgIL1Gr,$aFBgIL1Im,$aFBgIL1Fl,$aFBgIL1Rp,$aFBgIL1Op,$aFBgIL1XY,$aFBgIL1Fx); ?>></div>
      <input id="aFBgIL1On" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgIL1On" value="1" <?php checked($aFBgIL1On,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Image 1
        <label for="aFBgIL1On">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgIL1MH" class="labelcss">Match Height of Page:</label>
        <input id="aFBgIL1MH" type="checkbox" name="aFBgIL1MH" value="1" <?php checked($aFBgIL1MH,'1'); ?> class="checkcss aFcontractr" />
        <div class="aFcontract">
          <div class="aFprllxcntrx">
            <label for="aFBgIL1Sn" class="labelcss">On Scroll, Stick to<span class="aFhideTxt"> Sreen</span>:</label>
            <div class="fieldcss">
              <input id="aFBgIL1Sn" class="aFswitch" type="checkbox" name="aFBgIL1Sn" value="1" <?php checked($aFBgIL1Sn,'1'); ?> />
              <label class="aFswitch wp-core-ui" for="aFBgIL1Sn">
                <span class="aFswitch" style="display:none;">
                  <b data-default="1" class="wp-ui-highlight">PAGE</b>
                  <b data-default="0" class="wp-ui-notification">SCREEN</b>
                </span>
              </label>
              <span class="dashicons dashicons-info" title="On scroll, stick this layer to the screen."></span>
            </div>
          </div>
          <div class="aFprllxpnds">
            <label for="aFBgIL1_Z" class="labelcss">Z Position:</label>
            <div class="fieldcss">
              <input id="aFBgIL1_Z" type="number" class="small-text" size="3" name="aFBgIL1_Z" value="<?php echo $aFBgIL1_Z; ?>" />
              <span class="description">Default for this layer: 11 </span> <span class="dashicons dashicons-info" title="For parallax effect. Numerical value only."></span>
            </div>
          </div>
        </div>
        <label for="aFBgIL1ImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgIL1Im" type="text" class="aFsrcfield full-text" size="36" name="aFBgIL1Im" value="<?php echo $aFBgIL1Im; ?>" />
          <input id="aFBgIL1ImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title="Choose/upload an image to be used as a background. Applies to the 1st layer."></span>
        </div>
        <br>
        <label for="aFBgIL1Ob" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgIL1Ob" type="checkbox" name="aFBgIL1Ob" value="1" <?php checked($aFBgIL1Ob,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgIL1Fx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgIL1Fx" class="aFswitch" type="checkbox" name="aFBgIL1Fx" value="1" <?php checked($aFBgIL1Fx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgIL1Fx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <label for="aFBgIL1Fl" class="labelcss">Fill:</label>
        <select id="aFBgIL1Fl" name="aFBgIL1Fl">
          <option value=""<?php         echo ($aFBgIL1Fl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgIL1Fl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgIL1Fl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgIL1Fl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgIL1Rp" class="labelcss">Repeat:</label>
        <select id="aFBgIL1Rp" name="aFBgIL1Rp">
          <option value=""<?php         echo ($aFBgIL1Rp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgIL1Rp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgIL1Rp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgIL1Rp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgIL1Gr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgIL1Gr" class="large-text" name="aFBgIL1Gr" rows="5"><?php echo $aFBgIL1Gr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgIL1Co" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgIL1Co" type="text" class="full-text aFcolorpikr" size="36" name="aFBgIL1Co" value="<?php echo $aFBgIL1Co; ?>" />
        <br>
        <label for="aFBgIL1Op" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgIL1Op" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgIL1Op" value="<?php echo $aFBgIL1Op; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgIL1XY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYLT" value="lt" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'lt'); ?> />
          <label for="aFBgIL1XYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYCT" value="ct" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'ct'); ?> />
          <label for="aFBgIL1XYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYRT" value="rt" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'rt'); ?> />
          <label for="aFBgIL1XYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYLC" value="lm" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'lm'); ?> />
          <label for="aFBgIL1XYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYCC" value="cm" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'cm'); ?> />
          <label for="aFBgIL1XYCC">Center Center </label>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYRC" value="rm" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'rm'); ?> />
          <label for="aFBgIL1XYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYLB" value="lb" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'lb'); ?> />
          <label for="aFBgIL1XYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYCB" value="cb" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'cb'); ?> />
          <label for="aFBgIL1XYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgIL1XY" id="aFBgIL1XYRB" value="rb" <?php if(isset( $aFBgVals['aFBgIL1XY']))checked($aFBgVals['aFBgIL1XY'][0],'rb'); ?> />
          <label for="aFBgIL1XYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgIL1HL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgIL1HL" class="large-text" style="resize:both;" name="aFBgIL1HL" rows="5"><?php echo $aFBgIL1HL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="image-2-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgIL2Co,$aFBgIL2Gr,$aFBgIL2Im,$aFBgIL2Fl,$aFBgIL2Rp,$aFBgIL2Op,$aFBgIL2XY,$aFBgIL2Fx); ?>></div>
      <input id="aFBgIL2On" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgIL2On" value="1" <?php checked($aFBgIL2On,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Image 2
        <label for="aFBgIL2On">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <label for="aFBgIL2MH" class="labelcss">Match Height of Page:</label>
        <input id="aFBgIL2MH" type="checkbox" name="aFBgIL2MH" value="1" <?php checked($aFBgIL2MH,'1'); ?> class="checkcss aFcontractr" />
        <div class="aFcontract">
          <div class="aFprllxcntrx">
            <label for="aFBgIL2Sn" class="labelcss">On Scroll, Stick to<span class="aFhideTxt"> Sreen</span>:</label>
            <div class="fieldcss">
              <input id="aFBgIL2Sn" class="aFswitch" type="checkbox" name="aFBgIL2Sn" value="1" <?php checked($aFBgIL2Sn,'1'); ?> />
              <label class="aFswitch wp-core-ui" for="aFBgIL2Sn">
                <span class="aFswitch" style="display:none;">
                  <b data-default="1" class="wp-ui-highlight">PAGE</b>
                  <b data-default="0" class="wp-ui-notification">SCREEN</b>
                </span>
              </label>
              <span class="dashicons dashicons-info" title="On scroll, stick this layer to the screen."></span>
            </div>
          </div>
          <div class="aFprllxpnds">
            <label for="aFBgIL2_Z" class="labelcss">Z Position:</label>
            <div class="fieldcss">
              <input id="aFBgIL2_Z" type="number" class="small-text" size="3" name="aFBgIL2_Z" value="<?php echo $aFBgIL2_Z; ?>" />
              <span class="description">Default for this layer: 7 </span> <span class="dashicons dashicons-info" title="For parallax effect. Numerical value only."></span>
            </div>
          </div>
        </div>
        <br>
        <label for="aFBgIL2ImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgIL2Im" type="text" class="aFsrcfield full-text" size="36" name="aFBgIL2Im" value="<?php echo $aFBgIL2Im; ?>" />
          <input id="aFBgIL2ImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title="Choose/upload an image to be used as a background. Applies to the 2st layer."></span>
        </div>
        <br>
        <label for="aFBgIL2Ob" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgIL2Ob" type="checkbox" name="aFBgIL2Ob" value="1" <?php checked($aFBgIL2Ob,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgIL2Fx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgIL2Fx" class="aFswitch" type="checkbox" name="aFBgIL2Fx" value="1" <?php checked($aFBgIL2Fx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgIL2Fx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <label for="aFBgIL2Fl" class="labelcss">Fill:</label>
        <select id="aFBgIL2Fl" name="aFBgIL2Fl">
          <option value=""<?php         echo ($aFBgIL2Fl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgIL2Fl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgIL2Fl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgIL2Fl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgIL2Rp" class="labelcss">Repeat:</label>
        <select id="aFBgIL2Rp" name="aFBgIL2Rp">
          <option value=""<?php         echo ($aFBgIL2Rp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgIL2Rp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgIL2Rp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgIL2Rp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgIL2Gr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgIL2Gr" class="large-text" name="aFBgIL2Gr" rows="5"><?php echo $aFBgIL2Gr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgIL2Co" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgIL2Co" type="text" class="full-text aFcolorpikr" size="36" name="aFBgIL2Co" value="<?php echo $aFBgIL2Co; ?>" />
        <br>
        <label for="aFBgIL2Op" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgIL2Op" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgIL2Op" value="<?php echo $aFBgIL2Op; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgIL2XY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYLT" value="lt" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'lt'); ?> />
          <label for="aFBgIL2XYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYCT" value="ct" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'ct'); ?> />
          <label for="aFBgIL2XYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYRT" value="rt" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'rt'); ?> />
          <label for="aFBgIL2XYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYLC" value="lm" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'lm'); ?> />
          <label for="aFBgIL2XYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYCC" value="cm" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'cm'); ?> />
          <label for="aFBgIL2XYCC">Center Center </label>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYRC" value="rm" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'rm'); ?> />
          <label for="aFBgIL2XYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYLB" value="lb" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'lb'); ?> />
          <label for="aFBgIL2XYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYCB" value="cb" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'cb'); ?> />
          <label for="aFBgIL2XYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgIL2XY" id="aFBgIL2XYRB" value="rb" <?php if(isset( $aFBgVals['aFBgIL2XY']))checked($aFBgVals['aFBgIL2XY'][0],'rb'); ?> />
          <label for="aFBgIL2XYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgIL2HL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgIL2HL" class="large-text" style="resize:both;" name="aFBgIL2HL" rows="5"><?php echo $aFBgIL2HL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="image-3-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgIL3Co,$aFBgIL3Gr,$aFBgIL3Im,$aFBgIL3Fl,$aFBgIL3Rp,$aFBgIL3Op,$aFBgIL3XY,$aFBgIL3Fx); ?>></div>
      <input id="aFBgIL3On" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgIL3On" value="1" <?php checked($aFBgIL3On,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Image 3
        <label for="aFBgIL3On">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgIL3MH" class="labelcss">Match Height of Page:</label>
        <input id="aFBgIL3MH" type="checkbox" name="aFBgIL3MH" value="1" <?php checked($aFBgIL3MH,'1'); ?> class="checkcss aFcontractr" />
        <div class="aFcontract">
          <div class="aFprllxcntrx">
            <label for="aFBgIL3Sn" class="labelcss">On Scroll, Stick to<span class="aFhideTxt"> Sreen</span>:</label>
            <div class="fieldcss">
              <input id="aFBgIL3Sn" class="aFswitch" type="checkbox" name="aFBgIL3Sn" value="1" <?php checked($aFBgIL3Sn,'1'); ?> />
              <label class="aFswitch wp-core-ui" for="aFBgIL3Sn">
                <span class="aFswitch" style="display:none;">
                  <b data-default="1" class="wp-ui-highlight">PAGE</b>
                  <b data-default="0" class="wp-ui-notification">SCREEN</b>
                </span>
              </label>
              <span class="dashicons dashicons-info" title="On scroll, stick this layer to the screen."></span>
            </div>
          </div>
          <div class="aFprllxpnds">
            <label for="aFBgIL3_Z" class="labelcss">Z Position:</label>
            <div class="fieldcss">
              <input id="aFBgIL3_Z" type="number" class="small-text" size="3" name="aFBgIL3_Z" value="<?php echo $aFBgIL3_Z; ?>" />
              <span class="description">Default for this layer: 3 </span> <span class="dashicons dashicons-info" title="For parallax effect. Numerical value only."></span>
            </div>
          </div>
        </div>
        <label for="aFBgIL3ImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgIL3Im" type="text" class="aFsrcfield full-text" size="36" name="aFBgIL3Im" value="<?php echo $aFBgIL3Im; ?>" />
          <input id="aFBgIL3ImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title="Choose/upload an image to be used as a background. Applies to the 3st layer."></span>
        </div>
        <br>
        <label for="aFBgIL3Ob" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgIL3Ob" type="checkbox" name="aFBgIL3Ob" value="1" <?php checked($aFBgIL3Ob,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgIL3Fx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgIL3Fx" class="aFswitch" type="checkbox" name="aFBgIL3Fx" value="1" <?php checked($aFBgIL3Fx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgIL3Fx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <label for="aFBgIL3Fl" class="labelcss">Fill:</label>
        <select id="aFBgIL3Fl" name="aFBgIL3Fl">
          <option value=""<?php         echo ($aFBgIL3Fl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgIL3Fl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgIL3Fl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgIL3Fl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgIL3Rp" class="labelcss">Repeat:</label>
        <select id="aFBgIL3Rp" name="aFBgIL3Rp">
          <option value=""<?php         echo ($aFBgIL3Rp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgIL3Rp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgIL3Rp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgIL3Rp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgIL3Gr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgIL3Gr" class="large-text" name="aFBgIL3Gr" rows="5"><?php echo $aFBgIL3Gr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgIL3Co" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgIL3Co" type="text" class="full-text aFcolorpikr" size="36" name="aFBgIL3Co" value="<?php echo $aFBgIL3Co; ?>" />
        <br>
        <label for="aFBgIL3Op" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgIL3Op" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgIL3Op" value="<?php echo $aFBgIL3Op; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgIL3XY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYLT" value="lt" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'lt'); ?> />
          <label for="aFBgIL3XYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYCT" value="ct" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'ct'); ?> />
          <label for="aFBgIL3XYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYRT" value="rt" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'rt'); ?> />
          <label for="aFBgIL3XYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYLC" value="lm" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'lm'); ?> />
          <label for="aFBgIL3XYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYCC" value="cm" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'cm'); ?> />
          <label for="aFBgIL3XYCC">Center Center </label>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYRC" value="rm" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'rm'); ?> />
          <label for="aFBgIL3XYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYLB" value="lb" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'lb'); ?> />
          <label for="aFBgIL3XYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYCB" value="cb" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'cb'); ?> />
          <label for="aFBgIL3XYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgIL3XY" id="aFBgIL3XYRB" value="rb" <?php if(isset( $aFBgVals['aFBgIL3XY']))checked($aFBgVals['aFBgIL3XY'][0],'rb'); ?> />
          <label for="aFBgIL3XYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgIL3HL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgIL3HL" class="large-text" style="resize:both;" name="aFBgIL3HL" rows="5"><?php echo $aFBgIL3HL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="image-4-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgIL4Co,$aFBgIL4Gr,$aFBgIL4Im,$aFBgIL4Fl,$aFBgIL4Rp,$aFBgIL4Op,$aFBgIL4XY,$aFBgIL4Fx); ?>></div>
      <input id="aFBgIL4On" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgIL4On" value="1" <?php checked($aFBgIL4On,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Image 4
        <label for="aFBgIL4On">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgIL4MH" class="labelcss">Match Height of Page:</label>
        <input id="aFBgIL4MH" type="checkbox" name="aFBgIL4MH" value="1" <?php checked($aFBgIL4MH,'1'); ?> class="checkcss aFcontractr" />
        <div class="aFcontract">
          <div class="aFprllxcntrx">
            <label for="aFBgIL4Sn" class="labelcss">On Scroll, Stick to<span class="aFhideTxt"> Sreen</span>:</label>
            <div class="fieldcss">
              <input id="aFBgIL4Sn" class="aFswitch" type="checkbox" name="aFBgIL4Sn" value="1" <?php checked($aFBgIL4Sn,'1'); ?> />
              <label class="aFswitch wp-core-ui" for="aFBgIL4Sn">
                <span class="aFswitch" style="display:none;">
                  <b data-default="1" class="wp-ui-highlight">PAGE</b>
                  <b data-default="0" class="wp-ui-notification">SCREEN</b>
                </span>
              </label>
              <span class="dashicons dashicons-info" title="On scroll, stick this layer to the screen."></span>
            </div>
          </div>
          <div class="aFprllxpnds">
            <label for="aFBgIL4_Z" class="labelcss">Z Position:</label>
            <div class="fieldcss">
              <input id="aFBgIL4_Z" type="number" class="small-text" size="3" name="aFBgIL4_Z" value="<?php echo $aFBgIL4_Z; ?>" />
              <span class="description">Default for this layer: 1 </span> <span class="dashicons dashicons-info" title="For parallax effect. Numerical value only."></span>
            </div>
          </div>
        </div>
        <label for="aFBgIL4ImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgIL4Im" type="text" class="aFsrcfield full-text" size="36" name="aFBgIL4Im" value="<?php echo $aFBgIL4Im; ?>" />
          <input id="aFBgIL4ImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title="Choose/upload an image to be used as a background. Applies to the 4st layer."></span>
        </div>
        <br>
        <label for="aFBgIL4Ob" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgIL4Ob" type="checkbox" name="aFBgIL4Ob" value="1" <?php checked($aFBgIL4Ob,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgIL4Fx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgIL4Fx" class="aFswitch" type="checkbox" name="aFBgIL4Fx" value="1" <?php checked($aFBgIL4Fx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgIL4Fx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <label for="aFBgIL4Fl" class="labelcss">Fill:</label>
        <select id="aFBgIL4Fl" name="aFBgIL4Fl">
          <option value=""<?php         echo ($aFBgIL4Fl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgIL4Fl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgIL4Fl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgIL4Fl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgIL4Rp" class="labelcss">Repeat:</label>
        <select id="aFBgIL4Rp" name="aFBgIL4Rp">
          <option value=""<?php         echo ($aFBgIL4Rp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgIL4Rp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgIL4Rp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgIL4Rp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgIL4Gr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgIL4Gr" class="large-text" name="aFBgIL4Gr" rows="5"><?php echo $aFBgIL4Gr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgIL4Co" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgIL4Co" type="text" class="full-text aFcolorpikr" size="36" name="aFBgIL4Co" value="<?php echo $aFBgIL4Co; ?>" />
        <br>
        <label for="aFBgIL4Op" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgIL4Op" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgIL4Op" value="<?php echo $aFBgIL4Op; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgIL4XY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYLT" value="lt" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'lt'); ?> />
          <label for="aFBgIL4XYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYCT" value="ct" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'ct'); ?> />
          <label for="aFBgIL4XYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYRT" value="rt" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'rt'); ?> />
          <label for="aFBgIL4XYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYLC" value="lm" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'lm'); ?> />
          <label for="aFBgIL4XYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYCC" value="cm" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'cm'); ?> />
          <label for="aFBgIL4XYCC">Center Center </label>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYRC" value="rm" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'rm'); ?> />
          <label for="aFBgIL4XYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYLB" value="lb" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'lb'); ?> />
          <label for="aFBgIL4XYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYCB" value="cb" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'cb'); ?> />
          <label for="aFBgIL4XYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgIL4XY" id="aFBgIL4XYRB" value="rb" <?php if(isset( $aFBgVals['aFBgIL4XY']))checked($aFBgVals['aFBgIL4XY'][0],'rb'); ?> />
          <label for="aFBgIL4XYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgIL4HL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgIL4HL" class="large-text" style="resize:both;" name="aFBgIL4HL" rows="5"><?php echo $aFBgIL4HL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="window-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgWinCo,$aFBgWinGr,$aFBgWinIm,$aFBgWinFl,$aFBgWinRp,$aFBgWinOp,$aFBgWinXY,$aFBgWinFx); ?>></div>
      <div class="aFpaneovrly"></div>
      <input id="aFBgWinOn" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgWinOn" value="1" <?php checked($aFBgWinOn,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Window
        <label for="aFBgWinOn">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgWinIm" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgWinIm" type="text" class="aFsrcfield full-text" size="36" name="aFBgWinIm" value="<?php echo $aFBgWinIm; ?>" />
          <input id="aFBgWinImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title='Choose/upload an image to be used as a background. Applies to the Window (a.k.a. "above the fold").'></span>
        </div>
        <br>
        <label for="aFBgWinHt" class="labelcss">Height:</label>
        <input id="aFBgWinHt" type="text" class="full-text" size="36" name="aFBgWinHt" value="<?php echo $aFBgWinHt; ?>" /> <span class="dashicons dashicons-info" title="Must include amount unit (e.g. 800px). Default: 100vh"></span>
        <br>
        <label for="aFBgWinOb" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgWinOb" type="checkbox" name="aFBgWinOb" value="1" <?php checked($aFBgWinOb,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgWinFx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgWinFx" class="aFswitch" type="checkbox" name="aFBgWinFx" value="1" <?php checked($aFBgWinFx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgWinFx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <br>
        <label for="aFBgWinFl" class="labelcss">Fill:</label>
        <select id="aFBgWinFl" name="aFBgWinFl">
          <option value=""<?php         echo ($aFBgWinFl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgWinFl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgWinFl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgWinFl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgWinRp" class="labelcss">Repeat:</label>
        <select id="aFBgWinRp" name="aFBgWinRp">
          <option value=""<?php         echo ($aFBgWinRp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgWinRp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgWinRp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgWinRp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgWinGr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgWinGr" class="large-text" name="aFBgWinGr" rows="5"><?php echo $aFBgWinGr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgWinCo" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgWinCo" type="text" class="full-text aFcolorpikr" size="36" name="aFBgWinCo" value="<?php echo $aFBgWinCo; ?>" />
        <br>
        <label for="aFBgWinOp" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgWinOp" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgWinOp" value="<?php echo $aFBgWinOp; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgWinXY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYLT" value="lt" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'lt'); ?> />
          <label for="aFBgWinXYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYCT" value="ct" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'ct'); ?> />
          <label for="aFBgWinXYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYRT" value="rt" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'rt'); ?> />
          <label for="aFBgWinXYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYLC" value="lm" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'lm'); ?> />
          <label for="aFBgWinXYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYCC" value="cm" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'cm'); ?> />
          <label for="aFBgWinXYCC">Center Center </label>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYRC" value="rm" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'rm'); ?> />
          <label for="aFBgWinXYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYLB" value="lb" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'lb'); ?> />
          <label for="aFBgWinXYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYCB" value="cb" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'cb'); ?> />
          <label for="aFBgWinXYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgWinXY" id="aFBgWinXYRB" value="rb" <?php if(isset( $aFBgVals['aFBgWinXY']))checked($aFBgVals['aFBgWinXY'][0],'rb'); ?> />
          <label for="aFBgWinXYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgWinHL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgWinHL" class="large-text" style="resize:both;" name="aFBgWinHL" rows="5"><?php echo $aFBgWinHL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="wall-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgWalCo,$aFBgWalGr,$aFBgWalIm,$aFBgWalFl,$aFBgWalRp,$aFBgWalOp,$aFBgWalXY,$aFBgWalFx); ?>></div>
      <input id="aFBgWalOn" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgWalOn" value="1" <?php checked($aFBgWalOn,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Wall
        <label for="aFBgWalOn">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgWalImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgWalIm" type="text" class="aFsrcfield full-text" size="36" name="aFBgWalIm" value="<?php echo $aFBgWalIm; ?>" />
          <input id="aFBgWalImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title='Choose/upload an image to be used as a background. Applies to the Wall (a.k.a. "below the fold").'></span>
        </div>
        <br>
        <label for="aFBgWalOb" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgWalOb" type="checkbox" name="aFBgWalOb" value="1" <?php checked($aFBgWalOb,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgWalFx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgWalFx" class="aFswitch" type="checkbox" name="aFBgWalFx" value="1" <?php checked($aFBgWalFx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgWalFx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <br>
        <label for="aFBgWalFl" class="labelcss">Fill:</label>
        <select id="aFBgWalFl" name="aFBgWalFl">
          <option value=""<?php         echo ($aFBgWalFl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgWalFl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgWalFl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgWalFl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgWalRp" class="labelcss">Repeat:</label>
        <select id="aFBgWalRp" name="aFBgWalRp">
          <option value=""<?php         echo ($aFBgWalRp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgWalRp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgWalRp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgWalRp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgWalGr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgWalGr" class="large-text" name="aFBgWalGr" rows="5"><?php echo $aFBgWalGr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgWalCo" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgWalCo" type="text" class="full-text aFcolorpikr" size="36" name="aFBgWalCo" value="<?php echo $aFBgWalCo; ?>" />
        <br>
        <label for="aFBgWalOp" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgWalOp" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgWalOp" value="<?php echo $aFBgWalOp; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgWalXY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYLT" value="lt" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'lt'); ?> />
          <label for="aFBgWalXYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYCT" value="ct" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'ct'); ?> />
          <label for="aFBgWalXYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYRT" value="rt" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'rt'); ?> />
          <label for="aFBgWalXYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYLC" value="lm" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'lm'); ?> />
          <label for="aFBgWalXYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYCC" value="cm" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'cm'); ?> />
          <label for="aFBgWalXYCC">Center Center </label>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYRC" value="rm" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'rm'); ?> />
          <label for="aFBgWalXYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYLB" value="lb" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'lb'); ?> />
          <label for="aFBgWalXYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYCB" value="cb" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'cb'); ?> />
          <label for="aFBgWalXYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgWalXY" id="aFBgWalXYRB" value="rb" <?php if(isset( $aFBgVals['aFBgWalXY']))checked($aFBgVals['aFBgWalXY'][0],'rb'); ?> />
          <label for="aFBgWalXYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgWalHL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgWalHL" class="large-text" style="resize:both;" name="aFBgWalHL" rows="5"><?php echo $aFBgWalHL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
    <section id="overlay-settings" class="wp-core-ui">
      <div class="preview" <?php aFBgSetPreview($aFBgOvrCo,$aFBgOvrGr,$aFBgOvrIm,$aFBgOvrFl,$aFBgOvrRp,$aFBgOvrOp,$aFBgOvrXY,$aFBgOvrFx); ?>></div>
      <input id="aFBgOvrOn" class="aFtoggle-indicator aFexpandr" type="checkbox" name="aFBgOvrOn" value="1" <?php checked($aFBgOvrOn,'1'); ?> aria-hidden="true" />
      <h3 class="wp-ui-text-highlight">Overlay
        <label for="aFBgOvrOn">
          <span class="toggle-indicator" aria-hidden="true"></span>
        </label>
      </h3>
      <div class="aFexpand">
        <br>
        <label for="aFBgOvrMH" class="labelcss">Match Height of Page:</label>
        <input id="aFBgOvrMH" type="checkbox" name="aFBgOvrMH" value="1" <?php checked($aFBgOvrMH,'1'); ?> class="checkcss aFcontractr" />
        <div class="aFcontract">
          <div class="aFprllxcntrx">
            <label for="aFBgOvrSn" class="labelcss">On Scroll, Stick to<span class="aFhideTxt"> Sreen</span>:</label>
            <div class="fieldcss">
              <input id="aFBgOvrSn" class="aFswitch" type="checkbox" name="aFBgOvrSn" value="1" <?php checked($aFBgOvrSn,'1'); ?> />
              <label class="aFswitch wp-core-ui" for="aFBgOvrSn">
                <span class="aFswitch" style="display:none;">
                  <b data-default="1" class="wp-ui-highlight">PAGE</b>
                  <b data-default="0" class="wp-ui-notification">SCREEN</b>
                </span>
              </label>
              <span class="dashicons dashicons-info" title="On scroll, stick this layer to the screen."></span>
            </div>
          </div>
        </div>
        <label for="aFBgOvrImBttn" class="labelcss">Source:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <input id="aFBgOvrIm" type="text" class="aFsrcfield full-text" size="36" name="aFBgOvrIm" value="<?php echo $aFBgOvrIm; ?>" />
          <input id="aFBgOvrImBttn" type="button" class="aFsrcbutton button" value="Choose Image" /> <span class="dashicons dashicons-info" title="Choose/upload an image to be used as a background. Applies to the 4st layer."></span>
        </div>
        <br>
        <label for="aFBgOvrOb" class="labelcss">Object: <span class="dashicons dashicons-info" title="Make this layer an embeded object."></span></label>
        <input id="aFBgOvrOb" type="checkbox" name="aFBgOvrOb" value="1" <?php checked($aFBgOvrOb,'1'); ?> class="checkcss aFexpandr aFcontractr" />
        <div class="aFcontract">
          <label for="aFBgOvrFx" class="labelcss"><span class="aFhideTxt">Fixed </span>Scrolling:</label>
          <div class="fieldcss">
            <input id="aFBgOvrFx" class="aFswitch" type="checkbox" name="aFBgOvrFx" value="1" <?php checked($aFBgOvrFx,'1'); ?> />
            <label class="aFswitch wp-core-ui" for="aFBgOvrFx">
              <span class="aFswitch" style="display:none;">
                <b data-default="1" class="wp-ui-highlight">SCROLL</b>
                <b data-default="0" class="wp-ui-notification">FIXED</b>
              </span>
            </label>
          </div>
        </div>
        <label for="aFBgOvrFl" class="labelcss">Fill:</label>
        <select id="aFBgOvrFl" name="aFBgOvrFl">
          <option value=""<?php         echo ($aFBgOvrFl==''       )?'selected':''; ?>>Cover</option>
          <option value="contain"<?php  echo ($aFBgOvrFl=='contain')?'selected':''; ?>>Contain</option>
          <option value="actual"<?php   echo ($aFBgOvrFl=='actual' )?'selected':''; ?>>Actual Size</option>
          <option value="stretch"<?php  echo ($aFBgOvrFl=='stretch')?'selected':''; ?>>Stretch</option>
        </select>
        <br>
        <label for="aFBgOvrRp" class="labelcss">Repeat:</label>
        <select id="aFBgOvrRp" name="aFBgOvrRp">
          <option value=""<?php         echo ($aFBgOvrRp==''       )?'selected':''; ?>>None</option>
          <option value="x"<?php  echo ($aFBgOvrRp=='x')?'selected':''; ?>>Horizontally</option>
          <option value="y"<?php   echo ($aFBgOvrRp=='y' )?'selected':''; ?>>Vertically</option>
          <option value="r"<?php  echo ($aFBgOvrRp=='r')?'selected':''; ?>>All</option>
        </select>
        <br>
        <label for="aFBgOvrGr" class="labelcss">Gradient:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <div class="fieldcss">
          <textarea id="aFBgOvrGr" class="large-text" name="aFBgOvrGr" rows="5"><?php echo $aFBgOvrGr; ?></textarea>
          <?php aFBgSwatches(); ?>
          <span class="description"><a href="https://cssgradient.io/" target="_blank">Gradient Generator<span class="dashicons dashicons-external" style="text-decoration: none;"> </span></a></span>
          <br>
        </div>
        <br>
        <label for="aFBgOvrCo" class="labelcss">Color:
          <div class="aFclearbttn dashicons-before dashicons-no-alt"></div>
        </label>
        <input id="aFBgOvrCo" type="text" class="full-text aFcolorpikr" size="36" name="aFBgOvrCo" value="<?php echo $aFBgOvrCo; ?>" />
        <br>
        <label for="aFBgOvrOp" class="labelcss">Opacity:
          <div class="aFclearbttn dashicons-before dashicons-no-alt" style="<?php echo $fieldcss; ?>"></div>
        </label>
        <input id="aFBgOvrOp" type="number" step="0.1" min="0" max="1" class="small-text" size="3" name="aFBgOvrOp" value="<?php echo $aFBgOvrOp; ?>" /> <span class="dashicons dashicons-info" title="1 = opaque, 0 = transparent. Default: 1"></span>
        <br>
        <label for="aFBgOvrXY" class="labelcss">X/Y Position:</label>
        <div class="fieldcss aFxycontrol">
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYLT" value="lt" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'lt'); ?> />
          <label for="aFBgOvrXYLT" class="dashicons dashicons-arrow-up-alt aFdiagcount">Top Left </label>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYCT" value="ct" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'ct'); ?> />
          <label for="aFBgOvrXYCT" class="dashicons dashicons-arrow-up-alt">Top Center </label>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYRT" value="rt" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'rt'); ?> />
          <label for="aFBgOvrXYRT" class="dashicons dashicons-arrow-up-alt aFdiagclock">Top Right </label>
          <br>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYLC" value="lm" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'lm'); ?> />
          <label for="aFBgOvrXYLC" class="dashicons dashicons-arrow-left-alt">Center Left </label>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYCC" value="cm" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'cm'); ?> />
          <label for="aFBgOvrXYCC">Center Center </label>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYRC" value="rm" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'rm'); ?> />
          <label for="aFBgOvrXYRC" class="dashicons dashicons-arrow-right-alt">Center Right </label>
          <br>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYLB" value="lb" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'lb'); ?> />
          <label for="aFBgOvrXYLB" class="dashicons dashicons-arrow-down-alt aFdiagclock">Bottom Left </label>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYCB" value="cb" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'cb'); ?> />
          <label for="aFBgOvrXYCB" class="dashicons dashicons-arrow-down-alt">Bottom Center </label>
          <input type="radio" name="aFBgOvrXY" id="aFBgOvrXYRB" value="rb" <?php if(isset( $aFBgVals['aFBgOvrXY']))checked($aFBgVals['aFBgOvrXY'][0],'rb'); ?> />
          <label for="aFBgOvrXYRB" class="dashicons dashicons-arrow-down-alt aFdiagcount">Bottom Right </label>
          <span class="aFxycoordinates wp-ui-highlight"></span>
        </div>
        <br>
        <label for="aFBgOvrHL" class="labelcss">Raw HTML:</label>
        <div class="fieldcss">
          <textarea id="aFBgOvrHL" class="large-text" style="resize:both;" name="aFBgOvrHL" rows="5"><?php echo $aFBgOvrHL; ?></textarea>
          <br>
        </div>
      </div>
    </section>
<?php }
add_action('save_post', 'aFBg_save');
function aFBg_save( $post_id ){
  if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)return;
  if(!isset($_POST['aFBgMBNonce'])||!wp_verify_nonce($_POST['aFBgMBNonce'],'aFBgNonce'))return;
  if(!current_user_can('edit_post')) return;

  // General
  if(isset($_POST['aFBgKills']))update_post_meta($post_id,'aFBgKills',$_POST['aFBgKills']);
  $aFBgCheck =(isset($_POST['aFBgPrllx']) && $_POST['aFBgPrllx'])? '1' : '';
    update_post_meta($post_id,'aFBgPrllx',$aFBgCheck);

  // Body
  $aFBgCheck =(isset($_POST['aFBgBdyOn']) && $_POST['aFBgBdyOn'])? '1' : '';
    update_post_meta($post_id,'aFBgBdyOn',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgVidFx']) && $_POST['aFBgVidFx'])? '1' : '';
    update_post_meta($post_id,'aFBgVidFx',$aFBgCheck);
  if(isset($_POST['aFBgClass']))update_post_meta($post_id,'aFBgClass',$_POST['aFBgClass']);
  if(isset($_POST['aFBgBdyIm']))update_post_meta($post_id,'aFBgBdyIm',$_POST['aFBgBdyIm']);
  if(isset($_POST['aFBgBdyGr']))update_post_meta($post_id,'aFBgBdyGr',$_POST['aFBgBdyGr']);
  if(isset($_POST['aFBgBdyCo']))update_post_meta($post_id,'aFBgBdyCo',$_POST['aFBgBdyCo']);
  if(isset($_POST['aFBgBdyFl']))update_post_meta($post_id,'aFBgBdyFl',$_POST['aFBgBdyFl']);
  $aFBgCheck =(isset($_POST['aFBgBdyFx']) && $_POST['aFBgBdyFx'])? '1' : '';
    update_post_meta($post_id,'aFBgBdyFx',$aFBgCheck);
  if(isset($_POST['aFBgBdyRp']))update_post_meta($post_id,'aFBgBdyRp',$_POST['aFBgBdyRp']);
  if(isset($_POST['aFBgBdyXY']))update_post_meta($post_id,'aFBgBdyXY',$_POST['aFBgBdyXY']);
  if(isset($_POST['aFBgBdyAY']))update_post_meta($post_id,'aFBgBdyAY',$_POST['aFBgBdyAY']);
  if(isset($_POST['aFBgBdyHL']))update_post_meta($post_id,'aFBgBdyHL',$_POST['aFBgBdyHL']);

  // Window
  $aFBgCheck =(isset($_POST['aFBgWinOn']) && $_POST['aFBgWinOn'])? '1' : '';
    update_post_meta($post_id,'aFBgWinOn',$aFBgCheck);
  if(isset($_POST['aFBgWinIm']))update_post_meta($post_id,'aFBgWinIm',$_POST['aFBgWinIm']);
  if(isset($_POST['aFBgWinGr']))update_post_meta($post_id,'aFBgWinGr',$_POST['aFBgWinGr']);
  if(isset($_POST['aFBgWinCo']))update_post_meta($post_id,'aFBgWinCo',$_POST['aFBgWinCo']);
  if(isset($_POST['aFBgWinOp']))update_post_meta($post_id,'aFBgWinOp',$_POST['aFBgWinOp']);
  if(isset($_POST['aFBgWinHt']))update_post_meta($post_id,'aFBgWinHt',$_POST['aFBgWinHt']);
  if(isset($_POST['aFBgWinFl']))update_post_meta($post_id,'aFBgWinFl',$_POST['aFBgWinFl']);
  $aFBgCheck =(isset($_POST['aFBgWinFx']) && $_POST['aFBgWinFx'])? '1' : '';
    update_post_meta($post_id,'aFBgWinFx',$aFBgCheck);
  if(isset($_POST['aFBgWinRp']))update_post_meta($post_id,'aFBgWinRp',$_POST['aFBgWinRp']);
  if(isset($_POST['aFBgWinXY']))update_post_meta($post_id,'aFBgWinXY',$_POST['aFBgWinXY']);
  if(isset($_POST['aFBgWinAY']))update_post_meta($post_id,'aFBgWinAY',$_POST['aFBgWinAY']);
  if(isset($_POST['aFBgWinHL']))update_post_meta($post_id,'aFBgWinHL',$_POST['aFBgWinHL']);

  // Wall
  $aFBgCheck =(isset($_POST['aFBgWalOn']) && $_POST['aFBgWalOn'])? '1' : '';
    update_post_meta($post_id,'aFBgWalOn',$aFBgCheck);
  if(isset($_POST['aFBgWalIm']))update_post_meta($post_id,'aFBgWalIm',$_POST['aFBgWalIm']);
  if(isset($_POST['aFBgWalGr']))update_post_meta($post_id,'aFBgWalGr',$_POST['aFBgWalGr']);
  if(isset($_POST['aFBgWalCo']))update_post_meta($post_id,'aFBgWalCo',$_POST['aFBgWalCo']);
  if(isset($_POST['aFBgWalOp']))update_post_meta($post_id,'aFBgWalOp',$_POST['aFBgWalOp']);
  if(isset($_POST['aFBgWalFl']))update_post_meta($post_id,'aFBgWalFl',$_POST['aFBgWalFl']);
  $aFBgCheck =(isset($_POST['aFBgWalFx']) && $_POST['aFBgWalFx'])? '1' : '';
    update_post_meta($post_id,'aFBgWalFx',$aFBgCheck);
  if(isset($_POST['aFBgWalRp']))update_post_meta($post_id,'aFBgWalRp',$_POST['aFBgWalRp']);
  if(isset($_POST['aFBgWalXY']))update_post_meta($post_id,'aFBgWalXY',$_POST['aFBgWalXY']);
  if(isset($_POST['aFBgWalAY']))update_post_meta($post_id,'aFBgWalAY',$_POST['aFBgWalAY']);
  if(isset($_POST['aFBgWalHL']))update_post_meta($post_id,'aFBgWalHL',$_POST['aFBgWalHL']);

  // Image Layer 1
  $aFBgCheck =(isset($_POST['aFBgIL1On']) && $_POST['aFBgIL1On'])? '1' : '';
    update_post_meta($post_id,'aFBgIL1On',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL1MH']) && $_POST['aFBgIL1MH'])? '1' : '';
    update_post_meta($post_id,'aFBgIL1MH',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL1Sn']) && $_POST['aFBgIL1Sn'])? '1' : '';
    update_post_meta($post_id,'aFBgIL1Sn',$aFBgCheck);
  if(isset($_POST['aFBgIL1Im']))update_post_meta($post_id,'aFBgIL1Im',$_POST['aFBgIL1Im']);
  $aFBgCheck =(isset($_POST['aFBgIL1Ob']) && $_POST['aFBgIL1Ob'])? '1' : '';
    update_post_meta($post_id,'aFBgIL1Ob',$aFBgCheck);
  if(isset($_POST['aFBgIL1Co']))update_post_meta($post_id,'aFBgIL1Co',$_POST['aFBgIL1Co']);
  if(isset($_POST['aFBgIL1Op']))update_post_meta($post_id,'aFBgIL1Op',$_POST['aFBgIL1Op']);
  if(isset($_POST['aFBgIL1Gr']))update_post_meta($post_id,'aFBgIL1Gr',$_POST['aFBgIL1Gr']);
  if(isset($_POST['aFBgIL1Fl']))update_post_meta($post_id,'aFBgIL1Fl',$_POST['aFBgIL1Fl']);
  $aFBgCheck =(isset($_POST['aFBgIL1Fx']) && $_POST['aFBgIL1Fx'])? '1' : '';
    update_post_meta($post_id,'aFBgIL1Fx',$aFBgCheck);
  if(isset($_POST['aFBgIL1Rp']))update_post_meta($post_id,'aFBgIL1Rp',$_POST['aFBgIL1Rp']);
  if(isset($_POST['aFBgIL1XY']))update_post_meta($post_id,'aFBgIL1XY',$_POST['aFBgIL1XY']);
  if(isset($_POST['aFBgIL1AY']))update_post_meta($post_id,'aFBgIL1AY',$_POST['aFBgIL1AY']);
  if(isset($_POST['aFBgIL1_Z']))update_post_meta($post_id,'aFBgIL1_Z',$_POST['aFBgIL1_Z']);
  if(isset($_POST['aFBgIL1HL']))update_post_meta($post_id,'aFBgIL1HL',$_POST['aFBgIL1HL']);

  // Image Layer 2
  $aFBgCheck =(isset($_POST['aFBgIL2On']) && $_POST['aFBgIL2On'])? '1' : '';
    update_post_meta($post_id,'aFBgIL2On',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL2MH']) && $_POST['aFBgIL2MH'])? '1' : '';
    update_post_meta($post_id,'aFBgIL2MH',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL2Sn']) && $_POST['aFBgIL2Sn'])? '1' : '';
    update_post_meta($post_id,'aFBgIL2Sn',$aFBgCheck);
  if(isset($_POST['aFBgIL2Im']))update_post_meta($post_id,'aFBgIL2Im',$_POST['aFBgIL2Im']);
  $aFBgCheck =(isset($_POST['aFBgIL2Ob']) && $_POST['aFBgIL2Ob'])? '1' : '';
    update_post_meta($post_id,'aFBgIL2Ob',$aFBgCheck);
  if(isset($_POST['aFBgIL2Co']))update_post_meta($post_id,'aFBgIL2Co',$_POST['aFBgIL2Co']);
  if(isset($_POST['aFBgIL2Op']))update_post_meta($post_id,'aFBgIL2Op',$_POST['aFBgIL2Op']);
  if(isset($_POST['aFBgIL2Gr']))update_post_meta($post_id,'aFBgIL2Gr',$_POST['aFBgIL2Gr']);
  if(isset($_POST['aFBgIL2Fl']))update_post_meta($post_id,'aFBgIL2Fl',$_POST['aFBgIL2Fl']);
  $aFBgCheck =(isset($_POST['aFBgIL2Fx']) && $_POST['aFBgIL2Fx'])? '1' : '';
    update_post_meta($post_id,'aFBgIL2Fx',$aFBgCheck);
  if(isset($_POST['aFBgIL2Rp']))update_post_meta($post_id,'aFBgIL2Rp',$_POST['aFBgIL2Rp']);
  if(isset($_POST['aFBgIL2XY']))update_post_meta($post_id,'aFBgIL2XY',$_POST['aFBgIL2XY']);
  if(isset($_POST['aFBgIL2AY']))update_post_meta($post_id,'aFBgIL2AY',$_POST['aFBgIL2AY']);
  if(isset($_POST['aFBgIL2_Z']))update_post_meta($post_id,'aFBgIL2_Z',$_POST['aFBgIL2_Z']);
  if(isset($_POST['aFBgIL2HL']))update_post_meta($post_id,'aFBgIL2HL',$_POST['aFBgIL2HL']);

  // Image Layer 3
  $aFBgCheck =(isset($_POST['aFBgIL3On']) && $_POST['aFBgIL3On'])? '1' : '';
    update_post_meta($post_id,'aFBgIL3On',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL3MH']) && $_POST['aFBgIL3MH'])? '1' : '';
    update_post_meta($post_id,'aFBgIL3MH',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL3Sn']) && $_POST['aFBgIL3Sn'])? '1' : '';
    update_post_meta($post_id,'aFBgIL3Sn',$aFBgCheck);
  if(isset($_POST['aFBgIL3Im']))update_post_meta($post_id,'aFBgIL3Im',$_POST['aFBgIL3Im']);
  $aFBgCheck =(isset($_POST['aFBgIL3Ob']) && $_POST['aFBgIL3Ob'])? '1' : '';
    update_post_meta($post_id,'aFBgIL3Ob',$aFBgCheck);
  if(isset($_POST['aFBgIL3Co']))update_post_meta($post_id,'aFBgIL3Co',$_POST['aFBgIL3Co']);
  if(isset($_POST['aFBgIL3Op']))update_post_meta($post_id,'aFBgIL3Op',$_POST['aFBgIL3Op']);
  if(isset($_POST['aFBgIL3Gr']))update_post_meta($post_id,'aFBgIL3Gr',$_POST['aFBgIL3Gr']);
  if(isset($_POST['aFBgIL3Fl']))update_post_meta($post_id,'aFBgIL3Fl',$_POST['aFBgIL3Fl']);
  $aFBgCheck =(isset($_POST['aFBgIL3Fx']) && $_POST['aFBgIL3Fx'])? '1' : '';
    update_post_meta($post_id,'aFBgIL3Fx',$aFBgCheck);
  if(isset($_POST['aFBgIL3Rp']))update_post_meta($post_id,'aFBgIL3Rp',$_POST['aFBgIL3Rp']);
  if(isset($_POST['aFBgIL3XY']))update_post_meta($post_id,'aFBgIL3XY',$_POST['aFBgIL3XY']);
  if(isset($_POST['aFBgIL3AY']))update_post_meta($post_id,'aFBgIL3AY',$_POST['aFBgIL3AY']);
  if(isset($_POST['aFBgIL3_Z']))update_post_meta($post_id,'aFBgIL3_Z',$_POST['aFBgIL3_Z']);
  if(isset($_POST['aFBgIL3HL']))update_post_meta($post_id,'aFBgIL3HL',$_POST['aFBgIL3HL']);

  // Image Layer 4
  $aFBgCheck =(isset($_POST['aFBgIL4On']) && $_POST['aFBgIL4On'])? '1' : '';
    update_post_meta($post_id,'aFBgIL4On',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL4MH']) && $_POST['aFBgIL4MH'])? '1' : '';
    update_post_meta($post_id,'aFBgIL4MH',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgIL4Sn']) && $_POST['aFBgIL4Sn'])? '1' : '';
    update_post_meta($post_id,'aFBgIL4Sn',$aFBgCheck);
  if(isset($_POST['aFBgIL4Im']))update_post_meta($post_id,'aFBgIL4Im',$_POST['aFBgIL4Im']);
  $aFBgCheck =(isset($_POST['aFBgIL4Ob']) && $_POST['aFBgIL4Ob'])? '1' : '';
    update_post_meta($post_id,'aFBgIL4Ob',$aFBgCheck);
  if(isset($_POST['aFBgIL4Co']))update_post_meta($post_id,'aFBgIL4Co',$_POST['aFBgIL4Co']);
  if(isset($_POST['aFBgIL4Op']))update_post_meta($post_id,'aFBgIL4Op',$_POST['aFBgIL4Op']);
  if(isset($_POST['aFBgIL4Gr']))update_post_meta($post_id,'aFBgIL4Gr',$_POST['aFBgIL4Gr']);
  if(isset($_POST['aFBgIL4Fl']))update_post_meta($post_id,'aFBgIL4Fl',$_POST['aFBgIL4Fl']);
  $aFBgCheck =(isset($_POST['aFBgIL4Fx']) && $_POST['aFBgIL4Fx'])? '1' : '';
    update_post_meta($post_id,'aFBgIL4Fx',$aFBgCheck);
  if(isset($_POST['aFBgIL4Rp']))update_post_meta($post_id,'aFBgIL4Rp',$_POST['aFBgIL4Rp']);
  if(isset($_POST['aFBgIL4XY']))update_post_meta($post_id,'aFBgIL4XY',$_POST['aFBgIL4XY']);
  if(isset($_POST['aFBgIL4AY']))update_post_meta($post_id,'aFBgIL4AY',$_POST['aFBgIL4AY']);
  if(isset($_POST['aFBgIL4_Z']))update_post_meta($post_id,'aFBgIL4_Z',$_POST['aFBgIL4_Z']);
  if(isset($_POST['aFBgIL4HL']))update_post_meta($post_id,'aFBgIL4HL',$_POST['aFBgIL4HL']);

  // Video
  $aFBgCheck =(isset($_POST['aFBgVidOn']) && $_POST['aFBgVidOn'])? '1' : '';
    update_post_meta($post_id,'aFBgVidOn',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgVidMH']) && $_POST['aFBgVidMH'])? '1' : '';
    update_post_meta($post_id,'aFBgVidMH',$aFBgCheck);
  if(isset($_POST['aFBgVideo']))update_post_meta($post_id,'aFBgVideo',$_POST['aFBgVideo']);
  if(isset($_POST['aFBgVidAl']))update_post_meta($post_id,'aFBgVidAl',$_POST['aFBgVidAl']);
  if(isset($_POST['aFBgVidCo']))update_post_meta($post_id,'aFBgVidCo',$_POST['aFBgVidCo']);
  if(isset($_POST['aFBgVidOp']))update_post_meta($post_id,'aFBgVidOp',$_POST['aFBgVidOp']);
  if(isset($_POST['aFBgVidGr']))update_post_meta($post_id,'aFBgVidGr',$_POST['aFBgVidGr']);
  if(isset($_POST['aFBgVidFl']))update_post_meta($post_id,'aFBgVidFl',$_POST['aFBgVidFl']);
  if(isset($_POST['aFBgVidRp']))update_post_meta($post_id,'aFBgVidRp',$_POST['aFBgVidRp']);
  $aFBgCheck =(isset($_POST['aFBgVidSn']) && $_POST['aFBgVidSn'])? '1' : '';
    update_post_meta($post_id,'aFBgVidSn',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgVidFx']) && $_POST['aFBgVidFx'])? '1' : '';
    update_post_meta($post_id,'aFBgVidFx',$aFBgCheck);
  if(isset($_POST['aFBgVidXY']))update_post_meta($post_id,'aFBgVidXY',$_POST['aFBgVidXY']);
  if(isset($_POST['aFBgVidAY']))update_post_meta($post_id,'aFBgVidAY',$_POST['aFBgVidAY']);
  if(isset($_POST['aFBgVid_Z']))update_post_meta($post_id,'aFBgVid_Z',$_POST['aFBgVid_Z']);

  // Overlay
  $aFBgCheck =(isset($_POST['aFBgOvrOn']) && $_POST['aFBgOvrOn'])? '1' : '';
    update_post_meta($post_id,'aFBgOvrOn',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgOvrMH']) && $_POST['aFBgOvrMH'])? '1' : '';
    update_post_meta($post_id,'aFBgOvrMH',$aFBgCheck);
  $aFBgCheck =(isset($_POST['aFBgOvrSn']) && $_POST['aFBgOvrSn'])? '1' : '';
    update_post_meta($post_id,'aFBgOvrSn',$aFBgCheck);
  if(isset($_POST['aFBgOvrIm']))update_post_meta($post_id,'aFBgOvrIm',$_POST['aFBgOvrIm']);
  $aFBgCheck =(isset($_POST['aFBgOvrOb']) && $_POST['aFBgOvrOb'])? '1' : '';
    update_post_meta($post_id,'aFBgOvrOb',$aFBgCheck);
  if(isset($_POST['aFBgOvrCo']))update_post_meta($post_id,'aFBgOvrCo',$_POST['aFBgOvrCo']);
  if(isset($_POST['aFBgOvrOp']))update_post_meta($post_id,'aFBgOvrOp',$_POST['aFBgOvrOp']);
  if(isset($_POST['aFBgOvrGr']))update_post_meta($post_id,'aFBgOvrGr',$_POST['aFBgOvrGr']);
  if(isset($_POST['aFBgOvrFl']))update_post_meta($post_id,'aFBgOvrFl',$_POST['aFBgOvrFl']);
  $aFBgCheck =(isset($_POST['aFBgOvrFx']) && $_POST['aFBgOvrFx'])? '1' : '';
    update_post_meta($post_id,'aFBgOvrFx',$aFBgCheck);
  if(isset($_POST['aFBgOvrRp']))update_post_meta($post_id,'aFBgOvrRp',$_POST['aFBgOvrRp']);
  if(isset($_POST['aFBgOvrXY']))update_post_meta($post_id,'aFBgOvrXY',$_POST['aFBgOvrXY']);
  if(isset($_POST['aFBgOvrAY']))update_post_meta($post_id,'aFBgOvrAY',$_POST['aFBgOvrAY']);
  if(isset($_POST['aFBgOvr_Z']))update_post_meta($post_id,'aFBgOvr_Z',$_POST['aFBgOvr_Z']);
  if(isset($_POST['aFBgOvrHL']))update_post_meta($post_id,'aFBgOvrHL',$_POST['aFBgOvrHL']);
}

function aFBgAdminCSS() {
  global $post_type;
  if($post_type === 'background'){ ?>
  <style id="aFBgAdminCSS" type="text/css">
    <?php $aFCSSInclude = file_get_contents(plugins_url('/css/admin.css', __FILE__).'?v='.time());
    echo $aFCSSInclude; ?>
  </style>
<?php }
}
add_action('admin_head', 'aFBgAdminCSS');

function aFBgAdminStyle() {
  global $post_type;
  if($post_type === 'background'){
    wp_enqueue_style('minicolors', plugins_url('/css/jquery.minicolors.css', __FILE__)); //,'',time());
  }
}
add_action('admin_enqueue_scripts', 'aFBgAdminStyle');

function aFBgAdminJS() {
  global $post_type;
  if($post_type === 'background'){
    wp_enqueue_media();
    wp_enqueue_script('addfunc-backgrounds-admin',plugins_url('js/addfunc-backgrounds-admin.js',__FILE__),array('jquery'),time());
    wp_enqueue_script('addfunc-backgrounds-uploader',plugins_url('js/addfunc-backgrounds-uploader.js',__FILE__),array('jquery'),time());
    wp_enqueue_script('minicolors',plugins_url('js/jquery.minicolors.min.js',__FILE__),array('jquery'));
  }
}
add_action('admin_enqueue_scripts','aFBgAdminJS');
function aFBgAdminJSConfig() {
  global $post_type;
  if($post_type === 'background'){
    $js =  '<script id="minicolorsjs" type="text/javascript">'."\n"
          .'    var aFBgswatches = [("White","#ffffff"),("Black","#000000")]'."\n"
          .'    $(\'input.aFcolorpikr\').minicolors({'."\n"
          .'      control: \'wheel\','."\n"
          .'      format: \'rgb\','."\n"
          .'      keywords: \'currentColor,inherit,initial,transparent\','."\n"
          .'      opacity: true,'."\n"
          .'      swatches: aFBgswatches,'."\n"
          .'    });'."\n"
          .'</script>'."\n";
    echo $js;
  }
}
add_action('admin_footer', 'aFBgAdminJSConfig');
