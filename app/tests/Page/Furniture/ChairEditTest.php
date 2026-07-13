<?php

declare(strict_types=1);

namespace App\Tests\Page\Furniture;

use App\Controller\Furniture\ChairDeleteController;
use App\Controller\Furniture\ChairEditController;
use App\Controller\Furniture\ChairIndexController;
use App\Entity\Furniture\Chair;
use App\Form\Furniture\ChairType;
use App\Repository\Furniture\ChairRepository;
use App\Tests\Page\Furniture\ChairFixture;
use App\Tests\Page\Product\GenerateString;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the chair edit page.
 */
#[
    PA\CoversClass(ChairDeleteController::class),
    PA\CoversClass(ChairEditController::class),
    PA\UsesClass(Chair::class),
    PA\UsesClass(ChairIndexController::class),
    PA\UsesClass(ChairRepository::class),
    PA\UsesClass(ChairType::class),
    PA\Group('pages'),
    PA\Group('pages_chair'),
    PA\Group('pages_chair_edit'),
    PA\Group('chair')
]
class ChairEditTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use ChairFixture;


    // Methods :

    /**
     * Tests that a chair can be edited.
     */
    public function testCanDisplayChairEdit(): void
    {
        $client = static::createClient();
        $this->addChairFixture();
        $crawler = $client->request('GET', '/chairs/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Edit chair', 'There must be a <h1> for chairs.');

        $form = $crawler->filter('form[name="chair"]');
        self::assertCount(1, $form, 'There must be a form to create chairs.');
        $this->assertHasAnInputForName($form->filter('#chair_name'));
        $this->assertHasAnInputForDescription($form->filter('#chair_description'));
        $this->assertHasAnInputForPrice($form->filter('#chair_price'));
        self::assertNotEmpty($form->filter('#chair__token')->attr('value'), 'The create form must have a token.');
    }

    /**
     * The assertions for the input of the name.
     * @param \Symfony\Component\DomCrawler\Crawler $nameInput the crawler.
     */
    private function assertHasAnInputForName(Crawler $nameInput): void
    {
        self::assertSame('50', $nameInput->attr('maxlength'), 'The name input maxlength must be 50.');
        self::assertSame('chair[name]', $nameInput->attr('name'), 'The name input must be named "chair[name]".');
        self::assertSame('^.+$', $nameInput->attr('pattern'), 'The name input pattern must be "^.+$".');
        self::assertSame('The name', $nameInput->attr('placeholder'), 'The name input placeholder must be "The name".');
        self::assertSame('required', $nameInput->attr('required'), 'The name input must be required.');
        self::assertSame('30', $nameInput->attr('size'), 'The name input size must be 30.');
        self::assertSame('The name of the product.', $nameInput->attr('title'), 'The name input title must be "The name of the product.".');
        self::assertSame('text', $nameInput->attr('type'), 'The name input must be of type text.');
        self::assertSame('test-name', $nameInput->attr('value'), 'The name input must have "test-name" as a value.');
    }

    /**
     * The assertions for the input of the description.
     * @param \Symfony\Component\DomCrawler\Crawler $nameInput the crawler.
     */
    private function assertHasAnInputForDescription(Crawler $descriptionInput): void
    {
        self::assertSame('300', $descriptionInput->attr('maxlength'), 'The description input maxlength must be 300.');
        self::assertSame('chair[description]', $descriptionInput->attr('name'), 'The description input must be named "chair[description]".');
        self::assertSame('A description', $descriptionInput->attr('placeholder'), 'The description input placeholder must be "A description".');
        self::assertSame('The description of the product.', $descriptionInput->attr('title'), 'The description input title must be "The description of the product.".');
        self::assertSame('test-description', $descriptionInput->text(), 'The description input must have "test-description" as a value.');
    }

    /**
     * The assertions for the input of the price.
     * @param \Symfony\Component\DomCrawler\Crawler $priceInput the crawler.
     */
    private function assertHasAnInputForPrice(Crawler $priceInput): void
    {
        self::assertSame('number', $priceInput->attr('type'), 'The price input maxlength must be 300.');
        self::assertSame('chair[price]', $priceInput->attr('name'), 'The price input must be named "chair[price]".');
        self::assertSame('1', $priceInput->attr('placeholder'), 'The price input placeholder must be "1".');
        self::assertSame('The price of the product.', $priceInput->attr('title'), 'The price input title must be "The price of the product.".');
        self::assertSame('5', $priceInput->attr('value'), 'The price input must have "5" as a value.');
    }


    /**
     * Tests that the page can be browsed
     * back to the chair index page.
     */
    #[PA\Depends('testCanDisplayChairEdit')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();
        $this->addChairFixture();
        $crawler = $client->request('GET', '/chairs/1/edit');
        $backLink = $crawler->filter('a.btn-secondary')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that a chair can be updated.
     */
    #[PA\Depends('testCanDisplayChairEdit')]
    public function testCanUpdateAChair(): void
    {
        $client = static::createClient();
        $this->addChairFixture();
        $crawler = $client->request('GET', '/chairs/1/edit');
        $form = $crawler->filter('#chair_submit')->form();

        $chairDatas = [
            'chair[name]' => 'test-update-name',
            'chair[description]' => 'test-update-description',
            'chair[price]' => 10
        ];

        $client->submit($form, $chairDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $chair = $entityManager->find(Chair::class, 1);
        $entityManager->refresh($chair);

        self::assertSame(
            'test-update-name',
            $chair->name,
            'The updated name of the chair must be "test-update-name".'
        );
        self::assertSame(
            'test-update-description',
            $chair->description,
            'The updated description of the chair must be "test-update-description".'
        );
        self::assertSame(
            10,
            $chair->price,
            'The updated price of the chair must be 10.'
        );
    }


    /**
     * Returns invalid chair datas.
     * @return mixed invalid chair datas.
     */
    public static function getInvalidChairDatas(): array
    {
        $chairDatas = [
            'chair[name]' => 'test-update-name',
            'chair[description]' => 'test-update-description',
            'chair[price]' => 10
        ];

        $emptyStringName = $chairDatas;
        $emptyStringName['chair[name]'] = '';

        $nameTooLong = $chairDatas;
        $nameTooLong['chair[name]'] = self::generateLongString(51);

        $descriptionTooLong = $chairDatas;
        $descriptionTooLong['chair[description]'] = self::generateLongString(301);

        $priceToLow = $chairDatas;
        $priceToLow['chair[price]'] = -1;

        return [
            'the name is an empty string.' => [$emptyStringName],
            'the name is too long (>50 chars).' => [$nameTooLong],
            'the description is too long (>300 chars).' => [$descriptionTooLong],
            'the price is too low (<0).' => [$priceToLow]
        ];
    }

    /**
     * Tests that chairs can not be updated
     * with invalid chair datas.
     * @param mixed[] $chairDatas invalid chair datas.
     */
    #[
        PA\DataProvider('getInvalidChairDatas'),
        PA\Depends('testCanDisplayChairEdit'),
        PA\TestDox('Can not update a chair when $_dataName')
    ]
    public function testCanNotUpdateAChair(array $chairDatas): void
    {
        $client = static::createClient();
        $this->addChairFixture();
        $crawler = $client->request('GET', '/chairs/1/edit');

        $form = $crawler->filter('#chair_submit')->form();
        $notSavedCrawler = $client->submit($form, $chairDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $chair = $entityManager->find(Chair::class, 1);
        $entityManager->refresh($chair);

        self::assertNotEquals(
            $chairDatas['chair[name]'],
            $chair->name,
            'The chair name must not be updated.'
        );
        self::assertNotEquals(
            $chairDatas['chair[description]'],
            $chair->description,
            'The chair description must not be updated.'
        );
        self::assertNotEquals(
            $chairDatas['chair[price]'],
            $chair->price,
            'The chair price must not be updated.'
        );
    }


    /**
     * Tests that a chair name can be unique.
     */
    #[PA\Depends('testCanDisplayChairEdit')]
    public function testAChairNameCanBeUnique(): void
    {
        $client = static::createClient();
        $this->addChairFixture();

        $chair = new Chair('test-name2', 'test-description2');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($chair);
        $entityManager->flush();

        $crawler = $client->request('GET', '/chairs/2/edit');

        $form = $crawler->filter('form[name="chair"]')->form();

        $chairDatas = [
            'chair[name]' => 'test-name',
            'chair[description]' => 'test-description'
        ];

        $notSavedCrawler = $client->submit($form, $chairDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');
    }


    /**
     * Tests that a chair can be deleted.
     */
    #[PA\Depends('testCanDisplayChairEdit')]
    public function testCanDeleteChair(): void
    {
        $client = static::createClient();

        $this->addChairFixture();

        $crawler = $client->request('GET', '/chairs/1/edit');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $deletedChair = $entityManager->find(Chair::class, 1);

        self::assertNull($deletedChair, 'The Chair has not been deleted.');
    }
}
