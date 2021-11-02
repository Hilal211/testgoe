<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Announcement;
use Carbon\Carbon;
use Functions;
use Validator;
use Datatables;

class AnnouncementController extends Controller
{
    public function index()
    {
        $states = Announcement::all();
        $data = [
            'data' => $states
        ];
        return view('admin.announcement', $data);
    }
    public function getAnnouncements(Request $request)
    {
        $data = Announcement::all();
        return Datatables::of($data)
        ->editColumn('status', function ($data) {
            $name = $data->status;
            $id = $data->id;
            
            $name .="<div class='action-controls'>";
            $name .="   <a href='javascript:;' onclick='showEdit(this)' data-id='".$id."'><i class='fa fa-pencil'></i></a>"; 
            
            $name .="</div>";   
            return $name;
        })
        ->removeColumn('english_image')
        ->removeColumn('francais_image')
            ->removeColumn('id')
            ->make();
    }
    public function saveAnnouncements(Request $request)
    {
        $ann = new Announcement();
        $description = $request->description;
        $status = $request->status;

        $file = $request->file('english_image');
        if ($request->hasFile('english_image')) {
            if ($file->isValid()) {
                $image_data = Functions::UploadPic($file, config('theme.COUPON_UPLOAD'));
                $inputs['english_image'] = $image_data['encrypted_name'];
            }
        }

        $file = $request->file('francais_image');
        if ($request->hasFile('francais_image')) {
            if ($file->isValid()) {
                $image_data = Functions::UploadPic($file, config('theme.COUPON_UPLOAD'));
                $inputs['francais_image'] = $image_data['encrypted_name'];
            }
        }

        $ann->description = $description;
        $ann->english_image = $inputs['english_image'];
        $ann->francais_image = $inputs['francais_image'];
        $ann->status = $status;
       

        $Image = Functions::UploadsPath(config('theme.COUPON_UPLOAD')) . $coupon->english_image;
        $Image = public_path() . '/' . $Image;
        $Image = str_replace('public/public', 'public', $Image);
        chmod($Image, 0777);
        $ann->save();


        return response()->json(array(
            "status" => "success",
            "message" => $description,
            "p1" => $inputs['english_image'],
            "des" => $inputs['description']

        ));















        // if($file!=null){
        //     if($OldAnn->english_image!=''){
        //         $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$OldAnn->english_image;
        //         \File::delete($Image);
        //     }
        //     $data = Functions::UploadPic($file,config('theme.CATEGORY_UPLOAD'));
        //     $OldAnn->update(['english_image'=>$data['encrypted_name']]);
        //     $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$data['encrypted_name'];
        //     $Image = public_path().'/'.$Image;
        //     $Image = str_replace('public/public','public',$Image);
        //     chmod($Image,0777);
        // }
        // $file2 = $request->file('francais_image');
        // if($file!=null){
        //     if($OldAnn->english_image!=''){
        //         $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$OldAnn->english_image;
        //         \File::delete($Image);
        //     }
        //     $data = Functions::UploadPic($file,config('theme.CATEGORY_UPLOAD'));
        //     $OldAnn->update(['francais_image'=>$data['encrypted_name']]);
        //     $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$data['encrypted_name'];
        //     $Image = public_path().'/'.$Image;
        //     $Image = str_replace('public/public','public',$Image);
        //     chmod($Image,0777);
        // }
        // $status = $request->status;


        // if ($request->hasFile('english_image')) {
        //     $original_name=$request->file('english_image')->getClientOriginalName();
        //     $uniqueid=time();

        //     $size=$request->file('english_image')->getSize();
        //     $extension=$request->file('english_image')->getClientOriginalExtension();

        //     $name=$uniqueid.'.'.$extension;
        //     $path=$request->file('english_image')->storeAs('public/assets/uploads', $name);



        //     if ($path) {
        //         $ann = new Announcement();
        //         $ann->description=$description;
        //         $ann->status=$status;
        //         $ann->english_image=$name;
        //         $ann->save();

        //         return response()->json(array('status'=>'success','message'=>'Image successfully uploaded','english_image'=>$name));
        //     } else {
        //         return response()->json(array('status'=>'error','message'=>'failed to upload english_image'));
        //     }
        // }




    }
}
