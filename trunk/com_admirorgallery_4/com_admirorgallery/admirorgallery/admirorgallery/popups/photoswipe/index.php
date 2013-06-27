<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

// Load JavaScript from current popup folder
$this->loadJS($this->currPopupRoot . 'lib/klass.min.js');
$this->loadJS($this->currPopupRoot . 'code.photoswipe-3.0.5.min.js');

// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot . 'photoswipe.css');

// Set REL attribute needed for Popup engine
$this->popupEngine->rel = 'photoswipe[AdmirorGallery' . $this->getGalleryID() . ']';

// Insert JavaScript code needed to be loaded after gallery is formed
$this->popupEngine->endCode = '	
<script type="text/javascript">
    (function(window, PhotoSwipe){		
        document.addEventListener(\'DOMContentLoaded\', function(){
            var options = { enableMouseWheel: false , enableKeyboard: false },
            instance = PhotoSwipe.attach( window.document.querySelectorAll(\'#AG_01 a\'), options );
        }, false);
    }
    (window, window.Code.PhotoSwipe));
</script>		
';
?>