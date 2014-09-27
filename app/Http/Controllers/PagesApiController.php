<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response as Response;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;

class PagesApiController extends BaseApiController {

	protected $ContentRepository;

	/**
	* construct
	*
	* @return void
	*/
	function __construct(ContentRepository $ContentRepository)
	{
		// call parent constrcutor
		parent::__construct();

		// Repositories
		$this->ContentRepository = $ContentRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json('Parameter missing: id or path. For more information read the documentation: http://dev.formandsystem.com',200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
