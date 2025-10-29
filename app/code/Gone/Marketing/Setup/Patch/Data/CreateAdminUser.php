<?php


namespace Gone\Marketing\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\User\Model\UserFactory;
use Magento\User\Model\ResourceModel\User;

class CreateAdminUser implements DataPatchInterface
{

    private ModuleDataSetupInterface $moduleDataSetup;
    protected UserFactory $_userFactory;

    /**
     * @var User
     */
    protected User $_userResourceModel;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        UserFactory $userFactory,
        User $userResourceModel
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_userFactory = $userFactory;
        $this->_userResourceModel = $userResourceModel;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $adminInfoArr = [
            0 => [
                'username' => 'raphael',
                'firstname' => 'raphael',
                'lastname' => 'grossot',
                'email' => 'raphael.grossot@graphic-reseau.com',
                'password' => uniqid(),
                'interface_locale' => 'en_US',
                'is_active' => 1
            ],
            1 => [
                'username' => 'olivier',
                'firstname' => 'olivier',
                'lastname' => 'bayart',
                'email' => 'olivier.bayart@graphic-reseau.com',
                'password' => uniqid(),
                'interface_locale' => 'en_US',
                'is_active' => 1
            ]
        ];

        foreach ($adminInfoArr as $adminInfo) {
            $user = $this->_userFactory->create()->load($adminInfo['email'], 'email');

            if (!$user->getId()) {
                $user = $this->_userFactory->create();
                $user->setData($adminInfo);
                $user->setRoleId(1);
                $this->_userResourceModel->save($user);
            }
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
