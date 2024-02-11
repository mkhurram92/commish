<?php
namespace App\Exports;

use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SurveysExport implements FromView
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
        $questions= $surveys=Survey::with(['survey_status'])
            ->whereHas('category')
            ->whereHas('cm_branches')
            ->whereHas('questions')
            ->whereHas('main_category');
        if(\Auth::user()->employee_of=='cm' ){
            $surveys->whereHas('cm_branches',function ($q){
                $q->whereCityMunicipalityId(\Auth::user()->employee_of_id);
            })->whereHas('category',function ($q){
                $q->whereHas('users',function ($q){
                    $q->where('users.id',\Auth::user()->id);
                });
            });
        }else if(\Auth::user()->employee_of=='cmb'){
            $surveys->whereHas('cm_branches',function ($q){
                $q->where('cm_branches.id',\Auth::user()->employee_of_id);
            });
        }
        if(!empty($this->search)){
            $questions->where(function ($q){
                $q->where('title','like','%'.$this->search.'%')
                    ->orWhere('title_ar','like','%'.$this->search.'%')
                    ->orWhereHas('survey_status',function ($que){
                        $que->where('title','like','%'.$this->search.'%')->orWhere('title_ar','like','%'.$this->search.'%');
                    });
            });
        }
        return view('surveys.surveys_export', [
            'surveys' => $questions->get()
        ]);
    }
}
