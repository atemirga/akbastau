<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .dropzone { border: 2px dashed #007bff; padding: 20px; background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Upload Multiple Files</h1>
    <form action="{{ route('upload.files') }}" class="dropzone" id="fileDropzone" method="POST" enctype="multipart/form-data">
        @csrf
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        Dropzone.options.fileDropzone = {
            paramName: "files[]", // Название параметра для передачи файлов
            maxFilesize: 1, // Максимальный размер файла в MB
            parallelUploads: 20, // Количество одновременно загружаемых файлов
            maxFiles: 400, // Максимальное количество файлов
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt", // Разрешённые типы файлов
            init: function () {
                this.on("success", function (file, response) {
                    console.log("Successfully uploaded:", response);
                });
                this.on("error", function (file, response) {
                    console.error("Upload error:", response);
                });
            }
        };
    </script>
</body>
</html>
