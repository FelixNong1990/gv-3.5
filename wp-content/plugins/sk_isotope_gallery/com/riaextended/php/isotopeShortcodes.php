<?php
/**
 * Shortcode
 */
require_once(IISOTOPE_CLASS_PATH.'/com/riaextended/php/libs/rx_resizer.php');
class rxIsotopeShortcodes{
	
	public function registerShortcodes(){						
		add_shortcode('isotope_gallery', array($this, 'rx_isotope_gallery'));																										
	}
	
	/* igallery shortcode
	================================================== */	
	public function rx_isotope_gallery($atts, $content = null){
		extract(shortcode_atts(array('id' => ''), $atts));
		$return_val = 'salut';		
		
		//get post
		$gallery_post = get_post($id, OBJECT);
		if(!is_null($gallery_post)){
			$gallery_post_meta = get_post_meta($gallery_post->ID, IISOTOPE_SLUG.'-data', false);
			$gallery_thumb_size = get_post_meta($gallery_post->ID, IISOTOPE_SLUG.'-thumbs_size', false);
			$gallery_extra_data = get_post_meta($gallery_post->ID, IISOTOPE_SLUG.'-extra_data', false);
			
			$wdt = 250;	
			if(isset($gallery_thumb_size[0])){
				$wdt = (float)$gallery_thumb_size[0]['width'];										
			}
			$gap01 = 5;
			$lightbox_colors = "6abde9";
			$menu_text_color = "484848";
			$menu_back_color = "6abde9";
			$label_all = "All";			
													
			if(isset($gallery_extra_data[0])){
				$gap01 = (float)$gallery_extra_data[0]['gap01'];
				$lightbox_colors = $gallery_extra_data[0]['lightbox_colors'];
				$menu_text_color = $gallery_extra_data[0]['menu_text_color'];	
				$menu_back_color = $gallery_extra_data[0]['menu_back_color'];
				$label_all = $gallery_extra_data[0]['label_all'];														
			}
			$itemsColor = $menu_back_color;
			$rgba = $this->html2rgb($itemsColor);	
												
			$isotopeMenuHTML = '<ul class="isotopeMenu" data-selectedcolor="'.$menu_back_color.'" data-lightboxcolor="'.$lightbox_colors.'">';	
			$isotopeMenuHTML .= '<li><a class="eye" style="color: #'.$menu_text_color.';" href="*">'.$label_all.'</a></li>';	
			
			$isotopeItemsHTML = '';			
			$return_val = '';
			
			if(isset($gallery_post_meta[0])){
				$mainJSONData = (isset($gallery_post_meta[0]['mainJSONData']))?$mainJSONData = $gallery_post_meta[0]['mainJSONData']:$mainJSONData='';				
				$temp_data = json_decode($mainJSONData);

				$groups = $temp_data->groups;
				$count_items = -1;
				//interate groups	
				for ($i=0; $i < sizeof($groups); $i++) {
					$groupName = $groups[$i]->name;
					$id_group = $groups[$i]->idGroup;						
					$isotopeMenuHTML .= '<li><a class="eye" style="color: #'.$menu_text_color.';" href="_group'.$id_group.'">'.$groupName.'</a></li>';					
										 					
					$groupImages = $groups[$i]->imageItems;									
					for ($j=0; $j < sizeof($groupImages); $j++) {
						$count_items++;												
						//image caption
						$imageCaption = wptexturize($groupImages[$j]->caption);
												
						$attachementID = $groupImages[$j]->attachementID;
						$thumbHeight = $groupImages[$j]->imageHeight;					
						//image full
						$imageFullArray = wp_get_attachment_image_src($attachementID, 'full');
						($imageFullArray)?$imgFullUrl = $imageFullArray[0]:$imgFullUrl='http://placehold.it/1000x800';
						
						//thumb
						$thumb_url = 'http://placehold.it/'.$wdt.'x'.$thumbHeight;
						if($imageFullArray){
							$thumb_temp_url = rx_resize($imgFullUrl, $wdt, $thumbHeight, true);
							($thumb_temp_url)?$thumb_url = $thumb_temp_url:$thumb_url=$thumb_url;
						}
						
						$isotopeItemsHTML .= '<div style="width: '.$wdt.'px; height: '.$thumbHeight.'px; margin: '.$gap01.'px;" class="isotopeItem _group'.$id_group.'">
							<img class="isotopeThumb" src="'.$thumb_url.'" />
							<div class="isotopeItemOverlay" data-indx="'.$count_items.'" data-full_url="'.$imgFullUrl.'" style="background-color: #'.$itemsColor.';background: rgba('.$rgba[0].', '.$rgba[1].', '.$rgba[2].', .15);">
								<div class="rx_isotope_beacon">
									<div class="genericBeaconIsotope" data-href="#">
										<div class="beaconCircle1" style="background-color: #'.$itemsColor.';background: rgba('.$rgba[0].', '.$rgba[1].', '.$rgba[2].', .4);"></div>
										<div class="beaconCircle2" style="background-color: #'.$itemsColor.';background: rgba('.$rgba[0].', '.$rgba[1].', '.$rgba[2].', .8);">
											<img class="isotopeItemOpenLink" src="'.IISOTOPE_TEMPPATH.'/images/link_icon_beacon.png" alt="" />
										</div>
									</div>	
								</div>
								<p class="isotopeItemCaption">'.$imageCaption.'</p>							
							</div>						
						</div>';					
					}									
				}

				$isotopeMenuHTML .= '</ul>';
				
				$return_val = '<div class="rx_isotope_ui">';
				
					$return_val .= $isotopeMenuHTML;
					$return_val .= '<div class="isotope_top_space"></div>';
					$return_val .= '<div class="isotopeContainer">';
					$return_val .= $isotopeItemsHTML;
					$return_val .= '</div>';
				
				$return_val .= '</div>';
				
			}else{
				$return_val = 'gallery meta data not found';
			}
		}else{
			$return_val = 'gallery not found';
		}
				
		return $return_val;		
	}
	
	//utils - convert hex to rgb	
	protected function html2rgb($color)
	{
	    if ($color[0] == '#')
	        $color = substr($color, 1);
	    if (strlen($color) == 6)
	        list($r, $g, $b) = array($color[0].$color[1],
	                                 $color[2].$color[3],
	                                 $color[4].$color[5]);
	    elseif (strlen($color) == 3)
	        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	    else
	        return false;
	    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
	    return array($r, $g, $b);
	}					

		
}

?>