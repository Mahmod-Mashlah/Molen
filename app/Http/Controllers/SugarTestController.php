<?php

namespace App\Http\Controllers;

use App\Models\SugarTest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SugarTestsResource;
use App\Http\Requests\StoreSugarTestRequest;
use App\Http\Requests\UpdateSugarTestRequest;

class SugarTestController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return SugarTestsResource::collection(
        #   SugarTest::where('user_id',Auth::user()->id)->get() ) ; // get sugarTests thats users are authenticated

        return SugarTestsResource::collection(
            SugarTest::where('doctor_id', Auth::user()->id)->get()
        ); // get sugarTests thats users are authenticated

    }



    public function store(StoreSugarTestRequest $request)
    {
        $request->validated($request->all());


        $sugarTest = SugarTest::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'type' => $request -> type,
            'result' => $request -> result ,
            'date' => $request -> date,
            'time' => $request -> time,

        ]);

        return new SugarTestsResource($sugarTest);
    }


    public function show(SugarTest $sugarTest)
    {
        if (Auth::user()->id !== $sugarTest->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new SugarTestsResource($sugarTest);
    }


    public function update(UpdateSugarTestRequest $request, SugarTest $sugarTest)
    {
        $sugarTest = SugarTest::find($sugarTest->id);
        if (Auth::user()->id !== $sugarTest->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $sugarTest->update($request->all());
        $sugarTest->save();

        return new SugarTestsResource($sugarTest);
    }

    public function destroy(SugarTest $sugarTest)
    {
        // way 1 :
        // $sugarTest->delete();
        // return $this->success('SugarTest was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($sugarTest) ? $this->isNotAuthorized($sugarTest) : $sugarTest->delete();
        if (Auth::user()->id !== $sugarTest->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        } else {
            $sugarTest->delete();
            return $this->success('SugarTest Deleted Successfuly ', null, 200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($sugarTest)
    {
        if (Auth::user()->id !== $sugarTest->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
