<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\User;
use Framework\Controller;
use Framework\Response;
use Framework\Helpers\Auth;
use Framework\Helpers\Session;
use Framework\Helpers\CSRF;
use Framework\Helpers\Data;

class Users extends Controller
{
    private $user;

    public function __construct(private User $usersModel)
    {
        $this->user = $this->usersModel->getUser() ?? false;
    }

    public function dashboard(): Response         
    {
        Auth::failRedirect(['message'=>'Kindly Login to View this page']);
        return $this->view('users/dashboard.mvc', [
            'user' => $this->user,
            'success' => Session::flash('success')
        ]);

    }
   
    public function updateProfile(): Response
    {
        $user = $this->user;
        return $this->view('users/update-profile.mvc', [
            'user' => (object) $user,
            'CSRF' => CSRF::generate()
        ]);
    }

    public function profileUpdate(): Response
    {
        $user = $this->user;
        return $this->view('users/update-profile.mvc', [
            'user' => (object) $user,
            'CSRF' => CSRF::generate()
        ]);
    }

    public function viewProfile(): Response
    {
        $user = $this->user;
        return $this->view('users/view-profile.mvc', [
            'user' => (object) $user,
            'time_ago' => Data::timeAgo($user->created_on)
        ]);
    }

    public function edit(string $id): Response
    {
        $id = (int)$id;
        $user = $this->usersModel->findById($id);
       
        return $this->view('users/edit.mvc', [
            "user" => $user
        ]);
    }

    public function update(string $id): Response
    {
        $id = (int)$id;
        $user = $this->usersModel->findById($id);
        $user->name = $this->request->post['name'];
        $update = $this->request->post;
        

        if($this->usersModel->updateRow($id, $update)){
            header("Location: {$_ENV['URL_ROOT']}/users/show/{$id}");
            exit;
        }else{
            return $this->view('users/edit.mvc', [
                'user' => $user,
                'errors' => $this->usersModel->getErrors()
            ]);
        }
    }

    
    public function destroy(string $id): Response
    {
        $id = (int)$id;
        $this->usersModel->findById( $id);
        $this->usersModel->deleteRow($id);
        header("Location: {$_ENV['URL_ROOT']}/users/index");
        exit;
    }

    public function delete(string $id): Response
    {
        $id = (int)$id;
        $user = $this->usersModel->findById($id);
        return $this->view('users/delete.mvc', [
            "user" => $user
        ]);
    }

    public function responseCodeExample(): Response
    {
        $this->response->setStatusCode(451);
        $this->response->setBody("Unavailable for legal reasons");
        return $this->response;
    }
}