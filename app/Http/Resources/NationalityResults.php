<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NationalityResults extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $party_name = '';
        $party_logo = '';
        $party_color = '';
        $candidate_name = '';
        if ($this->Party != null) {
            $party_name = $this->Party->uec_party_name;
            $party_logo =  $this->Party->party_logo_url;
            $party_color = $this->Party->color_code;
        }
        if ($this->Candidate != null) {
            $candidate_name = $this->Candidate->name;
        }
        return [
            'id' => $this->id,
            'total_vote' => $this->valid_ps_vote + $this->valid_adv_vote,
            'party_name' => $party_name,
            'party_logo' => $party_logo,
            'party_color' => $party_color,
            'candidate_name' => $candidate_name,
            'seat_name' => $this->NationalityHouse->seat_name_eng,
            'region' => $this->NationalityHouse->region_id
        ];
    }
}
