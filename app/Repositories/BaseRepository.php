<?php

namespace App\Repositories;

use App\Libs\ValueUtil;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{DB, Log};
use Throwable;

abstract class BaseRepository
{
    protected $model;

    protected $validDelFlg;

    public function __construct() {
        $this->setModel();
        $this->validDelFlg = ValueUtil::constToValue('common.del_flg.VALID');
    }

    abstract public function getModel();

    public function setModel() {
        $this->model = app()->make(
            $this->getModel(),
        );
    }

    /**
     * Find by id
     *
     * @param string|int $id
     * @param bool $isFindAll
     * @param string|null $loggingChannel
     * @return object|bool
     */
    public function findById($id, $isFindAll = false) {
        try {
            $query = $this->model->where($this->model->getKeyName(), $id);
            if (! $isFindAll) {
                $query->where('del_flg', ValueUtil::constToValue('common.del_flg.VALID'));
            }

            return $query->first();
        } catch (Exception $e) {
            Log::error($e);

            return false;
        }
    }

    /**
     * Insert or update record if id exist, return true if success and false if not
     *
     * @param int|null $id
     * @param array $params
     * @param mixed $isFindAll
     *
     * @return mixed
     */
    public function save($id = null, $params, $isFindAll = false) {
        try {
            DB::beginTransaction();
            if ($id) {
                $result = $this->findById($id, $isFindAll);
                $result->fill($params);
                $result = $result->save($result);
            } else {
                $result = $this->model->create($params);
            }
            if (! $result) {
                DB::rollBack();
            }
            DB::commit();

            return $result;
        } catch (Throwable $th) {
            Log::error($th);
            DB::rollBack();

            return false;
        }
    }

    /**
     * Insert or update multiple record if id exist, return true if success and false if not
     *
     * @param array|null $ids
     * @param array $attributes
     *
     * @return mixed
     */
    public function saveMany($ids = null, $attributes) {
        try {
            DB::beginTransaction();
            $models = [];

            foreach ($attributes as $index => $attribute) {
                $id = $ids[$index];

                if ($id) {
                    $model = $this->model->find($id);
                    $model = $model->update($attribute);
                } else {
                    $model = $this->model->save($attribute);
                }
                if (! $model) {
                    DB::rollBack();
                }
                $models[] = $model;
            }
            DB::commit();

            return $models;
        } catch (Throwable $th) {
            Log::error($th);
            DB::rollBack();

            return false;
        }
    }

    /**
     * Perform delete1 on a model
     * @param mixed $id
     */
    public function delete1($id) {
        try {
            DB::beginTransaction();

            $model = $this->findById($id);
            if ($model === null) {
                throw new ModelNotFoundException();
            }
            $model['del_flg'] = ValueUtil::constToValue('common.del_flg.INVALID');
            $model->save();
            DB::commit();
        } catch (Throwable $th) {
            Log::error($th);
            DB::rollBack();
            throw $th;
        }
    }
}
