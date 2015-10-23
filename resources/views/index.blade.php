<!doctype html>
<html>
    <head>
        <title>Files list</title>
    </head>
    <body>
        @if (count($directories) > 0)
        <h2>Directories</h2>
        <ul>
            @foreach ($directories as $directory)
            <li>
                <a href="/{{ $directory }}">{{ $directory }}</a>
            </li>
            @endforeach
        </ul>
        @endif
        @if (count($files) > 0)
        <h2>Files</h2>
        <ul>
            @foreach ($files as $file)
            <li>
                <a href="/{{ $file }}">{{ $file }}</a>
            </li>
            @endforeach
        </ul>
        @endif
    </body>
</html>
