<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/edit', function() {
    $filename = request()->input('file');
    $trip_alias = request()->input('trip_alias');
    if($trip_alias){
        $filename = preg_replace('/IXF/', '/data/env/www', $filename, 1);
    }

    if(check_file_path($filename)){
        // save filepath in session
        $realpath = realpath($filename);
        session(['filepath' => $realpath]);
        add_recent_files($realpath);

        $mode = get_ace_mode_by_ext(get_ext_from_filename($realpath));
        $stat = stat($realpath);
        $pathinfo = pathinfo($realpath);
        $content = file_get_contents($realpath);
        return view("edit", [
            'filename' => $filename, 'realpath' => $realpath,
            'stat'  =>  $stat, 'pathinfo' => $pathinfo,
            'content' => $content, 'mode' => $mode,
            'recent_files' => load_recent_files(),
        ]);
    }
    die("no access $filename");
});

Route::post('/save', function() {
    $filepath = session('filepath');
    if(check_file_path($filepath)){
        $realpath = realpath($filepath);
        $content = request()->input('content');
        try{
            file_put_contents($realpath, $content);
            return response()->json(['r' => 1]);
        }catch(\Exception $e){
            return response()->json(['r' => 0, 'msg' => $e->getMessage()]);
        }
    }
});

Route::get('/reload', function() {
    $filepath = session('filepath');
    if(check_file_path($filepath)){
        $realpath = realpath($filepath);
        $content = file_get_contents($realpath);
        return response()->json(['content' => $content]);
    }
});
