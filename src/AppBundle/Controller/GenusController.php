<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Service\MarkdownTransformer;
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
            ->findAllPublishedOrderedByRecentlyActive();
        
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
        
        $transformer = new MarkdownTransformer($this->get('markdown.parser.parser_manager'));
        $funFact = $transformer->parse($genus->getFunFact());
        
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
        
        $recentNotes = $em->getRepository('AppBundle:GenusNote')
            ->findAllRecentNotesForGenus($genus);
        
        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'funFact' => $funFact,
            'recentNoteCount' => count($recentNotes),
        ));
    }
    
    /**
     * @Route("/genus/{name}/notes", name="genus_show_notes")
     * @Method("GET")
     * Используем Genus $genus вместо name в аргументе ф-ии, так как в Genus есть свойство $name
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [];
        
        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/'.$note->getUserAvatarFilename(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y'),
            ];
        }
    
        $data = [
            'notes' => $notes,
        ];
        
        return new JsonResponse($data);
    }
}