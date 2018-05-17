<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Module;
use AppBundle\Entity\Specialite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Reglages controller.
 *
 * @Route("Reglages")
 */
class SettingsController extends Controller
{
    
    /**
     * Finds and displays a settings page.
     *
     * @Route("/", name="reglages_show")
     * @Method("GET")
     */
    public function reglageAction()
    {  if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
        $em = $this->getDoctrine()->getManager();

        if( $this->getUser()->hasRole('ROLE_ETUDIANT')){
            $sections = $em->getRepository('AppBundle:Section')->findAll();
            $modules = $em->getRepository('AppBundle:Module')->findAll();
            $eleve = $em->getRepository("AppBundle:Etudiant")->findOneBy(array('user' => $this->getUser()->getId()));

            return $this->render('@App/settings/show_settings.html.twig', array(
                'user' => $this->getUser(),
                'sections' => $sections,
                'modules' => $modules,
                'eleve' => $eleve,
            ));
        }
        else{
            $sections = $em->getRepository('AppBundle:Section')->findAll();
            $modules = $em->getRepository('AppBundle:Module')->findAll();
            $specialites = $em->getRepository('AppBundle:Specialite')->findAll();

            return $this->render('@App/settings/show_settings.html.twig', array(
                'user' => $this->getUser(),
                'modules' => $modules,
                'sections' => $sections,
                'specialites' => $specialites,
            ));
        }
        
    }


         /**
     * 
     *
     * @Route("/addModule", name="module_add")
     * @Method("POST")
     */
    public function addModuleAction(Request $request)
    {if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
    $em = $this->getDoctrine()->getManager();
    $module = new Module;
    $module->setName($request->request->get('name'));
    $em->persist($module);
    $em->flush();

    $this->addFlash("Succès", "Le module a bien été ajouté");
        
    return $this->redirectToRoute('reglages_show');



    }


     /**
     * 
     *
     * @Route("/addSpe", name="spe_add")
     * @Method("POST")
     */
    public function addSpeAction(Request $request)
    {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
    $em = $this->getDoctrine()->getManager();
    $spe= new Specialite;
    $spe->setName($request->request->get('name'));
    $em->persist($spe);
    $em->flush();

    $this->addFlash("Succès", "La spécialité a bien été ajoutée");
        
    return $this->redirectToRoute('reglages_show');


    }


     /**
     * 
     *
     * @Route("/editinfos", name="edit_infos")
     * @Method("POST")
     */
    public function editInfos(Request $request){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
    
     $em = $this->getDoctrine()->getManager();
     $user=$this->getUser();
     
      if($request->request->get('old_password') != ""){

        if(!password_verify($request->request->get('old_password'),$user->getPassword())){
         $this->addFlash("Erreur", "L'ancien mot de passe est erroné");
          return $this->redirectToRoute('reglages_show');
         }

        if(strcmp($request->request->get('password'), $request->request->get('password_bis')) != 0){
          $this->addFlash("Erreur", "Les mots de passe entrés ne sont pas identiques ");
          return $this->redirectToRoute('reglages_show');
        }

        $user->setPassword(password_hash($request->request->get('password'), PASSWORD_DEFAULT));


        }

        $user->setEmail($request->request->get('_usernamebis'));
        $user->setUsername($request->request->get('_usernamebis'));

        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));

         if ($_FILES['photo']['error'] == 0)
            {
                if ($_FILES['photo']['size'] <= 1048576)
                { 
                    $infosfichier = pathinfo($_FILES['photo']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg','webp', 'JPG', 'PNG',
                        'png');
                    if (in_array($extension_upload,
                        $extensions_autorisees))
                    {
                        move_uploaded_file($_FILES['photo']['tmp_name'], 'assets/public/users/images/' .
                            basename("user_".$user->getId()).".jpg");
                        $user = $em->getRepository("AppBundle:User")->findOneBy(array('id' => $user->getId()));
                        $user->setImage("user_".$user->getId().".jpg");
                    }else{
                        
                        $this->addFlash("Erreur", "Extension incorrecte.");
                      return $this->redirectToRoute('reglages_show');

                    }
                }else{   $this->addFlash("Erreur", "La taille de l'image est trop grande");
                   
          return $this->redirectToRoute('reglages_show');
                }
            }

        if($this->getUser()->hasRole('ROLE_ETUDIANT')){
        $etudiant = $em->getRepository("AppBundle:Etudiant")->findOneBy(array('user' => $user->getId()));
        
        $etudiant->setMatricule($request->request->get('matricule'));
        $em->persist($etudiant);


        }

        $em->persist($user);
        $em->flush();

        $this->addFlash("Succès", "Les changements ont bien été effectués");
         return $this->redirectToRoute('reglages_show');
    }


}
