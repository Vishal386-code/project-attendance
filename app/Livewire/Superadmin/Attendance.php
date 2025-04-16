<?php

namespace App\Livewire\Superadmin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class Attendance extends Component
{
    public $sendableId;
    public $messageBody;
    public $month;
    public $year;

    public function mount()
    {
        $this->sendableId = 1;
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function submit()
    {
        DB::table('wire_messages')->insert([
            'sendable_id' => $this->sendableId,
            'sendable_type' => 'App\Models\User',
            'conversation_id' => 2,
            'body' => $this->messageBody,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->messageBody = '';

        session()->flash('message', 'Message submitted successfully!');
    }

    public function getWorkedMinutesFromMessages()
    {
        $messages = DB::table('wire_messages')
            ->where('sendable_id', $this->sendableId)
            ->where('sendable_type', 'App\Models\User')
            ->where('conversation_id', 2)
            ->whereNull('deleted_at')
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get(['body']);

        $totalMinutes = 0;

        foreach ($messages as $message) {
            $body = strtolower($message->body);

            // Match: (in X hour[s] Y min)
            if (preg_match('/\(in\s*(?:(\d+)\s*hour[s]?)?\s*(?:(\d+)\s*min)?\)/', $body, $matches)) {
                $hours = isset($matches[1]) ? (int)$matches[1] : 0;
                $mins = isset($matches[2]) ? (int)$matches[2] : 0;
                $totalMinutes += ($hours * 60) + $mins;
            }

            // Match: (less by X hour[s] Y min) or (less X min)
            elseif (preg_match('/\(less(?: by)?\s*(?:(\d+)\s*hour[s]?)?\s*(?:(\d+)\s*min)?\)/', $body, $matches)) {
                $hours = isset($matches[1]) ? (int)$matches[1] : 0;
                $mins = isset($matches[2]) ? (int)$matches[2] : 0;
                $totalMinutes -= ($hours * 60) + $mins;
            }
        }

        return $totalMinutes;
    }


    public function render()
    {
        $users = User::all();

        $messages = DB::table('wire_messages')
            ->where('sendable_id', $this->sendableId)
            ->where('sendable_type', 'App\Models\User')
            ->where('conversation_id', 2)
            ->whereNull('deleted_at')
            ->get(['body']);

        $totalMinutes = $this->getWorkedMinutesFromMessages();
        $workedHours = floor($totalMinutes / 60);
        $workedMins = $totalMinutes % 60;

        return view('livewire.superadmin.attendance', [
            'users' => $users,
            'messages' => $messages,
            'workedHours' => $workedHours,
            'workedMins' => $workedMins,
        ])->layout('layouts.app');
    }
}
