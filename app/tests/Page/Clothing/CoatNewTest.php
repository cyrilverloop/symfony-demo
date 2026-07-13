<?php

declare(strict_types=1);

namespace App\Tests\Page\Clothing;

use App\Controller\Clothing\CoatIndexController;
use App\Controller\Clothing\CoatNewController;
use App\Entity\Clothing\Coat;
use App\Form\Clothing\CoatType;
use App\Repository\Clothing\CoatRepository;
use App\Tests\Page\Product\GenerateString;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the coat index page.
 */
#[
    PA\CoversClass(CoatNewController::class),
    PA\UsesClass(Coat::class),
    PA\UsesClass(CoatIndexController::class),
    PA\UsesClass(CoatRepository::class),
    PA\UsesClass(CoatType::class),
    PA\Group('pages'),
    PA\Group('pages_coat'),
    PA\Group('pages_coat_new'),
    PA\Group('coat')
]
class CoatNewTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use CoatFixture;


    // Methods :

    /**
     * Tests that the page to create a new coat
     * can be displayed.
     */
    public function testCanDisplayNewCoatPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/coats/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Create new coat', 'There must be a <h1> for new coats.');

        $form = $crawler->filter('form[name="coat"]');
        self::assertCount(1, $form, 'There must be a form to create coats.');
        $this->assertHasAnInputForName($form->filter('#coat_name'));
        $this->assertHasAnInputForDescription($form->filter('#coat_description'));
        $this->assertHasAnInputForPrice($form->filter('#coat_price'));
        self::assertNotEmpty($form->filter('#coat__token')->attr('value'), 'The form must have a token.');
    }

    /**
     * The assertions for the input of the name.
     * @param \Symfony\Component\DomCrawler\Crawler $nameInput the crawler.
     */
    private function assertHasAnInputForName(Crawler $nameInput): void
    {
        self::assertSame('50', $nameInput->attr('maxlength'), 'The name input maxlength must be 50.');
        self::assertSame('coat[name]', $nameInput->attr('name'), 'The name input must be named "coat[name]".');
        self::assertSame('^.+$', $nameInput->attr('pattern'), 'The name input pattern must be "^.+$".');
        self::assertSame('The name', $nameInput->attr('placeholder'), 'The name input placeholder must be "The name".');
        self::assertSame('required', $nameInput->attr('required'), 'The name input must be required.');
        self::assertSame("30", $nameInput->attr('size'), 'The name input size must be 30.');
        self::assertSame('The name of the product.', $nameInput->attr('title'), 'The name input title must be "The name of the product.".');
        self::assertSame('text', $nameInput->attr('type'), 'The name input must be of type text.');
    }

    /**
     * The assertions for the input of the description.
     * @param \Symfony\Component\DomCrawler\Crawler $nameInput the crawler.
     */
    private function assertHasAnInputForDescription(Crawler $nameInput): void
    {
        self::assertSame('300', $nameInput->attr('maxlength'), 'The name input maxlength must be 300.');
        self::assertSame('coat[description]', $nameInput->attr('name'), 'The name input must be named "coat[description]".');
        self::assertSame('A description', $nameInput->attr('placeholder'), 'The name input placeholder must be "A description".');
        self::assertSame('The description of the product.', $nameInput->attr('title'), 'The name input title must be "The description of the product.".');
    }

    /**
     * The assertions for the input of the price.
     * @param \Symfony\Component\DomCrawler\Crawler $priceInput the crawler.
     */
    private function assertHasAnInputForPrice(Crawler $priceInput): void
    {
        self::assertSame('number', $priceInput->attr('type'), 'The price input maxlength must be 300.');
        self::assertSame('coat[price]', $priceInput->attr('name'), 'The price input must be named "coat[price]".');
        self::assertSame('1', $priceInput->attr('placeholder'), 'The price input placeholder must be "1".');
        self::assertSame('The price of the product.', $priceInput->attr('title'), 'The price input title must be "The price of the product.".');
        self::assertNull($priceInput->attr('value'), 'The price input must have null as a value.');
    }


    /**
     * Tests that new coats can be created.
     */
    #[PA\Depends('testCanDisplayNewCoatPage')]
    public function testCanCreateNewCoat(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/coats/new');

        $form = $crawler->filter('#coat_submit')->form();

        $coatDatas = [
            'coat[name]' => 'test-new-name',
            'coat[description]' => 'test-new-description',
            'coat[price]' => 10
        ];

        $client->submit($form, $coatDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $coat = $entityManager->find(Coat::class, 1);

        self::assertSame(
            'test-new-name',
            $coat->name,
            'The new coat must be named "test-new-name".'
        );
        self::assertSame(
            'test-new-description',
            $coat->description,
            'The new coat must be named "test-new-description".'
        );
        self::assertSame(
            10,
            $coat->price,
            'The price of the new coat must be "test-new-description".'
        );
    }


    /**
     * Returns invalid coat datas.
     * @return mixed invalid coat datas.
     */
    public static function getInvalidCoatDatas(): array
    {
        $coatDatas = [
            'coat[name]' => 'test-new-name',
            'coat[description]' => 'test-new-description',
            'coat[price]' => 5
        ];

        $emptyStringName = $coatDatas;
        $emptyStringName['coat[name]'] = '';

        $nameTooLong = $coatDatas;
        $nameTooLong['coat[name]'] = self::generateLongString(51);

        $descriptionTooLong = $coatDatas;
        $descriptionTooLong['coat[name]'] = self::generateLongString(301);

        $priceToLow = $coatDatas;
        $priceToLow['coat[price]'] = -1;

        return [
            'the name is an empty string.' => [$emptyStringName],
            'the name is too long (>50 chars).' => [$nameTooLong],
            'the description is too long (>300 chars).' => [$descriptionTooLong],
            'the price is too low (<0).' => [$priceToLow]
        ];
    }

    /**
     * Tests that new coats can not be created
     * with invalid coat datas.
     * @param mixed[] $coatDatas invalid coat datas.
     */
    #[
        PA\DataProvider('getInvalidCoatDatas'),
        PA\Depends('testCanDisplayNewCoatPage'),
        PA\TestDox('Can not create new coat when $_dataName')
    ]
    public function testCanNotCreateNewCoat(array $coatDatas): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/coats/new');

        $form = $crawler->filter('#coat_submit')->form();

        $notSavedCrawler = $client->submit($form, $coatDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        self::bootKernel();
        $coatRepository = static::getContainer()->get(CoatRepository::class);
        $coats = $coatRepository->findAll();

        self::assertEmpty($coats, 'The coat must not be added.');
    }


    /**
     * Tests that a coat name can be unique.
     */
    #[PA\Depends('testCanDisplayNewCoatPage')]
    public function testACoatNameCanBeUnique(): void
    {
        $client = static::createClient();
        $this->addCoatFixture();
        $crawler = $client->request('GET', '/coats/new');

        $form = $crawler->filter('#coat_submit')->form();

        $coatDatas = [
            'coat[name]' => 'test-name',
            'coat[description]' => 'test-description'
        ];

        $notSavedCrawler = $client->submit($form, $coatDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        self::bootKernel();
        $coatRepository = static::getContainer()->get(CoatRepository::class);
        $coats = $coatRepository->findAll();

        self::assertCount(1, $coats, 'The second coat must not be added.');
    }
}
