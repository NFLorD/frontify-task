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
     * @Given there is a color named :name, with hex :hex
     */
    public function thereIsAColorNamedWithHex($name, $hex)
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
    public function iTryToCreateAColorNamedWithHex($name, $hex)
    {
        $this->restApiContext->post('/api/color', json_encode([
            'name' => $name,
            'hex' => $hex
        ]));
    }

    /**
     * @When I try to delete the color :name
     */
    public function iTryToDeleteTheColor($name)
    {
        $id = $this->colors[$name];
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
    public function iShouldHaveAColorNamed($name)
    {
        if (null === $this->colorRepository->findOneBy(['name' => $name])) {
            throw new \RuntimeException;
        }
    }
}
