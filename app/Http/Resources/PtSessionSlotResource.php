<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PtSessionSlotResource extends JsonResource
{
    /**
     * Disable the default "data" wrapper so the JSON is flat.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this['date'])->locale('id');

        return [
            'date' => $this['date'],
            'formatted_date' => $date->translatedFormat('l, d F Y'),
            'trainer' => [
                'id' => $this['trainer']->id,
                'name' => $this['trainer']->name,
            ],
            'groups' => $this['groups'],
        ];
    }
}
