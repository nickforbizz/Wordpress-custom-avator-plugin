<?php
namespace CustomAvatar;

class Avatar_Uploader {
    public function init() {
        add_action('show_user_profile', array($this, 'avatar_upload_field'));
        add_action('edit_user_profile', array($this, 'avatar_upload_field'));
        add_action('personal_options_update', array($this, 'save_custom_avatar'));
        add_action('edit_user_profile_update', array($this, 'save_custom_avatar'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    

    /**
     * enqueue_assets function
     *
     * @return void
     */
    public function enqueue_assets() {
        $version = '1.0.0';
        wp_enqueue_style('custom-avatar-css', plugin_dir_url(__FILE__) . '../assets/css/custom-avatar.css', array(), $version);
        wp_enqueue_script('custom-avatar-js', plugin_dir_url(__FILE__) . '../assets/js/custom-avatar.js', array('jquery'), $version, true);
    }


    /**
     * avatar_upload_field function
     *
     * @param  [type] $user
     * @return void
     */
    public function avatar_upload_field($user) { ?>
        <h3>Custom Avatar</h3>
        <table class="form-table">
            <tr>
                <th><label for="custom_avatar">Upload Avatar</label></th>
                <td>
                    <input type="file" name="custom_avatar" id="custom_avatar" /><br />
                    <?php if (get_user_meta($user->ID, 'custom_avatar', true)) : ?>
                        <img src="<?php echo esc_url(get_user_meta($user->ID, 'custom_avatar', true)); ?>" width="96" />
                        <p><strong>Current Avatar</strong></p>
                    <?php endif; ?>
                    <!-- Add nonce field -->
                    <?php wp_nonce_field('custom_avatar_upload_action', 'custom_avatar_upload_nonce'); ?>
                </td>
            </tr>
        </table>
    <?php }


    /**
     * save_custom_avatar function
     *
     * @param  [type] $user_id
     * @return void
     */
    public function save_custom_avatar($user_id) {
        // Verify the nonce before proceeding
        if (!isset($_POST['custom_avatar_upload_nonce']) || !wp_verify_nonce($_POST['custom_avatar_upload_nonce'], 'custom_avatar_upload_action')) {
            // If the nonce check fails, do not proceed
            error_log('Nonce verification failed.');
            return;
        }

        if (!empty($_FILES['custom_avatar']['name'])) {
            // Validate file type
            $allowed_file_types = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array($_FILES['custom_avatar']['type'], $allowed_file_types)) {
                error_log('Invalid file type: ' . $_FILES['custom_avatar']['type']);
                return;
            }
    
            // Handle the file upload
            $upload = wp_handle_upload($_FILES['custom_avatar'], array('test_form' => false));
    
            if (isset($upload['error'])) {
                // Log the error
                error_log('Avatar upload error: ' . $upload['error']);
            } elseif (isset($upload['url'])) {
                // Save the uploaded avatar URL in user meta
                $updated = update_user_meta($user_id, 'custom_avatar', $upload['url']);
                if ($updated) {
                    error_log('Avatar URL saved: ' . $upload['url']);
                } else {
                    error_log('Failed to save avatar URL.');
                }
            }
        }
    }
    
}
