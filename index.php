<?php
// Automatically redirect to the public folder (either Laravel or raw PHP)
if (is_dir(__DIR__ . '/laravel')) {
    header("Location: laravel/public/");
} else {
    header("Location: public/");
}
exit;
