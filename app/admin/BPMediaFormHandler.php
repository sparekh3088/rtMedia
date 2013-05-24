<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BPMediaFormHandler
 *
 * @author udit
 */
class BPMediaFormHandler {

	public static function checkbox($args) {

		global $bp_media;
		$options = $bp_media->options;
		$defaults = array(
			'setting' => '',
			'option' => '',
			'desc' => '',
			'show_desc' => false
		);
		$args = wp_parse_args($args, $defaults);
		extract($args);

		if (empty($option)) {
			trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the following format array( \'option\' => \'option_name\' ) ', 'buddypress-media'));
			return;
		}

		$args['id'] = $option;

		if (!empty($setting)) {
			$args['name'] = $setting . '[' . $option . ']';
			$options = bp_get_option($setting);
		}
		else
			$args['name'] = $option;

		if (!isset($options[$option]))
			$options[$option] = '';

		$args['rtForm_options'] = array(array('' => 1, 'checked' => $options[$option]));

		$chkObj = new rtForm();
		echo $chkObj->get_checkbox($args);
	}

	public static function radio($args) {
		global $bp_media;
            $options = $bp_media->options;
            $defaults = array(
                'setting' => '',
                'option' => '',
                'radios' => array(),
                'default' => '',
				'show_desc' => false
            );
            $args = wp_parse_args($args, $defaults);
            extract($args);
            if (empty($option) || ( 2 > count($radios) )) {
                if (empty($option))
                    trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the following format array( \'option\' => \'option_name\' )', 'buddypress-media'));
                if (2 > count($radios))
                    trigger_error(__('Need to specify atleast to radios else use a checkbox instead', 'buddypress-media'));
                return;
            }

            if (!empty($setting)) {
                $name = $setting . '[' . $option . ']';
                $options = bp_get_option($setting);
            } else
                $name = $option;

            if ((isset($options[$option]) && empty($options[$option])) || !isset($options[$option])) {
                $options[$option] = $default;
            }

            foreach ($radios as $value => $desc) {
                    ?>
                <label for="<?php echo sanitize_title($desc); ?>"><input<?php checked($options[$option], $value); ?> value="<?php echo $value; ?>" name="<?php echo $name; ?>" id="<?php echo sanitize_title($desc); ?>" type="radio" />&nbsp;<?php echo $desc; ?></label><br /><?php
            }
	}

	public static function dimensions($args) {

		$dmnObj = new rtDimensions();
		echo $dmnObj->get_dimensions($args);
	}

	public static function number($args) {
		global $bp_media;
		$options = $bp_media->options;
		$defaults = array(
			'setting' => '',
			'option' => '',
			'desc' => '',
			'password' => false,
			'hidden' => false,
			'number' => false,
		);
		$args = wp_parse_args($args, $defaults);
		extract($args);

		if (empty($option)) {
			trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the following format array( \'option\' => \'option_name\' )', 'buddypress-media'));
			return;
		}

		if (!empty($setting)) {
			$args['name'] = $setting . '[' . $option . ']';
			$options = bp_get_option($setting);
		}
		else
			$args['name'] = $option;

		if ((isset($options[$option]) && empty($options[$option])) || !isset($options[$option])) {
			$options[$option] = '';
		}

		$args['id'] = sanitize_title($option);
		$args['value'] = $options[$option];

		$numObj = new rtForm();
		echo $numObj->get_number($args);
	}

	public static function types_content($page = '') {

		global $wp_settings_fields;

		if (!isset($wp_settings_fields) ||
				!isset($wp_settings_fields[$page]) ||
				!isset($wp_settings_fields[$page]['bpm-settings']) ||
				!isset($wp_settings_fields[$page]['bpm-featured']))
			return;

		$bpm_settings = $wp_settings_fields[$page]['bpm-settings'];
		$bpm_featured = $wp_settings_fields[$page]['bpm-featured'];
		$headers = array(
			array('title' => "Media", 'class' => 'large-4'),
			array('title' => "Enable", 'class' => 'large-1'),
			array('title' => "Featured", 'class' => 'large-1'),
			array('title' => "File Extensions", 'class' => 'large-4')
		);

		$image = array(
			array(
				'class' => 'large-4',
				'content' => $bpm_settings['bpm-image']['title']
			),
			array(
				'class' => 'large-1',
				'callback' => $bpm_settings['bpm-image']['callback'],
				'args' => $bpm_settings['bpm-image']['args']
			),
			array(
				'class' => 'large-1',
				'callback' => $bpm_featured['bpm-featured-image']['callback'],
				'args' => $bpm_featured['bpm-featured-image']['args']
			),
			array(
				'class' => 'large-4',
				'content' => "gif,jpeg,png"
			),
		);

		$video = array(
			array(
				'class' => 'large-4',
				'content' => $bpm_settings['bpm-video']['title']
			),
			array(
				'class' => 'large-1',
				'callback' => $bpm_settings['bpm-video']['callback'],
				'args' => $bpm_settings['bpm-video']['args']
			),
			array(
				'class' => 'large-1',
				'callback' => $bpm_featured['bpm-featured-video']['callback'],
				'args' => $bpm_featured['bpm-featured-video']['args']
			),
			array(
				'class' => 'large-4',
				'content' => "avi,mp4,mpeg"
			),
		);

		$audio = array(
			array(
				'class' => 'large-4',
				'content' => $bpm_settings['bpm-audio']['title']
			),
			array(
				'class' => 'large-1',
				'callback' => $bpm_settings['bpm-audio']['callback'],
				'args' => $bpm_settings['bpm-audio']['args']
			),
			array(
				'class' => 'large-1',
				'callback' => $bpm_featured['bpm-featured-audio']['callback'],
				'args' => $bpm_featured['bpm-featured-audio']['args']
			),
			array(
				'class' => 'large-4',
				'content' => "mp3,wav"
			),
		);

		$body = array($image, $video, $audio);

		//container
		echo '<div class="large-12">';

		//header
		echo '<div class="row">';
		foreach ($headers as $val) {
			echo '<h4 class="columns ' . $val['class'] . '">' . $val['title'] . '</h4>';
		}
		echo '</div>';
		echo '<hr>';

		//body
		foreach ($body as $section) {
			echo '<div class="row">';
			foreach ($section as $value) {
				echo '<div class="columns ' . $value['class'] . '">';

				if (isset($value['content']))
					echo $value['content'];
				else
					call_user_func($value['callback'], $value['args']);
				echo '</div>';
			}
			echo '</div>';
		}

		echo '</div>';
	}

	public static function sizes_content($page = '') {

		global $wp_settings_sections, $wp_settings_fields;

		if (!isset($wp_settings_fields) ||
				!isset($wp_settings_fields[$page]) ||
				!isset($wp_settings_fields[$page]['bpm-image-settings']) ||
				!isset($wp_settings_fields[$page]['bpm-video-settings']) ||
				!isset($wp_settings_fields[$page]['bpm-audio-settings']) ||
				!isset($wp_settings_fields[$page]['bpm-featured']))
			return;

		$dimension = '<span class="large-offset-1">Width</span>
					<span class="large-offset-2">Height</span>
					<span class="large-offset-2">Crop</span>';
		$headers = array(
			array('title' => 'Category', 'class' => 'large-3'),
			array('title' => 'Entity', 'class' => 'large-3'),
			array('title' => $dimension, 'class' => 'large-4')
		);

		$sections = array("bpm-image-settings", "bpm-video-settings", "bpm-audio-settings", "bpm-featured");

		$contents = array();
		$body = array();
		foreach ($sections as $section) {

			$contents[$section] = array(
				'entity_names' => array(),
				'callbacks' => array(),
				'args' => array()
			);

			if ($section == "bpm-featured") {
				$contents[$section]['entity_names'][] = $wp_settings_fields[$page][$section]['bpm-featured-media-dimensions']['title'];
				$contents[$section]['callbacks'][] = $wp_settings_fields[$page][$section]['bpm-featured-media-dimensions']['callback'];
				$contents[$section]['args'][] = $wp_settings_fields[$page][$section]['bpm-featured-media-dimensions']['args'];
			} else {
				foreach ($wp_settings_fields[$page][$section] as $value) {
					$contents[$section]['entity_names'][] = $value['title'];
					$contents[$section]['callbacks'][] = $value['callback'];
					$contents[$section]['args'][] = $value['args'];
				}
			}

			$body[$section] = array(
				//title
				array(
					'class' => 'large-3',
					'content' => ( $section == "bpm-featured" ) ? "Featured Media" : $wp_settings_sections[$page][$section]['title']
				),
				//entity names
				array(
					'class' => 'large-3',
					'content' => ( $section == "bpm-featured" ) ? $wp_settings_fields[$page][$section]['bpm-featured-media-dimensions']['title'] : $contents[$section]['entity_names']
				),
				//dimensions
				array(
					'class' => 'large-4',
					'callbacks' => $contents[$section]['callbacks'],
					'args' => $contents[$section]['args']
				)
			);
		}


		//container
		echo '<div class="large-12">';

		//header
		echo '<div class="row">';
		foreach ($headers as $value) {
			echo '<h4 class="columns ' . $value['class'] . '">' . $value['title'] . '</h4>';
		}
		echo'</div>';
		echo '<hr>';

		//body
		foreach ($body as $section) {
			echo '<div class="row">';
			foreach ($section as $value) {
				echo '<div class="columns ' . $value['class'] . '">';
				if (isset($value['content'])) {
					if (is_array($value['content'])) {
						foreach ($value['content'] as $entity) {
							echo '<div class="entity">';
							echo $entity;
							echo '</div>';
						}
					}
					else
						echo $value['content'];
				} else {
					for ($i = 0; $i < count($value['callbacks']); $i++) {
						echo '<div>';
						call_user_func($value['callbacks'][$i], $value['args'][$i]);
						echo '</div>';
					}
				}
				echo '</div>';
			}
			echo '</div>';
		}

		echo '</div>';
	}

	public static function privacy_content($page = '') {

		global $wp_settings_fields;

		if (!isset($wp_settings_fields) ||
				!isset($wp_settings_fields[$page]) ||
				!isset($wp_settings_fields[$page]['bpm-privacy']))
			return;

		echo '<div class="large-12">';
			foreach ($wp_settings_fields[$page]['bpm-privacy'] as $key => $value) {
				echo '<div class="row ' . $key . '">';
					echo '<h4 class="columns large-3">';
						$args = array_merge_recursive($value['args'],
									array('class' => array("large-offset-1")),
									array('label' => $value['title']));
						call_user_func($value['callback'],$args);
					echo '</h4>';
				echo '</div>';
			}
		echo '</div>';
	}

	public static function misc_content($page = '') {
		echo 'content misc';
	}

	public static function rtForm_settings_tabs_content($page, $sub_tabs) {

		global $wp_settings_sections, $wp_settings_fields;

		if (!isset($wp_settings_sections) || !isset($wp_settings_sections[$page]))
			return;

		echo '<div id="bpm-settings-contents">';

		foreach ($sub_tabs as $tab) {
			echo '<div class="tab-content" id="' . substr($tab['href'], 1) . '">
					<h3>' . $tab['title'] . '</h3>';
			call_user_func($tab['callback'], $page);
			echo '</div>';
		}

		echo '</div>';

		echo "<pre>";
//		print_r($wp_settings_sections);
//		echo "<br>---------------------------------------------------------------------------<br><br>";
		print_r($wp_settings_fields);
		echo "</pre>";

//		echo '<div class="small-11 small-centered columns">';
//			foreach ( (array) $wp_settings_sections[$page] as $section ) {
//				if ( $section['title'] )
//					echo "<div><h3>{$section['title']}</h3>";
//
//				if ( $section['callback'] )
//						call_user_func( $section['callback'], $section );
//
//				if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
//					continue;
//				echo '<div class="row small-11 small-centered columns">';
//					self::rtForm_do_settings_fields( $page, $section['id'] );
//				echo '</div></div>';
//			}
//		echo '</div>';
	}

	public static function rtForm_do_settings_fields($page, $section) {
		global $wp_settings_fields;

		if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]))
			return;

		foreach ((array) $wp_settings_fields[$page][$section] as $field) {
			echo '<div class="row">';
			echo '<div class="large-11 columns">';

			if (isset($field['args']['label_for']) && !empty($field['args']['label_for']))
				call_user_func($field['callback'], array_merge($field['args'], array('label' => $field['args']['label_for'])));
			else if (isset($field['title']) && !empty($field['title']))
				call_user_func($field['callback'], array_merge($field['args'], array('label' => $field['title'])));
			else
				call_user_func($field['callback'], $field['args']);
			echo '</div>';
			echo '</div>';
		}
	}
}
?>
