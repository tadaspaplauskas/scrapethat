<?php

use Illuminate\Database\Seeder;

use App\Snapshot;
use App\Page;

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
<html><head><style>.pkt_added {text-decoration:none !important;}</style></head><body style=""><header>\n
<title>http://info.cern.ch</title>\n
</header>\n
\n
<h1>http://info.cern.ch - home of the first website</h1>\n
<p class="target">This is dummy paragraph</p>\n
<p class="target">From here you can:</p>\n
<ul>\n
<li><a href="http://info.cern.ch/hypertext/WWW/TheProject.html">Browse the first website</a></li>\n
<li><a href="http://line-mode.cern.ch/www/hypertext/WWW/TheProject.html">Browse the first website using the line-mode browser simulator</a></li>\n
<li><a href="http://home.web.cern.ch/topics/birth-web">Learn about the birth of the web</a></li>\n
<li><a href="http://home.web.cern.ch/about">Learn about CERN, the physics laboratory where the web was born</a></li>\n
</ul>\n
\n
</body></html>
HTML;

        Page::truncate();

        foreach (Snapshot::all() as $snapshot) {
            $snapshot->pages()->create([
                'url' => 'http://crawler.loc/tests/1.html',
                'html' => $html,
            ]);
        }
    }
}
