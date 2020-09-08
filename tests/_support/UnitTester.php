<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/

use App\Client\Product\Persistence\ProductRepository;

use App\Component\View;
use App\Generated\ProductDataProvider;
use App\Service\SessionUser;

class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    /**
     * Define custom actions here
     * @param int $id
     * @throws Exception
     */
    /** @var  View $view */
    private View $view;
    private $container;

    public function arrange()
    {
        $this->container = (new \App\Component\SymfonyContainer())->getContainer();
    }
    public function setUpBootstrap()
    {
        $this->view = include __DIR__.'/../../Bootstrap.php';
    }
    public function getProduct(string $articleNumber): ?ProductDataProvider
    {
        $productRepository = $this->getProductRepository();
        return $productRepository->get($articleNumber);
    }
    public function getContainer()
    {
        return $this->container;
    }
    public function getProductList()
    {
        $productRepository = $this->getProductRepository();
        return $productRepository->getList();
    }
    public function getSmartyParams()
    {
        return $this->view->getParam();
    }
    public function exchangeDtoToSmartyParam($value, string $name)
    {
        $this->makeSmarty($value, $name);
        return $this->getSmartyParams();
    }
    public function setSession()
    {
        $tmp = $this->container->get(SessionUser::class);
        $tmp->loginUser('nina');
        $tmp->setUserRole('root');
    }
    public function createArticleNumber():string
    {
        $list = $this->getProductRepository()->getList();
        $idCounter = end($list)->getId() + 1;
        return (string)$idCounter;
    }

    private function makeSmarty($value, string $name):void
    {
        $this->view->addTlpParam($name, $value);
    }
    private function getProductRepository():ProductRepository
    {
        return $this->container->get(ProductRepository::class);
    }


}
