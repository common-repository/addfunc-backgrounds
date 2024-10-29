<?php
/*
    Plugin Name: AddFunc Backgrounds
    Plugin URI:
    Description: Create Backgrounds and choose them from a dropdown on any Post, Page or custom post type.
    Version: 0.10
    Author: AddFunc
    @return string HTML content to display gallery.
           ______
       _  |  ___/   _ _ __   ____
     _| |_| |__| | | | '_ \ / __/™
    |_ Add|  _/| |_| | | | | (__
      |_| |_|   \__,_|_| |_|\___\
*/



/*
    C U S T O M   P O S T   T Y P E
    ===============================
    Register content type for Backgrounds
*/

add_action('init', 'aFBg_post_type', 0);
function aFBg_post_type() {
  $labels = array(
    'name'               => _x('Backgrounds', 'post type general name'),
    'singular_name'      => _x('Background', 'post type singular name'),
    'add_new'            => __('Add New'),
    'add_new_item'       => __('Add New Background'),
    'edit_item'          => __('Edit Background'),
    'new_item'           => __('New Background'),
    'view_item'          => __('View Background'),
    'search_items'       => __('Search Backgrounds'),
    'not_found'          => __('No Backgrounds found'),
    'not_found_in_trash' => __('No Backgrounds found in Trash'),
    'menu_name'          => 'Backgrounds'
  );
  $show_in_admin = '';
  if(!current_user_can('edit_posts')){
    $show_in_admin = false;
  }
  else {
    $show_in_admin = true;
  }
  $supports = array('title','revisions');
  $args = array(
    'menu_icon'=> 'dashicons-format-image',
    'labels' => $labels,
    'public' => false,
    'show_ui' => $show_in_admin,
    'show_in_menu' => $show_in_admin,
    'show_in_admin_bar' => $show_in_admin,
    'query_var' => 'background',
    'rewrite' => array('slug'=>'background'),
    'has_archive' => false,
    'hierarchical' => false,
    'supports' => $supports
  );
  register_post_type('background',$args);
}



/*
     V A R I A B L E S
     =================
*/

$aFBgThePost = '';
$aFBgPostCust = '';
$aFBgID = 0;
$aFBgVals = '';
$aFBgCont = '';
$aFBgSlug = '';
$aFBgVarList = array(
'SetOn','Class','Kills','Wrapr','Prllx',
'BdyOn','BdyIm','BdyGr','BdyCo','BdyFx','BdyFl','BdyRp','BdyXY','BdyHL',
'WinOn','WinIm','WinGr','WinCo','WinFx','WinFl','WinRp','WinXY','WinHL','WinOp','WinOb','WinHt',
'WalOn','WalIm','WalGr','WalCo','WalFx','WalFl','WalRp','WalXY','WalHL','WalOp','WalOb',
'VidOn','Video','VidGr','VidCo','VidFx','VidFl','VidRp','VidXY','VidHL','VidOp','VidOb','VidAl','VidSn','Vid_Z',
'IL1On','IL1Im','IL1Gr','IL1Co','IL1Fx','IL1Fl','IL1Rp','IL1XY','IL1HL','IL1Op','IL1Ob','IL1MH','IL1Sn','IL1_Z',
'IL2On','IL2Im','IL2Gr','IL2Co','IL2Fx','IL2Fl','IL2Rp','IL2XY','IL2HL','IL2Op','IL2Ob','IL2MH','IL2Sn','IL2_Z',
'IL3On','IL3Im','IL3Gr','IL3Co','IL3Fx','IL3Fl','IL3Rp','IL3XY','IL3HL','IL3Op','IL3Ob','IL3MH','IL3Sn','IL3_Z',
'IL4On','IL4Im','IL4Gr','IL4Co','IL4Fx','IL4Fl','IL4Rp','IL4XY','IL4HL','IL4Op','IL4Ob','IL4MH','IL4Sn','IL4_Z',
'OvrOn','OvrIm','OvrGr','OvrCo','OvrFx','OvrFl','OvrRp','OvrXY','OvrHL','OvrOp','OvrOb','OvrMH','OvrSn'
);
foreach ($aFBgVarList as $opt => $value){
  $value = "aFBg".$value;
  $$value = '';
}
add_action('wp','aFBgVariables');
function aFBgVariables(){
  global $aFBgThePost,$aFBgPostCust,$aFBgID,$aFBgVals,$aFBgCont,$aFBgSlug,$aFBgVarList,$wp_query;
  if(isset($wp_query->post->ID)){
    $aFBgThePost = get_post();
    $aFBgPostCust = get_post_custom($aFBgThePost->ID);
    $aFBgID = isset($aFBgPostCust['aFBg_select']) ? esc_attr($aFBgPostCust['aFBg_select'][0]) : '';
    $aFBgVals = get_post_custom($aFBgID);
    $aFBgCont = get_post($aFBgID);
    $aFBgSlug = $aFBgCont->post_name;
    foreach ($aFBgVarList as $opt => $value){
      $value = "aFBg".$value;
      $$value = isset($aFBgVals[$value]) ? $aFBgVals[$value][0] :'';
    }
  }
}



