<?php
namespace App\Exports;

use App\Models\CmBranch;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServicesExport implements FromView
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
        $service=Service::with('category')->whereHas('category')->whereHas('main_category');
        if($this->category_id>0){
            $service->where('category_id', $this->category_id);
        }
        if(!empty($this->search)){
            $service->where(function ($q){
                $q->where('title','like','%'.$this->search)
                    ->orWhere('title_ar','like','%'.$this->search)
                    ->orWhere('description','like','%'.$this->search)
                    ->orWhere('description_ar','like','%'.$this->search)
                    ->orwhereHas('category',function ($q){
                        $q->where('title','like','%'.$this->search.'%');
                        $q->orwhere('title_ar','like','%'.$this->search.'%');});
            });
        }
        return view('services.services_export', [
            'services' => $service->get()
        ]);
    }
}
