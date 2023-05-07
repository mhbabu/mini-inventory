<?php

namespace App\Libraries;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Intervention\Image\Facades\Image;

class CommonFunction {

    public static function storeUploadPhoto($targetFile,$photoDirectory,$PreviousPhoto){

        if($targetFile){
            $path = 'uploads/company-logo/';
            $_companyLogo = $request->file('logo');
            $mimeType = $_companyLogo->getClientMimeType();
            if(!in_array($mimeType,['image/jpg', 'image/jpeg', 'image/png']))
                return redirect()->back();

            $previousLogo = $path.'/'.$company->logo; // get previous image from folder
            if (File::exists($previousLogo)) // unlink or remove previous image from folder
                @unlink($previousLogo);

            if(!file_exists($path))
                mkdir($path, 0777, true);

            $companyLogo = trim(sprintf('%s', uniqid('CompanyLogo_', true))) . '.' . $_companyLogo->getClientOriginalExtension();
            Image::make($_companyLogo->getRealPath())->resize(300,300)->save($path . '/' . $companyLogo);
            $company->logo = $companyLogo;
        }
    }






    /*     * ****************************End of Class***************************** */
}
