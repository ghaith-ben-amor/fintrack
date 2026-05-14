<?php

namespace App\Controller\BackOffice;

use App\Entity\CarteVirtuelle;
use App\Repository\CarteVirtuelleRepository;
use App\Service\Admin\PdfExportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/carte-virtuelle', name: 'admin_carte_virtuelle_')]
final class CarteVirtuelleController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CarteVirtuelleRepository $carteVirtuelleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $cartes = $carteVirtuelleRepository->createQueryBuilder('c')
            ->join('c.portefeuille', 'p')->addSelect('p')
            ->join('p.user', 'u')->addSelect('u')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('backoffice/carte_virtuelle/index.html.twig', [
            'cartes' => $cartes,
        ]);
    }

    #[Route('/export/pdf', name: 'export_pdf', methods: ['GET'])]
    public function exportPdf(CarteVirtuelleRepository $carteVirtuelleRepository, PdfExportService $pdfExportService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $cartes = $carteVirtuelleRepository->createQueryBuilder('c')
            ->join('c.portefeuille', 'p')->addSelect('p')
            ->join('p.user', 'u')->addSelect('u')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();

        $html = $this->renderView('backoffice/carte_virtuelle/export_pdf.html.twig', [
            'cartes' => $cartes,
            'generatedAt' => new \DateTimeImmutable(),
        ]);

        return $pdfExportService->renderPdfResponse(
            $html,
            sprintf('admin-cartes-virtuelles-%s.pdf', (new \DateTimeImmutable())->format('Ymd-His'))
        );
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(CarteVirtuelle $carteVirtuelle, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$this->isCsrfTokenValid('delete' . $carteVirtuelle->getId(), (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');

            return $this->redirectToRoute('admin_carte_virtuelle_index');
        }

        $cardId = $carteVirtuelle->getId();
        $txCount = (int) $entityManager->getConnection()->fetchOne(
            'SELECT COUNT(*) FROM transaction WHERE carte_source_id = :id OR carte_dest_id = :id',
            ['id' => $cardId]
        );
        $virementCount = (int) $entityManager->getConnection()->fetchOne(
            'SELECT COUNT(*) FROM virement_programme WHERE carte_source_id = :id OR carte_dest_id = :id',
            ['id' => $cardId]
        );

        if ($txCount > 0 || $virementCount > 0) {
            $this->addFlash('warning', 'Suppression impossible: cette carte est li�e � des transactions ou virements programm�s.');

            return $this->redirectToRoute('admin_carte_virtuelle_index');
        }

        $entityManager->remove($carteVirtuelle);
        $entityManager->flush();

        $this->addFlash('success', 'Carte virtuelle supprim�e avec succ�s.');

        return $this->redirectToRoute('admin_carte_virtuelle_index');
    }
}
