<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AboutController extends Controller
{

    // public function collet(Request $request) {

    //     $token = $request->token;
    //     // echo $token;
    //     $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    //     $email = $decoded_token->email;

    //     $updateData = [];
    //     $updateData['PersonaIProfile'] = $request->PersonaIProfile;
    //     DB::table("users")->where("email", "=", $email)->update($updateData);
    //     $PersonaIProfile = DB::table("users")->select('PersonaIProfile')->where("email", "=", $email)->get();
    //     // return $PersonaIProfile;
    //     return response()->json([
    //         'PersonaIProfile' => $PersonaIProfile,
    //     ]);
    // }

    // 我自己的文章
    // public function mypost(Request $request){
    //     $token = $request->token;
    //     // echo $token;
    //     $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    //     $id = $decoded_token->id;
    //     // return $decoded_token;
    //     // return response()->json([
    //     //     "id" => $id
    //     // ]);
    //     $mypost=DB::table("users")
    //     ->join("UserPost","users.id","=","UserPost.UID")
    //     ->join("LikeAndDislike", "UserPost.WID", "=", "LikeAndDislike.WID")
    //     ->where("users.id",$id)
    //     ->select(
    //         "users.name",
    //         "UserPost.WID",
    //         "UserPost.UID",
    //         "UserPost.InProgress",
    //         "UserPost.Title",
    //         "UserPost.Article",
    //         "UserPost.ItemLink",
    //         "UserPost.ItemIMG",
    //         "UserPost.PostTime",
    //         "UserPost.ChangeTime",
    //         "UserPost.ConcessionStart",
    //         "UserPost.ConcessionEnd",
    //         "UserPost.ReportTimes",
    //         "UserPost.Hiding",
    //         "UserPost.location_tag",
    //         "UserPost.product_tag",
    //         "LikeAndDislike.GiveLike",
    //         "LikeAndDislike.GiveDisLike",

    //     )

    //     return response()->json([
    //         'mypost' => $mypost
    //     ]);


    // }

    public function about(Request $request)
    {


        $token = $request->token;
        // echo $token;
        $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
        $id = $decoded_token->id;
        // return $decoded_token;
        // return response()->json([
        //     "id" => $id
        // ]);


        $mypost = DB::table("users")
            ->join("UserPost", "users.id", "=", "UserPost.UID")
            ->join("LikeAndDislike", "UserPost.WID", "=", "LikeAndDislike.WID")
            ->where("users.id", $id)
            ->select(
                "users.name",
                "users.email",
                "users.password",
                "UserPost.WID",
                "UserPost.UID",
                "UserPost.InProgress",
                "UserPost.Title",
                "UserPost.Article",
                "UserPost.ItemLink",
                "UserPost.ItemIMG",
                "UserPost.PostTime",
                "UserPost.ChangeTime",
                "UserPost.ConcessionStart",
                "UserPost.ConcessionEnd",
                "UserPost.ReportTimes",
                "UserPost.Hiding",
                "UserPost.location_tag",
                "UserPost.product_tag",
                "LikeAndDislike.GiveLike",
                "LikeAndDislike.GiveDisLike",
            )
            ->get();

            $userName = DB::table('users')->where('id', $id)->value('name');

            if ($mypost->isEmpty()) {
                $mypost = [['name' => $userName]];
            } else {
                $mypost;
            }

        $good = DB::table("users")
            ->join("UserPost", "users.id", "=", "UserPost.UID")
            ->join("LikeAndDislike", "UserPost.WID", "=", "LikeAndDislike.WID")
            ->where("users.id", $id)
            ->selectRaw('IFNULL(SUM(GiveLike),"0") as Sumlike')
            // ->groupBy('users.id')
            ->get();

            if ($good->isEmpty()) {
                $good = ['Sumlike' => "0"];
            } else {
                $good;
            }

        $collect = DB::table("users")
            ->join("SubAndReport", "users.id", "=", "SubAndReport.UID")
            ->join("UserPost", "SubAndReport.TargetWID", "=", "UserPost.WID")
            ->join("LikeAndDislike", "UserPost.WID", "=", "LikeAndDislike.WID")
            ->join("PostMessage", "UserPost.WID", "=", "PostMessage.WID")
            // ->join("UserPost", "SubAndReport.TargetWID", "=", "UserPost.WID")
            // ->where("users.id", $id)
            ->select(
                "UserPost.WID",
                "UserPost.UID",
                "UserPost.InProgress",
                "UserPost.Title",
                "UserPost.Article",
                "UserPost.ItemLink",
                "UserPost.ItemIMG",
                "UserPost.PostTime",
                "UserPost.ChangeTime",
                "UserPost.ConcessionStart",
                "UserPost.ConcessionEnd",
                "UserPost.ReportTimes",
                "UserPost.Hiding",
                "UserPost.location_tag",
                "UserPost.product_tag",
                "LikeAndDislike.GiveLike",
                "LikeAndDislike.GiveDisLike",
                "users.name",
                "PostMessage.UID",
                "PostMessage.MSGPost",
                "PostMessage.MSGPostTime"
            )
            ->get();


        // $collect = DB::table("users")
        //     ->join("SubAndReport", "users.id", "=", "SubAndReport.UID")
        //     ->join("UserPost", "SubAndReport.TargetWID", "=", "UserPost.WID")
        //     ->join("LikeAndDislike", "UserPost.WID", "=", "LikeAndDislike.WID")
        //     ->join("PostMessage", "UserPost.WID", "=", "PostMessage.WID")
        //     // ->join("UserPost", "SubAndReport.TargetWID", "=", "UserPost.WID")
        //     ->where("users.id", $id)
        // ->select("UserPost.WID",
        // "UserPost.UID",
        // "UserPost.InProgress",
        // "UserPost.Title",
        // "UserPost.Article",
        // "UserPost.ItemLink",
        // "UserPost.ItemIMG",
        // "UserPost.PostTime",
        // "UserPost.ChangeTime",
        // "UserPost.ConcessionStart",
        // "UserPost.ConcessionEnd",
        // "UserPost.ReportTimes",
        // "UserPost.Hiding",
        // "UserPost.location_tag",
        // "UserPost.product_tag",
        // "LikeAndDislike.GiveLike",
        // "LikeAndDislike.GiveDisLike",
        // "users.name",
        // "PostMessage.UID",
        // "PostMessage.MSGPost",
        // "PostMessage.MSGPostTime")
        // ->get();

        $Self_introduction = DB::table("users")->where("users.id", $id)->select("PersonalProfile","image")->get();

        return response()->json([
            "讚數" => $good,
            "收藏文章" => $collect,
            "自我介紹" => $Self_introduction,
            'mypost' => $mypost
        ]);
    }

    public function post(Request $request)
    {

        $token = $request->token;
        // echo $token;
        $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
        $id = $decoded_token->id;

        $postmsg = DB::table("users")
            ->join("SubAndReport", "users.id", "=", "SubAndReport.UID")
            ->join("UserPost", "SubAndReport.TargetWID", "=", "UserPost.WID")
            ->join("PostMessage", "UserPost.WID", "=", "PostMessage.WID")
            ->where("users.id", $id)
            ->select(
                "PostMessage.UID",
                "PostMessage.MSGPost",
                "PostMessage.WID",
                "PostMessage.MSGPostTime"
            )
            ->get();

        $mypostmsg = DB::table("users")
            ->join("UserPost", "users.id", "=", "UserPost.UID")
            ->join("PostMessage", "UserPost.WID", "=", "PostMessage.WID")
            ->where("users.id", $id)
            ->select(
                "PostMessage.UID",
                "PostMessage.MSGPost",
                "PostMessage.WID",
                "PostMessage.MSGPostTime"
            )
            ->get();

        return response()->json([
            "postmsg" => $postmsg,
            "mypostmsg" => $mypostmsg,
        ]);

    }


    public function update_item(Request $request)
    {

        // 接前端送來的token
        // $token = $request->token;
        // 判斷資料是不是符合格式
        // $request->validate([
        //     // 'id' => 'required|integer',
        //     // 'name' => 'string',
        //     // "password" => "integer",
        // ]);
        // COOKIE版本
        $token = $request->token;
        // echo $token;
        $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
        $email = $decoded_token->email;
        
        // return $email;
        
        // // TOKEN放資料庫版本
        // $token = User::select('remember_token')->where('email', '=', $request->email)->get();
        // echo $token[0];
        // $token = $token[0]['remember_token'];
        
        
        
        // $data = $request->imageApple;
        // $data = str_replace("C:\\fakepath\\", "D:\\06\\test\\", $data);
        // $imageData = base64_encode($data);
        // $src = "base64,$imageData";
        
        // $src = $request->imageApple;
        
        
        // $original_image = User::select('image')->where('email', '=', $email)->get();
        // $original_name = User::select('name')->where('email', '=', $email)->get();
        // $original_password = User::select('password')->where('email', '=', $email)->get();
        
        $original_image = DB::table("users")->select('image')->where('email', '=', $email)->get();
        $original_name = DB::table("users")->select('name')->where('email', '=', $email)->get();
        $original_password = DB::table("users")->select('password')->where('email', '=', $email)->get();
        $PersonalProfile = DB::table("users")->select('PersonalProfile')->where("email", "=", $email)->get();
        $updateData = [];
        
        // 判斷使用者修改甚麼欄位
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imagedata = base64_encode(file_get_contents($file->getPathname()));
            $updateData['image'] = $imagedata;
        }
        // if (($request->imageApple) != "" && ($request->imageApple != $original_image)) {
            //     $updateData['image'] = $request->imageApple;
            //     $src = $request->imageApple;
            // } else if (($request->imageApple) == "") {
                //     $src = $original_image;
                // }
                
                if (($request->name) != "" && ($request->name != $original_name)) {
                    $updateData['name'] = $request->name;
                }
                
                
                if (($request->PersonalProfile) != "" && ($request->PersonalProfile != $PersonalProfile)) {
                    $updateData['PersonalProfile'] = $request->PersonalProfile;
                }
                
                if (($request->password) != "" && ($request->password != $original_password)) {
                    $updateData['password'] = Hash::make($request->password);
                }


        // // 判斷使用者修改甚麼欄位
        // if (($request->name) == "" &&  ($request->password) != "") {
        //     $updateData = [
        //         // "image" => $src,
        //         'password' => Hash::make($request->password)
        //     ];
        //     die("OK");
        // } else if (($request->password) == "" && ($request->name) != "") {
        //     $updateData = [
        //         // "image" => $src,
        //         'name' => $request->name
        //     ];
        // } else {
        //     $updateData = [
        //         // "image" => $src,
        //         'name' => $request->name,
        //         'password' => Hash::make($request->password)
        //     ];
        // };

        // return $email;
        // return $updateData;
        // $aa = DB::table("users")->where('email', '=', $email)->get();
        // return $aa;
        // return $updateData['image'];
        DB::table("users")->where('email', '=', $email)->update($updateData);
        // User::where('email', '=', $email)->update($updateData);

        // die("OK");

        return response()->json([
            // 'data2' => $data,
            // "src" => $src,
            'image' => $imagedata,
            'message' => 'Item updated successfully',
            // "update_item" => $update_item
        ]);
        // ->header("Access-Control-Allow-Origin", "*");
    }
}
