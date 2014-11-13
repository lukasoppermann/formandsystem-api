<?php

use Illuminate\Database\Seeder;

class ReferencesStreamTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$start = 11;
		$amount = 42;

		for( $i = 0; $i < $amount; $i++ )
		{
			$array[$start+$i] = array (
				'id' => $start+$i,
				'stream' => 'references',
				'parent_id' => 0,
				'position' => $i+1,
				'article_id' => $start+$i,
			);
		}
		\DB::table('fs_stream')->insert($array);
	}

}
