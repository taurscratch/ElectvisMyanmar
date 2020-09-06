<?php

namespace App\Http\Controllers;

use App\Area;
use App\Candidate;
use App\Helpers\OperatorHelper;
use App\Http\Resources\NationalityResults;
use App\NationalityHouse;
use App\NationalityResult;
use App\Party;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NationalityResultController extends Controller
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

    public function getResultsCountry($year)
    {
        $region_state_array = Region::get();
        $country_array = [];
        foreach ($region_state_array as $region_state) {
            $area_id = '';
            $area = Area::where('pcode', $region_state->pcode)->first();
            if ($area != null) {
                $area_id = $area->id;
            }
            $houses = NationalityHouse::where('region_id', $region_state->region_state)->where('election_year', $year)->get();
            $seat_array = [];
            foreach ($houses as $house) {
                $data =  NationalityResult::where('nationalityhouse_id', $house->id)->get();
                if ($data != null) {
                    $max_vote = 0;
                    $max_vote_party = '';
                    foreach ($data as $result) {
                        $total_vote = (int)$result->valid_ps_vote + (int)$result->valid_adv_vote;
                        if ($total_vote >= $max_vote) {
                            $max_vote = $total_vote;
                            $max_vote_party = $result->party_id;
                        }
                    }
                    $party = Party::find($max_vote_party);
                    $party_name = '';
                    $party_id = '';
                    $party_color = '';

                    if ($party != null) {
                        $party_name = $party->uec_party_name;
                        $party_id = $party->id;
                        $party_color = $party->color_code;
                    };
                    if (count($seat_array) == 0) {
                        array_push($seat_array, [
                            'party_name' => $party_name,
                            'party_id' => $party_id,
                            'party_color' => $party_color,
                            'area_id' => $area_id,
                            'total_votes' => 1
                        ]);
                    } else {
                        $switch = 0;
                        foreach ($seat_array as $key => $seat) {
                            if ($seat['party_name'] == $party_name) {
                                $switch = 1;
                                $seat_array[$key]['total_votes'] += 1;
                            }
                        }
                        if ($switch == 0) {
                            array_push($seat_array, [
                                'party_name' => $party_name,
                                'party_id' => $party_id,
                                'party_color' => $party_color,
                                'area_id' => $area_id,
                                'total_votes' => 1
                            ]);
                        }
                    }
                }
            }
            $max_party = '';
            $max_party_id = '';
            $max_party_seat = 0;
            $max_party_color = '';
            foreach ($seat_array as $seat) {
                if ($seat['total_votes'] >= $max_party_seat) {
                    $max_party = $seat['party_name'];
                    $max_party_id = $seat['party_id'];
                    $max_party_seat = $seat['total_votes'];
                    $max_party_color = $seat['party_color'];
                }
            }
            array_push(
                $country_array,
                [
                    'region_state' => $region_state->region_state,
                    'pcode' => $region_state->pcode,
                    'area_id' => $area_id,
                    'party_name' => $max_party,
                    'party_color' => $max_party_color,
                    'party_id' => $max_party_id,
                    'total_seats' => $max_party_seat
                ]
            );
        }
        return $country_array;
    }

    public function getResultsByRegion(Request $request, $region, $year)
    {
        $houses = NationalityHouse::where('region_id', $region)->where('election_year', $year)->get();

        $house_array = [];
        $seat_array = [];
        $gender_array = [];
        $pcode = '';
        foreach ($houses as $house) {
            if ($house->Area != null) {
                $pcode = $house->Area->pcode;
            }
            $data =  NationalityResult::where('nationalityhouse_id', $house->id)->get();
            if ($data != null) {
                $max_vote = 0;
                $max_vote_party = '';
                $max_vote_candidate = '';
                foreach ($data as $result) {
                    $total_vote = (int)$result->valid_ps_vote + (int)$result->valid_adv_vote;
                    if ($total_vote >= $max_vote) {
                        $max_vote = $total_vote;
                        $max_vote_party = $result->party_id;
                        $max_vote_candidate = $result->candidate_id;
                    }
                }
                $party = Party::find($max_vote_party);
                $candidate = Candidate::find($max_vote_candidate);
                $party_name = '';
                $party_id = '';
                $party_color = '';
                $party_logo = '';
                $candidate_id = '';
                $candidate_name = '';
                $candidate_gender = '';
                if ($party != null) {
                    $party_name = $party->uec_party_name;
                    $party_id = $party->id;
                    $party_color = $party->color_code;
                    $party_logo = $party->party_logo_url;
                };
                if ($candidate != null) {
                    $candidate_id = $candidate->id;
                    $candidate_name = $candidate->name;
                    $candidate_gender = $candidate->gender;
                }

                array_push(
                    $house_array,
                    [
                        'total_vote' => $max_vote,
                        'nationalityhouse_name' => $house->seat_name_eng,
                        'pcode' => $pcode,
                        'nationalityhouse_id' => $house->id,
                        'party_name' => $party_name,
                        'party_color' => $party_color,
                        'party_logo' => $party_logo,
                        'party_id' => $party_id,
                        'candidate_id' => $candidate_id,
                        'candidate_name' => $candidate_name,
                        'candidate_gender' => $candidate_gender,
                    ]
                );
            }
        }

        if ($request->type == 'total') {
            foreach ($house_array as $key1 => $total_seat) {
                if (count($seat_array) == 0) {
                    array_push($seat_array, [
                        'party_name' => $total_seat['party_name'],
                        'party_color' => $total_seat['party_color'],
                        'party_id' => $total_seat['party_id'],
                        'total_votes' => 1
                    ]);
                } else {
                    $switch = 0;
                    foreach ($seat_array as $key => $seat) {
                        if ($seat['party_name'] == $total_seat['party_name']) {
                            $switch = 1;
                            $seat_array[$key]['total_votes'] += 1;
                        }
                    }
                    if ($switch == 0) {
                        array_push($seat_array, [
                            'party_name' => $total_seat['party_name'],
                            'party_color' => $total_seat['party_color'],
                            'party_id' => $total_seat['party_id'],
                            'total_votes' => 1
                        ]);
                    }
                }
            }
            return $seat_array;
        } else if ($request->type == 'gender') {
            foreach ($house_array as $key1 => $total_seat) {
                if (count($gender_array) == 0) {
                    array_push($gender_array, [
                        'candidate_gender' => $total_seat['candidate_gender'],
                        'count' => 1
                    ]);
                } else {
                    $switch = 0;
                    foreach ($gender_array as $key => $seat) {
                        if ($seat['candidate_gender'] == $total_seat['candidate_gender']) {
                            $switch = 1;
                            $gender_array[$key]['count'] += 1;
                        }
                    }
                    if ($switch == 0) {
                        array_push($gender_array, [
                            'candidate_gender' => $total_seat['candidate_gender'],
                            'count' => 1
                        ]);
                    }
                }
            }
            return $gender_array;
        } else {
            return $house_array;
        }
    }

    public function getResultsSeat(NationalityHouse $nationalityhouse)
    {
        return NationalityResults::collection($nationalityhouse->NationalityResults);
    }

    public function getCandidatesRegion(Region $region, $year)
    {
        $data = $region->NationalityHouses()->where('election_year', $year)->get();
        $candidate_array = [];
        $gender_array = [];
        $gender_array_per_region = [];
        foreach ($data as $data) {
            $results = NationalityResult::where('nationalityhouse_id', $data->id)->get();
            $max_vote = 0;
            $max_vote_candidate = '';
            foreach ($results as $result) {
                $total_vote = (int)$result->valid_ps_vote + (int)$result->valid_adv_vote;
                if ($total_vote >= $max_vote) {
                    $max_vote = $total_vote;
                    $max_vote_candidate = $result->candidate_id;
                }
            }
            $candidate = Candidate::find($max_vote_candidate);
            $candidate_name = '';
            $candidate_gender = '';
            if ($candidate != null) {
                $candidate_gender = $candidate->gender;
            };
            if (count($gender_array_per_region) == 0) {
                array_push($gender_array_per_region, [
                    'candidate_gender' => $candidate_gender,
                    'count' => 1,
                ]);
            } else {
                $switch = 0;
                foreach ($gender_array_per_region as $key => $seat) {
                    if ($seat['candidate_gender'] == $candidate_gender) {
                        $switch = 1;
                        $gender_array_per_region[$key]['count'] += 1;
                    }
                }
                if ($switch == 0) {
                    array_push($gender_array_per_region, [
                        'candidate_gender' => $candidate_gender,
                        'count' => 1,
                    ]);
                }
            }
            foreach ($results as $result) {
                array_push(
                    $candidate_array,
                    [
                        'id' => $result->Candidate->id,
                        'name' => $result->Candidate->name,
                        'seat_name' => $data->seat_name_eng,
                        'candidate_gender' => $result->Candidate->gender
                    ]
                );
            }
        }

        foreach ($candidate_array as $key1 => $gender) {
            if (count($gender_array) == 0) {
                array_push($gender_array, [
                    'candidate_gender' => $gender['candidate_gender'],
                    'count' => 1
                ]);
            } else {
                $switch = 0;
                foreach ($gender_array as $key => $seat) {
                    if ($seat['candidate_gender'] == $gender['candidate_gender']) {
                        $switch = 1;
                        $gender_array[$key]['count'] += 1;
                    }
                }
                if ($switch == 0) {
                    array_push($gender_array, [
                        'candidate_gender' => $gender['candidate_gender'],
                        'count' => 1
                    ]);
                }
            }
        }
        $final_array = [];
        foreach ($gender_array_per_region as $gender_region) {
            foreach ($gender_array as $gender) {
                if ($gender['candidate_gender'] == 'male' and $gender_region['candidate_gender'] == 'male') {
                    array_push($final_array, [
                        'male' => [
                            'total_candidate' => $gender['count'],
                            'elected_candidate' => $gender_region['count']
                        ]
                    ]);
                } else if ($gender['candidate_gender'] == 'female' and $gender_region['candidate_gender'] == 'female') {
                    array_push($final_array, [
                        'female' => [
                            'total_candidate' => $gender['count'],
                            'elected_candidate' => $gender_region['count']
                        ]
                    ]);
                }
            }
        }
        return $final_array;
    }

    public function getResultsTotal(Request $request, $year)
    {
        $houses = NationalityHouse::where('election_year', $year)->get();
        $seat_array = [];
        $gender_max_array = [];
        $gender_total_array = [];
        foreach ($houses as $house) {
            $data =  NationalityResult::where('nationalityhouse_id', $house->id)->get();
            if ($data != null) {
                $max_vote = 0;
                $max_vote_party = '';
                $max_vote_candidate = '';
                foreach ($data as $result) {
                    $total_vote = (int)$result->valid_ps_vote + (int)$result->valid_adv_vote;
                    if ($total_vote >= $max_vote) {
                        $max_vote = $total_vote;
                        $max_vote_party = $result->party_id;
                        $max_vote_candidate = $result->candidate_id;
                    }
                }

                if ($request->type == 'gender') {
                    foreach ($data as $result) {
                        if (count($gender_total_array) == 0) {
                            array_push($gender_total_array, [
                                'candidate_gender' => $result->Candidate->gender,
                                'count' => 1,
                            ]);
                        } else {
                            $switch = 0;
                            foreach ($gender_total_array as $key => $seat) {
                                if ($seat['candidate_gender'] == $result->Candidate->gender) {
                                    $switch = 1;
                                    $gender_total_array[$key]['count'] += 1;
                                }
                            }
                            if ($switch == 0) {
                                array_push($gender_total_array, [
                                    'candidate_gender' => $result->Candidate->gender,
                                    'count' => 1,
                                ]);
                            }
                        }
                    }

                    $candidate = Candidate::find($max_vote_candidate);
                    $candidate_name = '';
                    $candidate_gender = '';
                    if ($candidate != null) {
                        $candidate_gender = $candidate->gender;
                    };
                    if (count($gender_max_array) == 0) {
                        array_push($gender_max_array, [
                            'candidate_gender' => $candidate_gender,
                            'count' => 1,
                        ]);
                    } else {
                        $switch = 0;
                        foreach ($gender_max_array as $key => $seat) {
                            if ($seat['candidate_gender'] == $candidate_gender) {
                                $switch = 1;
                                $gender_max_array[$key]['count'] += 1;
                            }
                        }
                        if ($switch == 0) {
                            array_push($gender_max_array, [
                                'candidate_gender' => $candidate_gender,
                                'count' => 1,
                            ]);
                        }
                    }
                } else {
                    $party = Party::find($max_vote_party);
                    $party_name = '';
                    $party_id = '';
                    $party_color = '';
                    if ($party != null) {
                        $party_name = $party->uec_party_name;
                        $party_id = $party->id;
                        $party_color = $party->color_code;
                    };
                    if (count($seat_array) == 0) {
                        array_push($seat_array, [
                            'party_name' => $party_name,
                            'party_id' => $party_id,
                            'party_color' => $party_color,
                            'total_votes' => 1,
                        ]);
                    } else {
                        $switch = 0;
                        foreach ($seat_array as $key => $seat) {
                            if ($seat['party_name'] == $party_name) {
                                $switch = 1;
                                $seat_array[$key]['total_votes'] += 1;
                            }
                        }
                        if ($switch == 0) {
                            array_push($seat_array, [
                                'party_name' => $party_name,
                                'party_id' => $party_id,
                                'party_color' => $party_color,
                                'total_votes' => 1,
                            ]);
                        }
                    }
                }
            }
        }
        if ($request->type == "gender") {
            foreach ($gender_total_array as $gender_total) {
                foreach ($gender_max_array as $gender_max) {
                    if ($gender_max['candidate_gender'] == 'male' and $gender_total['candidate_gender'] == 'male') {
                        array_push($seat_array, [
                            'male' => [
                                'total_candidate' => $gender_total['count'],
                                'elected_candidate' => $gender_max['count']
                            ]
                        ]);
                    } else if ($gender_max['candidate_gender'] == 'female' and $gender_total['candidate_gender'] == 'female') {
                        array_push($seat_array, [
                            'female' => [
                                'total_candidate' => $gender_total['count'],
                                'elected_candidate' => $gender_max['count']
                            ]
                        ]);
                    }
                }
            }
        }

        return $seat_array;
    }

    public function compareTotal($type, Request $request)
    {
        $compare_seat = [];
        $houses2015 = [];
        $region = $request->region;
        $houses_2010 = [];
        if ($type == 'country') {
            $houses_2010 = NationalityHouse::where('election_year', '2010')->get();
            $houses2015 = NationalityHouse::where('election_year', '2015')->get();
        } else if ($type == 'region') {
            $houses_2010 = NationalityHouse::where('region_id', $region)->where('election_year', '2010')->get();
            $houses2015 = NationalityHouse::where('region_id', $region)->where('election_year', '2015')->get();
        }
        $seat_array_2010 = [];
        $seat_array_2015 = [];
        foreach ($houses_2010 as $house) {
            $data =  NationalityResult::where('nationalityhouse_id', $house->id)->get();
            if ($data != null) {
                $max_vote = 0;
                $max_vote_party = '';
                foreach ($data as $result) {
                    $total_vote = (int)$result->valid_ps_vote + (int)$result->valid_adv_vote;

                    if ($total_vote >= $max_vote) {
                        $max_vote = $total_vote;
                        $max_vote_party = $result->party_id;
                    }
                }
                $party = Party::find($max_vote_party);
                $party_name = '';
                $party_id = '';
                $party_color = '';
                if ($party != null) {
                    $party_name = $party->uec_party_name;
                    $party_id = $party->id;
                    $party_color = $party->color_code;
                }

                if (count($seat_array_2010) == 0) {
                    array_push($seat_array_2010, [
                        'party_name' => $party_name,
                        'party_id' => $party_id,
                        'party_color' => $party_color,
                        'total_votes' => 1
                    ]);
                } else {
                    $switch = 0;
                    foreach ($seat_array_2010 as $key => $seat) {
                        if ($seat['party_name'] == $party_name) {
                            $switch = 1;
                            $seat_array_2010[$key]['total_votes'] += 1;
                        }
                    }
                    if ($switch == 0) {
                        array_push($seat_array_2010, [
                            'party_name' => $party_name,
                            'party_id' => $party_id,
                            'party_color' => $party_color,
                            'total_votes' => 1
                        ]);
                    }
                }
            }
        }

        foreach ($houses2015 as $house) {
            $data =  NationalityResult::where('nationalityhouse_id', $house->id)->get();
            if ($data != null) {
                $max_vote = 0;
                $max_vote_party = '';
                foreach ($data as $result) {
                    $total_vote = (int)$result->valid_ps_vote + (int)$result->valid_adv_vote;
                    if ($total_vote >= $max_vote) {
                        $max_vote = $total_vote;
                        $max_vote_party = $result->party_id;
                    }
                }
                $party = Party::find($max_vote_party);
                $party_name = '';
                $party_id = '';
                $party_color = '';
                if ($party != null) {
                    $party_name = $party->uec_party_name;
                    $party_id = $party->id;
                    $party_color = $party->color_code;
                };

                if (count($seat_array_2015) == 0) {
                    array_push($seat_array_2015, [
                        'party_name' => $party_name,
                        'party_id' => $party_id,
                        'party_color' => $party_color,
                        'total_votes' => 1
                    ]);
                } else {
                    $switch = 0;
                    foreach ($seat_array_2015 as $key => $seat) {
                        if ($seat['party_name'] == $party_name) {
                            $switch = 1;
                            $seat_array_2015[$key]['total_votes'] += 1;
                        }
                    }
                    if ($switch == 0) {
                        array_push($seat_array_2015, [
                            'party_name' => $party_name,
                            'party_id' => $party_id,
                            'party_color' => $party_color,
                            'total_votes' => 1
                        ]);
                    }
                }
            }
        }
        if (count($seat_array_2010) >= count($seat_array_2015)) {
            foreach ($seat_array_2010 as $seat_2010) {
                $switch = 0;
                foreach ($seat_array_2015 as $key => $seat_2015) {
                    if ($seat_2010['party_id'] == $seat_2015['party_id']) {
                        $switch = 1;
                        $difference = $seat_2015['total_votes'] - $seat_2010['total_votes'];
                        if ($difference > 0) {
                            array_push($compare_seat, [
                                'party_id' => $seat_2010['party_id'],
                                'party_color' => $seat_2010['party_color'],
                                'party_name' => $seat_2010['party_name'],
                                '2010_seats' => $seat_2010['total_votes'],
                                '2015_seats' => $seat_2015['total_votes'],
                                'difference' => '+' . $difference
                            ]);
                        } else if ($difference < 0) {
                            array_push($compare_seat, [
                                'party_id' => $seat_2010['party_id'],
                                'party_color' => $seat_2010['party_color'],
                                'party_name' => $seat_2010['party_name'],
                                '2010_seats' => $seat_2010['total_votes'],
                                '2015_seats' => $seat_2015['total_votes'],
                                'difference' => '-' . abs($difference)
                            ]);
                        } else {
                        }
                    }
                }

                if ($switch == 0) {
                    array_push($compare_seat, [
                        'party_id' => $seat_2010['party_id'],
                        'party_color' => $seat_2010['party_color'],
                        'party_name' => $seat_2010['party_name'],
                        '2010_seats' => $seat_2010['total_votes'],
                        '2015_seats' => 0,
                        'difference' => '-' . abs($seat_2010['total_votes'])
                    ]);
                }
            }
            foreach ($seat_array_2015 as $seat_2015) {
                $switch = 0;
                foreach ($compare_seat as $filtered_seat) {
                    if ($seat_2015['party_id'] == $filtered_seat['party_id']) {
                        $switch = 1;
                    }
                }
                if ($switch == 0) {
                    array_push($compare_seat, [
                        'party_id' => $seat_2015['party_id'],
                        'party_color' => $seat_2015['party_color'],
                        'party_name' => $seat_2015['party_name'],
                        '2010_seats' => 0,
                        '2015_seats' => $seat_2015['total_votes'],
                        'difference' => '+' . $seat_2015['total_votes']
                    ]);
                }
            }
        } else {
            foreach ($seat_array_2015 as $seat_2015) {
                $switch = 0;
                foreach ($seat_array_2010 as $key => $seat_2010) {
                    if ($seat_2015['party_id'] == $seat_2010['party_id']) {
                        $switch = 1;
                        $difference = $seat_2015['total_votes'] - $seat_2010['total_votes'];
                        if ($difference > 0) {
                            array_push($compare_seat, [
                                'party_id' => $seat_2015['party_id'],
                                'party_color' => $seat_2015['party_color'],
                                'party_name' => $seat_2015['party_name'],
                                '2010_seats' => $seat_2010['total_votes'],
                                '2015_seats' => $seat_2015['total_votes'],
                                'difference' => '+' . $difference
                            ]);
                        } else if ($difference < 0) {
                            array_push($compare_seat, [
                                'party_id' => $seat_2015['party_id'],
                                'party_color' => $seat_2015['party_color'],
                                'party_name' => $seat_2015['party_name'],
                                '2010_seats' => $seat_2010['total_votes'],
                                '2015_seats' => $seat_2015['total_votes'],
                                'difference' => '-' . abs($difference)
                            ]);
                        } else {
                        }
                    }
                }

                if ($switch == 0) {
                    array_push($compare_seat, [
                        'party_id' => $seat_2015['party_id'],
                        'party_color' => $seat_2015['party_color'],
                        'party_name' => $seat_2015['party_name'],
                        '2010_seats' => 0,
                        '2015_seats' => $seat_2015['total_votes'],
                        'difference' => '+' . abs($seat_2015['total_votes'])
                    ]);
                }
            }
            foreach ($seat_array_2010 as $seat_2010) {
                $switch = 0;
                foreach ($compare_seat as $filtered_seat) {
                    if ($seat_2010['party_id'] == $filtered_seat['party_id']) {
                        $switch = 1;
                    }
                }
                if ($switch == 0) {
                    array_push($compare_seat, [
                        'party_id' => $seat_2010['party_id'],
                        'party_color' => $seat_2010['party_color'],
                        'party_name' => $seat_2010['party_name'],
                        '2010_seats' => $seat_2010['total_votes'],
                        '2015_seats' => 0,
                        'difference' => '-' . $seat_2010['total_votes']
                    ]);
                }
            }
        }
        return $compare_seat;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\NationalityResult  $nationalityResult
     * @return \Illuminate\Http\Response
     */
    public function show(NationalityResult $nationalityResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NationalityResult  $nationalityResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NationalityResult $nationalityResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NationalityResult  $nationalityResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(NationalityResult $nationalityResult)
    {
        //
    }
}
