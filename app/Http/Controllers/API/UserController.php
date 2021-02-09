<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FileUpload;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\FileUploadRequest;




class UserController extends ApiController {

    
    public function fetchAllUser($country) {
        
        $country = Country::where('country',$country)->get();

        if(count($country) == 0) {
            return $this->respondWithArray([], ["Country does not exist"], 1,400);
         }

       $company = Company::where('country_id',$country[0]['id'])->get();

        foreach ($company as $key => $value) {
           $company[$key]['country'] = Country::find($value['country_id']);
           $company[$key]['users'] = User::find($value['user_id']);
          unset($value['country_id']);
          unset($value['user_id']);
          unset($value['deleted_at']);
        }
       
      return $this->respondWithArray(json_decode($company), ["Company List"], 0, 200, null);
        
    }

    public function fileUpload(FileUploadRequest $request)
    {
        $this->request = $request;
        $input = $this->request->all();
        
        //-- Get FileName & FileSize---//
        $getFileName = $this->request->file('file_upload')->getClientOriginalName();
        $getFilesize = $this->request->file('file_upload')->getClientSize();
        $filename = $getFilesize . '_' . $getFileName;

        //-- Find Filename and Filesize in database --//
        $fileUpload = FileUpload::where(['file_upload' => $filename, 'file_size' => $getFilesize])->get();

         if ($this->request->file('file_upload')) {
            
            if (count($fileUpload)>0) {
               if (File::exists(public_path('file_upload/' . $fileUpload[0]['file_upload']))) {
                   File::delete(public_path('file_upload/' . $fileUpload[0]['file_upload']));
                }
            }

              //-- File upload on file_upload folder---//
              $image =$this->request->file_upload;   
              Storage::disk('file_upload')->put($filename,base64_decode($image));


             FileUpload::updateOrCreate(
                      ['file_upload' => $filename, 'file_size' => $getFilesize],
                      ['file_upload' =>  $filename,'file_size' => $getFilesize]
                    );

              return $this->respondWithArray([], ["File Upload successfully"], 0);
          }
    }
 
   


}
