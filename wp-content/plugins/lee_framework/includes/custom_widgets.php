<?php
// Include Custom widgets
foreach (glob(LEE_FRAMEWORK_PLUGIN_PATH . '/includes/widgets/*.php') as $file) {
    include_once $file;
}