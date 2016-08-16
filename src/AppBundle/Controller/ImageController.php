<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;



/**
 * Image controller.
 *
 */
class ImageController extends Controller
{
    /**
     * Lists all Image entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findBy(array(), array('votesRatio'=>'DESC'));

        $delete_forms = array_map(
            function ($image) {
                return $this->createDeleteForm($image)->createView();
            }
            , $images
        );
        dump($delete_forms);
        return $this->render('image/index_all.html.twig', array(
            'images' => $images,
            'delete_forms' => $delete_forms
        ));
    }

    /**
     * get 2 random images
     *
     */
    public function twoRandomAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image');

        $twoRandom = $images->myFind2Random($this->getUser()->getId());


        return $this->render('image/vote.html.twig', array(
            'images' => $twoRandom,
        ));
    }

    public function voteAction(Request $request) {
        $winner = $request->request->get('winner');
        $looser = $request->request->get('looser');

        $em = $this->getDoctrine()->getManager();
        $winImg = $em->getRepository('AppBundle:Image')->find($winner);
        // dump($winImg);
        $looseImg = $em->getRepository('AppBundle:Image')->find($looser);
        // dump($looseImg);

        if (!$winImg) {
            throw $this->createNotFoundException(
                'No product found for id '.$winner
            );
        }
        if (!$looseImg) {
            throw $this->createNotFoundException(
                'No product found for id '.$looser
            );
        }

        // We increment the count of participated votes for each images
        $winImg->setParticipatedVotes($winImg->getParticipatedVotes()+1);
        $looseImg->setParticipatedVotes($looseImg->getParticipatedVotes()+1);

        // We increment counter of won votes for winning image
        $winImg->setWonVotes($winImg->getWonVotes()+1);
        
        // We refresh the ratio for each images
        $winImg->setVotesRatio($winImg->getWonVotes()/$winImg->getParticipatedVotes());
        $looseImg->setVotesRatio($looseImg->getWonVotes()/$looseImg->getParticipatedVotes());

        $em->persist($winImg);
        $em->persist($looseImg);
        $em->flush();
        
        $session = new Session();
        $session->getFlashBag()->add('infos', 'A voté !');        
        
        return $this->redirectToRoute('image_two_random');
    }


    /**
     * Lists all my images
     *
     */
    public function indexMineAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findByOwnerId($this->getUser());

        return $this->render('image/index.html.twig', array(
            'images' => $images,
        ));
    }

    /**
     * Creates a new Image entity.
     *
     */
    public function newAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm('AppBundle\Form\ImageType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $image->getPath();
            if ($imageFile === null) {

            } else {
                $imageFilename = md5(uniqid()).'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('img_directory'),
                    $imageFilename
                );
                $image->setPath($imageFilename);
            }
            // insertion de la sate
            $image->setUploadedAt(new \Datetime());
            $image->setOwnerId($this->getUser());


            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('image_show', array('id' => $image->getId()));
        }

        return $this->render('image/new.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Image entity.
     *
     */
    public function showAction(Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return $this->render('image/show.html.twig', array(
            'image' => $image,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    // /**
    //  * Displays a form to edit an existing Image entity.
    //  *
    //  */
    // public function editAction(Request $request, Image $image)
    // {
    //     $deleteForm = $this->createDeleteForm($image);
    //     $editForm = $this->createForm('AppBundle\Form\ImageType', $image);
    //     $editForm->handleRequest($request);

    //     if ($editForm->isSubmitted() && $editForm->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($image);
    //         $em->flush();

    //         return $this->redirectToRoute('image_edit', array('id' => $image->getId()));
    //     }

    //     return $this->render('image/edit.html.twig', array(
    //         'image' => $image,
    //         'edit_form' => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    /**
     * Deletes a Image entity.
     *
     */
    public function deleteAction(Request $request, Image $image)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('image_index');
    }

    /**
     * Creates a form to delete a Image entity.
     *
     * @param Image $image The Image entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Image $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('image_delete', array('id' => $image->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
