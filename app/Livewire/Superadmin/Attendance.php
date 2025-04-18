<?php

namespace App\Livewire\Superadmin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class Attendance extends Component
{
    public $sendableId;
    public $messageBody = '';
    public $month;
    public $year;

    public function mount()
    {
        $this->sendableId = 1;
        $this->month = now()->month;
        $this->year = now()->year;
    }

    // Submit only sends message if messageBody is not empty
    public function submit()
    {
        if (trim($this->messageBody) === '') {
            session()->flash('error', 'Message cannot be empty.');
            return;
        }

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

    public function getAttendanceStatusFromMessages($userId, $date)
    {
        $query = DB::table('wire_messages')
            ->where('sendable_id', $userId)
            ->where('sendable_type', 'App\Models\User')
            ->where('conversation_id', 2)
            ->whereNull('deleted_at')
            ->whereDate('created_at', $date);

        $messages = $query->get(['body']);

        if ($messages->isEmpty()) {
            return ['status' => 'Leave', 'minutes' => 0, 'extra' => ''];
        }

        foreach ($messages as $message) {
            $body = strtolower($message->body);

            if (str_contains($body, 'half day')) {
                return ['status' => 'HD', 'minutes' => 240, 'extra' => ''];
            }
            if (str_contains($body, 'short leave')) {
                return ['status' => 'SL', 'minutes' => 0, 'extra' => ''];
            }
            if (preg_match('/\(in\s*(?:(\d+)\s*hour[s]?)?\s*(?:(\d+)\s*min)?\)/', $body, $matches)) {
                $hours = isset($matches[1]) ? (int)$matches[1] : 0;
                $mins = isset($matches[2]) ? (int)$matches[2] : 0;
                $totalMinutes = ($hours * 60) + $mins;
                
                $extra = '';
                if ($totalMinutes > 480) {
                    $extraMinutes = $totalMinutes - 480;
                    $extraHours = floor($extraMinutes / 60);
                    $extraRemain = $extraMinutes % 60;
                    $extra = "{$extraHours}h {$extraRemain}m OT";
                }
                
                return ['status' => 'Present', 'minutes' => $totalMinutes, 'extra' => $extra];
            }
        }

        return ['status' => 'Present', 'minutes' => 480, 'extra' => ''];
    }

    public function render()
    {
        $users = User::all()->where('role','user');
        $start = Carbon::create($this->year, $this->month, 1);
        $end = $start->copy()->endOfMonth();
        $today = now()->format('Y-m-d');

        // Get holidays for the selected month/year
        $holidays = DB::table('techno_holidays')
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        $dates = [];
        while ($start <= $end) {
            $dates[] = [
                'date' => $start->format('Y-m-d'),
                'day' => $start->format('l'),
            ];
            $start->addDay();
        }

        $matrix = [];
        $plCount = [];
        $leaveTracker = []; // Track leaves taken by each user

        foreach ($users as $user) {
            $plCount[$user->id] = 1;
            $leaveTracker[$user->id] = [
                'paidLeaveUsed' => 1,
                'shortLeaveUsed' => 0.5,
                'totalLeaves' => 1
            ];
        }

        foreach ($dates as $day) {
            $row = ['date' => $day['date'], 'day' => $day['day']];
            $isFuture = $day['date'] > $today;
            $isWeekend = in_array($day['day'], ['Saturday', 'Sunday']);
            $isHoliday = in_array($day['date'], $holidays);

            foreach ($users as $user) {
                if ($isHoliday) {
                    // Mark as holiday for all users
                    $row['user_' . $user->id] = 'Holiday';
                    $row['status_' . $user->id] = 'Holiday';
                    $row['extra_' . $user->id] = 'OFF';
                    $row['min_' . $user->id] = 0;
                    continue;
                }

                $att = $this->getAttendanceStatusFromMessages($user->id, $day['date']);
                $status = $att['status'];
                $minutes = $att['minutes'];
                $extra = $att['extra'];

                if ($isWeekend) {
                    if ($status === 'Present') {
                        // Working on weekend adds 1 PL
                        $row['user_' . $user->id] = 'Present';
                        $row['status_' . $user->id] = 'Present';
                        $row['extra_' . $user->id] = '';
                        $plCount[$user->id]++;
                    } else {
                        $row['user_' . $user->id] = '';
                        $row['status_' . $user->id] = '';
                        $row['extra_' . $user->id] = '';
                    }
                } else {
                    if ($status === 'Leave' && $isFuture) {
                        $row['user_' . $user->id] = '';
                        $row['status_' . $user->id] = '';
                        $row['extra_' . $user->id] = '';
                    } else {
                        $row['user_' . $user->id] = $status;
                        $row['status_' . $user->id] = $status;
                        $row['extra_' . $user->id] = $extra;
                        
                        // Track leaves
                        if ($status === 'Leave' && !$isFuture) {
                            $leaveTracker[$user->id]['totalLeaves']++;
                            
                            // If they haven't used their paid leave yet, deduct it
                            if ($leaveTracker[$user->id]['totalLeaves'] == 1) {
                                $leaveTracker[$user->id]['shortLeaveUsed'] = 1;  // Short leave
                            }
                            // Additional leaves (after the 2nd leave) will decrease PL count
                            elseif ($leaveTracker[$user->id]['totalLeaves'] > 1) {
                                $plCount[$user->id]--;  // Deduct from Paid Leave (PL)
                            }
                        }
                    }
                }

                $row['min_' . $user->id] = $minutes;
            }

            $matrix[] = $row;
        }

        // Calculate working hours for selected user
        $totalMinutes = 0;
        foreach ($dates as $day) {
            if (!in_array($day['day'], ['Saturday', 'Sunday']) && $day['date'] <= $today && !in_array($day['date'], $holidays)) {
                $att = $this->getAttendanceStatusFromMessages($this->sendableId, $day['date']);
                if (in_array($att['status'], ['Present', 'HD'])) {
                    $totalMinutes += $att['minutes'];
                } elseif ($att['status'] === 'Leave') {
                    $totalMinutes -= 480; // Subtract 1 day work time
                }
            }
        }

        $workedHours = floor($totalMinutes / 60);
        $workedMins = abs($totalMinutes % 60);

        // Calculate total extra minutes across all users
        $totalExtraMinutes = 0;
        foreach ($users as $user) {
            foreach ($dates as $day) {
                if (!in_array($day['date'], $holidays)) {
                    $att = $this->getAttendanceStatusFromMessages($user->id, $day['date']);
                    if (!empty($att['extra'])) {
                        if (preg_match('/(\d+)h\s*(\d+)m/', $att['extra'], $matches)) {
                            $hours = (int)$matches[1];
                            $mins = (int)$matches[2];
                            $totalExtraMinutes += ($hours * 60) + $mins;
                        }
                    }
                }
            }
        }

        $totalExtraHours = floor($totalExtraMinutes / 60);
        $totalExtraMins = $totalExtraMinutes % 60;

        return view('livewire.superadmin.attendance', [
            'users' => $users,
            'matrix' => $matrix,
            'workedHours' => $workedHours,
            'workedMins' => $workedMins,
            'plCount' => $plCount,
            'totalExtraHours' => $totalExtraHours,
            'totalExtraMins' => $totalExtraMins,
        ])->layout('layouts.app');
    }
}