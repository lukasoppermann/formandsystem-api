<?php

class PagesTest extends ApiTester {

	/** @test */
	public function it_fetches_pages()
	{
		$this->make('Content');

		$this->requestJson('v1/pages');

		$this->assertResponseOK();
	}

	/**
	 * return data for DB seeding
	 *
	 * @method getStub
	 */
	protected function getStub()
	{
		return [
			'status' 			=> 1,
			'language' 		=> $this->fake->randomElement(['de','en']),
			'article_id' 	=> $this->fake->unique()->randomDigitNotNull(),
			'data' 				=> json_encode([]),
			'tags' 				=> implode(',',$this->fake->words($this->fake->randomNumber(1))),
			'menu_label' 	=> $this->fake->word(),
			'link' 				=> $this->fake->word(),
			'parent_id' 	=> 0,
			'position' 		=> 0
		];
	}

}
