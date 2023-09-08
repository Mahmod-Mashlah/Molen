<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VaccinesResource;
use App\Http\Requests\StoreVaccineRequest;
use App\Http\Requests\UpdateVaccineRequest;

class VaccineController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return VaccinesResource::collection(
        #   Vaccine::where('user_id',Auth::user()->id)->get() ) ; // get vaccines thats users are authenticated

        return VaccinesResource::collection(
            Vaccine::where('doctor_id', Auth::user()->id)->get()
        ); // get vaccines thats users are authenticated

    }



    public function store(StoreVaccineRequest $request)
    {
        $request->validated($request->all());


        $vaccine = Vaccine::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'vaccine' => $request->vaccine,
            'date' => $request->date,
        ]);

        return new VaccinesResource($vaccine);
    }


    public function show(Vaccine $vaccine)
    {
        if (Auth::user()->id !== $vaccine->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new VaccinesResource($vaccine);
    }


    public function update(UpdateVaccineRequest $request, Vaccine $vaccine)
    {
        $vaccine = Vaccine::find($vaccine->id);
        if (Auth::user()->id !== $vaccine->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $vaccine->update($request->all());
        $vaccine->save();

        return new VaccinesResource($vaccine);
    }

    public function destroy(Vaccine $vaccine)
    {
        // way 1 :
        // $vaccine->delete();
        // return $this->success('Vaccine was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($vaccine) ? $this->isNotAuthorized($vaccine) : $vaccine->delete();
        if (Auth::user()->id !== $vaccine->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $vaccine->delete() ;
            return $this->success('Vaccine Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($vaccine)
    {
        if (Auth::user()->id !== $vaccine->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
