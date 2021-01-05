<?php

namespace AwemaPL\Storage\User\Sections\Sources\Repositories;

use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Sourceable;
use AwemaPL\Storage\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use AwemaPL\Storage\User\Sections\Sources\Scopes\EloquentSourceScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Sources\Services\SourceTypes;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;

class EloquentSourceRepository extends BaseRepository implements SourceRepository
{

    /** @var SourceTypes $sourceTypes */
    private $sourceTypes;

    protected $searchable = [
    ];

    public function __construct(SourceTypes $sourceTypes)
    {
        parent::__construct();
        $this->sourceTypes = $sourceTypes;
    }

    public function entity()
    {
        return Source::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);
        // apply custom scopes
        $this->entity = (new EloquentSourceScopes($request))->scope($this->entity);
        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        return Source::create($data);
    }

    /**
     * Update source
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete source
     *
     * @param int $id
     */
    public function delete($id)
    {
        $this->destroy($id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     * @return Model|Collection|static[]|static|null
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    /**
     * Select sourceable type
     *
     * @return array
     */
    public function selectSourceableType()
    {
        $data = [];
        foreach ($this->sourceTypes->getTypes() as $type => $resource){
            array_push($data, [
                'id' =>$type,
                'name' =>$this->sourceTypes->getName($type),
            ]);
        }
        return $data;
    }

    /**
     * Select sourceable ID
     *
     * @param string $sourceableType
     * @return array
     */
    public function selectSourceableId($sourceableType){
        $class = $this->sourceTypes->getType($sourceableType);
        $sources = $class::where('user_id', Auth::user()->id)->get();
        $data = [];
        /** @var Sourceable $source */
        foreach ($sources as $source){
            array_push($data, [
                'id' =>$source->getKey(),
                'name' =>$source->getName(),
            ]);
        }
        return $data;
    }
}
