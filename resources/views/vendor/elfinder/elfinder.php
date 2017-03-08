<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>

    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/elfinder.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/theme.css') ?>">

    <!-- elFinder JS (REQUIRED) -->
    <script src="<?= asset($dir.'/js/elfinder.min.js') ?>"></script>

    <?php if($locale){ ?>
    <!-- elFinder translation (OPTIONAL) -->
    <script src="<?= asset($dir."/js/i18n/elfinder.$locale.js") ?>"></script>
    <?php } ?>

    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function() {
            elFinder.prototype.i18.en.messages['cmdaceedit'] = 'AceEdit';
            elFinder.prototype._options.commands.push('aceedit');
            elFinder.prototype.commands.aceedit = function() {
                this.exec = function(hashes){
                    var file = this.files(hashes),
                        hash = file[0].hash,
                        file = this.fm.file(hash),
                        path = this.fm.path(hash);
                    if(file.mime == 'directory'){
                        alert("AceEdit不能以目录为对象!");
                    }else{
                        window.open("/edit?trip_alias=true&file=" + path);
                    }
                };
                this.getstate = function(){
                    return 0;
                };
            };

            $('#elfinder').elfinder({
                // set your elFinder options here
                <?php if($locale){ ?>
                    lang: '<?= $locale ?>', // locale
                <?php } ?>
                customData: { 
                    _token: '<?= csrf_token() ?>'
                },
                url : '<?= route("elfinder.connector") ?>',  // connector URL
                soundPath: '<?= asset($dir.'/sounds') ?>',
                contextmenu: {
                    navbar : ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'info'],
                    cwd: [
                        'reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'sort', '|', 'info'
                    ],
                    files: [
                        'getfile', '|', 'open', 'aceedit', '|', 'download', '|', 'copy', 'cut', 'paste', '|', 'rm', 'rename', 'resize', '|', 'archive', 'extract', 'info'
                    ]
                }
            });
        });
    </script>
</head>
<body>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>

</body>
</html>
