<?php

declare(strict_types=1);

namespace App\Tests\Page\Furniture;

use App\Controller\Furniture\ChairController;
use App\Entity\Furniture\Chair;
use App\Form\Furniture\ChairType;
use App\Repository\Furniture\ChairRepository;
use App\Tests\Page\Product\GenerateString;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test the chair index page.
 */
#[
    PA\CoversClass(ChairController::class),
    PA\UsesClass(Chair::class),
    PA\UsesClass(ChairRepository::class),
    PA\UsesClass(ChairType::class),
    PA\Group('pages'),
    PA\Group('pages_chair'),
    PA\Group('pages_chair_new'),
    PA\Group('chair')
]
class NewTest extends WebTestCase
{
    // Traits :
    use GenerateString;
    use ChairFixture;


    // Methods :

    /**
     * Tests that the page to create a new chair
     * can be displayed.
     */
    public function testCanDisplayNewChairPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/chairs/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Create new chair', 'There must be a <h1> for new chairs.');

        $form = $crawler->filter('form[name="chair"]');
        self::assertCount(1, $form, 'There must be a form to create chairs.');
        $this->assertHasAnInputForName($form->filter('#chair_name'));
        $this->assertHasAnInputForDescription($form->filter('#chair_description'));
        self::assertNotEmpty($form->filter('#chair__token')->attr('value'), 'The form must have a token.');
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
        self::assertSame('chair[description]', $nameInput->attr('name'), 'The name input must be named "chair[description]".');
        self::assertSame('A description', $nameInput->attr('placeholder'), 'The name input placeholder must be "A description".');
        self::assertSame('The description of the product.', $nameInput->attr('title'), 'The name input title must be "The description of the product.".');
    }


    /**
     * Tests that new chairs can be created.
     */
    #[PA\Depends('testCanDisplayNewChairPage')]
    public function testCanCreateNewChair(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/chairs/new');

        $form = $crawler->filter('#chair_submit')->form();

        $chairDatas = [
            'chair[name]' => 'test-new-name',
            'chair[description]' => 'test-new-description'
        ];

        $client->submit($form, $chairDatas);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $chair = $entityManager->find(Chair::class, 1);

        self::assertSame(
            'test-new-name',
            $chair->getName(),
            'The new chair must be named "test-new-name".'
        );
        self::assertSame(
            'test-new-description',
            $chair->getDescription(),
            'The new chair must be named "test-new-description".'
        );
    }


    /**
     * Returns invalid chair datas.
     * @return mixed invalid chair datas.
     */
    public static function getInvalidChairDatas(): array
    {
        $chairDatas = [
            'chair[name]' => 'test-new-name',
            'chair[description]' => 'test-new-description'
        ];

        $emptyStringName = $chairDatas;
        $emptyStringName['chair[name]'] = '';

        $nameTooLong = $chairDatas;
        $nameTooLong['chair[name]'] = self::generateLongString(51);

        $descriptionTooLong = $chairDatas;
        $descriptionTooLong['chair[name]'] = self::generateLongString(301);

        return [
            'the name is an empty string.' => [$emptyStringName],
            'the name is too long (>50 chars).' => [$nameTooLong],
            'the description is too long (>300 chars).' => [$descriptionTooLong]
        ];
    }

    /**
     * Tests that new chairs can not be created
     * with invalid chair datas.
     * @param mixed[] $chairDatas invalid chair datas.
     */
    #[
        PA\DataProvider('getInvalidChairDatas'),
        PA\Depends('testCanDisplayNewChairPage'),
        PA\TestDox('Can not create new chair when $_dataName')
    ]
    public function testCanNotCreateNewChair(array $chairDatas): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/chairs/new');

        $form = $crawler->filter('#chair_submit')->form();

        $notSavedCrawler = $client->submit($form, $chairDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        self::bootKernel();
        $chairRepository = static::getContainer()->get(ChairRepository::class);
        $chairs = $chairRepository->findAll();

        self::assertEmpty($chairs, 'The chair must not be added.');
    }


    /**
     * Tests that a chair name can be unique.
     */
    #[PA\Depends('testCanDisplayNewChairPage')]
    public function testAChairNameCanBeUnique(): void
    {
        $client = static::createClient();
        $this->addChairFixture();
        $crawler = $client->request('GET', '/chairs/new');

        $form = $crawler->filter('#chair_submit')->form();

        $chairDatas = [
            'chair[name]' => 'test-name',
            'chair[description]' => 'test-description'
        ];

        $notSavedCrawler = $client->submit($form, $chairDatas);

        $this->assertResponseIsSuccessful();

        $errorMessage = $notSavedCrawler->filter('div.invalid-feedback');

        self::assertCount(1, $errorMessage, 'There must be an error message.');

        self::bootKernel();
        $chairRepository = static::getContainer()->get(ChairRepository::class);
        $chairs = $chairRepository->findAll();

        self::assertCount(1, $chairs, 'The second chair must not be added.');
    }
}
