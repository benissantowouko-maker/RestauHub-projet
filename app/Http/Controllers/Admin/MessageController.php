<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = Message::latest()->paginate(15);
        $nonLusCount = Message::nonLu()->count();

        return view('admin.messages.index', compact('messages', 'nonLusCount'));
    }

    public function markAsRead(int $id): RedirectResponse
    {
        $message = Message::findOrFail($id);
        $message->update(['lu' => true]);

        return back()->with('success', 'Message marqué comme lu.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return back()->with('success', 'Message supprimé.');
    }
}
