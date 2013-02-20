<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BPMediaPrivacySettings
 *
 * @author saurabh
 */
class BPMediaPrivacySettings {

	/**
	 *
	 */
	function __construct() {
		$this->progress = new rtProgress();

	}

	function get_completed_count(){
		global $wpdb;
		$query =
                    "SELECT	COUNT(*) as Total
	FROM
		$wpdb->posts RIGHT JOIN $wpdb->postmeta on wp_postmeta.post_id = wp_posts.id
	WHERE
		`meta_key` = 'bp_media_privacy' AND
		( post_mime_type LIKE 'image%' OR post_mime_type LIKE 'audio%' OR post_mime_type LIKE 'video%' OR post_type LIKE 'bp_media_album')";
            return $result = $wpdb->get_results($query);
	}

	function get_total_count(){
		global $wpdb;
		$query =
                    "SELECT	COUNT(*) as Total
	FROM
		$wpdb->posts RIGHT JOIN $wpdb->postmeta on wp_postmeta.post_id = wp_posts.id
	WHERE
		`meta_key` = 'bp-media-key' AND
		( post_mime_type LIKE 'image%' OR post_mime_type LIKE 'audio%' OR post_mime_type LIKE 'video%' OR post_type LIKE 'bp_media_album')";
            $result = $wpdb->get_results($query);
		return $result;
	}

	function query(){

	}

	function init(){
		echo '<div id="rtprivacyinstaller">';
		$total = $this->get_total_count();
		$total = $total[0];
		$finished = $this->get_completed_count();
		$finished = $finished[0];
		foreach($total as $type=>$count){
			$types = $type;
			echo '<div class="rtprivacytype" id="'.strtolower($type).'">';
			echo '<strong>'.ucfirst($types).'</strong>: ';
			echo $finished->$type .' / '.$count;
			$progress =100;
			if($count!=0){
				$todo = $count-$finished->$type;
				$steps = ceil($todo/20);
				$progress = $this->progress->progress($finished->$type,$count);
				echo '<input type="hidden" value="'.$finished->$type.'" name="finished"/>';
				echo '<input type="hidden" value="'.$count.'" name="total"/>';
				echo '<input type="hidden" value="'.$todo.'" name="todo"/>';
				echo '<input type="hidden" value="'.$steps.'" name="steps"/>';

			}
			$this->progress->progress_ui($progress);
			echo "<br>";
			echo '</div>';
		}
		echo '<button id="rtprivacyinstall" class="button button-primary">Install Privacy</button>';
		echo '</div>';
	}

}

?>
