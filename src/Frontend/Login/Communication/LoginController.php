<?php
declare(strict_types=1);

namespace App\Frontend\Login\Communication;

use App\Client\User\Business\UserBusinessFacadeInterface;
use App\Component\View;
use App\Frontend\BackendController;
use App\Frontend\Login\Business\LoginManager;
use App\Frontend\User\Communication\DashboardController;
use App\Generated\EmailDataProvider;
use App\Generated\UserDataProvider;
use App\Service\PasswordManager;
use App\Service\SymfonyMailerManager;
use App\Service\SessionUser;

class LoginController implements BackendController
{
    public const ROUTE = 'login';
    private View $view;
    private UserBusinessFacadeInterface $userBusinessFacade;
    private PasswordManager $passwordManager;
    private SessionUser $userSession;
    private SymfonyMailerManager $mailManager;
    private LoginManager $loginManager;


    public function __construct(
        View $view,
        UserBusinessFacadeInterface $userBusinessFacade,
        PasswordManager $passwordManager,
        SessionUser $userSession,
        SymfonyMailerManager $mailManager,
        LoginManager $loginManager
    ) {
        $this->userSession = $userSession;
        $this->view = $view;
        $this->userBusinessFacade = $userBusinessFacade;
        $this->passwordManager = $passwordManager;
        $this->mailManager = $mailManager;
        $this->loginManager = $loginManager;
    }

    public function init(): void
    {
        if ($this->userSession->isLoggedIn() && !($_GET['page'] === 'logout')) {
            $this->view->setRedirect(DashboardController::ROUTE, '&page=list', ['admin=true']);
        }
        $this->view->addTlpParam('login', 'LOGIN AREA');
    }

    public function loginAction(): void
    {
        if (isset($_POST['login']) && !empty(trim($_POST['username'])) && !empty(trim($_POST['password']))) {
            $username = (string)trim($_POST['username']);
            $password = (string)trim($_POST['password']);
            $userDTO = $this->userBusinessFacade->get($username);
            if ($userDTO instanceof UserDataProvider) {
                $this->loginUser($userDTO, $password, $username);
            }
            $this->view->addTlpParam('loginMessage', 'Invalid Username or Password');
        }

        $this->view->addTemplate('login.tpl');
    }
    public function resetAction()
    {
        $this->view->addTemplate('passwordReset.tpl');
        if (isset($_POST['resetpassword']) && !empty(trim($_POST['email']))) {
            $username = (string)trim($_POST['email']);
            $userDTO = $this->userBusinessFacade->get($username);
            if ($userDTO instanceof UserDataProvider) {
                $resetCode = $this->passwordManager->createResetPassword();
                $emailDTO = new EmailDataProvider();
                $emailDTO->setTo($username);
                $emailDTO->setSubject('Reseting your Password');
                $emailDTO->setMessage('If you really have forgotten your password pls enter the following number:'.$resetCode);

                if ($this->mailManager->sendMail($emailDTO)) {
                    $sessionId = $this->setEmergencySession($username);
                    $this->setEmergencyUserData($sessionId, $resetCode, $userDTO);
                    $this->view->setRedirect(PasswordController::ROUTE, '&page=reset', ['admin=true']);
                } else {
                    throw new \Exception('Email could not be send.', 1);
                }
            } else {
                $this->view->addTlpParam('loginMessage', 'Invalid Username');
            }
        }
    }

    public function logoutAction()
    {
        $userDTO = $this->userBusinessFacade->get($this->userSession->getUser());

        if ($userDTO instanceof UserDataProvider) {
            $userDTO->setShoppingCardId($this->loginManager->createShoppingCard($this->userSession->getShoppingCard())->getId());

            $this->userBusinessFacade->save($userDTO);
        }
        $this->userSession->logoutUser();

        $this->view->setRedirect(LoginController::ROUTE, '&page=login', ['admin=true']);
    }
    private function loginUser(UserDataProvider $userDTO, string $password, string $username)
    {
        if ($this->passwordManager->checkPassword($password, $userDTO->getPassword())) {
            $userDTO->setShoppingCardId($this->loginManager->createShoppingCard($this->userSession->getShoppingCard())->getId());
            $this->userSession->setShoppingCard($this->loginManager-> extractSessionShoppingCard($userDTO->getShoppingCardId()));
            $this->userSession->loginUser($username);
            $this->userSession->setUserRole($userDTO->getRole());
            $this->view->setRedirect(DashboardController::ROUTE, '&page=list', ['admin=true']);
        }
    }
    private function setEmergencySession(string $username):String
    {
        $sessionId = $this->passwordManager->encryptPassword($username.time());
        $this->userSession->setSessionTimer();
        $this->userSession->setSessionId($sessionId);
        $this->userSession->setUser($username);
        return $sessionId;
    }

    private function setEmergencyUserData(String $sessionId, string $resetCode, UserDataProvider $userDTO)
    {
        $userDTO->setSessionId($sessionId);
        $userDTO->setResetPassword($resetCode);
        $this->userBusinessFacade->save($userDTO);
    }
}
