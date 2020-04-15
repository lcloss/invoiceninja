<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */


namespace App\Http\Controllers;

use App\Http\Requests\CompanyLedger\ShowCompanyLedgerRequest;
use App\Models\CompanyLedger;
use App\Transformers\CompanyLedgerTransformer;
use Illuminate\Http\Request;

class CompanyLedgerController extends BaseController
{
    protected $entity_type = CompanyLedger::class;

    protected $entity_transformer = CompanyLedgerTransformer::class;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *      path="/api/v1/company_ledger",
     *      operationId="getCompanyLedger",
     *      tags={"company_ledger"},
     *      summary="Gets a list of company_ledger",
     *      description="Lists the company_ledger.",
     *      @OA\Parameter(ref="#/components/parameters/X-Api-Secret"),
     *      @OA\Parameter(ref="#/components/parameters/X-Api-Token"),
     *      @OA\Parameter(ref="#/components/parameters/X-Requested-With"),
     *      @OA\Parameter(ref="#/components/parameters/include"),
     *      @OA\Response(
     *          response=200,
     *          description="A list of company_ledger",
     *          @OA\Header(header="X-API-Version", ref="#/components/headers/X-API-Version"),
     *          @OA\Header(header="X-RateLimit-Remaining", ref="#/components/headers/X-RateLimit-Remaining"),
     *          @OA\Header(header="X-RateLimit-Limit", ref="#/components/headers/X-RateLimit-Limit"),
     *          @OA\JsonContent(ref="#/components/schemas/CompanyLedger"),
     *       ),
     *       @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError"),

     *       ),
     *       @OA\Response(
     *           response="default",
     *           description="Unexpected Error",
     *           @OA\JsonContent(ref="#/components/schemas/Error"),
     *       ),
     *     )
     *
     */
    public function index(ShowCompanyLedgerRequest $request)
    {
        $company_ledger = CompanyLedger::whereCompanyId(auth()->user()->company()->id)->orderBy('id', 'ASC');

        return $this->listResponse($company_ledger);
    }
}
