<?php
declare(strict_types=1);
namespace App\Controllers;
use Framework\Controller;
use Framework\Response;
use App\Models\User;
use App\Models\Home;
use App\Models\RememberedLogins;
use Framework\Helpers\Session;
use Framework\Exceptions\PageNotFoundException;
use Framework\Helpers\Redirect;
use Framework\Helpers\Auth;
use Framework\Helpers\CSRF;
use Framework\Helpers\Mail;



class Homes extends Controller
{
    public function __construct(private User $usersModel, private Home $homeModel, private RememberedLogins $rememberedLogins)
    {
        if(!Auth::isLoggedIn()){
            if($rememberedLogins->loginFromRemeberCookie()){
                Session::set("success", 'Welcome back');
                Redirect::to("/dashboard");
            }
        }
    }

    public function index(): Response
    {
        Auth::passRedirect(['url'=>'/dashboard']);
        return $this->view('homes/index.mvc', [
            'success' => Session::flash('success'),
            'auth' => Session::flash('auth_message'),
            'CSRF'=>CSRF::generate()
        ]);
    }

    public function register(): Response
    {
        Auth::passRedirect(['url'=>'/dashboard']);
        return $this->view('homes/register.mvc', [
            'CSRF'=>CSRF::generate()
        ]);
    }

    public function registerNewUser(): Response
    {
        Redirect::post('');
        CSRF::check($this->request->post['csrf_token']);
        $data = [
            'name' => $this->request->post['name'],	
            'email' => $this->request->post['email'],
            'password' => $this->request->post['password'],
        ];
        $data = (object)$data;
        $this->usersModel->validateRegistration($data);

        if(empty($this->usersModel->getErrors())){
            $data->password = password_hash($data->password, PASSWORD_DEFAULT); 
            if($this->usersModel->insert($data)){
                Session::set('success','Registration successful: Check your email for verification');
                return $this->redirect("");
            }else{
                throw new PageNotFoundException("could not register user");
            }
        }else{
            unset($data->password);
            return $this->view('homes/register.mvc', [
                'errors'=> (object) $this->usersModel->getErrors(),
                'user' => $data,
                'CSRF'=> CSRF::generate()
            ]);
        }
    }

    public function logInUser(): Response
    {
        Redirect::post('');
        CSRF::check($this->request->post['csrf_token']);
        $data = [
            'email' => $this->request->post['email'],
            'password' => $this->request->post['password'],
            'remember_me' => isset($this->request->post['remember_me']),
        ];
        $data = (object)$data;
        
        $this->usersModel->validateLogIn($data);
        if(empty($this->usersModel->getErrors())){
            $user = $this->usersModel->loginUser($data); 
            if(!empty($user)){
                Auth::login($user);
                $user->remember_me = $data->remember_me;
                $this->rememberedLogins->rememberLogin($user);
                $page = Auth::returnPage();
                if(!empty($page)){
                    Redirect::to($page);
                }else{
                    Session::set('success','Login successful');
                    return $this->redirect("dashboard");
                }
            }else{
                throw new PageNotFoundException("could not login user");
            }
        }else{
            unset($data->password);
            return $this->view('homes/index.mvc', [
                'errors'=> (object) $this->usersModel->getErrors(),
                'user' => $data,
                'CSRF'=> CSRF::generate()

            ]);
        }
    }

    public function logOutUser(): Response
    {
        $this->rememberedLogins->deleteRememberMe(Session::get('id'));
        Auth::logout('You have logged out Successfully');
        Redirect::to('');
    }

    public function forgotPassword(): Response
    {
        return $this->view('homes/forgot-password.mvc', [
            'CSRF'=>CSRF::generate()
        ]);
    }

    public function recoverAccount(): Response
    {
        Redirect::post('');
        CSRF::check($this->request->post['csrf_token']);
        $data = [
            'email' => $this->request->post['email'],
        ];
        $data = (object)$data;
        $this->usersModel->validateRecoverAccount($data->email);
        if(empty($this->usersModel->getErrors())){
            $this->usersModel->resetAccount($data->email);
            Session::set('success','Kindly check your mail to recover your account if your are registered');
            return $this->redirect("");
        }else{
            return $this->view('homes/forgot-password.mvc', [
                'errors'=> (object) $this->usersModel->getErrors(),
                'user' => $data,
                'CSRF'=>CSRF::generate()
            ]);
        }
    }

    public function aboutUs(): Response
    {
        Auth::failRedirect(['message'=>'Kindly Login to View this page']);
        return $this->view('homes/about-us.mvc', []);
    }

    public function contact(): Response
    {
        return $this->view('homes/contact-us.mvc', [
            'CSRF'=> CSRF::generate()
        ]);
    }

    public function contactUs(): Response
    {
        Redirect::post('');
        CSRF::check($this->request->post['csrf_token']);
        $data = [
            'email' => $this->request->post['email'],
            'name' => $this->request->post['name'],
            'message' => $this->request->post['message'],
            'subject' => $this->request->post['subject'],
        ];
        $data = (object)$data;
        
        $this->homeModel->validateContactUs($data);
        if(empty($this->homeModel->getErrors())){
            $mail = new Mail;
            $mail->to('contact@LogReg.com');
            $mail->from($data->email, $data->name);
            $mail->replyto($data->email, $data->name);
            $mail->subject($data->subject);
            $mail->message($data->message);
            if($mail->send()){
                Session::set('success','Message sent. Thanks for contacting us');
                return $this->redirect("dashboard");
            }else{
                return $this->redirect("505");
            }
        }else{
            return $this->view('homes/contact-us.mvc', [
                'errors'=> (object) $this->homeModel->getErrors(),
                'contact' => $data,
                'CSRF'=> CSRF::generate()
            ]);
        }
    }

    public function e404(): Response
    {
        return $this->view('404.mvc', []);
    }

    public function e500(): Response
    {
        return $this->view('500.mvc', []);
    }

    public function test() : Response  
    {
        $mail = new Mail;
        $mail->to('ecuche@gmail.com', 'ucheeee');
        $mail->subject('test mail from my mail class');
        $mail->message("<a href=\"{$_ENV['URL_ROOT']}\">click here to visit our homepage</a>", true);
        // $mail->attachment($_ENV["FILES_PATH"]. 'TEST.JS');
        $mail->send();





        //always leave this exit line if you will not use template
        exit;
    }


}