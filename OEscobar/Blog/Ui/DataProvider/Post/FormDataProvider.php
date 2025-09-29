<?php
declare(strict_types=1);

namespace OEscobar\Blog\Ui\DataProvider\Post;

use Magento\Ui\DataProvider\AbstractDataProvider;
use OEscobar\Blog\Model\ResourceModel\Post\CollectionFactory;

class FormDataProvider extends AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        foreach ($this->collection->getItems() as $post) {
            $this->loadedData[(int)$post->getData('post_id')]['post'] = $post->getData();
        }

        if (!$this->loadedData) {
            $this->loadedData[null]['general'] = [
                'status' => 1
            ];
        }

        return $this->loadedData;
    }
}

