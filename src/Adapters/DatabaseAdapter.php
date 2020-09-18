<?php

declare(strict_types=1);

namespace Phalcon\Permission\Adapters;

use Casbin\Model\Model;
use Casbin\Persist\Adapter;
use Casbin\Persist\AdapterHelper;
use Phalcon\Permission\Models\CasbinRules;

class DatabaseAdapter implements Adapter
{
    use AdapterHelper;

    /**
     * Rules eloquent model.
     *
     * @var Rule
     */
    protected $eloquent;

    /**
     * the DatabaseAdapter constructor.
     *
     * @param Rule $eloquent
     */
    public function __construct(CasbinRules $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * savePolicyLine function.
     *
     * @param string $ptype
     * @param array  $rule
     */
    public function savePolicyLine(string $ptype, array $rule): void
    {
        $col['ptype'] = $ptype;
        foreach ($rule as $key => $value) {
            $col['v' . strval($key)] = $value;
        }

        $casbinrules = new CasbinRules();

        $casbinrules->ptype = isset($col['ptype']) ? $col['ptype'] : null;
        $casbinrules->v0 = isset($col['v0']) ? $col['v0'] : null;
        $casbinrules->v1 = isset($col['v1']) ? $col['v1'] : null;
        $casbinrules->v2 = isset($col['v2']) ? $col['v2'] : null;
        $casbinrules->v3 = isset($col['v3']) ? $col['v3'] : null;
        $casbinrules->v4 = isset($col['v4']) ? $col['v4'] : null;
        $casbinrules->v5 = isset($col['v5']) ? $col['v5'] : null;

        $casbinrules->create();
    }

    /**
     * loads all policy rules from the storage.
     *
     * @param Model $model
     */
    public function loadPolicy(Model $model): void
    {
        $rows = $this->eloquent->find()->toArray();
        foreach ($rows as &$row) {
            unset($row['id']);
        }
        unset($row);

        foreach ($rows as $row) {
            $line = implode(', ', array_filter($row, function ($val) {
                return '' != $val && !is_null($val);
            }));
            $this->loadPolicyLine(trim($line), $model);
        }
    }

    /**
     * saves all policy rules to the storage.
     *
     * @param Model $model
     */
    public function savePolicy(Model $model): void
    {
        foreach ($model['p'] as $ptype => $ast) {
            foreach ($ast->policy as $rule) {
                $this->savePolicyLine($ptype, $rule);
            }
        }

        foreach ($model['g'] as $ptype => $ast) {
            foreach ($ast->policy as $rule) {
                $this->savePolicyLine($ptype, $rule);
            }
        }
    }

    /**
     * adds a policy rule to the storage.
     * This is part of the Auto-Save feature.
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     */
    public function addPolicy(string $sec, string $ptype, array $rule): void
    {
        $this->savePolicyLine($ptype, $rule);
    }

    /**
     * This is part of the Auto-Save feature.
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     */
    public function removePolicy(string $sec, string $ptype, array $rule): void
    {
        $count = 0;

        $instance = $this->eloquent->query()->where('ptype = :ptype:');
        $bind = [
            'ptype' => $ptype,
        ];

        foreach ($rule as $key => $value) {
            $instance->andWhere("v{$key} = :v{$key}:");
            $bind["v{$key}"] = $value;
        }

        $instance->bind($bind);

        foreach ($instance->execute() as $model) {
            if ($model->delete()) {
                ++$count;
            }
        }
    }

    /**
     * RemoveFilteredPolicy removes policy rules that match the filter from the storage.
     * This is part of the Auto-Save feature.
     *
     * @param string $sec
     * @param string $ptype
     * @param int    $fieldIndex
     * @param string ...$fieldValues
     */
    public function removeFilteredPolicy(string $sec, string $ptype, int $fieldIndex, string ...$fieldValues): void
    {
        $count = 0;

        $instance = $this->eloquent->query()->where('ptype = :ptype:');
        $bind = [
            'ptype' => $ptype,
        ];

        foreach (range(0, 5) as $value) {
            if ($fieldIndex <= $value && $value < $fieldIndex + count($fieldValues)) {
                if ('' != $fieldValues[$value - $fieldIndex]) {
                    $instance->andWhere("v{$value} = :v{$value}:");
                    $bind["v{$value}"] = $fieldValues[$value - $fieldIndex];
                }
            }
        }

        $instance->bind($bind);

        foreach ($instance->execute() as $model) {
            if ($model->delete()) {
                ++$count;
            }
        }
    }
}
