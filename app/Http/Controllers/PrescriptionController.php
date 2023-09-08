<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PrescriptionsResource;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;

class PrescriptionController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return PrescriptionsResource::collection(
        #   Prescription::where('user_id',Auth::user()->id)->get() ) ; // get prescriptions thats users are authenticated

        return PrescriptionsResource::collection(
            Prescription::where('doctor_id', Auth::user()->id)->get()
        ); // get prescriptions thats users are authenticated

    }



    public function store(StorePrescriptionRequest $request)
    {
        $request->validated($request->all());


        $prescription = Prescription::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'medicine_name' => $request->medicine_name,
            'drug_type' => $request->drug_type,
            'repetition' => $request->repetition,
            'take_times' => $request->take_times,
        ]);

        return new PrescriptionsResource($prescription);
    }


    public function show(Prescription $prescription)
    {
        if (Auth::user()->id !== $prescription->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new PrescriptionsResource($prescription);
    }


    public function update(UpdatePrescriptionRequest $request, Prescription $prescription)
    {
        $prescription = Prescription::find($prescription->id);
        if (Auth::user()->id !== $prescription->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $prescription->update($request->all());
        $prescription->save();

        return new PrescriptionsResource($prescription);
    }

    public function destroy(Prescription $prescription)
    {
        // way 1 :
        // $prescription->delete();
        // return $this->success('Prescription was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($prescription) ? $this->isNotAuthorized($prescription) : $prescription->delete();
        if (Auth::user()->id !== $prescription->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $prescription->delete() ;
            return $this->success('Prescription Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($prescription)
    {
        if (Auth::user()->id !== $prescription->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
