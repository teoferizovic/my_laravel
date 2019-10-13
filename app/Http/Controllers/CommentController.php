<?php

namespace App\Http\Controllers;

use App\Comment;

use Illuminate\Http\Request;
use App\Http\Requests\Custom\CustomCommentRequest;

class CommentController extends Controller
{
	protected $comment;

	public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function index($id=null){
    	
    	if($id != null){
            $comment = $this->comment->get($id);
            return \Response::json(($comment == null) ? [] : [$comment],201);
        }

        $comments  = $this->comment->getAll();
        return \Response::json($comments,201);
    }

    public function create(Request $request){
     	
     	$input = $request->all();

     	$validation = CustomCommentRequest::validate($input);
        
        if ($validation['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validation['errors']], 400);
        }
     	
        $this->comment->new($input);
    	
    	return \Response::json(['message' => 'Successfully saved item!'], 200);

    }

    public function update($id,Request $request){
    	
    	$comment = $this->comment->get($id);
    	
    	if ($comment==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}
    		
        $input = $request->all();

        $validation = CustomCommentRequest::validate($input);
        
        if ($validation['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validation['errors']], 400);
        }


        $this->comment->edit($comment,$input);

    	return \Response::json(['message' => 'Successfully updated item!'], 200);
    }

     public function delete($id){
    	
    	$comment = $this->comment->get($id);

    	if ($comment == null) {
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	$this->comment->remove($comment->id);
      	
		return \Response::json(['message' => 'Successfully deleted item!'], 200);
    	
    }
}
