<?php
namespace App\Exports;

use App\Models\CmBranch;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServiceRequestsExport implements FromView
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
        $service=ServiceRequest::with(['service','service.category','cm_branch','user'])->whereHas('service.category')
            ->whereHas('service')
            ->whereHas('service.main_category')
            ->whereHas('cm_branch')
            ->whereHas('user');
        if(!empty($this->search)){
            $service->where(function ($q){
                $q->where('address','like','%'.$this->search)

                    ->orWhere('notes','like','%'.$this->search.'%')
                    ->orwhereHas('user',function ($q){
                        $q->where('first_name','like','%'.$this->search.'%');
                        $q->orwhere('last_name','like','%'.$this->search.'%');})
                    ->orwhereHas('cm_branch',function ($q){
                        $q->where('name','like','%'.$this->search.'%');})
                    ->orwhereHas('service',function ($q){
                        $q->where('title','like','%'.$this->search.'%');
                        $q->where('title_ar','like','%'.$this->search.'%');
                    })
                    ->orwhereHas('service.category',function ($q){
                        $q->where('title','like','%'.$this->search.'%');})
                    ->orwhereHas('service.main_category',function ($q){
                        $q->where('title','like','%'.$this->search.'%');});
            });
        }

        return view('services.service_requests_export', [
            'services' => $service->get()
        ]);
    }
}
