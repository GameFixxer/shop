<html lang="">
{extends file="basic.tpl"}
{block name="subtitel_h1"} {/block}
{block name="titel"}Here you will find everything you need{/block}
{block name="titel_button"}Back to home{/block}
{block name="titel_button_href"}"http://localhost:8080/Index.php?cl=home"{/block}
{block name ="body"}
    <body>
    <section class="page-section bg-light" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Productlist</h2>
                <h3 class="section-subheading text-muted">All available products are here.</h3>
            </div>
            <div class="row">
                {foreach name=aussen item=page from=$productlist}
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <object class="portfolio-item" id="{$page->getArticleNumber()}" data-title={$page->getArticleNumber()}>
                            <a class="portfolio-link"
                               href=/Index.php?cl=detail&id={$page->getArticleNumber()}>
                                <div class="portfolio-hover"
                                     data-title={$page->getName()} id={$page->getArticleNumber()}>
                                    <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <img class="img-fluid" src="assets/img/portfolio/01-thumbnail.jpg" alt=""/></a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">{$page->getName()}</div>
                                <div class="portfolio-caption-subheading text-muted">{$page->getDescription()}</div>
                            </div>
                            <form id="updateform" name="updateform" action="" method="post">
                            <td>
                                <button class="btn btn-primary btn-sm text-uppercase" id="add" data-title={$page->getArticleNumber()} name="add"
                                        type="submit" value={$page->getArticleNumber()}>Add
                                </button>
                            </td>
                                </form>
                        </object>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
    </body>
{/block}
</html>
