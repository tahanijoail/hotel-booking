<?php

namespace App\Livewire;

use App\Models\Hotel;
use Livewire\Component;

class HotelManager extends Component
{
     public $hotels;
    public $hotel_id;
    public $name, $location, $description, $number_of_rooms, $contacts;
    public $isEditMode = false;
    public function render()
    {
        $this->hotels=Hotel::all();
        return view('livewire.hotel-manager');
    }
     public function resetForm (){
        $this->hotel_id=null;
        $this->name = '';
        $this->location = '';
        $this->description = '';
        $this->number_of_rooms = '';
        $this->contacts = '';
        $this->isEditMode = false;
     }
     public function store(){
            $this->validate([
            'name' => 'required',
            'location' => 'required',
            'number_of_rooms' => 'required|integer',
            'contacts' => 'required|json',
        ]);

          Hotel::create([
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'number_of_rooms' => $this->number_of_rooms,
            'contacts' => $this->contacts,
        ]);

         session()->flash('message', 'تمت إضافة الفندق بنجاح!');
        $this->resetForm();
     }

     public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $this->hotel_id = $hotel->id;
        $this->name = $hotel->name;
        $this->location = $hotel->location;
        $this->description = $hotel->description;
        $this->number_of_rooms = $hotel->number_of_rooms;
        $this->contacts = $hotel->contacts;
        $this->isEditMode = true;
    }

    public function update(){
        $this->validate([
            'name' => 'required',
            'location' => 'required',
            'number_of_rooms' => 'required|integer',
            'contacts' => 'required|json',
        ]);

        $hotel = Hotel::findOrFail($this->hotel_id);
        $hotel->update([
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'number_of_rooms' => $this->number_of_rooms,
            'contacts' => $this->contacts,
        ]);
 session()->flash('message', 'تم تعديل الفندق بنجاح!');
        $this->resetForm();
    }
    public function delete($id)
    {
        Hotel::findOrFail($id)->delete();
        session()->flash('message', 'تم حذف الفندق!');
    }
}
