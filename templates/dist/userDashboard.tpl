<html lang="">
{extends file="basic.tpl"}
{block name="subtitel_h1"} {/block}
{block name="titel"}Welcome to the Backstagearea{/block}
{block name="titel_button"}Back to home{/block}
{block name="titel_button_href"}"http://localhost:8080/Index.php?cl=home"{/block}
{block name ="body"}
    <body>
    <section class="page-section bg-light" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Dashboard</h2>
                <h3 class="section-subheading text-muted">Welcome to your Dashboard {$user}</h3>
            </div>

        </div>
        <div class="text-center">
            <form id="logout" name="logout" action="" method="post">
                <a class="btn btn-primary btn-lg text-uppercase"
                   name="edit" href="/Index.php?cl=login&page=logout&admin=true"
                   type="submit"> Logout
                </a>
            </form>
        </div>
    </section>

    </body>
{/block}
</html>