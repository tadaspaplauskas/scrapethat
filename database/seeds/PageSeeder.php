<?php

use Illuminate\Database\Seeder;

use App\Models\Snapshot;
use App\Models\Page;

use Faker\Factory;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $html = <<<'HTML'
<html><head><style>.pkt_added {text-decoration:none !important;}</style></head><body style=""><header>
<title>http://info.cern.ch</title>
</header>

<p class="price">58 000 €</p>
<p class="meters">42.6</p>

<p class="price">78 000 €</p>
<p class="meters">53.91</p>

<p class="price">60 800 €</p>
<p class="meters">39.42</p>

<p class="price">60 500 €</p>
<p class="meters">44.95</p>

<p class="price">86 000 €</p>
<p class="meters">57</p>

<p class="price">83 500 €</p>
<p class="meters">53.5</p>

<p class="price">59 650 €</p>
<p class="meters">39.78</p>

<p class="price">67 700 €</p>
<p class="meters">47</p>

<p class="price">73 800 €</p>
<p class="meters">54</p>

<p class="price">65 000 €</p>
<p class="meters">49.26</p>

<p class="price">67 800 €</p>
<p class="meters">52.08</p>

<p class="price">80 000 €</p>
<p class="meters">57</p>

<p class="price">54 650 €</p>
<p class="meters">34.23</p>

<p class="price">86 000 €</p>
<p class="meters">70.61</p>

<p class="price">94 000</p>
<p class="meters">53.61</p>

<p class="price">91 140 €</p>
<p class="meters">52.08</p>

<p class="price">57 000 €</p>
<p class="meters">47.55</p>

<p class="price">40 000 €</p>
<p class="meters">25.93</p>

<p class="price">45 000 €</p>
<p class="meters">29.89</p>

<p class="price">79 900 €</p>
<p class="meters">46.63</p>

<p class="price">67 400 €</p>
<p class="meters">47.11</p>

<p class="price">77 037 €</p>
<p class="meters">50.89</p>

<p class="price">114 185 €</p>
<p class="meters">88.17</p>

<p class="price">53 200 €</p>
<p class="meters">34.33</p>

<p class="price">77 000 €</p>
<p class="meters">51.07</p>

</body></html>
HTML;

        Page::truncate();

        foreach (Snapshot::all() as $snapshot) {
            for ($i = 1; $i <= ($snapshot->to - $snapshot->from); $i++) {
                $snapshot->pages()->create([
                    'url' => 'http://scrapethat.test/tests/1.html',
                    'html' => $html,
                ]);
            }
        }
    }
}
