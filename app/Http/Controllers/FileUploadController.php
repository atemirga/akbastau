<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload-files');
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:1024', // Каждый файл до 1 МБ
        ]);

        $files = $request->file('files');

        if (!$files) {
            return response()->json(['message' => 'No files uploaded'], 400);
        }

        // Путь к директории storage/files
        $storagePath = storage_path('files');

        // Проверяем и создаём директорию, если она не существует
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $uploadedFiles = [];
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName(); // Оригинальное имя файла
            $filePath = $storagePath . '/' . $fileName;

            // Если файл уже существует, он будет перезаписан
            if (file_exists($filePath)) {
                unlink($filePath); // Удаляем старый файл
            }

            // Сохраняем файл
            $file->move($storagePath, $fileName);
            $uploadedFiles[] = $fileName;
        }

        return response()->json(['message' => 'Files uploaded successfully', 'files' => $uploadedFiles]);
    }


}