/*
     F U N C T I O N S
     =================
*/

function aFBgGenCSS($sel,$match=NULL,$img=NULL,$grad=NULL,$color=NULL,$html=NULL,$obj=NULL,$fill=NULL,$opac=NULL,$repeat=NULL,$screen=NULL
                    ,$fixed=NULL,$xy="cm",$ht="100vh",$prlx=NULL,$depth="0",$dflt="0",$cust=NULL,$xhgtpos=NULL){
  $css = "";
  if($img || $grad || $color || $html){
    $css =             '  '.$sel.' {'."\n";
    if($color){
      $css = $css     .'    background-color: '.$color.';'."\n";
    }
    if($grad){
      $css = $css     .'    background-image: '.$grad.';'."\n";
    }
    if($opac){
      $css = $css     .'    opacity: '.$opac.';'."\n";
    }
    if($img){
      if($obj !== '1'){
        $css = $css   .'    background-image: url('.$img.') !important;'."\n";
      }
    }
    if($fill === 'contain'){
      $css = $css     .'    background-size: contain;'."\n";
    }
    elseif($fill === 'actual'){
      $css = $css     .'    background-size: auto;'."\n";
    }
    elseif($fill === 'stretch'){
      $css = $css     .'    background-size: 100% 100%;'."\n";
    }
    else{
      $css = $css     .'    background-size: cover;'."\n";
    }
    if($repeat === 'r'){
      $css = $css     .'    background-repeat: repeat;'."\n";
    }
    elseif($repeat === 'x'){
      $css = $css     .'    background-repeat: repeat-x;'."\n";
    }
    elseif($repeat === 'y'){
      $css = $css     .'    background-repeat: repeat-y;'."\n";
    }
    else{
      $css = $css     .'    background-repeat: no-repeat;'."\n";
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
    $css = $css       .'    background-position: '.$xp.' '.$yp.';'."\n";
    if(!$fixed){
      $css = $css     .'    /* Background Scrolling: Scroll */'."\n";
      $css = $css     .'    background-attachment: scroll;'."\n";
    }
    else{
      $css = $css     .'    /* Background Scrolling: Fixed */'."\n";
      $css = $css     .'    background-attachment: fixed;'."\n";
    }
    if(!$xhgtpos){
      $css = $css     .'    padding: 0;'."\n"
                      .'    margin: 0;'."\n"
                      .'    width: 100%;'."\n";
      if($match){ // Match height of page, disallow parallax and stick to page on scroll.
        $css = $css   .'    height: auto;'."\n";
        $css = $css   .'    position: absolute;'."\n"
                      .'    top: 0;'."\n"
                      .'    left: 0;'."\n"
                      .'    right: 0;'."\n"
                      .'    bottom: 0;'."\n";
      }
      else{ // Match height of window.
        $css = $css   .'    height: '.$ht.';'."\n";
        if($prlx){ // On scroll, stick to screen, match height of window.
          $css = $css .'    position: absolute;'."\n";
        }
        else{ // Match height of window, allow parallax.
          if($screen){
            $css=$css .'    position: fixed;'."\n";
          }
          else{
            $css=$css .'    position: absolute;'."\n";
          }
        }
      }
    }
    $xt = "";
    $yt = "";
    $xd = "";
    $yd = "";
    if($sel != 'html > body'){
      if($xy === 'lt' || $xy === 'lm' || $xy === 'lb'){
        $xt = "0%";
        $xd = '    left: 0;'."\n";
      }
      elseif($xy === 'rt' || $xy === 'rm' || $xy === 'rb'){
        $xt = "0%";
        $xd = '    right: 0;'."\n";
      }
      else{
        $xt = "-50%";
        $xd = '    left: 50%;'."\n";
      }
      if($xy === 'lt' || $xy === 'ct' || $xy === 'rt'){
        $yt = "0%";
        $yd = '    top: 0;'."\n";
      }
      elseif($xy === 'lb' || $xy === 'cb' || $xy === 'rb'){
        $yt = "0%";
        $yd = '    bottom: 0;'."\n";
      }
      else{
        $yt = "-50%";
        $yd = '    top: 50%;'."\n";
      }
    }
    if($cust){
      $css = $css     .$cust."\n";
    }
    if($sel != 'html > body'){
      $css = $css     .'    z-index: -1;'."\n"
                      .'    overflow: hidden;'."\n";
    }
    $css = $css       .'  }'."\n";
    if($prlx && !$match && $sel != 'html > body'){
      if($depth !== '0'){
        $depth = empty($depth) ? $dflt : $depth;
      }
      $scale = $depth + 1;
      $depth = "-".$depth."px";
      $css = $css     .'  @supports ((perspective: 1px) and (not (-webkit-overflow-scrolling: touch))) {'."\n"
                      .'    '.$sel.' {'."\n"
                      .'      left: 0;'."\n"
                      .'      top: 0;'."\n"
                      .'      -webkit-transform: translateZ('.$depth.') scale('.$scale.');'."\n" /* translateX('.$xt.') translateY('.$yt.') */
                      .'      transform: translateZ('.$depth.') scale('.$scale.');'."\n"
                      .'      will-change: transform;'."\n"
                      .'    }'."\n"
                      .'  }'."\n";
    }
    if($obj === '1'){
      $css = $css     .'  '.$sel.' > * {'."\n";
      if($fill === 'contain'){
        $css = $css   .'    width: 100%;'."\n"
                      .'    min-width: unset;'."\n"
                      .'    max-width: 100%;'."\n";
        if($match){
          $css = $css .'    height: 100%;'."\n"
                      .'    min-height: unset;'."\n"
                      .'    max-height: 100%;'."\n";
        }
        else{
          $css = $css .'    height: '.$ht.';'."\n"
                      .'    min-height: unset;'."\n"
                      .'    max-height: '.$ht.';'."\n";
        }
      }
      elseif($fill === 'actual'){
        $css = $css   .'    width: auto;'."\n"
                      .'    min-width: unset;'."\n"
                      .'    max-width: 100%;'."\n"
                      .'    height: auto;'."\n"
                      .'    min-height: unset;'."\n"
                      .'    max-height: 100%;'."\n";
      }
      elseif($fill === 'stretch'){
        $css = $css   .'    width: 100%;'."\n"
                      .'    min-width: 100%;'."\n"
                      .'    max-width: 100%;'."\n";
        if($match){
          $css = $css .'    height: 100%;'."\n"
                      .'    min-height: 100%;'."\n"
                      .'    max-height: 100%;'."\n";
        }
        else{
          $css = $css .'    height: '.$ht.';'."\n"
                      .'    min-height: '.$ht.';'."\n"
                      .'    max-height: '.$ht.';'."\n";
        }
      }
      else{ //         'cover'
        $css = $css   .'    width: auto;'."\n"
                      .'    min-width: 100%;'."\n"
                      .'    max-width: unset;'."\n";
        if($match){
          $css = $css .'    height: 100%;'."\n"
                      .'    min-height: 100%;'."\n"
                      .'    max-height: unset;'."\n";
        }
        else{
          $css = $css .'    height: auto;'."\n"
                      .'    min-height: '.$ht.';'."\n"
                      .'    max-height: unset;'."\n";
        }
      }
      $css = $css     .'    display: block;'."\n"
                      .'    position: absolute;'."\n"
                      .$xd
                      .$yd
                      .'    -webkit-transform: translateX('.$xt.') translateY('.$yt.');'."\n"
                      .'    transform: translateX('.$xt.') translateY('.$yt.');'."\n";
      $css = $css     .'  }'."\n";
    } // end IS $obj
    echo $css;
  }
}
if(!function_exists('aFGetVideoType')){
  function aFGetVideoType($v){
    $t = '';
    if(strpos(    $v,'.mp4')){  $t = ' type="video/mp4"'; }
    elseif(strpos($v,'.webm')){ $t = ' type="video/webm"';}
    elseif(strpos($v,'.ogv')){  $t = ' type="video/ogv"'; }
    elseif(strpos($v,'.avi')){  $t = ' type="video/avi"'; }
    elseif(strpos($v,'.mov')){  $t = ' type="video/mov"'; }
    elseif(strpos($v,'.wmv')){  $t = ' type="video/wmv"'; }
    elseif(strpos($v,'.flv')){  $t = ' type="video/flv"'; }
    elseif(strpos($v,'.f4v')){  $t = ' type="video/f4v"'; }
    elseif(strpos($v,'.ogg')){  $t = ' type="video/ogg"'; }
    else { $t = ''; }
    return $t;
  }
}
if(!function_exists('aFBgBuffRec')){
  function aFBgBuffRec(){
    global $wp_query,$aFBgID,$aFBgVals,$aFBgVarList,$aFBgSlug,$aFBgClass;
    if(isset($wp_query->post->ID)){
      if(isset($aFBgID)){
        foreach ($aFBgVarList as $opt => $value){
          $value = "aFBg".$value;
          $$value = isset($aFBgVals[$value]) ? $aFBgVals[$value][0] :'';
        }
        if(!$aFBgWinHt){
          $aFBgWinHt = '100vh';
        }
        ob_start();
        if(!$aFBgID){return;}
        else{
        // if(isset($aFBgID)){
          add_filter('body_class','aFBgBodyClass');
          function aFBgBodyClass($classes){
            global $aFBgSlug,$aFBgClass;
            $classes[] = 'background-'.$aFBgSlug;
            $classes[] = $aFBgClass;
            return $classes;
          }
        ?>
  <style id="aFBgCSS" type="text/css">
<?php if($aFBgPrllx){ ?>
    @supports ((perspective: 1px) and (not (-webkit-overflow-scrolling: touch))) {
      html {
        height: 100%;
        overflow: hidden;
        overscroll-behavior: contain;
        scroll-behavior: smooth;
      }
      html > body {
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        overflow-x: hidden;
        overflow-y: auto;
        overscroll-behavior: contain;
        perspective: 1px;
        transform-style: preserve-3d;
        transform: translateZ(0);
        scroll-behavior: smooth;
      }
      html > body.admin-bar {
        padding-top: 32px;
        top: -32px;
      }
      @media (max-width: 783px) {
        html > body.admin-bar {
          padding-top: 46px;
          top: -46px;
        }
      }
      html > body:after {
        content: "";
        display: block;
        clear: both;
      }
    }
<?php } // end if $aFBgPrllx
      if($aFBgWinIm || $aFBgWinGr || $aFBgWinCo || $aFBgWalIm || $aFBgWalGr || $aFBgWalCo || $aFBgIL1MH || $aFBgIL2MH || $aFBgIL3MH || $aFBgIL4MH || $aFBgOvrIm || $aFBgOvrGr || $aFBgOvrCo || $aFBgOvrHL) {
      ?>
    #aF-background-page-wrapper {
      position: relative;
      z-index: 0;
    }
    #aF-background-page-wrapper:after {
      content: "";
      display: block;
      clear: both;
    }
<?php }
$aFBgWinCu = '    height: '.$aFBgWinHt.';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;';

$aFBgWalCu = '    position: absolute;
    top: '.$aFBgWinHt.';
    left: 0;
    right: 0;
    bottom: 0;';
//    aFBgGenCSS($sel                     ,$match    ,$img      ,$grad     ,$color    ,$html     ,$obj      ,$fill     ,$opac
//                  ,$repeat   ,$screen   ,$fixed    ,$xy       ,$ht       ,$prlx     ,$depth    ,$dflt,$cust,$xhgtpos);

      aFBgGenCSS('html > body'            ,''        ,$aFBgBdyIm,$aFBgBdyGr,$aFBgBdyCo,$aFBgBdyHL,''        ,$aFBgBdyFl,''
                    ,$aFBgBdyRp,''        ,$aFBgBdyFx,$aFBgBdyXY,$aFBgWinHt,''        ,''        ,'','','1');

      aFBgGenCSS('#aF-background-window'  ,''        ,$aFBgWinIm,$aFBgWinGr,$aFBgWinCo,$aFBgWinHL,''        ,$aFBgWinFl,$aFBgWinOp
                    ,$aFBgWinRp,''        ,$aFBgWinFx,$aFBgWinXY,$aFBgWinHt,''        ,''        ,'',$aFBgWinCu,'1');

      aFBgGenCSS('#aF-background-wall'    ,''        ,$aFBgWalIm,$aFBgWalGr,$aFBgWalCo,$aFBgWalHL,''        ,$aFBgWalFl,$aFBgWalOp
                    ,$aFBgWalRp,''        ,$aFBgWalFx,$aFBgWalXY,$aFBgWinHt,''        ,''        ,'',$aFBgWalCu,'1');

      aFBgGenCSS('#aF-background-video'   ,''        ,$aFBgVideo,$aFBgVidGr,$aFBgVidCo,$aFBgVidHL,'1'       ,$aFBgVidFl,$aFBgVidOp
                    ,$aFBgVidRp,$aFBgVidSn,$aFBgVidFx,$aFBgVidXY,$aFBgWinHt,$aFBgPrllx,$aFBgVid_Z,'1');

      aFBgGenCSS('#aF-background-layer-1' ,$aFBgIL1MH,$aFBgIL1Im,$aFBgIL1Gr,$aFBgIL1Co,$aFBgIL1HL,$aFBgIL1Ob,$aFBgIL1Fl,$aFBgIL1Op
                    ,$aFBgIL1Rp,$aFBgIL1Sn,$aFBgIL1Fx,$aFBgIL1XY,$aFBgWinHt,$aFBgPrllx,$aFBgIL1_Z,'11');

      aFBgGenCSS('#aF-background-layer-2' ,$aFBgIL2MH,$aFBgIL2Im,$aFBgIL2Gr,$aFBgIL2Co,$aFBgIL2HL,$aFBgIL2Ob,$aFBgIL2Fl,$aFBgIL2Op
                    ,$aFBgIL2Rp,$aFBgIL2Sn,$aFBgIL2Fx,$aFBgIL2XY,$aFBgWinHt,$aFBgPrllx,$aFBgIL2_Z,'7');

      aFBgGenCSS('#aF-background-layer-3' ,$aFBgIL3MH,$aFBgIL3Im,$aFBgIL3Gr,$aFBgIL3Co,$aFBgIL3HL,$aFBgIL3Ob,$aFBgIL3Fl,$aFBgIL3Op
                    ,$aFBgIL3Rp,$aFBgIL3Sn,$aFBgIL3Fx,$aFBgIL3XY,$aFBgWinHt,$aFBgPrllx,$aFBgIL3_Z,'3');

      aFBgGenCSS('#aF-background-layer-4' ,$aFBgIL4MH,$aFBgIL4Im,$aFBgIL4Gr,$aFBgIL4Co,$aFBgIL4HL,$aFBgIL4Ob,$aFBgIL4Fl,$aFBgIL4Op
                    ,$aFBgIL4Rp,$aFBgIL4Sn,$aFBgIL4Fx,$aFBgIL4XY,$aFBgWinHt,$aFBgPrllx,$aFBgIL4_Z,'1');

      aFBgGenCSS('#aF-background-overlay' ,'1'       ,$aFBgOvrIm,$aFBgOvrGr,$aFBgOvrCo,$aFBgOvrHL,$aFBgOvrOb,$aFBgOvrFl,$aFBgOvrOp
                    ,$aFBgOvrRp,$aFBgOvrSn,$aFBgOvrFx,$aFBgOvrXY,$aFBgWinHt,''        ,''        );

      if($aFBgKills){ ?>
    <?php echo $aFBgKills; ?> {
      background: none !important;
    }
<?php } ?>
  </style>
<?php // wp_enqueue_style('aFBgStylesheet',plugins_url('css/addfunc-slides.css',__FILE__));
      // wp_register_script('aFBgPrlx',plugins_url('js/addfunc-parallax.js',__FILE__));
      // wp_enqueue_script('aFBgPrlx');
        }
      }
    }
  }
}
// if(!is_archive()){
add_action('wp_head','aFBgBuffRec');
// }
if(!function_exists('aFBgBuffPlay')){
  function aFBgBuffPlay(){
    global $aFBgID,$aFBgVals,$aFBgVarList,$wp_query;
    if(isset($wp_query->post->ID)){
      if(!isset($aFBgID)){return;}
      else{
        foreach ($aFBgVarList as $opt => $value){
          $value = "aFBg".$value;
          $$value = isset($aFBgVals[$value]) ? $aFBgVals[$value][0] :'';
        }
        if(!isset($aFBgWinHt)){
          $aFBgWinHt = '100vh';
        }
        $tape = ob_get_clean();
        $pattern = '/<[bB][oO][dD][yY]\s[A-Za-z]{2,5}[A-Za-z0-9 "\'_,=%*\/():;\[\]\-\.]+>|<body>/';
        preg_match($pattern,$tape,$queue);
        $q = implode($queue);
        $q = $q."\n";
        function aFBgGenVid($id,$vd,$al,&$q){
          if($vd){
            $v1typ = aFGetVideoType($vd);
            $q = $q.'    <div id="'.$id.'"><video class="background-video" src="'.$vd.'" autoplay muted loop playsinline preload="metadata"><source'.$v1typ.'" src="'.$vd.'">'."\n";
            if($al){
              $v2typ = aFGetVideoType($al);
              $q = $q.'      <source type="video/'.$v2typ.'" src="'.$al.'">'."\n";
            }
            $q = $q.'      <a href="'.$vd.'">'.$vd.'</a></video></div>'."\n";
          }
        }
        function aFBgGenHTML($id,$im,$gr,$co,$hl,$ob,&$q){
          if($im || $gr || $co || $hl){
            if($hl){
              $hl = do_shortcode($hl);
            }
            if(!$ob){
              $q = $q.'    <div id="'.$id.'">'.$hl.'</div>'."\n";
            }
            else{
              if($im){$im = ' data="'.$im.'"';}
              $q = $q.'    <div id="'.$id.'"><object type="image/svg+xml"'.$im.'>'.$hl.'</object></div>'."\n";
            }
          }
        }
        if($aFBgBdyHL){
          $aFBgBdyHL =  do_shortcode($aFBgBdyHL);
          $q = $q.'  '.$aFBgBdyHL."\n";
        }
        aFBgGenVid("aF-background-video",$aFBgVideo,$aFBgVidAl,$q);
        if(!$aFBgIL1MH){
          aFBgGenHTML("aF-background-layer-1",$aFBgIL1Im,$aFBgIL1Gr,$aFBgIL1Co,$aFBgIL1HL,$aFBgIL1Ob,$q);
        }
        if(!$aFBgIL2MH){
          aFBgGenHTML("aF-background-layer-2",$aFBgIL2Im,$aFBgIL2Gr,$aFBgIL2Co,$aFBgIL2HL,$aFBgIL2Ob,$q);
        }
        if(!$aFBgIL3MH){
          aFBgGenHTML("aF-background-layer-3",$aFBgIL3Im,$aFBgIL3Gr,$aFBgIL3Co,$aFBgIL3HL,$aFBgIL3Ob,$q);
        }
        if(!$aFBgIL4MH){
          aFBgGenHTML("aF-background-layer-4",$aFBgIL4Im,$aFBgIL4Gr,$aFBgIL4Co,$aFBgIL4HL,$aFBgIL4Ob,$q);
        }
        if($aFBgWinIm || $aFBgWinGr || $aFBgWinCo
        || $aFBgWalIm || $aFBgWalGr || $aFBgWalCo
        || $aFBgIL1MH || $aFBgIL2MH || $aFBgIL3MH || $aFBgIL4MH
        || $aFBgOvrIm || $aFBgOvrGr || $aFBgOvrCo){
          $aFBgWrapr = '1';
          $q = $q.'  <div id="aF-background-page-wrapper">'."\n";
        }
        if($aFBgIL1MH){
          aFBgGenHTML("aF-background-layer-1",$aFBgIL1Im,$aFBgIL1Gr,$aFBgIL1Co,$aFBgIL1HL,$aFBgIL1Ob,$q);
        }
        if($aFBgIL2MH){
          aFBgGenHTML("aF-background-layer-2",$aFBgIL2Im,$aFBgIL2Gr,$aFBgIL2Co,$aFBgIL2HL,$aFBgIL2Ob,$q);
        }
        if($aFBgIL3MH){
          aFBgGenHTML("aF-background-layer-3",$aFBgIL3Im,$aFBgIL3Gr,$aFBgIL3Co,$aFBgIL3HL,$aFBgIL3Ob,$q);
        }
        if($aFBgIL4MH){
          aFBgGenHTML("aF-background-layer-4",$aFBgIL4Im,$aFBgIL4Gr,$aFBgIL4Co,$aFBgIL4HL,$aFBgIL4Ob,$q);
        }
        if($aFBgWinIm || $aFBgWinGr || $aFBgWinCo || $aFBgWinHL){
          $q = $q.'    <div id="aF-background-window">'.$aFBgWinHL.'</div>'."\n";
        }
        if($aFBgWalIm || $aFBgWalGr || $aFBgWalCo || $aFBgWalHL){
          $q = $q.'    <div id="aF-background-wall">'.$aFBgWalHL.'</div>'."\n";
        }
        aFBgGenHTML("aF-background-overlay",$aFBgOvrIm,$aFBgOvrGr,$aFBgOvrCo,$aFBgOvrHL,$aFBgOvrOb,$q);
        echo preg_replace($pattern,$q,$tape);
        if($aFBgWrapr){
          echo '  </div><!-- /#aF-background-page-wrapper -->'."\n";
        }
      }
    }
  }
}
// if(!is_archive()){
add_action('wp_footer','aFBgBuffPlay');
// }



/*
    M E T A B O X E S
    =================
*/

function aFBgMetaBoxes(){
  require_once(plugin_dir_path( __FILE__ ).'metaboxes.php');
}
add_action('admin_init','aFBgMetaBoxes');
