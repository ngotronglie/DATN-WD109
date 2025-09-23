<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('layouts.admin.contact.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        // Hiển thị chi tiết liên hệ; không thay đổi trạng thái ngoài 2 trạng thái hỗ trợ trong UI
        return view('layouts.admin.contact.show', compact('contact'));
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:pending,replied'
        ]);

        $contact->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công'
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Xóa tin nhắn liên hệ thành công.');
    }

    public function markAsReplied(Contact $contact)
    {
        $contact->update(['status' => 'replied']);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu đã phản hồi'
        ]);
    }
} 