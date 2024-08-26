<?php
namespace CustomAvatar;

class Avatar_Display {
    public function init() {
        add_filter('get_avatar', array($this, 'custom_or_gravatar_avatar'), 10, 6);
    }

    /**
     * custom_or_gravatar_avatar function
     *
     * @param  [type] $avatar
     * @param  [type] $id_or_email
     * @param  [type] $size
     * @param  [type] $default
     * @param  [type] $alt
     * @param  [type] $args
     * @return void
     */
    public function custom_or_gravatar_avatar($avatar, $id_or_email, $size, $default, $alt, $args) {
        $user_id = is_numeric($id_or_email) ? $id_or_email : (is_object($id_or_email) ? $id_or_email->ID : 0);

        if ($user_id && $custom_avatar_url = get_user_meta($user_id, 'custom_avatar', true)) {
            $avatar = '<img src="' . esc_url($custom_avatar_url) . '" alt="' . esc_attr($alt) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" class="' . esc_attr($args['class']) . '"/>';
        }

        return $avatar;
    }
}
