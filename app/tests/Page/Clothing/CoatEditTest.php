<?php

declare(strict_types=1);

namespace App\Tests\Page\Clothing;

use App\Controller\Clothing\CoatDeleteController;
use App\Controller\Clothing\CoatEditController;
use App\Controller\Clothing\CoatIndexController;
use App\Entity\Clothing\Coat;
use App\Form\Clothing\CoatType;
use App\Repository\Clothing\CoatRepository;
use App\Tests\Page\Clothing\CoatFixture;
use App\Tests\Page\Product\GenerateString;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the coat edit page.
 */
#[
    PA\CoversClass(CoatDeleteController::class),
    PA\CoversClass(CoatEditController::class),
    PA\UsesClass(Coat::class),
    PA\UsesClass(CoatIndexController::class),
    PA\UsesClass(CoatRepository::class),
    PA\UsesClass(CoatType::class),
    PA\Group('pages'),
    PA\Group('pages_coat'),
    PA\Group('pages_coat_edit'),
    PA\Group('coat')
]
class CoatEditTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use CoatFixture;


    // Methods :

    /**
     * Tests that a coat can be edited.
     */
    public function testCanDisplayCoatEdit(): void
    {
        $client = static::createClient();
        $this->addCoatFixture();
        $crawler = $client->request('GET', '/coats/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Edit coat', 'There must be a <h1> for coats.');

        $form = $crawler->filter('form[name="coat"]');
        self::assertCount(1, $form, 'There must be a form to create coats.');
        $this->assertHasAnInputForName($form->filter('#coat_name'));
        $this->assertHasAnInputForDescription($form->filter('#coat_description'));
        self::assertNotEmpty($form->filter('#coat__token')->attr('value'), 'The create form must have a token.');
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
        self::assertSame('30', $nameInput->attr('size'), 'The name input size must be 30.');
        self::assertSame('The name of the product.', $nameInput->attr('title'), 'The name input title must be "The name of the product.".');
        self::assertSame('text', $nameInput->attr('type'), 'The name input must be of type text.');
        self::assertSame('test-name', $nameInput->attr('value'), 'The name input must have "test-name" as a value.');
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
        self::assertSame('test-description', $nameInput->text(), 'The description input must have "test-description" as a value.');
    }


    /**
     * Tests that the page can be browsed
     * back to the coat index page.
     */
    #[PA\Depends('testCanDisplayCoatEdit')]
    public function testCanBrowseBackToTheIndex(): void
    {
        $client = static::createClient();
        $this->addCoatFixture();
        $crawler = $client->request('GET', '/coats/1/edit');
        $backLink = $crawler->filter('a.btn-secondary')->eq(0);

        $client->click($backLink->link());

        $this->assertResponseIsSuccessful();
    }


    /**
     * Tests that a coat can be updated.
     */
    #[PA\Depends('testCanDisplayCoatEdit')]
    public function testCanUpdateACoat(): void
    {
        $client = static::createClient();
        $this->addCoatFixture();
        $crawler = $client->request('GET', '/coats/1/edit');
        $form = $crawler->filter('#coat_submit')->form();

        $coatDatas = [
            'coat[name]' => 'test-update-name',
            'coat[description]' => 'test-update-description'
        ];

        $client->submit($form, $coatDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $coat = $entityManager->find(Coat::class, 1);
        $entityManager->refresh($coat);

        self::assertSame(
            'test-update-name',
            $coat->name,
            'The new coat must be named "test-update-name".'
        );
        self::assertSame(
            'test-update-description',
            $coat->description,
            'The new coat must be named "test-update-description".'
        );
    }


    /**
     * Returns invalid coat datas.
     * @return mixed invalid coat datas.
     */
    public static function getInvalidCoatDatas(): array
    {
        $coatDatas = [
            'coat[name]' => 'test-update-name',
            'coat[description]' => 'test-update-description'
        ];

        $emptyStringName = $coatDatas;
        $emptyStringName['coat[name]'] = '';

        $nameTooLong = $coatDatas;
        $nameTooLong['coat[name]'] = self::generateLongString(51);

        $descriptionTooLong = $coatDatas;
        $descriptionTooLong['coat[description]'] = self::generateLongString(301);

        return [
            'the name is an empty string.' => [$emptyStringName],
            'the name is too long (>50 chars).' => [$nameTooLong],
            'the description is too long (>300 chars).' => [$descriptionTooLong]
        ];
    }

    /**
     * Tests that coats can not be updated
     * with invalid coat datas.
     * @param mixed[] $coatDatas invalid coat datas.
     */
    #[
        PA\DataProvider('getInvalidCoatDatas'),
        PA\Depends('testCanDisplayCoatEdit'),
        PA\TestDox('Can not update a coat when $_dataName')
    ]
    public function testCanNotUpdateACoat(array $coatDatas): void
    {
        $client = static::createClient();
        $this->addCoatFixture();
        $crawler = $client->request('GET', '/coats/1/edit');

        $form = $crawler->filter('#coat_submit')->form();
        $notSavedCrawler = $client->submit($form, $coatDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $coat = $entityManager->find(Coat::class, 1);
        $entityManager->refresh($coat);

        self::assertNotEquals(
            $coatDatas['coat[name]'],
            $coat->name,
            'The coat name must not be updated.'
        );
        self::assertNotEquals(
            $coatDatas['coat[description]'],
            $coat->description,
            'The coat description must not be updated.'
        );
    }


    /**
     * Tests that a coat name can be unique.
     */
    #[PA\Depends('testCanDisplayCoatEdit')]
    public function testACoatNameCanBeUnique(): void
    {
        $client = static::createClient();
        $this->addCoatFixture();

        $coat = new Coat('test-name2', 'test-description2');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $entityManager->persist($coat);
        $entityManager->flush();

        $crawler = $client->request('GET', '/coats/2/edit');

        $form = $crawler->filter('form[name="coat"]')->form();

        $coatDatas = [
            'coat[name]' => 'test-name',
            'coat[description]' => 'test-description'
        ];

        $notSavedCrawler = $client->submit($form, $coatDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');
    }


    /**
     * Tests that a coat can be deleted.
     */
    #[PA\Depends('testCanDisplayCoatEdit')]
    public function testCanDeleteCoat(): void
    {
        $client = static::createClient();

        $this->addCoatFixture();

        $crawler = $client->request('GET', '/coats/1/edit');
        $form = $crawler->filter('#delete_product_form')->form();
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager('clothing');
        $deletedCoat = $entityManager->find(Coat::class, 1);

        self::assertNull($deletedCoat, 'The Coat has not been deleted.');
    }
}
