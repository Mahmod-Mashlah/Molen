<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TreatmentsResource;
use App\Http\Requests\StoreTreatmentRequest;
use App\Http\Requests\UpdateTreatmentRequest;

class TreatmentController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return TreatmentsResource::collection(
        #   Treatment::where('user_id',Auth::user()->id)->get() ) ; // get treatments thats users are authenticated

        return TreatmentsResource::collection(
            Treatment::where('doctor_id', Auth::user()->id)->get()
        ); // get treatments thats users are authenticated

    }



    public function store(StoreTreatmentRequest $request)
    {
        $request->validated($request->all());


        $treatment = Treatment::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'treatment' => $request->treatment,
            'date' => $request->date,
        ]);

        return new TreatmentsResource($treatment);
    }


    public function show(Treatment $treatment)
    {
        if (Auth::user()->id !== $treatment->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new TreatmentsResource($treatment);
    }


    public function update(UpdateTreatmentRequest $request, Treatment $treatment)
    {
        $treatment = Treatment::find($treatment->id);
        if (Auth::user()->id !== $treatment->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $treatment->update($request->all());
        $treatment->save();

        return new TreatmentsResource($treatment);
    }

    public function destroy(Treatment $treatment)
    {
        // way 1 :
        // $treatment->delete();
        // return $this->success('Treatment was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($treatment) ? $this->isNotAuthorized($treatment) : $treatment->delete();
        if (Auth::user()->id !== $treatment->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $treatment->delete() ;
            return $this->success('Treatment Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($treatment)
    {
        if (Auth::user()->id !== $treatment->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
