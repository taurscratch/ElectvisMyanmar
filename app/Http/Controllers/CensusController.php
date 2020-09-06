<?php

namespace App\Http\Controllers;

use App\Area;
use App\Census;
use App\Http\Resources\NationalityResults;
use App\NationalityHouse;
use App\NationalityResult;
use App\RegionalResult;
use App\RepresentativeResult;
use Illuminate\Http\Request;

class CensusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Census  $census
     * @return \Illuminate\Http\Response
     */
    public function show(Census $census)
    {
        //
    }

    public function censusVote($level, Request $request)
    {

        $total_vote = NationalityResult::where('election_date', '2015')->sum('valid_ps_vote') + NationalityResult::where('election_date', '2015')->sum('valid_adv_vote');
        if ($level == 'country') {
            $total_population = Census::sum('population_18_above');
            $absent_voters = 0;
            if ((int)$total_population > $total_vote) {
                $absent_voters =  $total_population - $total_vote;
            }
            return [
                'eligible_voters' => (int)$total_population,
                'total_votes' => $total_vote,
                'absent_voters' => $absent_voters
            ];
        } else if ($level == 'region') {
            $area = Area::find($request->area_id);
            $total_population = 0;
            $total_vote = 0;
            $absent_voters = 0;

            if ($area != null) {
                $total_population = $area->Census->population_18_above;
            }

            $nationality_seats = NationalityHouse::where('region_id', $request->region_id)->where('election_year', $request->year)->get();
            foreach ($nationality_seats as $nationality_seat) {
                foreach ($nationality_seat->NationalityResults as $seat) {
                    $total_vote +=  ($seat->valid_ps_vote + $seat->valid_adv_vote);
                }
            }
            if ((int)$total_population > $total_vote) {
                $absent_voters =  $total_population - $total_vote;
            }
            return [
                'eligible_voters' => (int)$total_population,
                'total_votes' => $total_vote,
                'absent_voters' => $absent_voters
            ];
        } else if ($level == 'constitution') {
            $area = NationalityHouse::find($request->house_id);
            $total_population = 0;
            $total_vote = 0;
            $absent_voters = 0;
            if ($area != null) {
                $total_population = $area->Area->Census->population_18_above;
            }
            $nationality_result = NationalityResult::where('nationalityhouse_id', $request->house_id)->get();
            foreach ($nationality_result as $result) {
                $total_vote += ($result->valid_ps_vote + $result->valid_adv_vote);
            }
            if ((int)$total_population > $total_vote) {
                $absent_voters =  $total_population - $total_vote;
            }
            return [
                'eligible_voters' => (int)$total_population,
                'total_votes' => $total_vote,
                'absent_voters' => $absent_voters
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Census  $census
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Census $census)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Census  $census
     * @return \Illuminate\Http\Response
     */
    public function destroy(Census $census)
    {
        //
    }
}
