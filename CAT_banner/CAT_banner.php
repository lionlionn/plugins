<?php
/*
Plugin Name: CAT_banner
Plugin URI: http://blog.lionlionn.com
Version: v1.00
Author: lion.wang
Description: CAT EVENTS banner.
*/

//先檢查這名字的類別是否已存在
if(!class_exists("CATBanner")){
	//類別	
	class CATBanner{
	    var $adminOptionsName = "CATBannerAdminOptions";
		//建構值
		function CATBanner(){
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
<h2>CAT Banner</h2>
<Br>
<?php
	global $post;
	//gallery:指外掛相簿的，就會去讀postImagePath所設的路徑資料夾
	//default:指theme的images資料夾
	$postImageType="gallery";
	$postImagePath="/wp-content/gallery/post_image/";
	$bannerCat=52;
	$type="banner";

    wp_reset_query();
    $mtime = date ("Y-m-d H:i:s", mktime (date("H")+8,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$liststr = "<CAT  M='".$mtime."'>";
	
	echo "postImageType-->".$postImageType."<br>";
	echo "postImagePath-->".$postImagePath."<br>";
	echo "bannerCat-->".$bannerCat."<br>";
	echo "mtime-->".$mtime."<br><br>";
	//echo lionn_f1()."<br>";
?>
<div id="reViewID"></div>
<div id="reViewPathBox"></div>
<div id="reViewBox"><img src="<?php echo get_bloginfo("stylesheet_directory")."/images/empty.gif";?>" /></div>
<?
	query_posts('cat='.$bannerCat.'&posts_per_page=7');
    if (have_posts()) : while (have_posts()) : the_post();
		//pic
		if($postImageType == "default"){
			$pic_url = get_bloginfo("stylesheet_directory")."/images/post_".$type."_".$post->ID.".jpg";
		}else if($postImageType == "gallery"){
			$pic_url = get_bloginfo("home").$postImagePath."post_".$type."_".$post->ID.".jpg";
		}
		//
		if( @fopen($pic_url, "r")){
		  	//return $pic_url;
		}else{
		   $pic_url = get_bloginfo("stylesheet_directory")."/images/post_".$type."_default.jpg";
		}

	    echo $post->ID."─<a href=\"#\" onclick=\"changPic('".$pic_url."','".$post->ID."');\" >".get_the_title()."</a><br>";		
		//echo $post->ID."─<a href=\"".$picURL."\" target=\"_blank\">".get_the_title()."</a><br>";	

	    $liststr = $liststr."<Item id='".$post->ID."' picURL='".$pic_url."'  />";
	
	endwhile; endif;
	wp_reset_query();

	if(!empty($_GET["lang"])){
		
		$liststr = $liststr."</CAT>";
		$fp= fopen ("../list_office.xml"  ,"w");
	
		if($fp){
			echo "<Br><br>  refresh sucess!!";
			echo "<br><a href=\"".get_bloginfo('home')."/list_office.xml"."\" target=\"_blank\">".get_bloginfo('home')."/list_office.xml"."</a>";
		}else{
			echo "<Br><br>  refresh failed!!";
		}
		fputs($fp,  $liststr) ;        
    	fclose($fp);    
	}

?>
<Br><Br><Br><Br>
<a href="<?php echo $_SERVER["REQUEST_URI"]."&lang=tw"; ?>">發佈XML</a><Br><Br>


</div>
					
					
					
					<?php
				}//End function printAdminPage()
		
		
		
		
	}// end class
}//end if


//
if(class_exists("CATBanner")){
   $cat_banner = new CATBanner();	

}

//Initialize the admin panel
if (!function_exists("CATBanner_ap")) {
	function CATBanner_ap() {
		global $cat_banner;
		if (!isset($cat_banner)) {
			return;
		}
		if (function_exists('add_options_page')) {
			add_options_page('CAT banner', 'CAT banner', 9, basename(__FILE__), array(&$cat_banner, 'printAdminPage'));
		}
	}	
}


if(isset($cat_banner)){
   //action
   add_action('admin_menu', 'CATBanner_ap');
}


?>