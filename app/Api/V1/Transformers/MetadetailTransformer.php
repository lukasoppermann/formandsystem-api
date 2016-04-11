<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Metadetail;


class MetadetailTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        // 'fragments'
        // 'collections'
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
      'pages'
    ];

    public function transform(Metadetail $metadetails)
    {
        return [
            'id'            => $metadetails->id,
            'type'          => $metadetails->type,
            'value'          => $this->decode($metadetails->value),
            'created_at'    => (string)$metadetails->created_at,
            'updated_at'    => (string)$metadetails->updated_at,
            'relationships' => $this->relationshipsLinks('metadetails/'.$metadetails->id),
        ];
    }
    /*
     * transform data received via post
     */
    public function transformPostData($data){
        if( count($data) > 0){
            return [
                'type'          => (string) $data['type'],
                'value'         => $this->encode($data['value']),
            ];
            isset($data['name']) ? $output['name'] = (string) $data['name'] : '';
            isset($data['slug']) ? $output['slug'] = (string) $data['slug'] : '';
        }
        return [];
    }
    /*
     * include Pages
     */
    public function includePages( Metadetail $metadetail )
    {
        return $this->collection( $metadetail->pages, new PageTransformer, 'pages' );
    }

}
