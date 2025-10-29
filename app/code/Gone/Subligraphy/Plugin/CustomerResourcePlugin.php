<?php

namespace Gone\Subligraphy\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\ResourceModel\Customer;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Framework\Exception\LocalizedException;

class CustomerResourcePlugin
{
    protected $request;
    protected $connection;
    protected $customerResource;

    public function __construct(
        RequestInterface $request,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        Customer $customerResource
    ) {
        $this->request = $request;
        $this->connection = $resourceConnection->getConnection();
        $this->customerResource = $customerResource;
    }

    public function beforeSave(Customer $subject, CustomerModel $customer)
    {

            // Accéder aux données de la requête
        $isSubligraph = @$this->request->getParam('customer')['is_subligraph'];

        // Si is_subligraph est défini, l'affecter au modèle customer
        if ($isSubligraph) {
            $customer->setData('is_subligraph', $isSubligraph);

            // Récupérer l'ID du client
            $customerId = $customer->getId();

            if($customerId){
                // Ajouter ou mettre à jour l'attribut is_subligraph dans customer_entity_int
                try {
                    $this->updateSubligraphAttribute($customerId, $isSubligraph);
                } catch (LocalizedException $e) {
                    // Gérer les erreurs dans l'exécution du plugin
                    throw new LocalizedException(__('An error occurred while saving the customer.'));
                }
            }

        }


        return [$customer];
    }

    /**
     * Insert or update the is_subligraph attribute in customer_entity_int
     *
     * @param int $customerId
     * @param mixed $isSubligraph
     * @return void
     */
    protected function updateSubligraphAttribute($customerId, $isSubligraph)
    {
        // Vérifier si la valeur is_subligraph est correcte (1 ou 0)
        $value = ($isSubligraph == 1) ? 1 : 0;

        // Table customer_entity_int
        $table = $this->customerResource->getTable('customer_entity_int');

        // Vérifier si une ligne pour l'attribut is_subligraph existe déjà
        $select = $this->connection->select()
            ->from($table)
            ->where('attribute_id = ?', 1269)  // L'attribut 'is_subligraph'
            ->where('entity_id = ?', $customerId);

        $existingData = $this->connection->fetchRow($select);

        // Si la ligne existe, mettre à jour
        if ($existingData) {
            $this->connection->update(
                $table,
                ['value' => $value],
                ['attribute_id = ?' => 1269, 'entity_id = ?' => $customerId]
            );
        } else {
            // Sinon, insérer une nouvelle ligne
            $this->connection->insert(
                $table,
                [
                    'attribute_id' => 1269,  // L'ID de l'attribut 'is_subligraph'
                    'entity_id' => $customerId,
                    'value' => $value,
                ]
            );
        }
    }
}
