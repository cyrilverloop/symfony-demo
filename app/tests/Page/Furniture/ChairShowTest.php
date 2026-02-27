<?php

declare(strict_types=1);

namespace App\Tests\Page\Furniture;

use App\Controller\Furniture\ChairDeleteController;
use App\Controller\Furniture\ChairEditController;
use App\Controller\Furniture\ChairIndexController;
use App\Controller\Furniture\ChairShowController;
use App\Entity\Furniture\Chair;
use App\Form\Furniture\ChairType;
use App\Repository\Furniture\ChairRepository;
use App\Tests\Page\Furniture\ChairFixture;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the chair show page.
 */
#[
    PA\CoversClass(ChairDeleteController::class),
    PA\CoversClass(ChairShowController::class),
    PA\UsesClass(Chair::class),
    PA\UsesClass(ChairEditController::class),
    PA\UsesClass(ChairIndexController::class),
    PA\UsesClass(ChairRepository::class),
    PA\UsesClass(ChairType::class),
    PA\Group('pages'),
    PA\Group('pages_chair'),
    PA\Group('pages_chair_show'),
    PA\Group('chair')
]
class ChairShowTest extends WebTestCase
{
    // Traits :
    use ChairFixture;


    // Methods :

    /**
     * Tests that chair can be shown.
     */
    public function testCanShowChair(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Chair');

        self::assertCount(
            1,
            $crawler->filter('.card'),
            'There must be 1 card on the chair\'s page.'
        );
        self::assertSame(
            'test-name',
            $crawler->filter('h5.card-header')->text(),
            'The name of the chair must be in a <h5>.'
        );
        self::assertSame(
            'test-description',
            $crawler->filter('p.card-text')->text(),
            'The description of the chair must be in a <p>.'
        );

        $links = $crawler->filter('.card-body a');

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

        $deleteButton = $crawler->filter('#delete_product_form button');

        self::assertSame('Delete', $deleteButton->text(), 'There must be a "Delete" button.');
        self::assertStringContainsString(
            'btn-primary',
            $deleteButton->attr('class'),
            'The delete button must have a "btn-primary" class.'
        );
    }


    /**
     * Tests that the page can be browsed
     * back to the chair index page.
     */
    #[PA\Depends('testCanShowChair')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/1');
        $backLink = $crawler->filter('.card-body a')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that the page can browsed
     * from the show to the edit page.
     */
    #[PA\Depends('testCanShowChair')]
    public function testCanBrowseFromShowToEdit(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/1');
        $editLink = $crawler->filter('.card-body a')->eq(1);

        $client->click($editLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that a chair can be deleted.
     */
    #[PA\Depends('testCanShowChair')]
    public function testCanDeleteChair(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/1');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $deletedChair = $entityManager->find(Chair::class, 1);

        self::assertNull($deletedChair, 'The Chair has not been deleted.');
    }
}
