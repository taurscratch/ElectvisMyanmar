<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NationalityHouse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pcode = '';
        if ($this->Area != null) {
            $pcode = $this->Area->pcode;
        }
        return [
            'id' => $this->id,
            'seat_name_mm' => $this->seat_name_mm,
            'seat_name_eng' => $this->seat_name_eng,
            'hluttaw_type_mm' => $this->hluttaw_type_mm,
            'hluttaw_type_eng' => $this->hluttaw_type_eng,
            'election_year' => $this->election_year,
            'area_id' => $this->area_id,
            'area_pcode' => $pcode,
            'region_id' => $this->region_id
        ];
    }
}
