<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExamsResource;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;

class ExamController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return ExamsResource::collection(
        #   Exam::where('user_id',Auth::user()->id)->get() ) ; // get exams thats users are authenticated

        return ExamsResource::collection(
            Exam::where('doctor_id', Auth::user()->id)->get()
        ); // get exams thats users are authenticated

    }



    public function store(StoreExamRequest $request)
    {
        $request->validated($request->all());


        $exam = Exam::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'hight' => $request->hight,
            'width' => $request->width,
            'temperature' => $request->temperature,
            'pressure' => $request->pressure,
            'blood_oxygen' => $request->blood_oxygen,
            'date' => $request->date,

        ]);

        return new ExamsResource($exam);
    }


    public function show(Exam $exam)
    {
        if (Auth::user()->id !== $exam->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new ExamsResource($exam);
    }


    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $exam = Exam::find($exam->id);
        if (Auth::user()->id !== $exam->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $exam->update($request->all());
        $exam->save();

        return new ExamsResource($exam);
    }

    public function destroy(Exam $exam)
    {
        // way 1 :
        // $exam->delete();
        // return $this->success('Exam was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($exam) ? $this->isNotAuthorized($exam) : $exam->delete();
        if (Auth::user()->id !== $exam->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $exam->delete() ;
            return $this->success('Exam Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($exam)
    {
        if (Auth::user()->id !== $exam->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
