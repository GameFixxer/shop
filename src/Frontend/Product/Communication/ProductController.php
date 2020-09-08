<?php

declare(strict_types=1);

namespace App\Frontend\Product\Communication;

use App\Client\Product\Business\ProductBusinessFacadeInterface;
use App\Component\View;
use App\Frontend\BackendController;
use App\Frontend\Login\Communication\LoginController;
use App\Frontend\Product\Business\ProductManagerInterface;
use App\Generated\ProductDataProvider;
use App\Service\SessionUser;

class ProductController implements BackendController
{
    public const ROUTE = 'product';
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private SessionUser $userSession;
    private ProductManagerInterface $productManager;
    private View $view;

    public function __construct(
        ProductBusinessFacadeInterface $productBusinessFacade,
        SessionUser $userSession,
        ProductManagerInterface $productManager,
        View $view
    ) {
        $this->productBusinessFacade = $productBusinessFacade;
        $this->userSession = $userSession;
        $this->view = $view;
        $this->productManager = $productManager;
    }

    public function init(): void
    {
        if (!$this->userSession->isLoggedIn()) {
            $this->view->setRedirect(LoginCOntroller::ROUTE, '&page=login', ['admin=true']);
        }
        if (($this->userSession->getUserRole() === 'user')) {
            $this->view->setRedirect(LoginCOntroller::ROUTE, '&page=logout', ['admin=true']);
        }
    }

    public function listAction()
    {
        $productDTO = $this->productBusinessFacade->getList();
        $this->view->addTlpParam('productlist', $productDTO);
        $this->view->addTemplate('productEditList.tpl');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($_POST) {
            case isset($_POST['delete']):
                $this->deleteProduct((string)$_POST['delete']);
                break;
            case isset($_POST['save']):
                $this->saveProduct(
                    (string)$_POST['save'],
                    (string)$_POST['newpagedescription'],
                    (string)$_POST['newpagename']
                );
                break;
            case isset($_POST['add']):
                $this->addToShoppingCard((string)$_POST['add']);
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
                $this->deleteProduct((string)$_POST['delete']);
                break;
            case !empty($_POST['save']):

                $this->saveProduct(
                    (string)$_POST['save'],
                    (string)$_POST['newpagedescription'],
                    (string)$_POST['newpagename']
                );
                break;
            case isset($_POST['add']):
                $this->addToShoppingCard((string)$_POST['add']);
                break;
            }
        }

        $productDTO = $this->productBusinessFacade->get($_GET['id']);
        $this->view->addTlpParam('product', $productDTO);
        $this->view->addTemplate('productEditPage.tpl');
    }

    private function addToShoppingCard(string $articleNumber)
    {
        $this->userSession->addToShoppingCard($articleNumber);
    }

    private function deleteProduct(string $articleNumber): void
    {
        $productDTO = new ProductDataProvider();
        $productDTO->setArticleNumber($articleNumber);
        $this->productManager->delete($productDTO);
    }

    private function saveProduct(string $articleNumber, string $description, string $name): void
    {
        $productDTO = new ProductDataProvider();
        $productDTO->setArticleNumber($articleNumber);
        $productDTO->setName($name);
        $productDTO->setDescription($description);
        $this->productManager->save($productDTO);
    }
}
