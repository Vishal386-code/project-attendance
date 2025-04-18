<?php

namespace App\Livewire\Superadmin;

use Livewire\Component;
use App\Models\TechnoHoliday;
use Carbon\Carbon;

class TechnoHolidayForm extends Component
{
    public $occasion, $date, $holidays;
    public $editId = null;
    public $editOccasion, $editDate;

    public function mount()
    {
        $this->loadHolidays();
    }

    public function updatedDate()
    {
        if ($this->date) {
            $this->day = Carbon::parse($this->date)->format('l');
        }
    }

    public function updatedEditDate()
    {
        if ($this->editDate) {
            $this->day = Carbon::parse($this->editDate)->format('l');
        }
    }

    public function save()
    {
        $this->validate([
            'occasion' => 'required|string|max:255',
            'date' => 'required|date|unique:techno_holidays,date',
        ]);

        TechnoHoliday::create([
            'occasion' => $this->occasion,
            'date' => $this->date,
            'day' => Carbon::parse($this->date)->format('l'),
        ]);

        session()->flash('success', 'Holiday Added!');
        $this->reset(['occasion', 'date']);
        $this->loadHolidays();
    }

    public function edit($id)
    {
        $holiday = TechnoHoliday::findOrFail($id);
        $this->editId = $id;
        $this->editOccasion = $holiday->occasion;
        $this->editDate = $holiday->date;
    }

    public function update()
    {
        $this->validate([
            'editOccasion' => 'required|string|max:255',
            'editDate' => 'required|date|unique:techno_holidays,date,'.$this->editId,
        ]);

        $holiday = TechnoHoliday::find($this->editId);
        $holiday->update([
            'occasion' => $this->editOccasion,
            'date' => $this->editDate,
            'day' => Carbon::parse($this->editDate)->format('l'),
        ]);

        session()->flash('success', 'Holiday Updated!');
        $this->cancelEdit();
        $this->loadHolidays();
    }

    public function delete($id)
    {
        TechnoHoliday::find($id)->delete();
        session()->flash('success', 'Holiday Deleted!');
        $this->loadHolidays();
    }

    public function cancelEdit()
    {
        $this->editId = null;
        $this->reset(['editOccasion', 'editDate']);
    }

    public function loadHolidays()
    {
        $this->holidays = TechnoHoliday::orderBy('date')->get();
    }

    public function render()
    {
        return view('livewire.superadmin.techno-holiday-form')->layout('layouts.app');
    }
}
