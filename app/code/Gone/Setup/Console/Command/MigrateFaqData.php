<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Setup\Console\Command;

use Amasty\Faq\Model\OptionSource\Question\Status;
use Amasty\Faq\Model\OptionSource\Question\Visibility;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filter\RemoveAccents;
use Magento\Framework\Registry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Amasty\Faq\Api\Data\QuestionInterface;
use Amasty\Faq\Api\Data\QuestionInterfaceFactory;
use Amasty\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\Serialize\SerializerInterface;

class MigrateFaqData extends \Symfony\Component\Console\Command\Command
{

    const MAX_LENGTH_SHORT_ANSWER = 200;

    protected Registry $_registry;
    protected State $_state;
    protected QuestionInterfaceFactory $_dataQuestionFactory;
    protected QuestionRepositoryInterface $_questionRepository;
    protected DirectoryList $_dir;
    protected SerializerInterface $_serializer;
    protected RemoveAccents $_removeAccents;

    public function __construct(
        State $state,
        Registry $registry,
        QuestionInterfaceFactory $dataQuestionFactory,
        QuestionRepositoryInterface $questionRepository,
        DirectoryList $dir,
        SerializerInterface $serializer,
        RemoveAccents $removeAccents,
        string $name = null
    ) {
        parent::__construct($name);
        $this->_dataQuestionFactory = $dataQuestionFactory;
        $this->_questionRepository = $questionRepository;
        $this->_dir = $dir;
        $this->_serializer = $serializer;
        $this->_registry = $registry;
        $this->_state = $state;
        $this->_removeAccents = $removeAccents;
    }

    /**
     * ex : php bin/magento gone_faq:import
     */
    protected function configure(): void
    {
        $this->setName('gone_faq:import');
        $this->setDescription('Migrate Faq Data');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->_registry->registry('isSecureArea') === null) {
            $this->_registry->register('isSecureArea', true);
        }
        try {
            $this->_state->setAreaCode(\Magento\Backend\App\Area\FrontNameResolver::AREA_CODE);
        } catch (\Exception $e) {
        }

        $baseDir = $this->_dir->getPath("app");
        // FAQ question
        $output->writeln('<comment>Begin import Faq Question</comment>');
        $faqQuestion = file_get_contents($baseDir . '/code/Gone/Setup/Console/Command/fixtures/faq-data.json');
        $faqQuestion = $this->_serializer->unserialize($faqQuestion);

        $numberOfQuestion = count($faqQuestion);
        $i = 1;
        foreach ($faqQuestion as $questionData) {
            $output->writeln('<comment>Process question ' . $i . ' / ' . $numberOfQuestion . '</comment>');
            $this->_createFaqQuestion(
                $questionData["question"],
                $questionData["answer"],
            );
            $i++;
        }

        $output->writeln('<comment>End import Faq Question</comment>');
        // Product FAQ
        $output->writeln('<comment>Begin import Product Faq Question</comment>');
        $productQuestion = file_get_contents($baseDir . '/code/Gone/Setup/Console/Command/fixtures/faq-product-data.json');
        $productQuestion = $this->_serializer->unserialize($productQuestion);

        $numberOfQuestion = count($productQuestion);
        $i = 1;
        foreach ($productQuestion as $questionData) {
            $output->writeln('<comment>Process question ' . $i . ' / ' . $numberOfQuestion . '</comment>');
            $this->_createFaqQuestion(
                $questionData["question"],
                $questionData["answer"],
                $questionData["product_id"],
            );
            $i++;
        }

        $output->writeln('<comment>End import Product Faq Question</comment>');
    }

    protected function _createFaqQuestion(string $title, string $answer, ?string $productId = null)
    {
        $shortAnswer = substr(strip_tags($answer), 0, self::MAX_LENGTH_SHORT_ANSWER) . "...";
        $fullAnswer = true;

        /** @var QuestionInterface $question */
        $question = $this->_dataQuestionFactory->create();
        $question
            ->setTitle($title)
            ->setStores([0]) // all store view, must be an array
            ->setAnswer($answer)
            ->setShortAnswer($shortAnswer)
            ->setStatus(Status::STATUS_ANSWERED)
            ->setVisibility(Visibility::VISIBILITY_PUBLIC)
            ->setUrlKey($this->_formatUrlKey($title, $productId));

        if (!empty($productId)) {
            $fullAnswer = false;
            $question->setProductIds([$productId]); // must be an array
        }

        $question->setIsShowFullAnswer($fullAnswer);

        $this->_questionRepository->save($question);
    }

    protected function _formatUrlKey(string $title, $productId) : string
    {
        $urlKey = strtolower($title . "" . $productId);
        $urlKey = str_replace([" ", ",", "(", ")"], "-", $urlKey);
        $urlKey = str_replace(["?", "'", '"',], "", $urlKey);
        $urlKey = preg_replace('/-$/', "", $urlKey);
        $urlKey = $this->_removeAccents->filter($urlKey);
        $urlKey = strtolower($urlKey); // because capital with accent are not lowercase the first time

        return $urlKey;
    }
}
