<?php

namespace App\Http\Controllers;

use App\Helpers\CSVHelper;
use App\Http\Requests\User\DeleteRequest;
use App\Http\Requests\User\SearchRequest;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Render user01 page
     * @param Request $request
     */
    public function viewAdminUserSearch(SearchRequest $request) {
        $paramSession = session()->get('usr01.search') ?? [];
        $users = $this->userRepository->search($paramSession);
        $users = $this->pagination($users);

        return view('screens.user.usr01', compact('users', 'paramSession'));
    }

    /**
     * Handle user01 page
     * @param Request $request
     */
    public function submitAdminUserSearch(SearchRequest $request) {
        $params = $request->only(['email', 'name', 'user_flg', 'date_of_birth', 'phone']);
        session()->forget('usr01.search');
        session()->put('usr01.search', $params);

        return to_route('ADMIN_USER_SEARCH');
    }

    /**
     * Perform delete1 on a user and 
     * @param Request $request
     * @param int $id
     */
    public function submitAdminUserDelete(DeleteRequest $request, int $id) {
        try {
            $this->userRepository->delete1($id);
        }
        catch (ModelNotFoundException $mnfe) {
            throw new NotFoundHttpException();
        }
        catch (Throwable $th) {
            session()->flash('error');
            return redirect()->back()->withErrors(getMessage("E013"));
        }

        return to_route('ADMIN_USER_SEARCH');
    }

    /**
     * Export current user search query into csv and download
     */
    public function exportAdminUser(SearchRequest $request)
    {
        $params = session()->get('usr01.search') ?? [];
        $query = $this->userRepository->search($params);
        $users = $query->get()->toArray();

        $fileName = 'export.csv';
        $filePath = storage_path('admin_user_search_export_' . Session::getId() . '.csv');

        CSVHelper::exportCSV($filePath, 'admin_user_search_export', $users);
        return response()->download($filePath, $fileName)->deleteFileAfterSend();
    }
}
