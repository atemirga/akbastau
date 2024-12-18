<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFGeneratorController extends Controller
{
    public function generatePDFs()
    {
        // Получаем всех пользователей из базы данных
        $users = User::all();

        foreach ($users as $user) {
            // Формируем имя файла без пробелов
            $fileName = str_replace(' ', '', $user->name) . '.pdf';

            // Генерируем контент PDF
            $content = "<h1>{$user->name}</h1>";

            // Создаём PDF
            $pdf = Pdf::loadHTML($content);

            // Сохраняем PDF в директории
            $filePath = storage_path("files/{$fileName}");
            $pdf->save($filePath);
        }

        return response()->json(['status' => 'PDF files generated successfully!']);
    }
}
