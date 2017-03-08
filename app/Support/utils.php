<?php

function get_ext_from_filename($filename) {
    $realpath = realpath($filename);
    filetype($realpath) !== 'file' && die("$filename not exists");
    is_readable($realpath) || die("$filename not readable");
    $r = pathinfo($realpath);
    return $r && isset($r['extension'])?$r['extension']:'text';
}

function get_ace_mode_by_ext($ext) {
    switch($ext) {
        case 'js':return 'javascript';
        case 'md':return 'markdown';
        default: return $ext;
    }
}

function check_file_path($filepath) {
    $realpath = realpath($filepath);
    return (bool)preg_match('/^\/data\/env\/www.*/', $realpath);
}

function load_recent_files() {
    $list = session('recent_files');
    $list = $list?$list:collect([]);
    return $list->sort()->reverse();
}

function add_recent_files($filepath) {
    $list = load_recent_files();
    $c = $list->get($filepath, 0);
    $list[$filepath] = $c + 1;
    $list = $list->sort()->reverse();
    if($list->count() > 10) $list->pop();
    session(['recent_files' => $list]);
}
