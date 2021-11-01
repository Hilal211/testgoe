<?php

use Illuminate\Database\Seeder;
use App\_list;

class ZipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $zips = array(
        	"H1A","H1B","H1C","H1E","H1G","H1H","H1J","H1K","H1L","H1M","H1N","H1P","H1R",
			"H1S","H1T","H1V","H1W","H1X","H1Y","H1Z","H2A","H2B","H2C","H2E","H2H","H2J","H2K","H2L","H2M",
			"H2N","H2P","H2R","H2S","H2T","H2V","H2W","H2X","H2Y","H2Z","H3A","H3B","H3C","H3E","H3G","H3H",
			"H3J","H3K","H3L","H3M","H3N","H3P","H3R","H3S","H3T","H3V","H3W","H3X","H3Y","H3Z","H4A","H4B","H4C",
			"H4E","H4G","H4H","H4J","H4K","H4L","H4M","H4N","H4P","H4R","H5A","H5B","H7A","H7B","H7C",
			"H7E","H7G","H7H","H7J","H7K","H7L","H7M","H7N","H7P","H7R","H7S","H7T","H7V","H7W","H7X","H7Y","H8N",
			"H8P","H8R","H8S","H8T","H8Y","H8Z","H9A","H9B","H9C","H9E","H9G","H9H","H9J","H9K","H9P","H9R",
			"H9S","H9W","H9X"
		);
		$listname = "newsletter_zip";
		foreach($zips as $zip){
			_list::create([
				'list_name'=>$listname,
				'item_name'=>$zip,
				'friendly_name'=>str_slug($zip,'_'),
				'status'=>'1'
			]);
		}
    }
}
