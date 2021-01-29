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
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Hash;
use App\Mail\LoginOtpMail;
use Illuminate\Support\Facades\Mail;




class UserController extends ApiController {

    
    public function __construct(Request $request) {

        $this->request = $request;
    }

    
    public function fetchAllUser($country) {
        
        $country = Country::where('country',$country)->get();

         // echo "<pre>";
         // print_r($country);

         if(count($country) == 0) {
            return $this->respondWithArray([], ["Country does not exist"], 1,400);
         }

        $countryWiseCompany = Country::find($country[0]['id'])->company;
        
        $user = User::all();

        $company = Company::where('country_id',$country[0]['id'])->get();


        foreach ($company as $key => $value) {
         $company->find($value['country_id'])->country;
          $company->find($value['user_id'])->users;
          unset($value['country_id']);
          unset($value['user_id']);
          unset($value['deleted_at']);
        }
       
        // echo "<pre>";
        // print_r(json_encode($company));

        // return json_encode($company)
        
       return $this->respondWithArray(json_decode($company), ["Company List"], 0, 200, null);
        
    }

    public function fileUpload()
    {

         $validator = Validator::make($this->request->all(), [
                    'file_upload' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error = array(
                'file_upload' => $errors->first('file_upload'),
                 );
            return $this->respondWithArray([], $error, 1,422);
        }

        
        $input = $this->request->all();
        

        $getFileName = $this->request->file('file_upload')->getClientOriginalName();

        $getFilesize = $this->request->file('file_upload')->getClientSize();
       
      
        $fileUpload = FileUpload::where(['file_upload' => $getFileName, 'file_size' => $getFilesize])->get();

         if ($this->request->file('file_upload')) {
              
            if (count($fileUpload)>0) {
               if (File::exists(public_path('file_upload/' . $fileUpload[0]['file_upload']))) {
                   File::delete(public_path('file_upload/' . $fileUpload[0]['file_upload']));
                }

              $image =$this->request->file_upload;   
              $filename = $getFileName;
              Storage::disk('file_upload')->put($filename,base64_decode($image));
                
              $findFile = FileUpload::find($fileUpload[0]['id']);
              $updateData = array('file_upload' =>  $filename,'file_size' => $getFilesize);
              $findFile->update($updateData);
              return $this->respondWithArray([], ["File Upload successfully updated"], 0);

            } else {
              $image =$this->request->file_upload;   
              $filename = $getFileName;
              Storage::disk('file_upload')->put($filename,base64_decode($image));
              $createData = array('file_upload' =>  $filename,'file_size' => $getFilesize);
              FileUpload::create($createData);
              return $this->respondWithArray([], ["File Upload successfully created"], 0);
            }
           
         }
    }
 
   


}
