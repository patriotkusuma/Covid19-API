<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class ScrapeController extends Controller
{
    private $states = [];

    public function corona($country = false)
    {
        $client = new Client();

        // echo '<pre>';
        // print_r($page);
        // $total = $page->filter('.maincounter-number')->text();
        if (!$country) {
            $page = $client->request('GET', 'https://www.worldometers.info/coronavirus/');

            $page->filter('.maincounter-number')->each(function ($item) {
                array_push($this->states, $item->text());
            });
        } else {

            $page = $client->request('GET', 'https://www.worldometers.info/coronavirus/country/' . $country);
            $page->filter('.maincounter-number')->each(function ($item) {
                array_push($this->states, $item->text());
            });
        }

        $result = $this->returnResult();

        return response($result, 200);
    }

    private function returnResult()
    {
        $output = [];
        $output['total_affected'] = $this->states[0];
        $output['total_death'] = $this->states[1];
        $output['total_recovered'] = $this->states[2];
        $output['Made with Love'] = 'github.com/patriotkusuma';
        return $output;
    }
}
