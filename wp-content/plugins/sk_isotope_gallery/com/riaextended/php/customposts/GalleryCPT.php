<?php
require_once(IISOTOPE_CLASS_PATH.'/com/riaextended/php/customposts/GenericPostType.php');
/**
 * Youtube CPT
 */
class isotopeGalleryCPT extends RXGenericPostType {
	
	/* VIDEO CONTAINER
	================================================== */
	public function groups_metabox(){
		global $post;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
			
			
			$custom = get_post_meta($post->ID, $this->getPostSlug().'-data', false);			
			//main json data
			$mainJSONData = "";
			
			if(isset($custom[0])){
				$mainJSONData = (isset($custom[0]['mainJSONData']))?$mainJSONData = $custom[0]['mainJSONData']:"";
			}				
		?>
		
	<!--boxes wrapper-->
	<div class="metabox_wrapper">
									
		<!--add group-->
		<div class="contentBox">			
			<a id="addGroupBTN" class="button-primary alignright"><?php _e('Add group', IISOTOPE_TEXTDOMAIN);?></a>
			<div class="vspace1"></div>
		</div>
		<!--add group-->	
		
		<!--groups-->
		<div id="accordion" class="contentBox">			
			 			 						
		</div>
		<!--groups-->		
		
		
		<!--json data-->
		<textarea id="mainJSONData" class="boxContentTextarea" name="<?php echo $this->getPostSlug().'-data'?>[mainJSONData]" rows="4"><?php echo $mainJSONData; ?></textarea>
		<!--/json data-->
		
	</div>
	<!--/boxes wrapper-->
		<?php		
	}
	
	/* PROPERTIES CONTAINER
	================================================== */
	public function properties_metabox(){
		global $post;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		
			$custom_size = get_post_meta($post->ID, $this->getPostSlug().'-thumbs_size', false);
			
			$wdt = 250;
			if(isset($custom_size[0])){
				$wdt = (float)$custom_size[0]['width'];										
			}
			
			$gap01 = 5;			
			$lightbox_colors = "6abde9";
			$menu_text_color = "484848";
			$menu_back_color = "6abde9";
			$label_all = "All";

			$custom_data = get_post_meta($post->ID, $this->getPostSlug().'-extra_data', false);
			if(isset($custom_data[0])){				
				$gap01 = (float)$custom_data[0]['gap01'];				
				($gap01<0)?$gap01=0:$gap01=$gap01;
				
				
				$lightbox_colors = $custom_data[0]['lightbox_colors'];		
				$menu_text_color = $custom_data[0]['menu_text_color'];	
				$menu_back_color = $custom_data[0]['menu_back_color'];
				$label_all = $custom_data[0]['label_all'];														
			}
			
		?>
		
		<!--properties container-->
		<div id="propertiesContainer">
			
			<!--shortcode-->
			<div class="contentBox">
				<label class="customLabel"><?php _e('Shortcode:  ', IISOTOPE_TEXTDOMAIN);?>  <span id="shortcode" data-postid="<?php echo $post->ID;?>" class="sk_defaultText"><span><?php echo '[isotope_gallery id="'.$post->ID.'"]';?></span></span></label>
			</div>
			<!--/shortcode-->
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Thumbnails width</p>
				<div class="vspace2"></div>
                <!--thumbnail width and height-->
                <input id="spinnerThumbW" class="spinnerBox" name="<?php echo $this->getPostSlug().'-thumbs_size';?>[width]" value="<?php echo $wdt;?>" /><label class="customLabel">Width</label>                
                <!--/endthumbnail width and height--> 				
			</div>
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Thumbnails gap</p>
				<div class="vspace2"></div>
                <input id="spinnerGap01" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[gap01]" value="<?php echo $gap01;?>" /><label class="customLabel">Gallery gap</label>	
			</div>			
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Colors</p>
				<div class="vspace2"></div>                
                <input id="lightbox_colors" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[lightbox_colors]" value="<?php echo $lightbox_colors;?>" /><label class="customLabel">Lightbox buttons color</label>                
                <div class="hLine"></div>
                <input id="menu_text_color" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[menu_text_color]" value="<?php echo $menu_text_color;?>" /><label class="customLabel">Menu's text color</label>
                <div class="hLine"></div>
                <input id="menu_back_color" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[menu_back_color]" value="<?php echo $menu_back_color;?>" /><label class="customLabel">Menu's background color</label>                                 			
			</div>
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Labels</p>
				<div class="vspace2"></div>                
                <input class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[label_all]" value="<?php echo $label_all;?>" /><label class="customLabel">Label (all)</label>                                            			
			</div>			
											
				
		</div>
		<!--/properties container-->		
		
		
		<?php		
	}
	
		
}


?>