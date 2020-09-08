<?php

declare(strict_types=1);

namespace App\Frontend\User\Communication;

use App\Client\User\Business\UserBusinessFacadeInterface;
use App\Component\View;
use App\Frontend\BackendController;
use App\Frontend\Login\Communication\LoginController;
use App\Frontend\User\Business\UserManagerInterface;
use App\Generated\UserDataProvider;
use App\Service\PasswordManager;
use App\Service\SessionUser;

class UserController implements BackendController
{
    public const ROUTE = 'user';
    private UserBusinessFacadeInterface $userBusinessFacade;
    private SessionUser $userSession;
    private View $view;
    private PasswordManager $passwordManager;
    private UserManagerInterface $userManager;

    public function __construct(
        UserBusinessFacadeInterface $userBusinessFacade,
        SessionUser $userSession,
        View $view,
        PasswordManager $passwordManager,
        UserManagerInterface $userManager
    ) {
        $this->userBusinessFacade = $userBusinessFacade;
        $this->userSession = $userSession;
        $this->passwordManager = $passwordManager;
        $this->view = $view;
        $this->userManager = $userManager;
    }

    public function init(): void
    {
        if (!$this->userSession->isLoggedIn()) {
            $this->view->setRedirect(LoginCOntroller::ROUTE, '&page=list', ['admin=true']);
        }
        if (!($this->userSession->getUserRole() === 'root')) {
            $this->view->setRedirect(LoginCOntroller::ROUTE, '&page=logout', ['admin=true']);
        }
    }

    public function listAction()
    {
        $userDataTransferObjects = $this->userBusinessFacade->getList();
        $this->view->addTlpParam('userList', $userDataTransferObjects);
        $this->view->addTemplate('userList.tpl');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($_POST) {
            case isset($_POST['delete']):
                $this->deleteUser($_POST['delete']);
                break;
            case isset($_POST['save']):
                $this->saveUser(
                    (string)$_POST['newuserpassword'],
                    (string)$_POST['newusername'],
                    (string)$_POST['newuserrole'],
                );
                break;
            }
            $this->view->setRedirect(self::ROUTE, '&page=list', ['admin=true']);
        }
    }


    public function detailAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($_POST) {
            case !empty($_POST['delete']):
                $this->deleteUser($_POST['delete']);
                break;
            case !empty($_POST['save']):

                $this->saveUser(
                    (string)$_POST['newuserpassword'],
                    (string)$_POST['newusername'],
                    (string)$_POST['newuserrole']
                );
                break;
            }
        }
        $userDTO = $this->userBusinessFacade->get($_GET['id']);
        if ($this->checkForValidDTO($userDTO)) {
            $this->view->addTlpParam('product', $userDTO);
            $this->view->addTemplate('productEditPage.tpl');
        } else {
            $this->displayPageDoesNotExists();
        }
    }

    private function deleteUser(String $username): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setUsername($username);
        $this->userManager->delete($userDTO);
    }

    private function saveUser(String $password, String $username, String $role): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setUsername($username);
        $userDTO->setPassword($this->passwordManager->encryptPassword($password));
        $userDTO->setRole($role);
        $this->userManager->save($userDTO);
    }

    private function checkForValidDTO($userDTO): bool
    {
        if (is_array($userDTO)) {
            return $this->checkArrayOfDTO($userDTO);
        } elseif ($userDTO === null) {
            return false;
        } else {
            return $userDTO instanceof UserDataProvider;
        }
    }

    private function checkArrayOfDTO($userDTO): bool
    {
        foreach ($userDTO as $key) {
            if (!($key instanceof UserDataProvider) || $key === null) {
                return false;
            }
        }
        return true;
    }

    private function displayPageDoesNotExists(): void
    {
        $this->view->addTlpParam('error', '404 Page not found.');
        $this->view->addTemplate('404.tpl');
    }
}
