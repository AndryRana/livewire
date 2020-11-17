<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfile extends Component
{

    public $userId;
    public $name;
    public $email;
    public $password;

    public $current_hashed_password;
    public $current_password_for_email;
    public $current_password_for_password;
    public $password_confirmation;

    public $prevName;
    public $prevEmail;



    public function mount()
    {
        $this->userId = auth()->user()->id;

        $model = User::find($this->userId);
        $this->name = $model->name;
        $this->email = $model->email;

        $this->prevName = $model->name;
        $this->prevEmail = $model->email;
        $this->current_hashed_password = $model->password;
    }

    protected $rules = [
        'name' => 'required',
        'email' => 'email',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'name' => 'required',
            'email' => 'email',
            'current_password_for_email' => ['required', 'customPassCheckHashed:' . $this->current_hashed_password],
            'current_password_for_password' => ['required', 'customPassCheckHashed:' . $this->current_hashed_password],
            'password' => 'min:6',
            'password_confirmation' => 'required|same:password'

        ]);
    }


    public function save()
    {
        $valideData = $this->validate();

        $data = [];

        // Just add validation if there are any changes in the fields
        if ($this->name !== $this->prevName) {
            if (empty($this->name)) {
                $valideData = array_merge($valideData, [
                    'name' => 'required'
                ]);
            }
        }
        if ($this->email !== $this->prevEmail) {
            if (empty($this->email) || !empty($this->email)) {
                $valideData = array_merge($valideData, [
                    'email' => 'required|email'
                ]);
            }
            $valideData = array_merge($valideData,[
                'current_password_for_email' => ['required', 'customPassCheckHashed:' . $this->current_hashed_password],
            ]);
        }


        //  we will check if there are any changes in the fields
        if ($this->name !== $this->prevName) {
            $data = array_merge($data, ['name' => $this->name]);
        }

        if ($this->email !== $this->prevEmail) {
            $data = array_merge($data, ['email' => $this->email]);
        }

        if (!empty($this->password)) {
            $valideData = array_merge($valideData, [
                'current_password_for_password' => ['required', 'customPassCheckHashed:' . $this->current_hashed_password],
                'password' => 'min:6',
                'password_confirmation' => 'required|same:password'
            ]);
        }

        if (!empty($this->password)) {
            $data = array_merge($data, ['password' => Hash::make($this->password)]);
        }

        if (count($data)) {
            User::find($this->userId)->update($data);
            return redirect()->to('/profile');
        }
    }


    public function render()
    {
        return view('livewire.user-profile');
    }
}
