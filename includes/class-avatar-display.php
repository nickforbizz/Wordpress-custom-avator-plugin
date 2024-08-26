<?php

namespace CustomAvatar;

class Avatar_Display
{
    public function init()
    {
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
    public function custom_or_gravatar_avatar($avatar, $id_or_email, $size, $default, $alt, $args)
    {
        $user_id = 0;

        // Handle if $id_or_email is a numeric ID
        if (is_numeric($id_or_email)) {
            $user_id = (int) $id_or_email;
        }
        // Handle if $id_or_email is an email address
        elseif (is_email($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
            if ($user) {
                $user_id = $user->ID;
            }
        }
        // Handle if $id_or_email is an object
        elseif (is_object($id_or_email)) {
            // Handle WP_User object
            if (isset($id_or_email->ID)) {
                $user_id = $id_or_email->ID;
            }
            // Handle WP_Comment object
            elseif (isset($id_or_email->user_id)) {
                $user_id = $id_or_email->user_id;
            }
        }

        if ($user_id && $custom_avatar_url = get_user_meta($user_id, 'custom_avatar', true)) {
            $avatar = '<img class="custom_avator_profile_img" src="' . esc_url($custom_avatar_url) . '" alt="' . esc_attr($alt) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" class="' . esc_attr($args['class']) . '"/>';
        }

        return $avatar;
    }
}
