<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReservationsResource;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;

class ReservationController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return ReservationsResource::collection(
        #   Reservation::where('user_id',Auth::user()->id)->get() ) ; // get reservations thats users are authenticated

        return ReservationsResource::collection(
            Reservation::where('doctor_id', Auth::user()->id)->get()
        ); // get reservations thats users are authenticated

    }

    public function store(StoreReservationRequest $request)
    {
        $request->validated($request->all());


        $reservation = Reservation::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'status' => $request->status,
            'from' => $request->from,
            'to' => $request->to,
        ]);

        return new ReservationsResource($reservation);
    }


    public function show(Reservation $reservation)
    {
        if (Auth::user()->id !== $reservation->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new ReservationsResource($reservation);
    }


    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $reservation = Reservation::find($reservation->id);
        if (Auth::user()->id !== $reservation->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $reservation->update($request->all());
        $reservation->save();

        return new ReservationsResource($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        // way 1 :
        // $reservation->delete();
        // return $this->success('Reservation was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($reservation) ? $this->isNotAuthorized($reservation) : $reservation->delete();
        if (Auth::user()->id !== $reservation->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $reservation->delete() ;
            return $this->success('Reservation Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($reservation)
    {
        if (Auth::user()->id !== $reservation->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
