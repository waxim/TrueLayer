<?php

namespace TrueLayer\Banking;

use TrueLayer\Data\Status;

class DataResolver
{
    /**
     * @param array $results
     * @return array
     */
    public function getAvailability($results)
    {
        $availability = [];

        foreach($results['results'] as $result) {
            foreach($result['providers'] as $name => $p){
                $status = new Status();
                $status->name = $p['provider_id'];
                foreach($p['endpoints'] as $ep) {
                    if($ep['endpoint'] === "accounts") {
                        $status->accounts = $ep['availability'];
                    }

                    if($ep['endpoint'] === "accounts/transactions") {
                        $status->transactions = $ep['availability'];
                    }

                    if($ep['endpoint'] === "cards") {
                        $status->cards = $ep['availability'];
                    }

                    if($ep['endpoint'] === "info") {
                        $status->pii = $ep['availability'];
                    }
                }
                $availability[$status->name] = $status;
            }
        }

        return $availability;
    }
}
