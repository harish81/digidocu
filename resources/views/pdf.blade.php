<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$docName}}</title>
</head>
<body style="margin: 0 auto">
    @foreach ($filePaths as $file)
        <img src="{{$file}}" style="display:block;margin-bottom: 0px;width: 100%">
    @endforeach
</body>
</html>
