<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500'
        ]);

        $sender = Auth::user();
        $receiverId = $request->receiver_id;

        // Check if invitation already exists
        $existingInvitation = UserInvitation::where('sender_id', $sender->id)
            ->where('receiver_id', $receiverId)
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'You have already sent an invitation to this person.');
        }

        // Check if they already have a connection (reverse)
        $reverseInvitation = UserInvitation::where('sender_id', $receiverId)
            ->where('receiver_id', $sender->id)
            ->first();

        if ($reverseInvitation) {
            return back()->with('error', 'This person has already sent you an invitation.');
        }

        UserInvitation::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Invitation sent successfully!');
    }

    public function accept(UserInvitation $invitation)
    {
        // Verify the invitation is for the authenticated user
        if ($invitation->receiver_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $invitation->accept();

        return back()->with('success', 'Invitation accepted! You are now connected.');
    }

    public function decline(UserInvitation $invitation)
    {
        // Verify the invitation is for the authenticated user
        if ($invitation->receiver_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $invitation->decline();

        return back()->with('success', 'Invitation declined.');
    }

    public function index()
    {
        $user = Auth::user();
        
        $receivedInvitations = $user->receivedInvitations()
            ->where('status', 'pending')
            ->with('sender')
            ->latest()
            ->get();

        $sentInvitations = $user->sentInvitations()
            ->with('receiver')
            ->latest()
            ->get();

        return view('invitations.index', compact('receivedInvitations', 'sentInvitations'));
    }
}