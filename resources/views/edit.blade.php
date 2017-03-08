<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Ace Editor</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.0/css/mdb.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.0/js/mdb.min.js"></script>
        <style>
          * { margin:0; padding:0; }
          html,body { width:100%; height:100%; overflow:hidden; }
          button { width:120px; cursor: pointer; }
          #sider { width:30%; height:100%; float:left;}
          #editor { width:70%; height:100%; float:left; }
          .card-header { font: 22px bold; padding:10px; }
          .fileinfo { padding:10px 15px; }
          .fileinfo span { margin-left: 8px; font-weight:bold; color:#666; text-decoration:underline; }
          .recent-files { padding:5px 20px; line-height:25px; }
          .recent-files li:hover { text-decoration:underline; }
        </style>
    </head>
    <body>
      <div id="sider" class="card">
        <h3 class="card-header primary-color white-text">Ace Source Code Editor</h3>
        <div class="card-block">
          <div class="card">
            <div class="card-block">
              <div class="fileinfo">
                <p>文件:<span>{{ $pathinfo['basename'] }}</span></p>
                <p>目录:<span>{{ $pathinfo['dirname'] }}</span></p>
                <p>路径:<span>{{ $realpath }}</span></p>
                <p>模式:<span>{{ $mode }}</span></p>
                <div class="btn-groups">
                  <button class="btn btn-success btn-save">保存</button>
                  <button class="btn btn-info btn-reload">刷新</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <h4 class="card-header">最近编辑...</h4>
            <div class="card-block">
              <ul class="list-group recent-files">
                @foreach($recent_files as $f=>$c)
                  <li class="list-group-item justify-content-between">
                    {{ ($loop->index + 1) . '#' }}<a href="/edit?file={{ $f }}">{{ $f }}</a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="editor">{{ $content }}</div>
      <script src="/js/ace/ace.js"></script>
      <script>
        $(function(){

          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          var editor = ace.edit('editor');
          editor.setTheme('ace/theme/monokai');
          editor.getSession().setMode('ace/mode/{{ $mode }}');

          $('.btn-save').on('click', function(){
            if(confirm("当前页面的内容会覆盖远端文件，是否确认操作?")){
              $.post('/save', {content: editor.getValue()}, function(d){
                if(d && d.r == 1){
                  alert("保存成功!");
                }else{
                  alert(d.msg);
                }
              });
            }
          });

          $('.btn-reload').on('click', function() {
            if(confirm("当前页面内未保存的内容会被覆盖，是否确认操作?")){
              $.get('/reload', function(d){
                if(d && d.content){
                  editor.setValue(d.content);
                }
              });
            }
          });
        });
      </script>
    </body>
</html>
