<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/8/4
 * Time: 15:06
 */
// Usage 1:
$hash = password_hash("wc456123", PASSWORD_DEFAULT);
echo $hash;
if (password_verify('wc456123', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}