<?php

namespace App\Http\Controllers;

use App\Helpers\CSVHelper;
use App\Http\Requests\User\{AddRequest, DeleteRequest, SearchRequest};
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{Request};
use Illuminate\Support\Facades\{Session};
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
        } catch (ModelNotFoundException $mnfe) {
            throw new NotFoundHttpException();
        } catch (Throwable $th) {
            session()->flash('error');

            return redirect()->back()->withErrors(getMessage('E013'));
        }

        return to_route('ADMIN_USER_SEARCH');
    }

    /**
     * Export current user search query into csv and download
     * @param SearchRequest $request
     */
    public function exportAdminUser(SearchRequest $request) {
        $params = session()->get('usr01.search') ?? [];
        $query = $this->userRepository->search($params);
        $users = $query->get()->toArray();

        $fileName = 'export.csv';
        $filePath = storage_path('admin_user_search_export_' . Session::getId() . '.csv');

        CSVHelper::exportCSV($filePath, 'admin_user_search_export', $users);

        return response()->download($filePath, $fileName)->deleteFileAfterSend();
    }

    public function viewAdminUserAdd() {
        return view('screens.user.add');
    }

    public function submitAdminUserAdd(AddRequest $request) {
        $addParams = $request->only(['email', 'name', 'password', 'user_flg', 'date_of_birth', 'phone', 'address']);
        if ($this->userRepository->save(null, $addParams)) {
            Session::flash('success', getMessage('I013'));

            return to_route('ADMIN_USER_SEARCH');
        }

        return redirect()->back()->withInput()->withErrors(getMessage('E014'));
    }
}
