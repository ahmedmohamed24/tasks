<?php

function getAvatarUrl($email)
{
    $email = md5($email);

    return "https://gravatar.com/avatar/{$email}?s=25&d=https://www.maxpixel.net/static/photo/1x/Placeholder-Avatar-Photo-User-Enter-1808597.png";
}