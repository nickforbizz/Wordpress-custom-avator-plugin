<?php
namespace CustomAvatar;

class Avatar_Handler {
    public function init() {
        $avatar_uploader = new Avatar_Uploader();
        $avatar_uploader->init();

        $avatar_display = new Avatar_Display();
        $avatar_display->init();
    }
}
