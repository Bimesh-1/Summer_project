<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/", name="main_index")
     */
    public function index(): Response
    {
        $mapboxToken = $_ENV['SEARCHBOX_API_KEY'];
        $weatherApiKey = $_ENV['WEATHER_API_KEY'];

        return $this->render('main/index.html.twig', [
            'mapbox_token' => $mapboxToken,
            'weather_api_key' => $weatherApiKey,
        ]);
    }

    /**
     * @Route("/weather", name="get_weather")
     */
    public function getWeather(Request $request): Response
    {
        $city = $request->query->get('city');
        $apiKey = $_ENV['WEATHER_API_KEY'];
        $response = $this->httpClient->request('GET', "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}");
        $weatherData = $response->toArray();

        return $this->json($weatherData);
    }

    /**
     * @Route("/countries", name="get_countries")
     */
    public function getCountries(): Response
    {
        $response = $this->httpClient->request('GET', 'https://restcountries.com/v3.1/all');
        $countries = $response->toArray();

        return $this->json($countries);
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutUs(): Response
    {
        return $this->render('main/about_us.html.twig');
    }

    /**
     * @Route("/choose-your-plan", name="choose_plan")
     */
    public function choosePlan(): Response
    {
        $mapboxToken = $_ENV['SEARCHBOX_API_KEY'];

        return $this->render('main/choose_plan.html.twig', [
            'mapbox_token' => $mapboxToken,
        ]);
    }
}
