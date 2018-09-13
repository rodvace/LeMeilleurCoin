<?php
/**
 * Created by PhpStorm.
 * User: Diginamic02
 * Date: 10/09/2018
 * Time: 15:20
 */

namespace AppBundle\Controller;

use AppBundle\Form\DeposerType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ad;
use AppBundle\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * @Route(name="annonces_", path="/annonces")
 */
class AnnoncesController extends Controller
{
    /**
     * @Route(name="deposer", path="/deposer")
     */
    public function deposerAction(Request $request)
    {
        $ad = new Ad();

        // Je gagne en ligne de code car le formbuilder est au niveau de DeposerType
        $form = $this->createForm(DeposerType::class, $ad);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($ad);
            $entityManager->flush();
        }
        return $this->render('annonces/deposer.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * L'ordre des routes est important. Si /list est après get, et bien le système va tenter d'afficher une ville nommé list
     *
     * @Route(name="voir", path="/voir")
     */
    public function voirAction(Request $request)
    {
        $tag = $request->get('tag', '');
        $ads = $this
            ->getDoctrine()
            ->getRepository(Ad::class)
            ->findByTag($tag);

        return $this->render(
            'annonces/voir.html.twig',
            ['ads' => $ads]
        );
    }

    /**
     * @Route(name="ajoutdirect", path="/ajoutdirect")
     */
    public function ajoutdirectAction()
    {
        $ads = [
            ['PC Portable', 'Vends pc acer en très bon état', 'Paris', '75002', 175],
            ['Table basse', 'Très propre, peu servie', 'Nantes', '54000', 50],
            ['Fiat Punto', 'Avec un moteur de 2CV Tuning', 'Dieppe', '76200', 9000],
            ['iPhone 2', 'Super téléphone très beau, hyper high tech Model 2G', 'Marseille', '13600', 7398],
        ];

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($ads as $data) {
            $ad = new Ad();
            $ad->setTitle($data[0]);
            $ad->setDescription($data[1]);
            $ad->setCity($data[2]);
            $ad->setZip($data[3]);
            $ad->setPrice($data[4]);
            $ad->setDateCreated(new \DateTime());
            // Ici je vais rajouter un persit, mais surtout pas de flush.
            $entityManager->persist($ad);
        }
        // Le flush doit bien être à la fin de la méthode
        $entityManager->flush();

        return new Response('<html><body></body></html>');
    }

    /**
     * @Route(name="details", path="/details/{id}")
     */
    public function detailsAction(Request $request)
    {
        $id = $request->get('id');
        $ad = $this
            ->getDoctrine()
            ->getRepository(Ad::class)
            ->find($id);
        return $this->render('annonces/details.html.twig', [
            'ad' => $ad,
        ]);
    }

 }
