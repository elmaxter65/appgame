<?php

namespace App\Http\Controllers;

use Response;
use Validator;
use Kreait\Firebase;
use App\Notification;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $notifications = Notification::all();
        return view('notifications/index',['notifications' => $notifications]);
    }

    public function getJson($id)
    {
        $notification = Notification::find($id);

        return Response::json(array('success' => true, 'data' => $notification), 200);
    }

    public function create(Request $request){
        $data = $request->all();

        $validator = $this->validator($request->all());
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors());
        }

        try{
            $notification = Notification::create([
                'title' => $data['title'],
                'notification' => $data['notification'],
                'send' => false,
            ]);
            $msg = "The notification was successfully created.";
            $type_msg = "success";
        }
        catch(\Exception $e){

            $msg = "Oops. There was an error and the notification couldn't be created";
            $type_msg = "danger";

        }

        $notifications = Notification::all();

        //return Redirect::route('notification',['notifications' => $notifications, 'msg' => $msg, 'type_msg' => $type_msg]);
        return view('notifications/index',['notifications' => $notifications, 'msg' => $msg, 'type_msg' => $type_msg]);

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'notification' => ['required', 'string'],
        ]);
    }

    public function delete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'notification_id' => ['required', 'int', 'exists:notifications,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $notification = Notification::find($request->get('notification_id'));
        $notification->delete();

        // delete from firebase
        /*$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/abbott-dev-firebase-adminsdk-vmges-cf7cdf32a6.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://abbott-dev.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $newNotification = $database->getReference("notification_id/$notification->id")
        ->remove();*/

        return Response::json(array('success' => true), 200);


    }

    public function send(Request $request){

        $validator = Validator::make($request->all(), [
            'notification_id' => ['required', 'int', 'exists:notifications,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $notification = Notification::find($request->get('notification_id'));
        $notification->send = true;
        $notification->save();

        $factory = (new Factory)->withServiceAccount(__DIR__.'/../abbott-dev-firebase-adminsdk-vmges-cf7cdf32a6.json');
        $messaging = $factory->createMessaging();
        $notifi = Notification::fromArray([
            'title' => $notification->title,
            'body' => $notification->notification,
        ]);
        $datanotifi = [
            'first_key' => 'First Value',
            'second_key' => 'Second Value',
        ];
        $message = CloudMessage::withTarget('topic', 'all')
            ->withNotification($notifi)
            ->withData($datanotifi);
        $messaging->send($message);

        /*$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/abbott-dev-firebase-adminsdk-vmges-cf7cdf32a6.json');
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://abbott-dev.firebaseio.com/')
        ->create();
        $database = $firebase->getDatabase();
        $newNotification = $database
        ->getReference("notification_id/$notification->id")
        ->push([
        'title' => $notification->title,
        'body' => $notification->notification
        ]);*/
        //$newPost->getKey(); // => -KVr5eu8gcTv7_AHb-3-
        //$newPost->getUri(); // => https://my-project.firebaseio.com/blog/posts/-KVr5eu8gcTv7_AHb-3-
        //$newPost->getChild('title')->set('Changed post title');
        //$newPost->getValue(); // Fetches the data from the realtime database
        //$newPost->remove();
        //echo"<pre>";
        //print_r($newNotification->getvalue());

        return Response::json(array('success' => true), 200);

    }


}
