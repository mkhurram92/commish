<?php
namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class UsersExport implements FromView
{
    public $category_id=0;
    public $search="";
    public function forCategory(int $type)
    {
        $this->category_id = $type;
        return $this;
    }
    public function search(string $search)
    {
        $this->search = $search;
        return $this;
    }

    public function view(): View
    {
        $data=User::select(DB::raw('users.*, CONCAT(users.first_name," ", users.last_name) as name'))->whereUserType(1)->with('role');
        if(!empty($this->search)){
            $data->where(function ($q){
                $q->where(DB::raw('CONCAT(users.first_name," ", users.last_name)'),'like','%'.$this->search.'%')
                    ->orWhere('street_address','like','%'.$this->search.'%')
                    ->orWhere('id_number','like','%'.$this->search.'%')
                    ->orWhere('fixed_line_contact','like','%'.$this->search.'%')
                    ->orWhereHas('role',function ($que){
                        $que->where('title','like','%'.$this->search.'%');
                    });
            });
        }
//        echo '<pre>';print_r($data->get()); exit;
        return view('users.users_export', [
            'data' => $data->get()
        ]);
    }
}
