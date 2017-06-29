<?php


use AppBundle\Entity\Site;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSitesData implements FixtureInterface
{

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $skelbiu = new Site();
        $skelbiu->setName('skelbiu');
        $skelbiu->setSiteUrl('skelbiu.lt');
        $manager->persist($skelbiu);
        $manager->flush();

        $autoplius = new Site();
        $autoplius->setName('autoplius');
        $autoplius->setSiteUrl('autoplius.lt');
        $manager->persist($autoplius);
        $manager->flush();

        $aruodas = new Site();
        $aruodas->setName('aruodas');
        $aruodas->setSiteUrl('aruodas.lt');
        $manager->persist($aruodas);
        $manager->flush();
    }
}
