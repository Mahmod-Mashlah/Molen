<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WorkTimesResource;
use App\Http\Requests\StoreWorkTimeRequest;
use App\Http\Requests\UpdateWorkTimeRequest;

class WorkTimeController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return WorkTimesResource::collection(
        #   WorkTime::where('user_id',Auth::user()->id)->get() ) ; // get workTimes thats users are authenticated

        return WorkTimesResource::collection(
            WorkTime::where('doctor_id', Auth::user()->id)->get()
        ); // get workTimes thats users are authenticated

    }

    public function store(StoreWorkTimeRequest $request)
    {
        $request->validated($request->all());


        $workTime = WorkTime::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'day_id' => $request->day_id,

            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return new WorkTimesResource($workTime);
    }


    public function show(WorkTime $workTime)
    {
        if (Auth::user()->id !== $workTime->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new WorkTimesResource($workTime);
    }


    public function update(UpdateWorkTimeRequest $request, WorkTime $workTime)
    {
        $workTime = WorkTime::find($workTime->id);
        if (Auth::user()->id !== $workTime->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $workTime->update($request->all());
        $workTime->save();

        return new WorkTimesResource($workTime);
    }

    public function destroy(WorkTime $workTime)
    {
        // way 1 :
        // $workTime->delete();
        // return $this->success('WorkTime was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($workTime) ? $this->isNotAuthorized($workTime) : $workTime->delete();
        if (Auth::user()->id !== $workTime->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $workTime->delete() ;
            return $this->success('WorkTime Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($workTime)
    {
        if (Auth::user()->id !== $workTime->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
