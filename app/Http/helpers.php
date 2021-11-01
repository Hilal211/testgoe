<?php
namespace App\Http;
use Collective\Html\HtmlFacade;
use App\Http\Request;
use Str;
use Cookie;

class CommanFunctions {
  public static function GetActionLinks($id) {
		$HTML = "";
		$HTML .="<div class='action-controls'>";	
		$HTML .="	<a href='javascript:;' onclick='SetEdit(this)' data-id='".$id."'><i class='fa fa-pencil'></i></a>";	
		$HTML .="	<a href='javascript:;' onclick='GetDelete(this)' data-id='".$id."'><i class='fa fa-remove'></i></a>";	
		$HTML .="</div>";	
   return $HTML;
  }
  public static function GetRandomUsername(){
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // generate a pin based on 2 * 7 digits + a random character
    $pin = mt_rand(100000, 999999).mt_rand(100000, 999999) . $characters[rand(0, strlen($characters) - 1)];
    // shuffle the result
    $string = str_shuffle($pin);
    return $string;
  }
  public static function GetStatus($status){
    if($status=='1'){
      return 'Active';
    }else{
      return 'Inactive';
    }
  }
  public static function GetImageName($Image,$type_name){
    $ext = '.'.\File::extension($Image);
    $name = str_replace($ext,'',$Image);
    return $name.$type_name.$ext;
  }
  public static function UploadPic($pic,$SubFolderPath){
    $name =  $pic->getClientOriginalName();
    $extension = $pic->getClientOriginalExtension();
    $encrypted_name = md5(uniqid().time()).".".$extension;
    $pic->move(self::UploadsPath($SubFolderPath),$encrypted_name);
    \Log::error($SubFolderPath);
    return array(
      "name"=>$name,        
      "encrypted_name"=>$encrypted_name,
      'path'=>self::UploadsPath($SubFolderPath)
    ); 
  }
  public static function UploadsPath($subFolder){
    //return config('theme.UPLOADS').$subFolder.DIRECTORY_SEPARATOR;
    return config('theme.UPLOADS').$subFolder.'/';
  }
  public static function GetAddress($data,$type="",$seprator=", "){
    $addressArr = array();
    if($type=='short'){
      $addressArr[] = $data->shipping_city;
    }else if($type=='full'){
      $addressArr[] = $data->shipping_address;
      $addressArr[] = $data->shipping_city;
      $addressArr[] = $data->shipping_state.' '.$data->shipping_zip;
    }else{
      return "";
    }
    $addressArr = array_filter($addressArr);
    return implode($seprator,$addressArr);
  }
  public static function GetPrice($price){
    return '$'.number_format($price, 2);
  }
  public static function GetRate($rate){
    return number_format($rate,1);
  }
  public static function GetLatLong($add1,$add2,$city,$state,$zip){
      $address = $add1.', '.$add2.', '.$city.', '.$state.', '.$zip; // Google HQ
      $prepAddr = str_replace(' ','+',$address);
      $prepAddr = urlencode($prepAddr);
      $url = 'https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false&key=AIzaSyCJObOq7U0Q7OM_RlzqppNYZvlvgFDARmM';
      $geocode=file_get_contents($url);
      //$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address=this+is+address&sensor=false');
      return $output= json_decode($geocode);
  }
  public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      if(!is_nan($miles)){
        return round($miles);
      }else{
        return 0;
      }
    }
  }
  public static function getShortenText($title,$length){
    $start = 0;
    //$length = 18;
    if(strlen($title)>$length){
        $comma="..";
    }else{
        $comma="";
    }
    return substr($title,$start,$length).$comma;
  }

  public static function getDifferenceLogText($newArr,$oldArr,$condition="false",$breakpoint="li"){
    $diff = array_diff($newArr,$oldArr);
    $logText = "";
    if($diff){
      foreach ($diff as $key => $value) {
          $oldValue = $oldArr[$key];
          $newValue = $newArr[$key];
          if($condition){
            if($key=='username'){
              $key="store contact person";
            }
          }
          if($breakpoint=='li'){
            $logText .= "<li>". ucwords($key).' is changed from '.$oldValue.' to '.$newValue.'</li>';
          }elseif($breakpoint=='br'){
            $logText .= ucwords($key).' is changed from '.$oldValue.' to '.$newValue.'<br/>';
          }
      }
    }
    return $logText;
  }

  public static function ResizeImage($path,$width,$height,$newName){
    $ImagePath = $path;
    $canvas = \Image::canvas($width,$height);
    $image = \Image::make($ImagePath)->resize($width,$height, function($constraint) {
      $constraint->aspectRatio();
    }); 
    $canvas->insert($image,'center');
    $canvas->save($newName,'70');
  }

  public static function convertTimeToLocal($date, $format,$type='object'){
      //$new_date = \Carbon::createFromFormat($format, $date)->setTimezone('Asia/Kolkata');
      $new_date = \Carbon::createFromFormat($format,$date)->setTimezone(Cookie::get('timezone'));
      if($type=='object'){
        return $new_date;
      }else{
        return $new_date->format($format);
      }
  }
}