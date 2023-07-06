<?php

namespace Airobot\Hyperf\Services\admin\org;

use Airobot\Hyperf\Model\Dictionary;
use Airobot\Hyperf\Utils\Util;
use Hyperf\Di\Annotation\Inject;

class DictionaryService
{
    #[Inject]
    private Util $util;

    public function list(): array
    {
        $data = Dictionary::query()->select(['id', 'pid', 'name', 'sort', 'value'])->get();
        return $this->util->arrayToTree($data->toArray());
    }

    public function add(array $data): void
    {
        $dictionary = new Dictionary();
        $dictionary->setFromData($data);
        $dictionary->save();
    }

    public function edit(int $id, array $data): void
    {
        $dictionary = Dictionary::query()->where('id', $id)->first();
        $dictionary->setFromData($data);
        $dictionary->save();
    }

    public function del(int $id): void
    {
        Dictionary::destroy($id);
    }
}