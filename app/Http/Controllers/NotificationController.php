<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;


class NotificationController extends Controller
{
    //
    public function index()
    {
        $query = Notification::query()->with(['proposal.files', 'proposal.user']); // Загружаем файлы и пользователя

        if (auth()->user()->role === 'admin') {
            // Администратор видит все уведомления
            $notifications = $query->orderBy('created_at', 'desc')->get()->unique('proposal_id');
        } else {
            // Обычный пользователь видит только свои уведомления
            $notifications = $query->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('notifications.index', compact('notifications'));
}


    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function updateProposalStatus(Request $request, Notification $notification)
    {
        $proposal = $notification->proposal;

        $proposal->update(['status' => $request->status]);

        // Уведомление для пользователя
        Notification::create([
            'user_id' => $proposal->user_id,
            'proposal_id' => $proposal->id,
            'message' => "Статус вашего предложения \"{$proposal->title}\" изменён на \"{$request->status}\".",
        ]);

        return response()->json(['success' => true]);
    }


}
