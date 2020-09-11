<?php

declare(strict_types=1);

namespace Phalcon\Permission\Models;

class CasbinRules extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $ptype;

    /**
     *
     * @var string
     */
    public $v0;

    /**
     *
     * @var string
     */
    public $v1;

    /**
     *
     * @var string
     */
    public $v2;

    /**
     *
     * @var string
     */
    public $v3;

    /**
     *
     * @var string
     */
    public $v4;

    /**
     *
     * @var string
     */
    public $v5;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var string
     */
    public $update_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phalcon");
        $this->setSource("casbin_rules");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CasbinRules[]|CasbinRules|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CasbinRules|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
