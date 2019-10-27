<?php

namespace App\Http\Controllers;

use Config;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Requests\Custom\CustomMessageRequest;
use App\Services\RedisService;

class MessageController extends Controller
{
    protected $message;

    public function __construct(Message $message){
        $this->message = $message;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id=null){
        
        if($id != null){
            $message = $this->message->get($id);
            $message = $message->toArray();
            $message['drafts'] = '';
            return ($message == null) ? \Response::json(['message' => 'Not Found!'],404) : \Response::json([$message],200);
        }

        $messages = $this->message->getAll();
        return \Response::json($messages,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $validation = CustomMessageRequest::validate($input);
        
        if ($validation['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validation['errors']], 400);
        }

        $this->message->new($input);

        //RedisService::publishOnChannel(config('cache.drafts-channel'));  
        
        return \Response::json(['message' => 'Successfully saved item!'], 201);
    }

    public function update($id,Request $request) {
        
        $message = $this->message->get($id);
        
        if ($message==null){
            return \Response::json(['message' => 'Not Found!'], 404);
        }
            
        $input = $request->all();

        if (empty($input['status'])){
             return \Response::json(['message' => 'Bad Request!'], 400);
        }

        $this->message->edit($message,$input);

        return \Response::json(['message' => 'Successfully updated item!'], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id){
        
        $message = $this->message->get($id);

        if ($message == null) {
            return \Response::json(['message' => 'Not Found!'], 404);
        }

        $this->message->remove($message->id);
        
        return \Response::json(['message' => 'Successfully deleted item!'], 200);
        
    }

     /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function createDraft(Request $request)
    {
        $input = $request->all();

        if (empty($input['sender_id']) or empty($input['body'])) {
             return \Response::json(['message' => 'Bad Request!'], 400);
        }

        $key = '#'.$input['sender_id'].'#'.date('Y-m-dTH:i:s', time());
        $value = $input['body'];
        //var_dump(RedisService::searchByPattern($input['sender_id'],'message-drafts'));die;

        $setValue = RedisService::setValue($key,json_encode($value),'message-drafts');
        $expireValue = RedisService::setExpire($key,10,'message-drafts');
        
        if(!($setValue && $expireValue)) {
            return \Response::json(['message' => 'Problem with saving'], 500); 
        }

        return \Response::json(['message' => 'Successfully saved item!'], 201); 
    }
}
