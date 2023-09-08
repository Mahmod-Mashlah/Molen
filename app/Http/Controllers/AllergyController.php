<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AllergiesResource;
use App\Http\Requests\StoreAllergyRequest;
use App\Http\Requests\UpdateAllergyRequest;

class AllergyController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return AllergiesResource::collection(
        #   Allergy::where('user_id',Auth::user()->id)->get() ) ; // get allergys thats users are authenticated

        return AllergiesResource::collection(
            Allergy::where('doctor_id', Auth::user()->id)->get()
        ); // get allergys thats users are authenticated

    }



    public function store(StoreAllergyRequest $request)
    {
        $request->validated($request->all());


        $allergy = Allergy::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'allergy' => $request->allergy,
            'date' => $request->date,
        ]);

        return new AllergiesResource($allergy);
    }


    public function show(Allergy $allergy)
    {
        if (Auth::user()->id !== $allergy->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new AllergiesResource($allergy);
    }


    public function update(UpdateAllergyRequest $request, Allergy $allergy)
    {
        $allergy = Allergy::find($allergy->id);
        if (Auth::user()->id !== $allergy->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $allergy->update($request->all());
        $allergy->save();

        return new AllergiesResource($allergy);
    }

    public function destroy(Allergy $allergy)
    {
        // way 1 :
        // $allergy->delete();
        // return $this->success('Allergy was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($allergy) ? $this->isNotAuthorized($allergy) : $allergy->delete();
        if (Auth::user()->id !== $allergy->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $allergy->delete() ;
            return $this->success('Allergy Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($allergy)
    {
        if (Auth::user()->id !== $allergy->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
