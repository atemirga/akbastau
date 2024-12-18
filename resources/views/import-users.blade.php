<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Импорт пользователей</title>
</head>
<body>
    <h1>Импорт пользователей</h1>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if ($errors->any())
        <p style="color: red;">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Выберите Excel файл:</label>
        <input type="file" name="file" id="file" required>
        <button type="submit">Импортировать</button>
    </form>
</body>
</html>
