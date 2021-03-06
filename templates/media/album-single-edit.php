<?php
global $rtmedia_query;

$model = new RTMediaModel();

$media = $model->get_media(array('id' => $rtmedia_query->media_query['album_id']), false, false);
global $rtmedia_media;
$rtmedia_media = $media[0];
?>
<div class="rtmedia-container rtmedia-single-container">
    <form method="post">
        <?php
        RTMediaMedia::media_nonce_generator($rtmedia_query->media_query['album_id']);
        $post_details = get_post($media[0]->media_id);
        $content = apply_filters('the_content', $post_details->post_content);
	$content = $post_details->post_content;
        ?>
        <h2><?php echo __ ( 'Edit Album : ' , 'rtmedia' ) . esc_attr($media[0]->media_title) ; ?></h2>
        <label for='media_title'><?php _e('Title: ', 'rtmedia'); ?></label>
        <input type="text" name="media_title" id='media_title' value="<?php echo esc_attr($media[0]->media_title); ?>" />
	<?php do_action("rtmedia_add_album_privacy"); ?>
        <div class="rtmedia-editor-description">
            <label for='media_title'><?php _e('Description: ', 'rtmedia'); ?></label>
            <?php wp_editor($content, 'description', array('media_buttons' => false, 'textarea_rows' => 4, 'quicktags' => false)); ?>
            <input type="submit" name="submit" value="<?php _e('Save', 'rtmedia'); ?>" />
        </div>


    </form>
    <?php if(!is_rtmedia_group_album()) { ?>
    <?php if (have_rtmedia()) { ?>
        <br />
        <form class="rtmedia-bulk-actions" method="post">
            <?php wp_nonce_field('rtmedia_bulk_delete_nonce', 'rtmedia_bulk_delete_nonce'); ?>
            <?php RTMediaMedia::media_nonce_generator($rtmedia_query->media_query['album_id']); ?>
            <span class="rtmedia-selection"><a class="select-all" href="#"><?php echo __('Select All Visible','rtmedia'); ?></a> |
                <a class="unselect-all" href="#"><?php _e('Unselect All Visible','rtmedia'); ?></a> | </span>
            <br />
            <input type="button" class="button rtmedia-move" value="<?php _e('Move','rtmedia'); ?>" />
            <input type="submit" name="delete-selected" class="button rtmedia-delete-selected" value="<?php _e('Delete Selected','rtmedia'); ?>" />
            <div class="rtmedia-move-container">
                <?php $global_albums = rtmedia_get_site_option('rtmedia-global-albums'); ?>
                <?php _e('Move selected media to', 'rtmedia'); ?>
                <?php echo '<select name="album" class="rtmedia-user-album-list">'.rtmedia_user_album_list().'</select>'; ?>
                <input type="submit" class="rtmedia-move-selected" name="move-selected" value="<?php _e('Move Selected','rtmedia'); ?>" />
            </div>


            <ul class="rtmedia-list  large-block-grid-4">

                <?php while (have_rtmedia()) : rtmedia(); ?>

                    <?php include ('media-gallery-item.php'); ?>

                <?php endwhile; ?>

            </ul>


            <!--  these links will be handled by backbone later
                                            -- get request parameters will be removed  -->
            <?php
            $display = '';
            if (rtmedia_offset() != 0)
                $display = 'style="display:block;"';
            else
                $display = 'style="display:none;"';
            ?>
            <a id="rtMedia-galary-prev" <?php echo $display; ?> href="<?php echo rtmedia_pagination_prev_link(); ?>"><?php _e('Prev','rtmedia'); ?></a>

            <?php
            $display = '';
            if (rtmedia_offset() + rtmedia_per_page_media() < rtmedia_count())
                $display = 'style="display:block;"';
            else
                $display = 'style="display:none;"';
            ?>
            <a id="rtMedia-galary-next" <?php echo $display; ?> href="<?php echo rtmedia_pagination_next_link(); ?>"><?php _e('Next','rtmedia'); ?></a>

        <?php } else { ?>
            <p><?php _e('The album is empty.', 'rtmedia'); ?></p>
        <?php } ?>
    <?php } ?>
    </form>


</div>