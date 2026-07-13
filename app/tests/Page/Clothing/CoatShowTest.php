<?php

declare(strict_types=1);

namespace App\Tests\Page\Clothing;

use App\Controller\Clothing\CoatDeleteController;
use App\Controller\Clothing\CoatEditController;
use App\Controller\Clothing\CoatIndexController;
use App\Controller\Clothing\CoatShowController;
use App\Entity\Clothing\Coat;
use App\Form\Clothing\CoatType;
use App\Repository\Clothing\CoatRepository;
use App\Tests\Page\Clothing\CoatFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the coat show page.
 */
#[
    PA\CoversClass(CoatDeleteController::class),
    PA\CoversClass(CoatShowController::class),
    PA\UsesClass(Coat::class),
    PA\UsesClass(CoatEditController::class),
    PA\UsesClass(CoatIndexController::class),
    PA\UsesClass(CoatRepository::class),
    PA\UsesClass(CoatType::class),
    PA\Group('pages'),
    PA\Group('pages_coat'),
    PA\Group('pages_coat_show'),
    PA\Group('coat')
]
class CoatShowTest extends WebTestCase
{
    // Traits :
    use CoatFixture;


    // Methods :

    /**
     * Tests that coat can be shown.
     */
    public function testCanShowCoat(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Coat');

        $this->assertHasCard($crawler->filter('.card'));
    }

    /**
     * The assertions for the card.
     * @param \Symfony\Component\DomCrawler\Crawler $card the crawler.
     */
    private function assertHasCard(Crawler $card): void
    {
        self::assertCount(
            1,
            $card,
            'There must be 1 card on the coat\'s page.'
        );

        self::assertSame(
            'test-name',
            $card->filter('h5.card-header')->text(),
            'The name of the coat must be in a <h5>.'
        );

        $this->assertHasCardBody($card->filter('.card-body'));
        $this->assertHasCardFooter($card->filter('.card-footer'));
    }

    /**
     * The assertions for the card's body.
     * @param \Symfony\Component\DomCrawler\Crawler $cardBody the crawler.
     */
    private function assertHasCardBody(Crawler $cardBody): void
    {
        self::assertSame(
            'test-description',
            $cardBody->filter('p.card-text')->text(),
            'The description of the coat must be in a <p>.'
        );
        self::assertSame(
            'Price : 5 €.',
            $cardBody->filter('ul li')->text(),
            'The price of the coat must be in a list.'
        );

        $priceHistorySwitch = $cardBody->filter('#price_history_switch');
        self::assertCount(
            1,
            $priceHistorySwitch,
            'There must be 1 price history switch.'
        );
        self::assertSame(
            '#price_history',
            $priceHistorySwitch->attr('data-bs-target'),
            'The price history must control the "price_history" ID.'
        );
        self::assertSame(
            'false',
            $priceHistorySwitch->attr('aria-expanded'),
            'The price history must be hidden by default.'
        );
        self::assertCount(
            0,
            $cardBody->filter('#price_chart'),
            'There must not be a price history.'
        );
    }

    /**
     * The assertions for the card's footer.
     * @param \Symfony\Component\DomCrawler\Crawler $cardFooter the crawler.
     */
    private function assertHasCardFooter(Crawler $cardFooter): void
    {
        $links = $cardFooter->filter('a');
        self::assertSame('back to list', $links->eq(0)->text(), 'There must be a "back to list" link.');
        self::assertStringContainsString(
            'btn-secondary',
            $links->eq(0)->attr('class'),
            'The back link must have a "btn-secondary" class.'
        );
        self::assertSame('edit', $links->eq(1)->text(), 'There must be an "edit" link.');
        self::assertStringContainsString(
            'btn-primary',
            $links->eq(1)->attr('class'),
            'The edit link must have a "btn-primary" class.'
        );

        $deleteButton = $cardFooter->filter('#delete_product_form button');

        self::assertSame('Delete', $deleteButton->text(), 'There must be a "Delete" button.');
        self::assertStringContainsString(
            'btn-primary',
            $deleteButton->attr('class'),
            'The delete button must have a "btn-primary" class.'
        );
    }


    /**
     * Tests that the page can be browsed
     * back to the coat index page.
     */
    #[PA\Depends('testCanShowCoat')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/1');
        $backLink = $crawler->filter('.card-footer a')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the show to the edit page.
     */
    #[PA\Depends('testCanShowCoat')]
    public function testCanBrowseFromShowToEdit(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/1');
        $editLink = $crawler->filter('.card-footer a')->eq(1);

        $client->click($editLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that a coat can be deleted.
     */
    #[PA\Depends('testCanShowCoat')]
    public function testCanDeleteCoat(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/1');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $deletedCoat = $entityManager->find(Coat::class, 1);

        self::assertNull($deletedCoat, 'The Coat has not been deleted.');
    }
}
