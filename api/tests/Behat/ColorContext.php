<?php

namespace App\Tests\Behat;

use App\Entity\Color;
use App\Repository\ColorRepository;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ColorContext implements Context
{
    /** @var array<Color> */
    private $colors = [];

    public function __construct(
        private RestApiContext $restApiContext,
        private EntityManagerInterface $entityManager,
        private ColorRepository $colorRepository
    ) { }

    /**
     * @BeforeScenario
     */
    public function clearData(): void
    {
        $this->colors = [];
        $this->entityManager->createQuery('DELETE FROM App:Color')->execute();
    }

    /**
     * @Given there is a color named :name, with hex :hex
     */
    public function thereIsAColorNamedWithHex($name, $hex): void
    {
        $color = new Color;
        $color->setName($name);
        $color->setHex($hex);

        $this->entityManager->persist($color);
        $this->entityManager->flush();

        $this->colors[$name] = $color->getId();
    }

    /**
     * @When I try to create a color named :name, with hex :hex
     */
    public function iTryToCreateAColorNamedWithHex($name, $hex): void
    {
        $this->restApiContext->post('/api/color', json_encode([
            'name' => $name,
            'hex' => $hex
        ]));

        $response = $this->restApiContext->getResponse();
        $payload = json_decode($response->getContent(), true);
        $id = $payload['id'] ?? 0;

        $this->colors[$name] = $id;
    }

    /**
     * @When I try to delete the color :name
     */
    public function iTryToDeleteTheColor($name): void
    {
        $id = $this->colors[$name] ?? 0;
        $this->restApiContext->delete("/api/color/$id");
    }

    /**
     * @When I list colors
     */
    public function iListColors()
    {
        $this->restApiContext->get('/api/color');
    }

    /**
     * @Then I should have a color named :name
     */
    public function iShouldHaveAColorNamed(string $name): void
    {
        $id = $this->colors[$name] ?? 0;

        if (null === $this->colorRepository->find($id)) {
            throw new \RuntimeException;
        }
    }

    /**
     * @Then I should not have a color named :name
     */
    public function iShouldNotHaveAColorNamed(string $name): void
    {
        $id = $this->colors[$name] ?? 0;

        if (null !== $this->colorRepository->find($id)) {
            throw new \RuntimeException;
        }
    }
}
