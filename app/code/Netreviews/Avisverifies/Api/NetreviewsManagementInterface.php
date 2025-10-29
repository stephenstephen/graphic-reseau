<?php
/**
 * Created by PhpStorm.
 * User: Djaber
 * Date: 04/02/2019
 * Time: 10:27
 */
namespace Netreviews\Avisverifies\Api;

interface NetreviewsManagementInterface
{
    /**
     *
     * check module active
     * @api
     * @param string $query
     * @param string $message
     * @return string
     */
    public function execute($query, $message);

}