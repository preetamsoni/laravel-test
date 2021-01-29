<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\FileUpload;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Social;


class UserResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        
        // $companyWiseUser = Company::find($this->id)->user;
//echo request()->segment(3);
        $companyWiseUser = User::find($this->id)->company;

         return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $companyWiseUser,
           ];
    }

}
