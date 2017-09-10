<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    
    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAllPublishedOrderedBySize();
        
        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses,
        ]);
    }
    
    /**
     * @Route("/genus/new")
     */
    public function newAction()
    {
        $genus = new Genus();
        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSubFamily('Octopodinae');
        $genus->setSpeciesCount(rand(100, 99999));
        
        $genusNote = new GenusNote();
        $genusNote->setUsername('AquaWeaver');
        $genusNote->setUserAvatarFilename('ryan.jpeg');
        $genusNote->setNote('I counted 8 legs... as they wrapped around me');
        $genusNote->setCreatedAt(new \DateTime('-1 month'));
        $genusNote->setGenus($genus);
        
        $em = $this->getDoctrine()->getManager();
        
        $em->persist($genus);
        $em->persist($genusNote);
        
        $em->flush();
        
        return new Response('<html><body>Genus created!</body></html>');
    }
    
    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName)
    {
//        $funFact = 'Octopuses can change the color of their body in just *three-tenths* of a second!';
    
        $em = $this->getDoctrine()->getManager();
    
        $genus = $em->getRepository('AppBundle:Genus')
            ->findOneBy(['name' => $genusName]);
        
        if (!$genus) {
            throw $this->createNotFoundException('No genus found');
        }
        
        /*
        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        
        $key = md5($funFact);
    
        if ($cache->contains($key)) {
            $funFact = $cache->fetch($key);
        } else {
            sleep(1);
            $funFact = $this->get('markdown.parser.parser_manager')
                ->transform($funFact);
            $cache->save($key, $funFact);
        }
        */
        
        return $this->render('genus/show.html.twig', array(
            'genus' => $genus
        ));
    }
    
    /**
     * @Route("/genus/{name}/notes", name="genus_show_notes")
     * @Method("GET")
     * Используем Genus $genus вместо name в аргументе ф-ии, так как в Genus есть свойство $name
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];
    
        $data = [
            'notes' => $notes,
        ];
        
        return new JsonResponse($data);
    }
}