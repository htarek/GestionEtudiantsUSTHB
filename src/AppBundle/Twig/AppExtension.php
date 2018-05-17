<?php 

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManager;
 
class AppExtension extends \Twig_Extension 
{
    protected $em;

    protected $urls = [
      1 => 'HOMEPAGE',
      2 => 'CATEGORIE_SHOW',
      3 => 'CATEGORIE_SHOW',
      4 => 'CATEGORIE_SHOW',
      5 => 'CATEGORIE_SHOW',
      5 => 'CATEGORIE_SHOW',
      5 => 'CONTACTS_INDEX',
      5 => 'CONNEXION',
    ];

    public function __construct(EntityManager $entityManager)
	{
	    $this->em = $entityManager;
	}

  public function getFunctions(){
     return array(
          'getInformations' => new \Twig_Function_Method($this, 'getInformations'),
     );
  }
 
  public function getInformations(){
    return $this->em->getRepository('AppBundle:Informations')->find(1);
  }
}