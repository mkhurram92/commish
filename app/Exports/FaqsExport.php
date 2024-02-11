<?php
namespace App\Exports;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class FaqsExport implements FromView
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
        $data=Faq::select('faqs.*');
        if(!empty($this->search)){
            $data->where(function ($q){
                    $q->where('statement','like','%'.$this->search.'%')
                    ->orWhere('statement_ar','like','%'.$this->search.'%')
                    ->orWhere('answer','like','%'.$this->search.'%')
                    ->orWhere('answer_ar','like','%'.$this->search.'%');
            });
        }
//        echo '<pre>';print_r($data->get()); exit;
        return view('faqs.faqs_export', [
            'data' => $data->get()
        ]);
    }
}
