<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class UsersSearchUser extends Component
{
    use WithPagination;

    protected $paginationTheme  =   'bootstrap';

    public $keyword;
    public $fltrRole;

    /**
     * Mount the variables
     * This will define it to your blade
     * 
     */
    public function mount()
    {
        $this->keyword    =   '';
    }


    public function render()
    {
        return view('livewire.users-search-user', [
            'users'     =>  User::when($this->keyword, function($query, $keyword) {
                                    return $query->where('first_name', 'like', "%$keyword%")
                                                ->orWhere('last_name', 'like', "%$keyword%")
                                                ->orWhere('email', 'like', "%$keyword%")
                                                ->orWhere('username', 'like', "%$keyword%");
                                })
                                ->when($this->fltrRole, function($query, $fltrRole) {
                                    return $query->where('role', $fltrRole);
                                })
                                ->orderBy('first_name')
                                ->paginate(10),
                                
            'roles'     =>  User::select('role')
                                ->distinct()
                                ->get()
        ]);
    }
}
