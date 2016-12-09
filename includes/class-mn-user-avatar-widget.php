<?php
/**
 * Defines widgets.
 *
 * @package User Avatar
 * @version 1.9.13
 */

class MN_User_Avatar_Profile_Widget extends MN_Widget {
  /**
   * Constructor
   * @since 1.9.4
   */
  public function __construct() {
    $widget_ops = array('classname' => 'widget_mn_user_avatar', 'description' => __('Insert').' '.__('[avatar_upload]', 'mn-user-avatar').'.');
    parent::__construct('mn_user_avatar_profile', __('User Avatar', 'mn-user-avatar'), $widget_ops);
  }

  /**
   * Add [avatar_upload] to widget
   * @since 1.9.4
   * @param array $args
   * @param array $instance
   * @uses object $mn_user_avatar
   * @uses bool $mnua_allow_upload
   * @uses object $mnua_shortcode
   * @uses add_filter()
   * @uses apply_filters()
   * @uses is_user_logged_in()
   * @uses remove_filter()
   * @uses mnua_edit_shortcode()
   * @uses mnua_is_author_or_above()
   */
  public function widget($args, $instance) {
    global $mn_user_avatar, $mnua_allow_upload, $mnua_shortcode;
    extract($args);
    $instance = apply_filters('mnua_widget_instance', $instance);
    $title = apply_filters('widget_title', empty($instance['title']) ? "" : $instance['title'], $instance, $this->id_base);
    $text = apply_filters('widget_text', empty($instance['text']) ? "" : $instance['text'], $instance);
    // Show widget only for users with permission
    if($mn_user_avatar->mnua_is_author_or_above() || ((bool) $mnua_allow_upload == 1 && is_user_logged_in())) {
      echo $before_widget;
      if(!empty($title)) {
        echo $before_title.$title.$after_title;
      }
      if(!empty($text)) {
        echo '<div class="textwidget">';
        echo !empty($instance['filter']) ? mnautop($text) : $text;
        echo '</div>';
      }
      // Remove profile title
      add_filter('mnua_profile_title', '__return_null');
      // Get [avatar_upload] shortcode
      echo $mnua_shortcode->mnua_edit_shortcode("");
      remove_filter('mnua_profile_title', '__return_null');
    }
  }

  /**
   * Set title
   * @since 1.9.4
   * @param array $instance
   * @uses mn_parse_args()
   */
  public function form($instance) {
    $instance = mn_parse_args((array) $instance, array('title' => "", 'text' => ""));
    $title = strip_tags($instance['title']);
    $text = esc_textarea($instance['text']);
  ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php _e('Title:','mn-user-avatar'); ?>
      </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Description:','mn-user-avatar'); ?></label>
    <textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
    <p>
      <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />
      <label for="<?php echo $this->get_field_id('filter'); ?>">
        <?php _e('Automatically add paragraphs','mn-user-avatar'); ?>
      </label>
    </p>
  <?php
  }

  /**
   * Update widget
   * @since 1.9.4
   * @param array $new_instance
   * @param array $old_instance
   * @uses current_user_can()
   * @return array
   */
  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    if(current_user_can('unfiltered_html')) {
      $instance['text'] =  $new_instance['text'];
    } else {
      $instance['text'] = stripslashes(mn_filter_post_kses(addslashes($new_instance['text'])));
    }
    $instance['filter'] = isset($new_instance['filter']);
    return $instance;
  }
}