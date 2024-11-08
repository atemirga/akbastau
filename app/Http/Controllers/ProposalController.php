<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = null;
        if(auth()->user()->role == 'admin')
        {
            $proposals = Proposal::all();
        }else{$proposals = Proposal::where('user_id', auth()->user()->id)->get();}

        return view('pages.tickets', compact('proposals')); //Передаем переменную в представление
    }


    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'title' => 'required|string|max:255',
            'current_state' => 'required|string',
            'future_state' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png', // Валидация файла
        ]);

        // Сохранение файла, если он был загружен
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('proposals_files', 'public');
        }

        // Создание нового предложения
        Auth::user()->proposals()->create([
            'title' => $request->title,
            'current_state' => $request->current_state,
            'future_state' => $request->future_state,
            'file_path' => $filePath, // Сохранение пути к файлу
        ]);

        return redirect()->route('tickets.index')->with('success', 'Proposal submitted successfully.');
    }


    public function show($id)
    {
        // Просмотр конкретного предложения
        $proposal = Proposal::findOrFail($id);

        // Проверка, что пользователь имеет доступ к предложению
        if ($proposal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('proposals.show', compact('proposal'));
    }

    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Убедитесь, что пользователь имеет доступ к этому предложению
        if ($proposal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('proposals.edit', compact('proposal'));
    }

    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Убедитесь, что пользователь имеет доступ к этому предложению
        if ($proposal->user_id !== Auth::id()) {
            abort(403);
        }

        $proposal->delete();

        return redirect()->route('tickets.index')->with('success', 'Предложение удалено.');
    }

    public function update(Request $request, $id)
    {
        // Валидация данных
        $request->validate([
            'title' => 'required|string|max:255',
            'current_state' => 'required|string',
            'future_state' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png', // Валидация файла, если он изменен
        ]);

        // Находим предложение, которое нужно обновить
        $proposal = Proposal::findOrFail($id);

        // Обновление пути к файлу, если был загружен новый файл
        if ($request->hasFile('file')) {
            // Удаляем старый файл, если он существует
            if ($proposal->file_path) {
                Storage::disk('public')->delete($proposal->file_path);
            }
            $proposal->file_path = $request->file('file')->store('proposals_files', 'public');
        }

        // Обновление данных предложения
        $proposal->title = $request->title;
        $proposal->current_state = $request->current_state;
        $proposal->future_state = $request->future_state;
        $proposal->save();

        // Перенаправление с сообщением об успешном обновлении
        return redirect()->route('tickets.index')->with('success', 'Proposal updated successfully.');
    }

    public function updateStatus(Request $request, Proposal $proposal)
    {
        // Проверка прав
        //if (!auth()->user()->hasRole('admin')) {
        //    return response()->json(['success' => false, 'message' => 'Недостаточно прав.'], 403);
        //}

        $request->validate([
            'status' => 'required|in:new,in_review,accepted,rejected',
            'comments' => 'nullable|string|max:255'
        ]);

        $proposal->status = $request->status;

        // Сохраняем комментарий, если он есть
        if ($request->status === 'rejected' && $request->comments) {
            $proposal->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comments,
            ]);
        }

        $proposal->save();

        return response()->json(['success' => true]);
    }
}

