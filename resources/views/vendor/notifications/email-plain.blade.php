<?php

/**
 * This template was customized by:
 *  - using strip_tags
 */

if (! empty($greeting)) {
    echo $greeting, "\n\n";
} else {
    echo $level == 'error' ? 'Whoops!' : 'Hello!', "\n\n";
}

if (! empty($introLines)) {
    echo strip_tags(implode("\n", $introLines), '<a>'), "\n\n";
}

if (isset($actionText)) {
    echo "{$actionText}: {$actionUrl}", "\n\n";
}

if (! empty($outroLines)) {
    echo strip_tags(implode("\n", $outroLines), '<a>'), "\n\n";
}

echo 'Regards,', "\n";
echo config('app.name'), "\n";
