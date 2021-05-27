<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use App\Models\User;
    use App\Traits\ApiResponser;
    use DB;

    Class UserController extends Controller {
        use ApiResponser;

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function getUsers(){
            $users = User::all();
            return response()->json($users, 200);
        }
   
        public function index() {
            $users = User::all();
            return $this->successResponse($users);
        }

        public function add(Request $request) {
            $rules = [
                'username' => 'required|max:255',
                'password' => 'required|max:255',
                'admin' => 'required|in:1,0',
            ];
            
            $this->validate($request, $rules);
            $users = User::create($request->all());
            return $this->successResponse($users, Response::HTTP_CREATED);
        }

        public function show($id) {
            $users = User::where('id', $id)->first();
            if ($users){
                return $this->successResponse($users);
            }
            return $this->errorResponse('User ID Does Not Exist', Response::HTTP_NOT_FOUND);
        }

        public function update(Request $request, $id) {
            $rules = [
                'username' => 'max:255',
                'password' => 'max:255',
                'admin' => 'in:1,0',
            ];

            $this->validate($request, $rules);

            $users = User::where('id', $id)->first();

                if($users){
                    $users->fill($request->all());
                    if($users->isClean()) {
                        return $this->errorResponse('At least one value must be changed',
                        Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $users->save();
                    return $this->successResponse($users);
                }
                return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);
        }

        public function delete($id) {
            $users = User::where('id', $id)->first();
            if($users){
                $users->delete();
                return $this->successResponse($users);
            }
            return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);
        }
    }

?>