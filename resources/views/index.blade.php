<!doctype html>
<html>
    <head>
        <title>Files list</title>
    </head>
    <body>
        <table>
            @foreach ($files as $file)
            <tr>
                <td>{{ $file->file['mime'] }}</td>
                <td>
                    <a href="{{ $file->file['filename'] }}">{{ $file->file['filename'] }}</a>
                </td>
            </tr>
            @endforeach
        </table>
    </body>
</html>
