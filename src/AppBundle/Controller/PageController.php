<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Entity\Proposal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/emmanuel-macron", name="page_emmanuel_macron")
     * @Method("GET")
     */
    public function emmanuelMacronAction()
    {
        return $this->createCachedPageResponse('emmanuel-macron/ce-que-je-suis', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('emmanuel-macron-ce-que-je-suis'),
        ]);
    }

    /**
     * @Route("/emmanuel-macron/revolution", name="page_emmanuel_macron_revolution")
     * @Method("GET")
     */
    public function emmanuelMacronRevolutionAction()
    {
        return $this->createCachedPageResponse('emmanuel-macron/revolution', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('emmanuel-macron-revolution'),
        ]);
    }

    /**
     * @Route("/emmanuel-macron/le-programme", name="page_emmanuel_macron_programme")
     * @Method("GET")
     */
    public function emmanuelMacronProgrammeAction()
    {
        return $this->createCachedPageResponse('emmanuel-macron/programme', [
            'proposals' => $this->getDoctrine()->getRepository(Proposal::class)->findAllOrderedByPosition(),
        ]);
    }

    /**
     * @Route("/emmanuel-macron/le-programme/{slug}", name="page_emmanuel_macron_proposition")
     * @Method("GET")
     */
    public function emmanuelMacronPropositionAction($slug)
    {
        $proposal = $this->getDoctrine()->getRepository(Proposal::class)->findOneBySlug($slug);

        if (!$proposal || !$proposal->isPublished()) {
            throw $this->createNotFoundException();
        }

        return $this->createCachedPageResponse('emmanuel-macron/proposition', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * @Route("/emmanuel-macron/mon-agenda", name="page_emmanuel_macron_mon_agenda")
     * @Method("GET")
     */
    public function emmanuelMacronMonAgendaAction()
    {
        return $this->createCachedPageResponse('emmanuel-macron/mon-agenda');
    }

    /**
     * @Route("/le-mouvement", name="page_le_mouvement")
     * @Method("GET")
     */
    public function mouvementValeursAction()
    {
        return $this->createCachedPageResponse('le-mouvement/nos-valeurs', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('le-mouvement-nos-valeurs'),
        ]);
    }

    /**
     * @Route("/le-mouvement/notre-organisation", name="page_le_mouvement_notre_organisation")
     * @Method("GET")
     */
    public function mouvementOrganisationAction()
    {
        return $this->createCachedPageResponse('le-mouvement/notre-organisation', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('le-mouvement-notre-organisation'),
        ]);
    }

    /**
     * @Route("/le-mouvement/les-comites", name="page_le_mouvement_les_comites")
     * @Method("GET")
     */
    public function mouvementComitesAction()
    {
        return $this->createCachedPageResponse('le-mouvement/les-comites', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('le-mouvement-les-comites'),
        ]);
    }

    /**
     * @Route("/le-mouvement/les-evenements", name="page_le_mouvement_les_evenements")
     * @Method("GET")
     */
    public function mouvementEvenementsAction()
    {
        return $this->createCachedPageResponse('le-mouvement/les-evenements', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('le-mouvement-les-evenements'),
        ]);
    }

    /**
     * @Route("/le-mouvement/devenez-benevole", name="page_le_mouvement_devenez_benevole")
     * @Method("GET")
     */
    public function mouvementBenevoleAction()
    {
        return $this->createCachedPageResponse('le-mouvement/devenez-benevole', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('le-mouvement-devenez-benevole'),
        ]);
    }

    /**
     * @Route("/mentions-legales", name="page_mentions_legales")
     * @Method("GET")
     */
    public function mentionsLegalesAction()
    {
        return $this->createCachedPageResponse('mentions-legales', [
            'page' => $this->getDoctrine()->getRepository(Page::class)->findOneBySlug('mentions-legales'),
        ]);
    }

    private function createCachedPageResponse(string $template, array $parameters = [])
    {
        return $this->get('app.cloudflare')->cacheIndefinitely(
            $this->render('page/'.$template.'.html.twig', $parameters),
            ['pages', 'page-'.str_replace('/', '-', $template)]
        );
    }
}