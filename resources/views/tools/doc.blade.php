@extends('layouts.master')
@section('head')
    <title>API文档生成工具 | Killua Chen</title>
@endsection
@section('content')
    <div style="width:500px; margin: 30px auto">
        <br/>
        <form method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="exampleInputEmail1">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $title }}" title=""/>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">URI</label>
                <input type="text" class="form-control" name="uri" value="{{ $uri }}" title=""/>
            </div>
            <div class="form-group">
                <label>Method</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="method" value="GET" {{$method=='GET'?'checked':''}}>
                        GET
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="method" value="POST"
                                {{(!$method||$method=='POST')?'checked':''}}>
                        POST
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Request Parameter</label>
                <textarea class="form-control" rows=3 title="" name="request">{{ $req }}</textarea>
            </div>
            <div class="form-group">
                <label>Response Format</label>
                <textarea class="form-control" rows=3 title="" name="response">{{ $res }}</textarea>
            </div>
            <div class="form-group">
                <label>Attributes</label>
                <textarea class="form-control" rows=3 title="" name="attr">{{ $attr }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Generate</button>
        </form>
        @if($doc)
            <hr/>
            <input type="button" class="btn btn-primary" value="Copy To Clipboard" id="copy"/>
            <br/>
            <br/>
            <textarea id="doc" class="form-control" rows=5 onChange="clip.setText(this.value)"
                      title="">{!! $doc !!}</textarea>
            <script type="text/javascript" src="{{asset('/plugins/zeroClipboard/ZeroClipboard.js')}}"></script>
            <script language="JavaScript">
                var clip = new ZeroClipboard.Client();
                clip.setText(document.getElementById('doc').value);
                ZeroClipboard.setMoviePath("{{asset('/plugins/zeroClipboard/ZeroClipboard.swf')}}");
                clip.glue('copy');
                clip.addEventListener('mouseup', function (client) {
                    document.getElementById('copy').value = 'Copy Success';
                });
            </script>
        @endif
    </div>

@endsection
