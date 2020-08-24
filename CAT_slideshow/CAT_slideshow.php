<?php
/*
Plugin Name: CAT_slideshow
Plugin URI: http://blog.lionlionn.com
Version: v1.00
Author: lion.wang
Description: CAT EVENTS slideShow.
*/

//先檢查這名字的類別是否已存在
if(!class_exists("CATSlideshow")){
	//類別	
	class CATSlideshow{
	    var $adminOptionsName = "CATSlideshowAdminOptions";
		//建構值
		function CATSlideshow(){
			//echo "DevleLion start<br>";
		}
		
		//Returns an array of admin options
		function getAdminOptions() {
			$devloungeAdminOptions = array('show_header' => 'true',
				'add_content' => 'true', 
				'comment_author' => 'true', 
				'content' => '');
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$devloungeAdminOptions[$key] = $option;
			}				
			update_option($this->adminOptionsName, $devloungeAdminOptions);
			return $devloungeAdminOptions;
		}

		//Prints out the admin page
		function printAdminPage() {
					$devOptions = $this->getAdminOptions();
										
					if (isset($_POST['update_devloungePluginSeriesSettings'])) { 
						if (isset($_POST['devloungeHeader'])) {
							$devOptions['show_header'] = $_POST['devloungeHeader'];
						}	
						if (isset($_POST['devloungeAddContent'])) {
							$devOptions['add_content'] = $_POST['devloungeAddContent'];
						}	
						if (isset($_POST['devloungeAuthor'])) {
							$devOptions['comment_author'] = $_POST['devloungeAuthor'];
						}	
						if (isset($_POST['devloungeContent'])) {
							$devOptions['content'] = apply_filters('content_save_pre', $_POST['devloungeContent']);
						}
						update_option($this->adminOptionsName, $devOptions);
						
						?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "DevloungePluginSeries");?></strong></p></div>
					<?php
					} ?>
                    
                    
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-latest.min.js"></script>
<script language="javascript"> 
function changPic(picURL,id){
	//alert("picURL-->"+picURL);
	//alert("id-->"+id);
	//$('#picBox').css("background-image", "url("+picName+")");
	//$('#reViewBox img').hide();
	$('#reViewPathBox').html(picURL); 
	$('#reViewBox img').attr({ 
          src: picURL
    });
	$('#reViewID').html("["+id+"]");
	//$('#reViewBox img').fadeIn(500);
}
</script>
                    
<div class=wrap style="border: 1px solid #FF33CC;">
<h2>CAT SlideShow</h2>
<Br>
<?php
	global $post;
	//gallery:指外掛相簿的，就會去讀postImagePath所設的路徑資料夾
	//default:指theme的images資料夾
	$postImageType="gallery";
	$postImagePath="/wp-content/gallery/post_image/";
	$slideShowCat=16;
	$type="slideShow_index";

    wp_reset_query();
    $mtime = date ("Y-m-d H:i:s", mktime (date("H")+8,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$liststr = "<CAT  M='".$mtime."'>";
	
	echo "postImageType-->".$postImageType."<br>";
	echo "postImagePath-->".$postImagePath."<br>";
	echo "slideShowCat--->".$slideShowCat."<br>";
	echo "mtime--->".$mtime."<br><br>";
?>
<div id="reViewID"></div>
<div id="reViewPathBox"></div>
<div id="reViewBox"><img src="<?php echo get_bloginfo("stylesheet_directory")."/images/empty.gif";?>" /></div>
<?
	query_posts('cat='.$slideShowCat.'&posts_per_page=7');
    if (have_posts()) : while (have_posts()) : the_post();
	
		//pic
		if($postImageType == "default"){
			$indexURL = get_bloginfo("stylesheet_directory")."/images/post_".$type."_".$post->ID.".jpg";
		}else if($postImageType == "gallery"){
			$indexURL = get_bloginfo("home").$postImagePath."post_".$type."_".$post->ID.".jpg";
		}
		//
		if( @fopen($indexURL, "r")){
		  	//return $pic_url;
		}else{
		   $indexURL = get_bloginfo("stylesheet_directory")."/images/post_".$type."_default.jpg";
		}

		echo $post->ID."─<a href=\"#\" onclick=\"changPic('".$indexURL."','".$post->ID."');\" >".get_the_title()."</a><br>";		

		//
		if($_GET["lang"] == "en"){
			if(post_custom('title_en') == ""){
				//$title = "nullTitle";
				$title = "";
				$title =  str_replace(chr(13).chr(10), '&#13;', $title);
			}else{
				$title = post_custom('title_en');
			}
			if(post_custom('con_en') == ""){
				//$con = "nullCon";
				$con = "";
			}else{
				$con = post_custom('con_en');
				$con =  str_replace(chr(13).chr(10), '&#13;', $con);
			}
		}else{
			if(post_custom('title_zh') == ""){
				//$title = "標題空白";
				$title = "";
			}else{
				$title = post_custom('title_zh');
				$title =  str_replace(chr(13).chr(10), '&#13;', $title);
			}
			if(post_custom('con_zh') == ""){
				//$con = "內容空白";
				$con = "";
			}else{
				$con = post_custom('con_zh');
				$con =  str_replace(chr(13).chr(10), '&#13;', $con);
				//echo "con-->".$con;
			}
		}
		//
		if(post_custom('x_index') == ""){
			$x_index = 600;
		}else{
			$x_index = post_custom('x_index');
		}
		if(post_custom('y_index') == ""){
			$y_index = 100;
		}else{
			$y_index = post_custom('y_index');
		}
		//
		/*
		if(post_custom('x_main') == ""){
			$x_main = 600;
		}else{
			$x_main = post_custom('x_main');
		}
		if(post_custom('y_main') == ""){
			$y_main = 100;
		}else{
			$y_main = post_custom('y_main');
		}
		*/
		//title字的顏色
		if(post_custom('titleColor_index') == ""){
			$titleColor_index = "0xffffff";
		}else{
			$titleColor_index = post_custom('titleColor_index');
		}
		//con字的顏色
		if(post_custom('conColor_index') == ""){
			$conColor_index = "0xffffff";
		}else{
			$conColor_index = post_custom('conColor_index');
		}
		/*
		//title字的顏色
		if(post_custom('titleColor_main') == ""){
			$titleColor_main = "0xffffff";
		}else{
			$titleColor_main = post_custom('titleColor_main');
		}
		//con字的顏色
		if(post_custom('conColor_main') == ""){
			$conColor_main = "0xffffff";
		}else{
			$conColor_main = post_custom('conColor_main');
		}
		*/
		//con字的背景顏色
		if(post_custom('txt_bg') == ""){
			$txtBg = "0x000000";
		}else{
			$txtBg = post_custom('txt_bg');
		}
		
		
		 //$liststr = $liststr."<Item id='".$post->ID."' title='".$title."' con='".$con."' indexURL='".$indexURL."' mainURL='".$mainURL."' indexX='".$x_index."'  indexY='".$y_index."'  mainX='".$x_main."'  mainY='".$y_main."'  titleColor_index='".$titleColor_index."' conColor_index='".$conColor_index."'  titleColor_main='".$titleColor_main."' conColor_main='".$conColor_main."' txtBg='".$txtBg."' />";

	    $liststr = $liststr."<Item id='".$post->ID."' title='".$title."' con='".$con."' indexURL='".$indexURL."' indexX='".$x_index."'  indexY='".$y_index."'   titleColor_index='".$titleColor_index."' conColor_index='".$conColor_index."'  txtBg='".$txtBg."' />";
	
	endwhile; endif;
	wp_reset_query();

    if(!empty($_GET["lang"])){
	
		$liststr = $liststr."</CAT>";
		if($_GET["lang"] == "en"){
			$fp= fopen ("../list_en.xml"  ,"w");
		}else{
			$fp= fopen ("../list_tw.xml"  ,"w");
		}
	
		if($fp){
			echo "<Br><br>".$_GET["lang"]."  refresh sucess!!";
			echo "<br><a href=\"".get_bloginfo('home')."/list_".$_GET["lang"].".xml"."\" target=\"_blank\">".get_bloginfo('home')."/list_".$_GET["lang"].".xml"."</a>";
		}else{
			echo "<Br><br>".$_GET["lang"]."  refresh failed!!";
		}
		fputs($fp,  $liststr) ;        
    	fclose($fp);    
    }

?>
<Br><Br><Br><Br>
<a href="<?php echo $_SERVER["REQUEST_URI"]."&lang=tw"; ?>">發佈XML(中文)</a><Br><Br>
<a href="<?php echo $_SERVER["REQUEST_URI"]."&lang=en"; ?>">發佈XML(英文)</a><Br><Br><Br>

</div>
					
					
					
					<?php
				}//End function printAdminPage()
		
		
		
		
	}// end class
}//end if


//
if(class_exists("CATSlideshow")){
   $cat_slideshow = new CATSlideshow();	

}

//Initialize the admin panel
if (!function_exists("CATSlideshow_ap")) {
	function CATSlideshow_ap() {
		global $cat_slideshow;
		if (!isset($cat_slideshow)) {
			return;
		}
		if (function_exists('add_options_page')) {
			add_options_page('CAT slideshow', 'CAT slideshow', 9, basename(__FILE__), array(&$cat_slideshow, 'printAdminPage'));
		}
	}	
}


if(isset($cat_slideshow)){
   //action
   add_action('admin_menu', 'CATSlideshow_ap');
}


?>