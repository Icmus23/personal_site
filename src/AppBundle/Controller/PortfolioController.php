<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PortfolioController extends Controller
{
    /**
     * @Route("/portfolio", name="portfolio")
     */
    public function portfolioAction()
    {
        $allPortfolio = $this->get('portfolio_service')->getAllPortfolio();

        // var_dump($allPortfolio[2]);

        return $this->render(
            'portfolio/portfolio.html.twig',
            [
                'all_portfolio' => $allPortfolio
            ]
        );
    }
}
