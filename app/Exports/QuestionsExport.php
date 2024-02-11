<?php
namespace App\Exports;

use App\Models\CmBranch;
use App\Models\Question;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuestionsExport implements FromView
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
        $questions=Question::with(['question_type','question_flag']);
        if(!empty($this->search)){
            $questions->where(function ($q){
                $q->where('title','like','%'.$this->search.'%')
                    ->orWhere('title_ar','like','%'.$this->search.'%')
                    ->orWhere('statement','like','%'.$this->search.'%')
                    ->orWhere('statement_ar','like','%'.$this->search.'%')
                    ->orwhereHas('question_type',function ($q){
                        $q->where('title','like','%'.$this->search.'%');})
                    ->orwhereHas('question_type',function ($q){
                        $q->where('title','like','%'.$this->search.'%');});
            });
        }
        return view('questions.questions_export', [
            'questions' => $questions->get()
        ]);
    }
}
