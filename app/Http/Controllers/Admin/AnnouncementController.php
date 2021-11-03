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
                $name .= "<div class='action-controls'>";
                $name .= "   <a href='javascript:;' onclick='showEdit(this)' data-id='" . $id . "'><i class='fa fa-pencil'></i></a>";

                $name .= "</div>";
                return $name;
            })
            ->removeColumn('english_image')
            ->removeColumn('francais_image')
            ->removeColumn('id')
            ->make();
    }
    public function saveAnnouncements(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
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
            $file2 = $request->file('francais_image');
            if ($request->hasFile('francais_image')) {
                if ($file2->isValid()) {
                    $image_data = Functions::UploadPic($file2, config('theme.COUPON_UPLOAD'));
                    $inputs['francais_image'] = $image_data['encrypted_name'];
                }
            }
            $ann->description = $description;
            $ann->english_image = $inputs['english_image'];
            $ann->francais_image = $inputs['francais_image'];
            $ann->status = $status;
            $ann->save();
            return response()->json(array(
                "status" => "success",
                "message" => $description,
                "p1" => $inputs['english_image'],
                "des" => $inputs['description'],
                "id" => $id

            ));
        } else {
            $ann = Announcement::where('id', $id)->first();
            $description = $request->description;
            $status = $request->status;

            $file = $request->file('english_image');
            if ($request->hasFile('english_image')) {
                if ($file->isValid()) {
                    $image_data = Functions::UploadPic($file, config('theme.COUPON_UPLOAD'));
                    $inputs['english_image'] = $image_data['encrypted_name'];
                }
            }

            $file2 = $request->file('francais_image');
            if ($request->hasFile('francais_image')) {
                if ($file2->isValid()) {
                    $image_data = Functions::UploadPic($file2, config('theme.COUPON_UPLOAD'));
                    $inputs['francais_image'] = $image_data['encrypted_name'];
                }
            }
            $ann->description = $description;
            if ($inputs['english_image'] != null) {
                $ann->english_image = $inputs['english_image'];
            }
            if ($inputs['francais_image'] != null) {
                $ann->francais_image = $inputs['francais_image'];
            }
            $ann->status = $status;
            $ann->save();
            return response()->json(array(
                "status" => "success",
                "message" => $description,
                "p1" => $inputs['english_image'],
                "des" => $inputs['description'],
                "id" => $id
            ));
        }
    }

    public function edit($id)
    {
        $inputs = Announcement::find($id);
        $image = ($inputs->english_image != '' ? url(Functions::UploadsPath(config('theme.COUPON_UPLOAD')) . $inputs->english_image) : "");
        $image2 = ($inputs->francais_image != '' ? url(Functions::UploadsPath(config('theme.COUPON_UPLOAD')) . $inputs->francais_image) : "");

        $filteredArr = [
            'id' => ["type" => "text", 'value' => $inputs->id],
            'description' => ["type" => "text", 'value' => $inputs->description],
            'status' => ["type" => "radio", 'selectedValue' => $inputs->status],
            'english_image' => ["type" => "image", 'file' => $image],
            'francais_image' => ["type" => "image", 'file' => $image2],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs" => $filteredArr,
        ));
    }
}
